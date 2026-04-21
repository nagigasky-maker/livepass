/**
 * POST /api/redeem-invite
 *
 * Validates an invite code against an env-var allowlist and, on success,
 * returns the plan + expiration that the client should cache locally and
 * mirror to Firestore users/{uid}.plan.
 *
 * Env vars (comma-separated codes, trimmed + upper-cased):
 *   INVITE_CODES_PRO      — grants plan="pro" for 365 days
 *                           Founding-artist seed bucket — plan for
 *                           20 key artists. Convention: EXPASS-PRO-XXXX.
 *                           Waives ¥2,480/mo × 12 = ¥29,760 per artist.
 *   INVITE_CODES_STANDARD — grants plan="standard" for 365 days
 *                           30 seats for next-tier artists.
 *                           Convention: EXPASS-STD-XXXX.
 *                           Waives ¥980/mo × 12 = ¥11,760 per artist.
 *   INVITE_CODES_BUSINESS — grants plan="business" for 365 days
 *                           (Phase 2 — not used at launch).
 *   INVITE_CODES_SEED     — legacy alias for PRO (earlier naming).
 *   INVITE_CODES_ARTIST   — legacy alias for STANDARD.
 *
 * Delivery channels (both supported end-to-end):
 *   1) Code entry via Settings → Upgrade → paywall invite field.
 *   2) Link: https://expass.app/invite/<CODE>
 *        - /invite/ landing page auto-redeems when the recipient is
 *          already signed in, or stashes the code in
 *          localStorage.livepass_pending_invite and routes to
 *          /onboarding. On the next /screen boot the pending code
 *          is consumed automatically.
 *
 * Expected request:
 *   POST { code: "EXPASS-PRO-A1B2C3", uid?: "firebase-uid" }
 *
 * Response:
 *   200 { ok:true, plan:"artist", planExpiresAt: 172345...}
 *   400 { ok:false, reason:"コードが無効です" }
 *
 * Security notes:
 *   · The endpoint is purely additive — it only grants, never revokes.
 *   · Duplicate-use prevention is best-effort; codes that have already
 *     been issued to a specific person are removed from the env-list
 *     after first claim (by the operator). A Firestore invites/{code}
 *     doc model is the right long-term move; this is the launch stub.
 *   · The client still has to write to users/{uid}.plan afterwards —
 *     Firestore Rules should restrict that field to the owner + a
 *     planSource == 'invite' | 'stripe' invariant enforced by Rules or
 *     by a follow-up server endpoint with firebase-admin.
 */

module.exports = async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ ok:false, reason:'method not allowed' });
  }

  let body = req.body;
  if (!body || typeof body !== 'object') {
    try {
      const raw = await readRawBody(req);
      body = JSON.parse(raw.toString('utf8') || '{}');
    } catch (_) { body = {}; }
  }

  const code = String((body && body.code) || '').trim().toUpperCase();
  if (!code) {
    return res.status(400).json({ ok:false, reason:'コードを入力してください。' });
  }

  const tiers = [
    // SEED grants PRO — use this bucket for the 30–50 founding artists.
    { plan:'pro',      env:'INVITE_CODES_SEED'     },
    { plan:'standard', env:'INVITE_CODES_STANDARD' },
    { plan:'pro',      env:'INVITE_CODES_PRO'      },
    { plan:'business', env:'INVITE_CODES_BUSINESS' },
    // Legacy alias — older ARTIST codes now map to STANDARD.
    { plan:'standard', env:'INVITE_CODES_ARTIST'   },
  ];

  for (const t of tiers) {
    const raw = (process.env[t.env] || '').trim();
    if (!raw) continue;
    const allow = raw.split(',').map(s => s.trim().toUpperCase()).filter(Boolean);
    if (allow.includes(code)) {
      const planExpiresAt = Date.now() + 365 * 24 * 60 * 60 * 1000;
      return res.status(200).json({
        ok: true,
        plan: t.plan,
        planExpiresAt,
      });
    }
  }

  // Last-resort stub — allow a well-known development code so we can test
  // the flow end-to-end before env vars exist on Vercel. Remove once the
  // real seed codes are configured.
  if (code === 'EXPASS-DEV-ARTIST') {
    return res.status(200).json({
      ok: true,
      plan: 'artist',
      planExpiresAt: Date.now() + 365 * 24 * 60 * 60 * 1000,
      stub: true,
    });
  }

  return res.status(400).json({
    ok: false,
    reason: 'コードが無効か、すでに使用済みです。',
  });
};

function readRawBody(req) {
  return new Promise((resolve, reject) => {
    const chunks = [];
    req.on('data', c => chunks.push(c));
    req.on('end',  () => resolve(Buffer.concat(chunks)));
    req.on('error', reject);
  });
}
