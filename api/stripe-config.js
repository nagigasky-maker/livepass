/**
 * GET /api/stripe-config
 *
 * Returns the Stripe publishable key so the client can boot
 * Stripe.js / Stripe Elements. The publishable key is safe to
 * expose (designed for the browser) — the secret key stays on
 * the server. If no key is configured we return a stub flag so
 * the client can render a helpful fallback instead of erroring.
 *
 * Env var: STRIPE_PUBLISHABLE_KEY (pk_test_... or pk_live_...)
 */
module.exports = function handler(req, res) {
  if (req.method !== 'GET') {
    return res.status(405).json({ error: 'method not allowed' });
  }
  const publishableKey = process.env.STRIPE_PUBLISHABLE_KEY || '';
  if (!publishableKey) {
    return res.status(200).json({
      stub: true,
      message: 'STRIPE_PUBLISHABLE_KEY が未設定です。Vercel 環境変数を追加してください。',
    });
  }
  return res.status(200).json({ publishableKey });
};
