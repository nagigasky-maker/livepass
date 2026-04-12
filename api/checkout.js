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

  const { productType, productId, title, description, amount, cover, userId } =
    req.body || {};

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
        app:         'livepass',
      },
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
