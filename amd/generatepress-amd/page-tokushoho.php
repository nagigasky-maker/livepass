<?php
/*
 * Template Name: Tokushoho (特定商取引法に基づく表記)
 * Template Post Type: page
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;1,300&family=Noto+Sans+JP:wght@300;400;500&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

html, body {
  background: #07070A !important;
  color: #EDEBE6 !important;
  font-family: 'Montserrat', 'Noto Sans JP', sans-serif;
  font-weight: 300;
  -webkit-font-smoothing: antialiased;
}

/* ── HEADER ── */
.pp-header {
  position: fixed; top: 0; left: 0; right: 0;
  z-index: 100;
  display: flex; align-items: center; justify-content: space-between;
  padding: 20px 28px;
  background: linear-gradient(to bottom, rgba(7,7,10,0.92) 0%, transparent 100%);
}
.pp-logo { display: block; }
.pp-logo img { height: 26px; width: auto; mix-blend-mode: screen; }
.pp-back {
  font-size: 11px; font-weight: 400;
  letter-spacing: 0.28em; text-transform: uppercase;
  color: #EDEBE6; text-decoration: none; opacity: 0.65;
  transition: opacity 0.2s;
}
.pp-back:hover { opacity: 1; color: #C8100A; }

/* ── CONTENT ── */
.pp-wrap { max-width: 760px; margin: 0 auto; padding: 100px 28px 80px; }

.pp-eyebrow {
  font-size: 9px; font-weight: 500;
  letter-spacing: 0.42em; text-transform: uppercase;
  color: #C8100A;
  margin-bottom: 14px;
}

.pp-title {
  font-size: clamp(22px, 4.2vw, 32px);
  font-weight: 300;
  line-height: 1.3;
  letter-spacing: -0.005em;
  color: #EDEBE6;
  margin-bottom: 14px;
  opacity: 0.95;
}

.pp-lead {
  font-size: 13px;
  font-weight: 300;
  line-height: 1.85;
  color: rgba(237,235,230,0.62);
  margin-bottom: 44px;
  max-width: 620px;
}

.ts-table {
  width: 100%;
  border-top: 1px solid rgba(237,235,230,0.1);
}
.ts-row {
  display: flex;
  gap: 18px;
  padding: 18px 0;
  border-bottom: 1px solid rgba(237,235,230,0.08);
  align-items: flex-start;
}
.ts-key {
  flex: 0 0 200px;
  font-size: 11px;
  font-weight: 500;
  letter-spacing: 0.14em;
  color: rgba(237,235,230,0.55);
  text-transform: uppercase;
  line-height: 1.6;
  padding-top: 2px;
}
.ts-val {
  flex: 1 1 auto;
  font-size: 13px;
  font-weight: 300;
  line-height: 1.85;
  color: rgba(237,235,230,0.88);
}
.ts-val strong { font-weight: 500; color: #EDEBE6; }
.ts-val a {
  color: #C8100A; text-decoration: none;
  border-bottom: 1px solid rgba(200,16,10,0.35);
}
.ts-val a:hover { border-color: #C8100A; }
.ts-val small {
  display: block;
  margin-top: 4px;
  font-size: 11px;
  color: rgba(237,235,230,0.48);
  letter-spacing: 0.02em;
}
.ts-val ul { padding-left: 1.15em; margin-top: 4px; }
.ts-val li { margin-bottom: 3px; }

.ts-note {
  margin-top: 44px;
  padding: 22px 24px;
  background: rgba(237,235,230,0.03);
  border-left: 2px solid rgba(200,16,10,0.65);
  font-size: 12px;
  font-weight: 300;
  line-height: 1.85;
  color: rgba(237,235,230,0.72);
}
.ts-note strong { color: #EDEBE6; font-weight: 500; }

@media (max-width: 560px) {
  .ts-row { flex-direction: column; gap: 4px; padding: 16px 0; }
  .ts-key { flex: 1 1 auto; font-size: 10px; letter-spacing: 0.18em; }
  .ts-val { font-size: 13px; }
}

/* ── FOOTER ── */
.pp-footer {
  text-align: center;
  padding: 32px 28px 48px;
  font-size: 10px;
  font-weight: 300;
  letter-spacing: 0.18em;
  color: rgba(237,235,230,0.3);
}

/* GeneratePressの残骸を非表示 */
#site-header, .site-header, #masthead,
#site-footer, .site-footer,
.navigation-bar, #page > header, #page > footer { display: none !important; }
#page, #content, .site-content, #primary, main {
  margin: 0 !important; padding: 0 !important;
  max-width: 100% !important;
  background: #07070A !important;
}
</style>
</head>
<body class="tokushoho-dark">

<div class="pp-header">
  <a class="pp-logo" href="<?= home_url('/') ?>">
    <img src="<?= get_stylesheet_directory_uri() ?>/logos/amdheaderlogo.png" alt="ALL MUST DANCE™">
  </a>
  <a class="pp-back" href="<?= home_url('/') ?>">← Back</a>
</div>

<div id="page">
  <main id="primary">
    <div class="pp-wrap">

      <div class="pp-eyebrow">LEGAL · COMMERCE DISCLOSURE</div>
      <h1 class="pp-title">特定商取引法に基づく表記</h1>
      <p class="pp-lead">
        ALL MUST DANCE™ が運営するウェブサイト及びイベントチケット販売に関して、特定商取引法第11条（通信販売についての広告）に基づき以下のとおり表示します。
      </p>

      <div class="ts-table">

        <div class="ts-row">
          <div class="ts-key">販売事業者</div>
          <div class="ts-val">
            ALL MUST DANCE™ <small>運営: SPACE COOKING™</small>
          </div>
        </div>

        <div class="ts-row">
          <div class="ts-key">運営責任者</div>
          <div class="ts-val">NOBBY (NIKO)</div>
        </div>

        <div class="ts-row">
          <div class="ts-key">所在地</div>
          <div class="ts-val">
            〒150-0043<br>東京都渋谷区道玄坂<br>
            <small>※ご請求があれば遅滞なく開示いたします</small>
          </div>
        </div>

        <div class="ts-row">
          <div class="ts-key">電話番号</div>
          <div class="ts-val">
            非公開<small>※ご請求があれば遅滞なく開示いたします。お問い合わせは原則としてメールにて承ります。</small>
          </div>
        </div>

        <div class="ts-row">
          <div class="ts-key">メールアドレス</div>
          <div class="ts-val">
            <a href="mailto:niko@allmustdance.com">niko@allmustdance.com</a>
          </div>
        </div>

        <div class="ts-row">
          <div class="ts-key">ウェブサイト</div>
          <div class="ts-val">
            <a href="https://allmustdance.com/">https://allmustdance.com/</a>
          </div>
        </div>

        <div class="ts-row">
          <div class="ts-key">販売価格</div>
          <div class="ts-val">
            各イベント／ワークショップ／グッズページに表示の金額 (消費税込)
            <small>イベントチケットは Early Bird / Advance / Door の区分により異なります。各商品ページを必ずご確認ください。</small>
          </div>
        </div>

        <div class="ts-row">
          <div class="ts-key">商品代金以外の必要料金</div>
          <div class="ts-val">
            <ul>
              <li>決済プラットフォーム手数料 (チケット販売の場合、販売事業者側で加算される場合があります)</li>
              <li>通信費 (お客様ご負担)</li>
              <li>グッズ購入時の送料 (ZZAZZ ZA™ ストアのページを参照)</li>
            </ul>
          </div>
        </div>

        <div class="ts-row">
          <div class="ts-key">支払方法</div>
          <div class="ts-val">
            クレジットカード (VISA / Master / AMEX / JCB 他)、PayPay、Apple Pay、コンビニ決済 等<small>ご利用いただける決済手段は外部決済プラットフォーム (Peatix / STORES 等) の仕様に準じます。</small>
          </div>
        </div>

        <div class="ts-row">
          <div class="ts-key">支払時期</div>
          <div class="ts-val">
            各決済手段の定める時期にお支払いください。原則として購入手続き完了時に決済が確定します。
          </div>
        </div>

        <div class="ts-row">
          <div class="ts-key">商品・サービスの引渡時期</div>
          <div class="ts-val">
            <strong>イベントチケット</strong>: ご購入後、決済確定メールに電子チケットのリンクが発行されます (即時)。<br>
            <strong>ワークショップ</strong>: ご購入後、開催日前までに入場方法をメールでご案内します。<br>
            <strong>グッズ</strong>: ZZAZZ ZA™ ストアの配送ポリシーに準じます。
          </div>
        </div>

        <div class="ts-row">
          <div class="ts-key">返品・キャンセルについて</div>
          <div class="ts-val">
            <strong>イベント／ワークショップチケット</strong>: イベントの性質上、購入後のお客様都合によるキャンセル・返金は承っておりません。ただし主催者側の都合で中止となった場合、販売手数料を除く額を返金します。<br>
            <small>公演の延期・時間変更等の場合は、別途ご案内いたします。</small><br><br>
            <strong>グッズ</strong>: ZZAZZ ZA™ ストアの返品・交換規定に準じます。商品到着後 7 日以内に不良品・誤配送があった場合は<a href="mailto:niko@allmustdance.com">niko@allmustdance.com</a>までご連絡ください。
          </div>
        </div>

        <div class="ts-row">
          <div class="ts-key">動作環境 (電子チケット)</div>
          <div class="ts-val">
            最新版の iOS Safari / Android Chrome / デスクトップブラウザでの閲覧を推奨します。電子チケットの表示には外部決済プラットフォーム指定のアプリ／ブラウザが必要となる場合があります。
          </div>
        </div>

        <div class="ts-row">
          <div class="ts-key">個人情報の取扱い</div>
          <div class="ts-val">
            お客様の個人情報の取扱いについては、<a href="<?= home_url('/privacy-policy/') ?>">プライバシーポリシー</a>をご確認ください。
          </div>
        </div>

      </div>

      <div class="ts-note">
        <strong>NOTICE.</strong> イベントチケットおよびワークショップチケットの販売は、Peatix・STORES・BASE 等の外部決済プラットフォームを通じて行われます。各プラットフォームには独自の特定商取引法表記・利用規約がございますので、購入の際はそちらも併せてご確認ください。<br><br>
        本ページの記載内容は予告なく変更される場合があります。最終更新日は本ページの直下に表示されます。
      </div>

    </div>
  </main>
</div>

<div class="pp-footer">
  最終更新: 2026.04 &nbsp;·&nbsp; © ALL MUST DANCE™ · Tokyo · 2026
</div>

<?php wp_footer(); ?>
</body>
</html>
