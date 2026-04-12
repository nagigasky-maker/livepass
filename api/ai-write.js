/**
 * POST /api/ai-write
 *
 * Body: { action: 'write' | 'proof', title: string, body: string }
 *
 * "write": from the article title (+ optional current body as a seed),
 *          draft a 300–500 char Japanese article body.
 * "proof": proofread the existing body — fix typos, kanji misconversions,
 *          and awkward phrasing while keeping tone and content.
 *
 * Uses the Anthropic Messages API directly (no SDK) so the serverless
 * function stays small. Requires process.env.ANTHROPIC_API_KEY.
 * If the key is missing, returns a stub response + flag so the client
 * can show a helpful hint instead of a hard error.
 */

module.exports = async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ error: 'method not allowed' });
  }
  const { action, title, body } = req.body || {};
  if (!['write', 'proof'].includes(action)) {
    return res.status(400).json({ error: 'invalid action' });
  }

  const key = process.env.ANTHROPIC_API_KEY;
  if (!key) {
    return res.status(200).json({
      text: (body || '') +
        '\n\n（AI機能を使うには Vercel の環境変数に ANTHROPIC_API_KEY を設定してください）',
      stub: true,
    });
  }

  let prompt;
  if (action === 'write') {
    prompt =
      'あなたは LIVE PASS（ダンス／DJ／イベントカルチャー）の編集者です。' +
      '以下のタイトルから、読み手（アーティスト・ファン）に響く日本語の記事本文を' +
      '300〜500文字で書いてください。段落は2〜3つ、テンポよく、感情が乗る文体で。\n\n' +
      'タイトル: ' + (title || '（未設定）') + '\n\n' +
      '現在の本文メモ:\n' + (body || '（なし）') + '\n\n' +
      '本文のみ返してください。前置きや説明は不要です。';
  } else {
    prompt =
      '以下の日本語記事本文を校正してください。誤字脱字・漢字変換ミス・' +
      '不自然な表現を直し、文体と内容は保持してください。修正後の本文のみを返してください。\n\n' +
      body;
  }

  try {
    const r = await fetch('https://api.anthropic.com/v1/messages', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'x-api-key': key,
        'anthropic-version': '2023-06-01',
      },
      body: JSON.stringify({
        model: 'claude-sonnet-4-5',
        max_tokens: 1024,
        messages: [{ role: 'user', content: prompt }],
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
}
