/**
 * POST /api/curator
 *
 * Body: { artist, question, history? }
 *
 *   artist  — the target artist's profile snapshot (the client passes
 *             whatever it already fetched from users/{uid}). We do NOT
 *             trust the client to be honest about who they are talking
 *             about, but the curator response is always grounded in the
 *             snapshot the client supplied — there's no other source
 *             to lie about.
 *   question — user's free-text question.
 *   history  — optional [{ role:'user'|'assistant', text }] for follow-ups.
 *
 * Response: { text }
 *
 * The curator is intentionally restricted:
 *   · It only answers from `artist` (bio / style / disciplines / region /
 *     era / achievements / socialLinks / role / posts summary). Anything
 *     not in the snapshot triggers an "本人未登録のため不明" reply.
 *   · No speculation. No invented credits. No "based on similar
 *     artists in this scene" guesses.
 *   · The voice is Wikipedia-meets-curator: factual, brief, respectful.
 *
 * Falls back to a stub when ANTHROPIC_API_KEY is missing so the client
 * can show a soft hint instead of a hard error.
 */

module.exports = async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ error: 'method not allowed' });
  }

  const { artist, question, history } = req.body || {};
  if (!artist || typeof artist !== 'object') {
    return res.status(400).json({ error: 'missing artist' });
  }
  if (!question || typeof question !== 'string' || !question.trim()) {
    return res.status(400).json({ error: 'missing question' });
  }

  const key = process.env.ANTHROPIC_API_KEY;

  // Compact the artist snapshot into a profile block the model can read.
  // Empty / missing fields are dropped so the model knows what's truly
  // unregistered (vs. registered-but-blank).
  function field(label, val){
    if (val == null || val === '') return null;
    if (Array.isArray(val) && val.length === 0) return null;
    if (Array.isArray(val)) return `${label}: ${val.map(x => typeof x === 'object' ? JSON.stringify(x) : String(x)).join(' / ')}`;
    return `${label}: ${String(val)}`;
  }
  const profileBlock = [
    field('Name',         artist.name),
    field('Role',         artist.role),
    field('Disciplines',  artist.disciplines),
    field('Style',        artist.style),
    field('Region',       artist.region),
    field('Era',          artist.era),
    field('Bio',          artist.bio),
    field('Achievements', artist.achievements),
    field('Links',        Array.isArray(artist.socialLinks)
                            ? artist.socialLinks.map(l => `${l.label || ''} ${l.url || ''}`.trim())
                            : null),
  ].filter(Boolean).join('\n');

  if (!key) {
    const stubText =
      `（AI未設定のためサンプル応答）\n\n` +
      `${artist.name || 'このアーティスト'}についての登録情報:\n` +
      (profileBlock || '（プロフィール未登録）') +
      `\n\nVercel に ANTHROPIC_API_KEY を設定すると、登録情報を読み解いた上で文化キュレーターとして自然言語で回答します。`;
    return res.status(200).json({ text: stubText, stub: true });
  }

  const systemPrompt =
    'あなたは EXPASS（文化アーカイブ・プラットフォーム）の AI キュレーターです。\n' +
    'ファンや一般ユーザーから受けた、登録アーティストに関する質問に答える役割があります。\n\n' +
    '【絶対のルール】\n' +
    '1. 回答は <PROFILE> ブロックに記載された情報のみを根拠とすること。記載されていない事実は\n' +
    '   絶対に推測・補完しない。「本人がこのプラットフォームに未登録のため不明です」と返す。\n' +
    '2. 一般知識・ネット情報・類似アーティストからの推論は一切使わない。\n' +
    '3. 文体は Wikipedia と展示キュレーターを足して2で割った感じ — 簡潔・敬意ある三人称・1〜3段落。\n' +
    '4. 美辞麗句で水増ししない。事実を淡々と、しかし熱量は失わずに伝える。\n' +
    '5. 質問が <PROFILE> の範囲外（私生活、住所、連絡先、噂など）なら丁寧に断る。\n' +
    '6. 言語は質問者と同じ言語で返す（質問が日本語なら日本語、英語なら英語）。\n' +
    '7. 出力にマークダウン記号（**, ##, --- など）は使わない。プレーンな文章のみ。\n\n' +
    '【ロール】\n' +
    'EXPASS は単なる SNS ではなく文化の記録です。アーティスト本人の権利と主張が第一。\n' +
    'あなたはマネージャー・プロデューサー・芸能プロダクションのように、本人がそこにいないときに\n' +
    '本人の世界観を保ったまま代弁する役割を担います。\n\n' +
    '<PROFILE>\n' + (profileBlock || '(empty)') + '\n</PROFILE>';

  // Build the conversation. The system prompt holds the profile, and
  // we append any prior turns + the new question.
  const messages = [];
  if (Array.isArray(history)) {
    for (const turn of history.slice(-10)) {
      if (!turn || typeof turn.text !== 'string') continue;
      const role = turn.role === 'assistant' ? 'assistant' : 'user';
      messages.push({ role, content: turn.text });
    }
  }
  messages.push({ role: 'user', content: question.trim() });

  try {
    const r = await fetch('https://api.anthropic.com/v1/messages', {
      method: 'POST',
      headers: {
        'Content-Type':      'application/json',
        'x-api-key':         key,
        'anthropic-version': '2023-06-01',
      },
      body: JSON.stringify({
        model:      'claude-sonnet-4-5',
        max_tokens: 800,
        system:     systemPrompt,
        messages,
      }),
    });
    const data = await r.json();
    if (!r.ok) {
      return res.status(500).json({
        error: data?.error?.message || 'anthropic api error',
      });
    }
    const text = data?.content?.[0]?.text || '(no response)';
    return res.status(200).json({ text });
  } catch (e) {
    return res.status(500).json({ error: e.message });
  }
};
