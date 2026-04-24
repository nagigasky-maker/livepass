/**
 * POST /api/webhook
 *
 * Stripe webhook endpoint. Receives events from Stripe, verifies the
 * signature using STRIPE_WEBHOOK_SECRET, and runs fulfillment logic
 * for completed checkout sessions.
 *
 * To configure:
 *   1. Stripe Dashboard → Developers → Webhooks → Add endpoint
 *   2. URL: https://livepass.vercel.app/api/webhook
 *   3. Events to send: checkout.session.completed, payment_intent.succeeded
 *   4. Copy the signing secret (whsec_...) into Vercel env:
 *      STRIPE_WEBHOOK_SECRET = whsec_...
 *
 * IMPORTANT: Stripe signature verification requires the RAW request body.
 * Vercel's serverless functions auto-parse JSON by default, which breaks
 * signature checks. We disable body parsing via the exported config and
 * read the raw buffer manually.
 */

module.exports = async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).send('method not allowed');
  }

  const secretKey    = process.env.STRIPE_SECRET_KEY;
  const webhookSecret = process.env.STRIPE_WEBHOOK_SECRET;

  if (!secretKey || !webhookSecret) {
    // Accept the request but log that we're in stub mode
    console.log('[livepass webhook] stub mode — keys not configured');
    return res.status(200).json({ received: true, stub: true });
  }

  let stripe;
  try {
    stripe = require('stripe')(secretKey);
  } catch (e) {
    return res.status(500).send('stripe module missing: ' + e.message);
  }

  // Read the raw body — needed for signature verification
  const buf = await readRawBody(req);
  const sig = req.headers['stripe-signature'];

  let event;
  try {
    event = stripe.webhooks.constructEvent(buf, sig, webhookSecret);
  } catch (err) {
    console.error('[livepass webhook] signature verification failed:', err.message);
    return res.status(400).send(`Webhook signature error: ${err.message}`);
  }

  // Handle the event
  switch (event.type) {
    case 'checkout.session.completed': {
      const session = event.data.object;
      console.log('[livepass webhook] checkout completed:', session.id, session.metadata);
      await fulfillCheckoutSession(session).catch(err => {
        console.error('[livepass webhook] fulfillment failed:', err && err.message);
      });
      break;
    }
    case 'payment_intent.succeeded': {
      const pi = event.data.object;
      console.log('[livepass webhook] payment_intent succeeded:', pi.id);
      break;
    }
    case 'account.updated': {
      const acct = event.data.object;
      // Connect Express onboarding status change. We log it; the Artist
      // onboarding UI (post-launch) will mirror this into
      // users/{uid}.stripeAccountOk when it ships.
      console.log('[livepass webhook] account updated:', acct.id,
        'charges_enabled=', acct.charges_enabled,
        'payouts_enabled=', acct.payouts_enabled);
      break;
    }
    case 'charge.refunded':
    case 'checkout.session.expired':
    case 'checkout.session.async_payment_failed':
    case 'payment_intent.payment_failed': {
      console.log('[livepass webhook] failed/refund/expired:', event.type, event.data.object.id);
      break;
    }
    default:
      console.log('[livepass webhook] unhandled event type:', event.type);
  }

  res.status(200).json({ received: true });
};

/**
 * Fulfill a completed Stripe Checkout Session.
 *
 * Writes the outcome into Firestore so the client reflects payment state
 * without waiting for the user to refresh:
 *   · productType === 'event' | 'workshop'  →  reservations/{productId}
 *     gets { paid: true, paidAt, checkoutSessionId, amount, currency }
 *   · productType === 'pass' (subscription tier purchase)
 *                                            →  subscriptions/{uid}_{plan}
 *     gets { active: true, plan, purchasedAt, expiresAt, checkoutSessionId }
 *
 * If firebase-admin isn't available (missing dep or no
 * FIREBASE_SERVICE_ACCOUNT) we log and return — the webhook still 200s
 * so Stripe doesn't retry forever, and a launch-follow-up can backfill
 * from Stripe's session list if needed.
 */
async function fulfillCheckoutSession(session) {
  const db = await getAdminDb();
  if (!db) {
    console.log('[livepass webhook] admin db unavailable — skipping fulfillment');
    return;
  }
  const meta = session.metadata || {};
  const productType = meta.productType;
  const productId   = meta.productId;
  const userId      = meta.userId;
  const amount      = session.amount_total;
  const currency    = session.currency;
  const sid         = session.id;

  if ((productType === 'event' || productType === 'workshop') && productId) {
    await db.collection('reservations').doc(productId).set({
      paid: true,
      paidAt: new Date().toISOString(),
      checkoutSessionId: sid,
      amount, currency,
      userId: userId || null,
    }, { merge: true });
    return;
  }
  if (productType === 'pass' && userId && meta.plan) {
    const planId = String(meta.plan).toLowerCase();
    const expiresAt = Date.now() + 365 * 24 * 60 * 60 * 1000;
    await db.collection('subscriptions').doc(`${userId}_${planId}`).set({
      userId,
      plan: planId,
      active: true,
      purchasedAt: new Date().toISOString(),
      expiresAt,
      checkoutSessionId: sid,
      amount, currency,
    }, { merge: true });
    return;
  }
  console.log('[livepass webhook] session has no fulfillable metadata:', meta);
}

/**
 * Lazy-initialise firebase-admin and return a Firestore reference, or
 * null if the environment isn't configured (dep missing or no service
 * account). Cached across invocations within a single serverless
 * container.
 */
let _adminDb = null;
let _adminInitTried = false;
async function getAdminDb() {
  if (_adminDb) return _adminDb;
  if (_adminInitTried) return null;
  _adminInitTried = true;
  const raw = process.env.FIREBASE_SERVICE_ACCOUNT;
  if (!raw) return null;
  let admin;
  try {
    admin = require('firebase-admin');
  } catch (_) {
    console.log('[livepass webhook] firebase-admin not installed');
    return null;
  }
  try {
    if (!admin.apps.length) {
      admin.initializeApp({
        credential: admin.credential.cert(JSON.parse(raw)),
      });
    }
    _adminDb = admin.firestore();
    return _adminDb;
  } catch (e) {
    console.error('[livepass webhook] admin init failed:', e.message);
    return null;
  }
}

// Vercel-specific config: disable the default body parser so we can read
// the raw request buffer for signature verification.
module.exports.config = {
  api: {
    bodyParser: false,
  },
};

function readRawBody(req) {
  return new Promise((resolve, reject) => {
    const chunks = [];
    req.on('data', (chunk) => chunks.push(chunk));
    req.on('end',  () => resolve(Buffer.concat(chunks)));
    req.on('error', reject);
  });
}
