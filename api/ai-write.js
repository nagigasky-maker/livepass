/**
 * POST /api/ai-write
 *
 * Body: { action, title, body, fields }
 *
 * action:
 *   "write" — from the article title (+ optional current body as a seed),
 *             draft a 300–500 char Japanese article body.
 *   "proof" — proofread the existing body — fix typos, kanji misconversions,
 *             and awkward phrasing while keeping tone and content.
 *   "event-supplement" — given the already-filled structured fields for an
 *             EVENT/WORKSHOP/EXHIBITION (title, venue, date, time, category,
 *             tags, artists, team), generate a supplementary description
 *             block that ONLY adds information NOT already captured in the
 *             form. Output follows the "■ LABEL" format that the detail
 *             modal parses into credits-style rows.
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
  const { action, title, body, fields } = req.body || {};
  if (!['write', 'proof', 'event-supplement'].includes(action)) {
    return res.status(400).json({ error: 'invalid action' });
  }

  const key = process.env.ANTHROPIC_API_KEY;
  if (!key) {
    const stubText =
      action === 'event-supplement'
        ? '■ DRESS CODE\nFree (smart casual welcome)\n\n■ NOTES\nID必須 / 20歳未満入場不可\n撮影は DJブース側のみ OK\n\n■ ACCESS\n最寄駅から徒歩5分\n\n（※AI未設定のためサンプル出力。Vercel に ANTHROPIC_API_KEY を設定すると実際のイベント内容に合わせた補足が生成されます）'
        : (body || '') +
          '\n\n（AI機能を使うには Vercel の環境変数に ANTHROPIC_API_KEY を設定してください）';
    return res.status(200).json({ text: stubText, stub: true });
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
  } else if (action === 'proof') {
    prompt =
      '以下の日本語記事本文を校正してください。誤字脱字・漢字変換ミス・' +
      '不自然な表現を直し、文体と内容は保持してください。修正後の本文のみを返してください。\n\n' +
      body;
  } else {
    // event-supplement
    const f = fields || {};
    const alreadyKnown = [
      f.title    && `- タイトル: ${f.title}`,
      f.category && `- カテゴリ: ${f.category}`,
      f.venue    && `- 会場: ${f.venue}`,
      f.date     && `- 日付: ${f.date}${f.time ? ' ' + f.time : ''}${f.endTime ? ' - ' + f.endTime : ''}${f.endDate ? ' - ' + f.endDate : ''}`,
      f.price    && `- 価格: ¥${f.price}`,
      f.tags     && f.tags.length     && `- タグ: ${f.tags.join(', ')}`,
      f.artists  && f.artists.length  && `- 出演アーティスト: ${f.artists.map(a => a.name || a).join(', ')}`,
      f.team     && f.team.length     && `- チーム: ${f.team.map(t => `${t.name}${t.role ? '(' + t.role + ')' : ''}`).join(', ')}`,
    ].filter(Boolean).join('\n');

    prompt =
      'あなたは LIVE PASS（ダンス／DJ／イベントカルチャー）の編集アシスタントです。\n' +
      'イベント投稿フォームには以下の「構造化フィールド」が既に入力されています。\n' +
      '詳細モーダルには別途これらが自動表示されるので、本文では絶対に重複させないでください:\n\n' +
      alreadyKnown + '\n\n' +
      '以下の制約で、イベントの詳細をより深く伝える「補足情報だけ」を日本語で書いてください:\n\n' +
      '1. 上記フィールドで既に示された情報は決して繰り返さない（タイトル/会場/日時/出演者/価格/タグ/チームは自動表示）\n' +
      '2. 各セクションは "■ LABEL" の短い英語見出しで始める (例: ■ DRESS CODE / ■ NOTES / ■ ACCESS / ■ HOSTS / ■ MUSIC / ■ FOOD & DRINK / ■ AGE RESTRICTION)\n' +
      '3. セクション本文は 1〜3 行、箇条書きが自然なら "・" で始める\n' +
      '4. 全体で 6〜10 行程度、簡潔に\n' +
      '5. イベントのカテゴリと出演者から文脈を読み取り、実用的な補足 (ドレスコード、入場条件、アクセス、持ち物、音楽ジャンルの説明、飲食、年齢制限、スポンサー/協賛、主催者メッセージ、撮影ルール等) に焦点\n\n' +
      '追加で伝えるべき補足セクションだけを返してください。前置きは不要です。';
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
