/**
 * POST /api/redeem-invite
 *
 * Validates an invite code against an env-var allowlist and, on success,
 * returns the plan + expiration that the client should cache locally and
 * mirror to Firestore users/{uid}.plan.
 *
 * Env vars (comma-separated codes, trimmed + upper-cased):
 *   INVITE_CODES_ARTIST   — grants plan="artist"   for 365 days
 *   INVITE_CODES_PRO      — grants plan="pro"      for 365 days
 *   INVITE_CODES_BUSINESS — grants plan="business" for 365 days
 *
 * Expected request:
 *   POST { code: "ARTIST30-ABCD", uid?: "firebase-uid" }
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
    { plan:'artist',   env:'INVITE_CODES_ARTIST'   },
    { plan:'pro',      env:'INVITE_CODES_PRO'      },
    { plan:'business', env:'INVITE_CODES_BUSINESS' },
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
