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
      // TODO: persist the order to KV/D1/Supabase
      // TODO: mark the user's PASS as claimed, issue NFT, etc.
      break;
    }
    case 'payment_intent.succeeded': {
      const pi = event.data.object;
      console.log('[livepass webhook] payment_intent succeeded:', pi.id);
      break;
    }
    case 'checkout.session.expired':
    case 'payment_intent.payment_failed': {
      console.log('[livepass webhook] failed/expired:', event.type, event.data.object.id);
      break;
    }
    default:
      console.log('[livepass webhook] unhandled event type:', event.type);
  }

  res.status(200).json({ received: true });
};

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
