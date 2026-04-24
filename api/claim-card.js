/**
 * POST /api/claim-card
 *
 * Validates an artist-card ID against an env-var allowlist. On success,
 * the client writes `users/{uid}.cards` via arrayUnion — Firestore Rules
 * already scope that write to the authenticated owner (see
 * firestore.rules `match /users/{uid}`, `cards` is on the update
 * whitelist). The server side exists to keep the card-ID allowlist out
 * of the bundle so we can stop forged ids from being written.
 *
 * Env vars:
 *   CARD_IDS_ALLOWLIST  — comma-separated card ids, case-insensitive,
 *                         trimmed. Operator manages the list in Vercel.
 *                         Example: "EXPASS-CARD-AAAA,EXPASS-CARD-BBBB".
 *
 * Expected request:
 *   POST { cardId: "EXPASS-CARD-AAAA", uid?: "firebase-uid" }
 *
 * Response:
 *   200 { ok:true, cardId:"EXPASS-CARD-AAAA" }
 *   400 { ok:false, reason:"..." }
 *
 * Fallbacks:
 *   · When the env var is missing (local dev / preview without secrets)
 *     the endpoint returns 200 with stub:true so client flows keep
 *     working during development. Remove once the list is populated.
 *
 * Why not server-side write?
 *   A server-authoritative write would need the client to forward its
 *   Firebase ID token so we can verify the uid. That's a launch-plus-1
 *   follow-up (same track as invite server-side move). For now the
 *   client write is safe because Firestore Rules enforce `isOwner(uid)`
 *   on `users/{uid}` updates and `cards` is whitelisted.
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

  const cardId = String((body && body.cardId) || '').trim().toUpperCase();
  if (!cardId) {
    return res.status(400).json({ ok:false, reason:'カードIDが指定されていません。' });
  }
  // Basic shape guard — card ids are ASCII, dash-separated, <= 64 chars.
  if (!/^[A-Z0-9][A-Z0-9\-_]{1,63}$/.test(cardId)) {
    return res.status(400).json({ ok:false, reason:'カードIDの形式が正しくありません。' });
  }

  const raw = (process.env.CARD_IDS_ALLOWLIST || '').trim();
  if (!raw) {
    // Stub mode — allowlist not yet configured on the host. Accept the
    // card so dev + preview flows keep working; production must set the
    // env var before launch.
    return res.status(200).json({ ok:true, cardId, stub:true });
  }

  const allow = raw.split(',').map(s => s.trim().toUpperCase()).filter(Boolean);
  if (!allow.includes(cardId)) {
    return res.status(400).json({
      ok:false,
      reason:'このカードIDは発行済みのリストにありません。',
    });
  }

  return res.status(200).json({ ok:true, cardId });
};

function readRawBody(req) {
  return new Promise((resolve, reject) => {
    const chunks = [];
    req.on('data', c => chunks.push(c));
    req.on('end',  () => resolve(Buffer.concat(chunks)));
    req.on('error', reject);
  });
}
