/**
 * POST /api/checkout
 *
 * Creates a Stripe Checkout Session for a LIVE PASS purchase.
 *
 * Body: {
 *   productType: 'event' | 'workshop' | 'pass',
 *   productId:   string,          // event/workshop/pass id
 *   title:       string,          // shown in Stripe hosted page
 *   description: string?,         // venue / date / subtitle
 *   amount:      number,          // JPY, whole yen (e.g. 3000)
 *   cover:       string?,         // absolute URL or null
 *   userId:      string?,         // livepass_holder id from localStorage
 *   plan:        string?,         // 'artist'|'pro'|'business' for pass
 *   artistStripeAccount: string?, // acct_... for Destination Charge.
 *                                 //   Omit → EXPASS-single-merchant flow.
 *   buyerPlan:   string?,         // caller's plan for fee calc (env-driven; see below)
 * }
 *
 * Returns: { sessionId, url }  →  client redirects to url
 *
 * Requires env var: STRIPE_SECRET_KEY (sk_test_... or sk_live_...)
 * If missing, returns a stub with a flag so the client can show a
 * helpful hint without blowing up.
 */

module.exports = async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ error: 'method not allowed' });
  }

  const {
    productType, productId, title, description, amount, cover, userId,
    plan, artistStripeAccount, buyerPlan,
  } = req.body || {};

  if (!productType || !title || typeof amount !== 'number' || amount < 0) {
    return res
      .status(400)
      .json({ error: 'invalid payload: need productType, title, amount' });
  }

  const key = process.env.STRIPE_SECRET_KEY;
  if (!key) {
    return res.status(200).json({
      stub: true,
      message:
        'Stripe テストモード未設定。Vercel の環境変数に STRIPE_SECRET_KEY を追加してください。',
    });
  }

  // Lazy-require so the module only loads when the key exists
  let stripe;
  try {
    stripe = require('stripe')(key);
  } catch (e) {
    return res.status(500).json({ error: 'stripe module missing: ' + e.message });
  }

  // Resolve origin for success/cancel URLs. Prefer the Host header so this
  // works on branch previews too.
  const proto = req.headers['x-forwarded-proto'] || 'https';
  const host  = req.headers['x-forwarded-host'] || req.headers.host || 'livepass.vercel.app';
  const origin = `${proto}://${host}`;

  // Stripe JPY is whole-yen (no cents). unit_amount must be an integer.
  const unitAmount = Math.max(0, Math.round(amount));

  // Stripe requires absolute HTTPS URLs for product images
  const productImages =
    cover && /^https?:\/\//i.test(cover) ? [cover] : undefined;

  // Destination Charge (Stripe Connect, Model A — EXPASS is merchant of
  // record, each paid-plan artist receives payouts to their own Express
  // account). When the caller supplies `artistStripeAccount`, we split
  // the charge: EXPASS keeps an application fee, the rest transfers to
  // the artist. No account → EXPASS single-merchant flow (identical to
  // the pre-Connect behaviour).
  //
  // Fee formula: env-configurable so the operator can match the public
  // policy in /terms/tokushou without redeploying code. Defaults match
  // the launch disclosure ("先着100アーティストはEXPASS手数料無料").
  //   EXPASS_FEE_RATE        — e.g. "0.05" → 5% of the paid amount.
  //                             Default 0.
  //   EXPASS_FEE_FIXED       — flat JPY added on top. Default 0.
  //   EXPASS_FEE_RATE_FREE   — rate for FREE-plan callers (override).
  //                             Default = EXPASS_FEE_RATE.
  const isFreePlan = !buyerPlan || buyerPlan === 'free';
  const feeRate = parseFloat(
    (isFreePlan && process.env.EXPASS_FEE_RATE_FREE) ||
    process.env.EXPASS_FEE_RATE || '0'
  ) || 0;
  const feeFixed = parseInt(process.env.EXPASS_FEE_FIXED || '0', 10) || 0;
  const rawFee = Math.round(unitAmount * feeRate) + feeFixed;
  const applicationFeeAmount = Math.max(0, Math.min(rawFee, unitAmount));
  const paymentIntentData = artistStripeAccount
    ? {
        ...(applicationFeeAmount > 0
            ? { application_fee_amount: applicationFeeAmount }
            : {}),
        transfer_data: { destination: artistStripeAccount },
      }
    : undefined;

  try {
    const session = await stripe.checkout.sessions.create({
      mode: 'payment',
      payment_method_types: ['card'],
      line_items: [
        {
          price_data: {
            currency: 'jpy',
            product_data: {
              name: title,
              description: description || undefined,
              images: productImages,
            },
            unit_amount: unitAmount,
          },
          quantity: 1,
        },
      ],
      metadata: {
        productType: productType,
        productId:   productId || '',
        userId:      userId || '',
        plan:        plan || '',
        artistStripeAccount: artistStripeAccount || '',
        app:         'livepass',
      },
      ...(paymentIntentData ? { payment_intent_data: paymentIntentData } : {}),
      success_url: `${origin}/checkout/success?session_id={CHECKOUT_SESSION_ID}`,
      cancel_url:  `${origin}/checkout/cancel`,
      locale: 'ja',
    });

    return res.status(200).json({
      sessionId: session.id,
      url: session.url,
    });
  } catch (e) {
    return res.status(500).json({
      error: e.message,
      type: e.type || 'unknown',
    });
  }
};
