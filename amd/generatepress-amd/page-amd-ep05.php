<?php
/**
 * ALL MUST DANCE - front-page.php
 * GeneratePress Child Theme
 */

// Site Settings固定ページのIDを取得
$settings_page = get_page_by_path('site-settings');
$sid = $settings_page ? $settings_page->ID : 0;

// Party情報
$party_video     = get_field('party_hero_video', $sid);
$party_date      = get_field('party_event_date', $sid);
$party_venue     = get_field('party_venue', $sid);
$party_time      = get_field('party_time', $sid);
$party_ticket    = get_field('party_ticket_url', $sid);
$party_eb_price  = get_field('party_eb_price', $sid);
$party_adv_price = get_field('party_adv_price', $sid);

// Workshop情報
$ws_video      = get_field('ws_hero_video', $sid);
$ws_date       = get_field('ws_date', $sid);
$ws_venue      = get_field('ws_venue', $sid);
$ws_time       = get_field('ws_time', $sid);
$ws_ticket     = get_field('ws_ticket_url', $sid);
$ws_ticket2    = get_field('ws_ticket_url_2', $sid);
$ws_1w_price   = get_field('ws_eb_price', $sid);
$ws_3w_price   = get_field('ws_gen_price', $sid);
// フォールバック: メタボックス直接取得 → さらに固定値
if(!$ws_ticket)   $ws_ticket   = get_post_meta($sid, 'ws_ticket_url', true)   ?: 'https://zzazz-za.stores.jp/items/69bd3dbba499220687ba06f6';
if(!$ws_ticket2)  $ws_ticket2  = get_post_meta($sid, 'ws_ticket_url_2', true)  ?: 'https://zzazz-za.stores.jp/items/69bd3ce63abc001fe0315977';
if(!$ws_date)     $ws_date     = get_post_meta($sid, 'ws_date', true)          ?: '64BEAT · APR 1 · 8 · 15';
if(!$ws_venue)    $ws_venue    = get_post_meta($sid, 'ws_venue', true)         ?: 'noah studio NAKANO · EMOTIONS 高円寺';
if(!$ws_time)     $ws_time     = get_post_meta($sid, 'ws_time', true)          ?: 'Coming soon';

// Partyアーティスト取得
$party_artists = get_posts([
    'post_type'   => 'artist',
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
    'numberposts' => -1,
    'meta_query'  => [[
        'key'     => 'chapter',
        'value'   => ['party','both'],
        'compare' => 'IN',
    ]]
]);

// Workshopアーティスト取得
$ws_artists = get_posts([
    'post_type'   => 'artist',
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
    'numberposts' => -1,
    'meta_query'  => [[
        'key'     => 'chapter',
        'value'   => ['workshop','both'],
        'compare' => 'IN',
    ]]
]);
?>
<!DOCTYPE html>
<html lang="ja" id="amdHtml">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>ALL MUST DANCE™</title>
<?php
// GeneratePressのCSSを読み込まない - タイトルとfaviconのみ
echo '<link rel="icon" href="' . get_stylesheet_directory_uri() . '/logos/amdheaderlogo.png">' . PHP_EOL;
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet"></noscript>
<style>
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
:root {
  --black: #0C0F1A;
  --white: #EDEBE6;
  --red:   #E8100A;
  --blue:  #1A2E6B;
  --line:  rgba(237,235,230,0.09);
}

/* ── BASE ── */
html {
  height: 100%;
}
/* Safari動的ビューポート: JSで--amd-full-hを更新 */
:root {
  --amd-full-h: 100dvh;
}
body {
  background: var(--black);
  color: var(--white);
  font-family: 'Noto Sans JP','Montserrat',sans-serif;
  font-weight: 300;
  font-feature-settings: 'palt';
  -webkit-font-smoothing: antialiased;
  overflow-y: scroll;
  min-height: 100%;
}

/* GP上書き */
#site-header,#site-footer,.site-header,.site-footer,.navigation-bar,#masthead { display:none !important; }
#page,#content,.site-content,#primary,main,article,.entry-content,.content-area { margin:0 !important; padding:0 !important; max-width:100% !important; display:block !important; width:100% !important; }

/* DECK / VTRACK */
#vtrack, #deck { width:100%; }

/* ── HEADER ── */
#amd-header {
  position: fixed; top:0; left:0; right:0; z-index:9999;
  display: flex; justify-content:space-between; align-items:center;
  padding: 20px 40px;
  background: linear-gradient(to bottom, rgba(12,15,26,0.85) 0%, transparent 100%);
  pointer-events: none;
}
.logo { pointer-events:all; text-decoration:none; }
.logo img { display:block; mix-blend-mode:screen; }
.header-right { display:flex; align-items:center; gap:12px; pointer-events:all; }
.lang-toggle {
  background:none; border:none; cursor:pointer;
  display:flex; align-items:center; gap:3px;
  font-size:10px; font-weight:400; letter-spacing:0.22em;
  pointer-events:all; padding:4px 2px;
}
.lang-jp, .lang-en {
  color:rgba(237,235,230,0.38);
  transition:color 0.2s, opacity 0.2s;
}
.lang-sep { color:rgba(237,235,230,0.2); }
[data-lang="jp"] .lang-jp { color:var(--white); font-weight:700; }
[data-lang="en"] .lang-en { color:var(--white); font-weight:700; }
#chap-counter, #panel-counter { display:none; }

/* ── SECTIONS (縦スナップ) ── */
.chapter {
  width: 100%;
  height: var(--amd-full-h);
  position: relative;
}
/* PARTY・WORKSHOPは100svh固定 */
#c0, #c1 {
  max-height: var(--amd-full-h);
  min-height: var(--amd-full-h);
  overflow: hidden;
}
/* VIDEO・STORE・CONNECTはコンテンツ量で伸縮 */
#c2, #c3, #c4 {
  height: auto;
}
/* コンテンツが多いchapterのみ伸ばす */
.chapter.chapter-auto {
  height: auto;
}

/* ── PANEL TRACK (横スクロール) ── */
.panel-track {
  display: flex;
  width: 100%;
  height: var(--amd-full-h);
  overflow-x: scroll;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;

}
.panel-track::-webkit-scrollbar { display:none; }

/* ── PANEL ── */
.panel {
  flex: 0 0 100%; width:100%; height:var(--amd-full-h);
  position: relative;
  display: flex; flex-direction:column;
  justify-content: flex-end;
  overflow: hidden;
}
/* ソロパネルも100svhに収める */
.panel.solo {
  display: flex; flex-direction: column;
  height: var(--amd-full-h);
}
#c2 .panel.solo, #c3 .panel.solo, #c4 .panel.solo {
  height: auto;
  overflow: visible;
  min-height: 0;
}
#c3 .panel-content, #c4 .panel-content {
  height: auto;
  overflow: visible;
  min-height: 0;
}

.panel.content-panel {
  justify-content: flex-start;
}
.panel-bg {
  position:absolute; inset:0; z-index:0;
  background: var(--black);
}
.vig {
  position:absolute; inset:0; z-index:1; pointer-events:none;
  background: linear-gradient(to top, rgba(12,15,26,0.97) 0%, rgba(12,15,26,0.55) 36%, rgba(12,15,26,0.15) 65%, transparent 100%);
}
.vig-heavy { position:absolute; inset:0; z-index:1; pointer-events:none; background:rgba(10,13,22,0.92); }
.vig-artist { position:absolute; inset:0; z-index:1; pointer-events:none; background:linear-gradient(to top, rgba(12,15,26,0.97) 0%, rgba(12,15,26,0.78) 40%, rgba(12,15,26,0.5) 70%, rgba(12,15,26,0.25) 100%); }

.panel-content {
  position:relative; z-index:2;
  padding: 0 56px 52px;
  width:100%; box-sizing:border-box;
  flex-shrink:0;
}
.content-panel .panel-content {
  position:relative; z-index:2;
  width:100%; height:100%;
  padding: 80px 56px 60px;
  overflow-y: hidden;
  overflow-x: hidden;
  box-sizing:border-box;
}
/* アーティストパネルは下寄せ */
#p0-1 .panel-content,
#p1-1 .panel-content {
  height: auto;
  margin-top: auto;
  padding-top: 0;
}

/* Noto for JP */
.panel-content p,.panel-content li,.zi-body,.zine-issue-body p,.connect-body,.body-txt {
  font-family: 'Noto Sans JP','Montserrat',sans-serif;
}

/* ── TYPOGRAPHY ── */
.eyebrow { font-size:10px; font-weight:500; letter-spacing:0.44em; text-transform:uppercase; color:var(--red); opacity:0.88; margin-bottom:16px; }
[data-lang="en"] [data-en]:not([data-en=""]) { content:attr(data-en); }
.lang-switchable { transition:opacity 0.25s; }
.h-hero { font-family:Arial,'Arial Black',sans-serif; font-size:clamp(26px,7vw,44px); line-height:0.96; letter-spacing:0.01em; color:var(--white); font-weight:900; }
.h-section { font-family:Arial,'Arial Black',sans-serif; font-size:clamp(28px,5.5vw,52px); line-height:0.94; letter-spacing:0.01em; color:var(--white); margin-bottom:20px; font-weight:900; }
.connect-h2 { font-family:Arial,'Arial Black',sans-serif; font-size:clamp(30px,6.5vw,56px); line-height:0.92; letter-spacing:0.01em; color:var(--white); margin-bottom:20px; font-weight:900; }
.connect-h2 span { color:var(--red); }
.af-name { font-family:Arial,'Arial Black',sans-serif; font-size:clamp(32px,7vw,64px); line-height:0.92; letter-spacing:0.01em; color:var(--white); margin-bottom:20px; font-weight:900; }
.body-txt { font-size:14px; font-weight:300; line-height:2.0; color:var(--white); opacity:0.88; max-width:380px; letter-spacing:0.04em; }
.body-txt-en { font-size:13px; font-weight:300; font-style:italic; line-height:1.9; color:var(--white); opacity:0.72; max-width:380px; margin-top:14px; }
/* JP/EN切り替え */
[data-lang="jp"] .body-txt    { display:block; }
[data-lang="jp"] .body-txt-en { display:none; }
[data-lang="en"] .body-txt    { display:none; }
[data-lang="en"] .body-txt-en { display:block; margin-top:0; font-style:normal; opacity:0.88; }
[data-lang="jp"] .af-desc     { display:block; }
[data-lang="jp"] .af-desc-en  { display:none; }
[data-lang="en"] .af-desc     { display:none; }
[data-lang="en"] .af-desc-en  { display:block; font-style:normal; opacity:0.92; }
.cta-row { display:flex; gap:14px; margin-top:32px; }
.btn-fill { display:inline-block; padding:18px 40px; background:var(--red); font-size:14px; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#EDEBE6; text-decoration:none; cursor:pointer; border:2px solid var(--red); position:relative; overflow:hidden; transition:transform 0.2s,box-shadow 0.25s; }
.btn-fill:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(232,16,10,0.35); }
.btn-fill:active { transform:translateY(0); }
.btn-ghost { display:inline-block; padding:16px 36px; border:2px solid rgba(237,235,230,0.6); font-size:13px; font-weight:500; letter-spacing:0.22em; text-transform:uppercase; color:#EDEBE6; text-decoration:none; cursor:pointer; transition:border-color 0.2s,transform 0.2s; }
.btn-ghost:hover { transform:translateY(-2px); border-color:var(--white); }
.meta-line { margin-top:12px; font-size:11px; font-weight:300; letter-spacing:0.32em; text-transform:uppercase; color:var(--white); opacity:0.78; line-height:1.8; }
.a-subtle { font-size:12px; font-weight:400; letter-spacing:0.25em; text-transform:uppercase; color:var(--white); text-decoration:none; opacity:0.8; border-bottom:1px solid rgba(237,235,230,0.25); padding-bottom:2px; transition:opacity 0.2s,color 0.2s; }
.a-subtle:hover { opacity:1; color:var(--red); border-color:var(--red); }

/* ── LAYOUT ── */
.two-col { display:grid; grid-template-columns:1fr 1fr; gap:72px; align-items:end; }
.info-table { width:100%; }
.info-row { display:flex; justify-content:space-between; align-items:baseline; padding:14px 0; border-bottom:1px solid rgba(237,235,230,0.14); }
.info-row:first-child { border-top:1px solid rgba(237,235,230,0.14); }
.ik { font-size:10px; font-weight:400; letter-spacing:0.38em; text-transform:uppercase; color:var(--white); opacity:0.82; }
.iv { font-size:13px; font-weight:300; text-align:right; letter-spacing:0.06em; color:var(--white); opacity:0.92; }
.iv small { display:block; font-size:11px; letter-spacing:0.15em; opacity:0.82; margin-top:2px; }
.ticket-section { margin-top:26px; }
.ticket-head { display:flex; justify-content:space-between; align-items:center; padding-bottom:11px; border-bottom:1px solid rgba(237,235,230,0.14); }
.ticket-head-lbl { font-size:13px; font-weight:500; letter-spacing:0.25em; text-transform:uppercase; color:var(--white); opacity:0.88; }
.ticket-head-note { font-size:12px; font-weight:500; letter-spacing:0.18em; color:var(--red); opacity:0.92; }
.trow { display:flex; align-items:center; justify-content:space-between; padding:15px 0; border-bottom:1px solid rgba(237,235,230,0.14); text-decoration:none; color:var(--white); transition:padding-left 0.2s; cursor:pointer; }
.trow:hover { padding-left:8px; }
.trow.disabled { opacity:0.55; pointer-events:none; }
.trow-left { display:flex; align-items:baseline; gap:18px; }
.trow-type { font-size:11px; font-weight:400; letter-spacing:0.3em; text-transform:uppercase; color:var(--white); opacity:0.82; min-width:80px; }
.trow-price { font-family:Arial,'Arial Black',sans-serif; font-size:28px; font-weight:900; line-height:1; letter-spacing:0.03em; color:var(--white); transition:color 0.2s; }
.trow:hover .trow-price { color:var(--red); }
.trow-right { display:flex; align-items:center; gap:14px; }
.trow-usd { font-size:9px; font-weight:200; letter-spacing:0.18em; color:var(--white); opacity:0.65; }
.trow-tag { font-size:11px; font-weight:500; letter-spacing:0.18em; text-transform:uppercase; padding:5px 11px; border:1px solid rgba(200,16,10,0.8); color:var(--red); }
.trow-arr { font-size:11px; color:var(--red); opacity:0; transform:translateX(-5px); transition:opacity 0.2s,transform 0.2s; }
.trow:hover .trow-arr { opacity:1; transform:translateX(0); }

/* ── ARTISTS ── */
.artists-layout { display:grid; grid-template-columns:220px 1fr; gap:56px; align-items:end; }
.artist-strip { display:grid; grid-template-columns:repeat(6,1fr); gap:1px; background:var(--line); }
.ac { background:#09090c; display:flex; flex-direction:column; overflow:hidden; transition:background 0.28s; }
.ac:hover { background:#0e0e14; }
.ac-img { aspect-ratio:2/3; background:linear-gradient(160deg,#111428 0%,#0C0F1A 100%); position:relative; display:flex; align-items:center; justify-content:center; overflow:hidden; }
.ac-img img { width:100%; height:100%; object-fit:cover; opacity:0; transition:opacity 0.6s; }
.ac-img img.on { opacity:1; }
.ac-initial { font-family:Arial,'Arial Black',sans-serif; font-size:38px; font-weight:900; color:rgba(237,235,230,0.06); }
.ac-info { padding:10px 8px 12px; }
.ac-name { font-size:11px; font-weight:400; letter-spacing:0.05em; color:var(--white); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ac-sub { font-size:7px; font-weight:200; letter-spacing:0.32em; text-transform:uppercase; color:var(--white); opacity:0.65; margin-top:2px; }
.ac.mystery .ac-name { opacity:0.48; }
.ac.mystery .ac-img { background:#080808; }
.af-genre { font-size:11px; font-weight:400; letter-spacing:0.45em; text-transform:uppercase; color:var(--red); opacity:0.95; margin-bottom:18px; text-shadow:0 1px 8px rgba(0,0,0,0.95); }
.af-desc { font-size:14px; font-weight:300; line-height:2.0; color:var(--white); opacity:0.92; max-width:560px; margin-top:0; margin-bottom:8px; text-shadow:0 1px 8px rgba(0,0,0,0.95); overflow:hidden; }
.af-desc-en { font-size:14px; font-weight:400; font-style:italic; line-height:1.75; color:var(--white); opacity:0.88; max-width:560px; text-shadow:0 1px 8px rgba(0,0,0,0.95); overflow:hidden; }
.amd-word { display:inline-block; will-change:transform,opacity; }
.af-links { display:flex; gap:20px; margin-top:12px; align-items:center; }

/* ── ARTIST GROUP PANELS ── */
.art-cover-content { display:flex; flex-direction:column; justify-content:flex-end; height:100%; }
.art-cover-names { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:14px; }
.art-cover-names span { font-size:11px; font-weight:400; letter-spacing:0.22em; color:rgba(237,235,230,0.65); }
.art-group-hint { font-size:10px; letter-spacing:0.38em; text-transform:uppercase; color:rgba(237,235,230,0.4); margin-top:8px; }
.art-detail-overlay {
  position:absolute; inset:0; z-index:20;
  display:flex; flex-direction:column;
  overflow:hidden;
  transform:translateY(100%);
  transition:transform 0.42s cubic-bezier(0.32,0,0.2,1);
}
.art-detail-overlay.open { transform:translateY(0); }

/* ── FLASH EFFECT ── */
#amd-chapter-line {
  position:fixed; top:0; left:0; right:0; height:2px;
  background:var(--red); z-index:9998;
  transform-origin:left center; transform:scaleX(0);
  pointer-events:none;
}
.amd-red-flash {
  position:fixed; inset:0; z-index:9997;
  background:var(--red); opacity:0;
  pointer-events:none;
}

/* ── TICKET OVERLAY ── */
.amd-ticket-overlay {
  position:fixed; inset:0; z-index:190;
  transform:translateY(100%);
  transition:transform 0.45s cubic-bezier(0.32,0,0.2,1);
  overflow:hidden;
  background:var(--black);
}
.amd-ticket-overlay.open { transform:translateY(0); }
.amd-ticket-close {
  position:fixed;
  bottom:max(32px, calc(env(safe-area-inset-bottom) + 24px));
  right:28px; z-index:250;
  background:none; border:1px solid rgba(237,235,230,0.2);
  color:rgba(237,235,230,0.6); font-size:18px; line-height:1;
  width:44px; height:44px; display:flex; align-items:center;
  justify-content:center; cursor:pointer; border-radius:50%;
  transition:border-color .2s, color .2s;
}
.amd-ticket-close:hover { border-color:var(--white); color:var(--white); }

/* ── ARTIST PANEL OVERLAY ── */
.amd-artist-panel {
  position:fixed; inset:0; z-index:200;
  transform:translateY(100%);
  transition:transform 0.45s cubic-bezier(0.32,0,0.2,1);
  display:flex; flex-direction:column;
}
.amd-artist-panel.open { transform:translateY(0); }
.amd-ap-bg { position:absolute; inset:0; background:rgba(12,15,26,0.97); pointer-events:none; }
.amd-ap-inner { position:relative; z-index:2; height:100%; display:flex; flex-direction:column; padding:max(72px, calc(env(safe-area-inset-top) + 60px)) 0 0; overflow:hidden; }
.amd-ap-close { position:fixed; bottom:max(32px, calc(env(safe-area-inset-bottom) + 24px)); right:28px; z-index:250; background:none; border:1px solid rgba(237,235,230,0.2); color:rgba(237,235,230,0.6); font-size:18px; line-height:1; width:44px; height:44px; display:flex; align-items:center; justify-content:center; cursor:pointer; border-radius:50%; transition:border-color .2s, color .2s; }
.amd-ap-title { font-size:9px; letter-spacing:0.42em; text-transform:uppercase; color:rgba(237,235,230,0.3); padding:0 20px; margin-bottom:16px; }
.amd-ap-groups { display:flex; flex-direction:column; gap:3px; padding:0 0 40px; overflow:visible; }
.amd-ap-group { position:relative; height:calc((var(--amd-full-h, 100svh) - 130px) / 3); min-height:140px; max-height:260px; overflow:hidden; cursor:pointer; border-radius:0; touch-action:manipulation; -webkit-tap-highlight-color:transparent; user-select:none; flex-shrink:0; display:block; width:100%; text-align:left; box-sizing:border-box; }
.amd-ap-group img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:0.65; transition:opacity 0.3s; pointer-events:none; }
.amd-ap-group:hover img, .amd-ap-group:active img { opacity:0.88; }
.amd-ap-group-vig { position:absolute; inset:0; background:linear-gradient(180deg,transparent 35%,rgba(12,15,26,0.82) 100%); pointer-events:none; }
.amd-ap-group-info { position:absolute; bottom:0; left:0; right:0; padding:16px 20px; pointer-events:none; }
.amd-ap-group-sub { font-size:9px; letter-spacing:0.38em; text-transform:uppercase; color:var(--red); margin-bottom:4px; }
.amd-ap-group-name { font-size:24px; font-weight:300; letter-spacing:0.05em; color:var(--white); line-height:1.1; }
.amd-ap-group-members { font-size:10px; letter-spacing:0.18em; color:rgba(237,235,230,0.45); margin-top:6px; line-height:1.8; }

/* ── CARD STACK OVERLAY (031) ── */
.amd-card-stack {
  position:fixed; inset:0; z-index:210;
  transform:translateY(100%);
  transition:transform 0.45s cubic-bezier(0.32,0,0.2,1);
  background:var(--black);
}
.amd-card-stack.open { transform:translateY(0); }
.amd-cs-close { position:absolute; top:max(22px, calc(env(safe-area-inset-top) + 14px)); left:24px; z-index:30; background:none; border:none; color:rgba(237,235,230,0.5); font-size:11px; letter-spacing:0.28em; text-transform:uppercase; cursor:pointer; }
.amd-cs-title { position:absolute; top:max(24px, calc(env(safe-area-inset-top) + 14px)); left:50%; transform:translateX(-50%); z-index:30; font-size:9px; letter-spacing:0.38em; text-transform:uppercase; color:rgba(237,235,230,0.3); white-space:nowrap; }
#cardStackStage { position:absolute; inset:0; }
.amd-card {
  position:absolute; inset:0;
  will-change:transform,opacity;
}
.amd-card-content { position:absolute; inset:0; z-index:2; display:flex; flex-direction:column; justify-content:flex-end; padding:28px 24px 44px; }
.amd-card-num { font-size:10px; letter-spacing:0.32em; color:rgba(237,235,230,0.28); margin-top:16px; }
.amd-card-nav { position:absolute; bottom:0; left:0; right:0; display:flex; justify-content:space-between; align-items:center; padding:14px 24px; z-index:300; border-top:1px solid rgba(237,235,230,0.1); background:rgba(12,15,26,0.6); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); }
.amd-card-nav-btn { background:none; border:none; color:rgba(237,235,230,0.55); font-size:11px; letter-spacing:0.32em; text-transform:uppercase; cursor:pointer; padding:8px 0; transition:color 0.2s; }
.amd-card-nav-btn:hover { color:var(--white); }
.amd-card-nav-btn:disabled { opacity:0; cursor:default; pointer-events:none; }
.art-detail-slide {
  position:absolute; inset:0;
  transition:transform 0.35s cubic-bezier(0.32,0,0.2,1);
}
.art-detail-bg { position:absolute; inset:0; }
.art-detail-content { position:relative; z-index:2; height:100%; display:flex; flex-direction:column; justify-content:flex-end; }
.art-detail-close {
  position:absolute; top:72px; left:28px;
  background:none; border:none; color:var(--white);
  font-size:12px; letter-spacing:0.28em; text-transform:uppercase;
  cursor:pointer; opacity:0.65; z-index:5;
  transition:opacity 0.2s;
}
.art-detail-close:hover { opacity:1; }
.art-detail-counter {
  font-size:10px; letter-spacing:0.32em; color:rgba(237,235,230,0.38);
  margin-top:12px; text-transform:uppercase;
}
.af-link { display:inline-flex; align-items:center; justify-content:center; text-decoration:none; transition:opacity 0.2s, transform 0.2s; }
.af-link:hover { color:var(--white); transform:scale(1.1); }
.af-link svg { width:40px; height:40px; fill:currentColor; }

/* ── ZINE ── */
.zine-head { display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px; }
.zine-list { display:flex; flex-direction:column; margin-top:12px; }
.zi { display:flex; align-items:baseline; gap:16px; padding:18px 0; border-bottom:1px solid rgba(237,235,230,0.1); text-decoration:none; color:var(--white); transition:padding-left 0.2s ease; position:relative; }
.zi:hover { padding-left:8px; }
.zi:first-child { border-top:1px solid rgba(237,235,230,0.1); }
.zi-num { font-family:Arial,'Arial Black',sans-serif; font-size:36px; line-height:1; font-weight:900; color:var(--red); opacity:0.95; letter-spacing:0.02em; min-width:72px; text-align:right; flex-shrink:0; }
.zi.zi-coming .zi-num { color:rgba(237,235,230,0.15); }
.zi-body { flex:1; }
.zi-ttl { font-family:Arial,'Arial Black',sans-serif; font-weight:900; font-size:26px; line-height:1.05; letter-spacing:0.03em; color:var(--white); margin-bottom:5px; }
.zi.zi-coming .zi-ttl { color:rgba(237,235,230,0.25); }
.zi-meta { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
.zi-yr { font-size:11px; font-weight:400; letter-spacing:0.14em; color:rgba(237,235,230,0.55); }
.zi-tag { font-size:10px; font-weight:500; letter-spacing:0.22em; text-transform:uppercase; color:var(--red); opacity:0.8; border:1px solid rgba(200,16,10,0.35); padding:2px 8px; }
.zi-desc { font-size:12px; font-weight:300; line-height:1.7; color:rgba(237,235,230,0.58); margin-top:6px; max-width:420px; }
.zi-arr { font-size:16px; color:var(--red); opacity:0; flex-shrink:0; transform:translateX(-4px); transition:opacity 0.2s,transform 0.2s; }
.zi:hover .zi-arr { opacity:0.8; transform:translateX(0); }

/* ── ZINE ISSUE ── */
.zine-issue-wrap { display:flex; flex-direction:column; gap:24px; }
.zine-ep-header { padding-bottom:20px; border-bottom:1px solid rgba(237,235,230,0.12); }
.zine-issue-title { font-family:Arial,'Arial Black',sans-serif; font-size:clamp(26px,6.5vw,50px); font-weight:900; line-height:0.92; color:var(--white); margin:14px 0 10px; }
.zine-issue-date { font-size:12px; font-weight:300; letter-spacing:0.18em; color:rgba(237,235,230,0.55); }
.zine-issue-body p { font-size:14px; font-weight:300; line-height:2.1; color:rgba(237,235,230,0.82); margin-bottom:16px; }
.zine-section-label { font-size:10px; font-weight:600; letter-spacing:0.4em; text-transform:uppercase; color:var(--red); margin:24px 0 10px; opacity:0.9; }
.zine-lead { font-size:15px; font-weight:300; font-style:italic; color:rgba(237,235,230,0.88); margin-bottom:12px; line-height:1.7; }
.zine-photo-grid { display:grid; grid-template-columns:1fr 1fr; gap:4px; margin:8px 0; }
.zine-photo-grid img { width:100%; aspect-ratio:1/1; object-fit:cover; opacity:0.9; }
.zine-music-list { margin:10px 0; }
.zine-music-row { font-size:12px; font-weight:300; letter-spacing:0.06em; color:rgba(237,235,230,0.72); padding:7px 0; border-bottom:1px solid rgba(237,235,230,0.07); }
.zine-credit-block { margin-top:20px; border-top:1px solid rgba(237,235,230,0.1); padding-top:16px; display:flex; flex-direction:column; }
.zine-credit-row { display:flex; justify-content:space-between; align-items:baseline; padding:9px 0; border-bottom:1px solid rgba(237,235,230,0.06); gap:16px; }
.zine-credit-row span:first-child { font-size:10px; font-weight:500; letter-spacing:0.32em; text-transform:uppercase; color:var(--red); opacity:0.85; white-space:nowrap; flex-shrink:0; }
.zine-credit-row span:last-child { font-size:13px; font-weight:300; color:rgba(237,235,230,0.78); text-align:right; }

/* ── YOUTUBE ── */
.yt-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:20px; }
.yt-card { display:block; text-decoration:none; color:var(--white); border:1px solid rgba(237,235,230,0.08); transition:border-color 0.2s; }
.yt-card:hover { border-color:rgba(237,235,230,0.25); }
.yt-thumb { aspect-ratio:16/9; background:linear-gradient(160deg,#111428 0%,#0C0F1A 100%); display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
.yt-play { font-size:20px; color:var(--white); opacity:0.35; transition:opacity 0.2s; position:relative; z-index:2; }
.yt-card:hover .yt-play { opacity:0.8; }
.yt-info { padding:10px 12px 12px; }
.yt-title { font-size:11px; font-weight:400; letter-spacing:0.04em; color:var(--white); margin-bottom:4px; line-height:1.4; }
.yt-sub { font-size:10px; font-weight:300; color:var(--white); opacity:0.42; }

/* ── STORE ── */
.store-head { display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px; }
.store-grid { display:grid; grid-template-columns:1fr 1fr; gap:1px; background:var(--line); }
.sc { background:#0c0f1a; display:flex; flex-direction:column; overflow:hidden; cursor:pointer; transition:background 0.28s; }
.sc:hover { background:#0d0d0d; }
.sc-vis { flex:1; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; min-height:180px; }
.sc-ghost { font-family:Arial,'Arial Black',sans-serif; font-size:36px; font-weight:900; color:rgba(237,235,230,0.05); text-align:center; line-height:1.25; transition:color 0.28s; }
.sc:hover .sc-ghost { color:rgba(237,235,230,0.09); }
.sc-badge { position:absolute; top:13px; left:13px; font-size:7px; font-weight:300; letter-spacing:0.3em; text-transform:uppercase; color:var(--red); border:1px solid rgba(200,16,10,0.4); padding:3px 8px; }
.sc-info { padding:12px 14px 16px; border-top:1px solid var(--line); }
.sc-cat { font-size:7px; font-weight:200; letter-spacing:0.42em; text-transform:uppercase; color:var(--white); opacity:0.42; margin-bottom:4px; }
.sc-name { font-size:13px; font-weight:300; color:var(--white); margin-bottom:2px; }
.sc-price { font-size:10px; font-weight:200; color:var(--white); opacity:0.65; }

/* ── CONNECT ── */
.connect-grid { display:grid; grid-template-columns:1fr 1fr; gap:80px; align-items:end; }
.connect-body { font-size:13px; font-weight:300; line-height:2.0; color:var(--white); opacity:0.82; max-width:300px; }
.connect-mail { display:inline-block; margin-top:24px; font-size:10px; font-weight:300; letter-spacing:0.18em; color:var(--white); text-decoration:none; opacity:0.58; border-bottom:1px solid rgba(237,235,230,0.2); padding-bottom:3px; transition:opacity 0.2s,color 0.2s; }
.connect-mail:hover { opacity:1; color:var(--red); }
.connect-list { list-style:none; }
.cl { display:flex; align-items:center; justify-content:space-between; padding:15px 0; border-bottom:1px solid rgba(237,235,230,0.1); transition:padding-left 0.2s; }
.cl:first-child { border-top:1px solid rgba(237,235,230,0.1); }
.cl:hover { padding-left:6px; }
.cl a { font-family:Arial,'Arial Black',sans-serif; font-size:30px; font-weight:900; letter-spacing:0.04em; color:var(--white); text-decoration:none; opacity:0.82; transition:opacity 0.2s,color 0.2s; }
.cl:hover a { opacity:1; color:var(--red); }
.cl-type { display:none; }
.connect-footer { margin-top:40px; display:flex; align-items:center; gap:24px; }
.copyright { font-size:10px; font-weight:400; letter-spacing:0.12em; color:var(--white); opacity:0.55; }
.privacy-link { font-size:10px; font-weight:400; letter-spacing:0.12em; color:var(--white); text-decoration:none; opacity:0.55; border-bottom:1px solid rgba(237,235,230,0.25); padding-bottom:1px; transition:opacity 0.2s; }
.privacy-link:hover { opacity:0.7; color:var(--red); }

/* ── LOGOS ── */
.partner-logos { margin-top:16px; }
.partner-logos-label { font-size:9px; font-weight:300; letter-spacing:0.38em; text-transform:uppercase; color:var(--white); opacity:0.25; margin-bottom:10px; }
.logo-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:0; background:transparent; }
.logo-cell { background:var(--black); display:flex; align-items:center; justify-content:center; padding:14px 16px; min-height:52px; }
.logo-cell img { max-width:80px; width:auto; object-fit:contain; opacity:0.5; filter:grayscale(1) brightness(2); transition:opacity 0.2s; display:block; }
.logo-cell img.color-logo { filter:none; opacity:0.7; max-width:70px; }
.logo-cell:hover img { opacity:1; }
.space-cooking-credit { display:flex; align-items:center; gap:10px; margin-top:16px; padding-top:16px; border-top:1px solid rgba(237,235,230,0.06); }
.sc-credit-label { font-size:10px; font-weight:400; letter-spacing:0.12em; color:var(--white); opacity:0.55; white-space:nowrap; }
.sc-credit-logo { display:inline-flex; align-items:center; opacity:0.55; transition:opacity 0.2s; }
.sc-credit-logo:hover { opacity:1; }

/* ── DOTS ── */
.panel-dots { position:fixed; bottom:56px; left:56px; z-index:400; display:flex; gap:8px; }
.pdot { width:4px; height:4px; border-radius:50%; background:var(--white); opacity:0.45; cursor:pointer; transition:opacity 0.3s,transform 0.3s,background 0.3s; }
.pdot.on { opacity:1; transform:scale(1.5); background:var(--red); }

/* ── BACK BTN ── */
.back-btn { position:fixed; bottom:52px; right:56px; z-index:400; display:none; align-items:center; gap:8px; font-family:Arial,'Arial Black',sans-serif; font-size:13px; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:var(--white); cursor:pointer; border:none; background:none; padding:8px 0; }
.back-btn.visible { display:flex; animation:backFloat 2.4s ease-in-out infinite; }
.back-btn:hover { animation:none; opacity:1; color:var(--red); }
@keyframes backFloat {
  0%,100% { opacity:0.55; transform:translateY(0); }
  50%      { opacity:0.95; transform:translateY(-5px); }
}

/* ── ARROWS ── */
.arrow { position:fixed; top:50%; z-index:400; transform:translateY(-50%); font-size:18px; font-weight:200; color:var(--white); opacity:0.52; cursor:pointer; user-select:none; padding:24px 18px; transition:opacity 0.22s; }
.arrow:hover { opacity:0.85; }
.arrow.off { opacity:0 !important; pointer-events:none; }
#aPrev { left:8px; } #aNext { right:8px; }
@media (pointer:coarse) { .arrow { display:none; } }

/* ── REVEAL ── */
.rv { opacity:0; transform:translateY(18px); transition:opacity 0.6s cubic-bezier(0.22,1,0.36,1), transform 0.6s cubic-bezier(0.22,1,0.36,1); }
.rv.visible { opacity:1; transform:translateY(0); }

/* ── MENU TOGGLE ── */
.menu-toggle { display:flex; flex-direction:column; justify-content:center; gap:5px; width:32px; height:32px; background:none; border:none; cursor:pointer; padding:4px; pointer-events:all; position:relative; z-index:500; }
.mt-bar { display:block; width:100%; height:1.5px; background:var(--white); opacity:0.82; transition:transform 0.3s cubic-bezier(0.23,1,0.32,1), opacity 0.3s; transform-origin:center; }
.mt-bar2 { width:70%; }
.menu-toggle.open .mt-bar1 { transform:translateY(6.5px) rotate(45deg); opacity:1; }
.menu-toggle.open .mt-bar2 { opacity:0; transform:translateX(8px); }
.menu-toggle.open .mt-bar3 { transform:translateY(-6.5px) rotate(-45deg); opacity:1; }

/* ── MENU OVERLAY ── */
.menu-overlay { position:fixed; inset:0; z-index:450; background:#0C0F1A; display:flex; flex-direction:column; pointer-events:none; opacity:0; transform:translateY(-12px); transition:opacity 0.35s cubic-bezier(0.23,1,0.32,1), transform 0.35s cubic-bezier(0.23,1,0.32,1); }
.menu-overlay.open { opacity:1; transform:translateY(0); pointer-events:all; }
.menu-inner { display:flex; flex-direction:column; justify-content:center; height:100%; padding:70px 20px 32px; }
.menu-nav { display:flex; flex-direction:column; flex:1; justify-content:center; }
/* ── MENU ICON ONLY ── */
.menu-item {
  display:flex; align-items:center;
  padding: 52px 0;
  border-bottom:none;
  text-decoration:none; color:var(--white);
  opacity:0; transform:translateY(20px);
  transition:opacity 0.4s ease, transform 0.4s ease, filter 0.2s;
}
/* ── MENU 035 ── */
.menu-inner {
  display:flex; flex-direction:column;
  height:100%; padding:70px 0 0;
}
.menu-nav {
  flex:1; width:100%; list-style:none;
  border-bottom:1px solid rgba(237,235,230,0.1);
  display:flex; flex-direction:column;
  overflow:hidden;
}
.menu-item {
  flex:1 1 52px;
  border-top:1px solid rgba(237,235,230,0.1);
  cursor:pointer;
  position:relative;
  overflow:hidden;
  transition:background 0.2s;
  /* 035: 初期状態は非表示 */
  opacity:0; transform:translateX(-20px);
}
.menu-item:hover, .menu-item.active { background:rgba(237,235,230,0.02); }
.menu-item.active { flex:1 1 80px; }
.menu-row-inner {
  display:flex; align-items:center;
  height:100%; padding:0 100px 0 28px;
  position:relative;
}
.menu-row-left { display:flex; flex-direction:column; gap:5px; z-index:2; }
.menu-label {
  position:absolute;
  width:1px; height:1px;
  padding:0; margin:-1px;
  overflow:hidden;
  clip:rect(0,0,0,0);
  white-space:nowrap;
  border:0;
}
.menu-item.active .menu-label { color:var(--red); }
.menu-sub {
  position:absolute;
  width:1px; height:1px;
  overflow:hidden; clip:rect(0,0,0,0);
  white-space:nowrap; border:0;
}
.menu-row-arr { display:none; }
.menu-item.active .menu-row-arr { color:var(--red); transform:translateX(4px); }
.menu-row-medias {
  position:absolute; right:20px; top:50%;
  transform:translateY(-50%);
  pointer-events:none; z-index:2;
}
.menu-row-media {
  width:64px; height:64px;
  object-fit:cover; border-radius:3px;
  transform:translateY(200%);
  display:block;
}
.menu-footer { display:flex; gap:24px; padding-top:24px; opacity:0; transition:opacity 0.4s 0.4s; }
.menu-overlay.open .menu-footer { opacity:1; }
.menu-social { font-size:11px; font-weight:300; letter-spacing:0.22em; text-transform:uppercase; color:rgba(237,235,230,0.42); text-decoration:none; transition:color 0.2s; }
.menu-social:hover { color:var(--white); }

/* ── SWIPE HINT ── */
.swipe-hint { display:none !important; }
.swipe-hint.hidden { opacity:0; pointer-events:none; }
.hint-hand { display:none; }
.hint-arrows { display:flex; gap:20px; align-items:center; font-size:28px; letter-spacing:0.3em; }
.hint-arrow-h,.hint-arrow-v { font-size:11px; font-weight:200; letter-spacing:0.3em; color:var(--white); opacity:0.5; }
.hint-label { font-size:8px; font-weight:200; letter-spacing:0.4em; text-transform:uppercase; color:var(--white); opacity:0.52; }
@keyframes hintPulse { 0%,100% { transform:translateY(0); opacity:0.6; } 50% { transform:translateY(-5px); opacity:1; } }

/* ── WORLD ── */
.world-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:var(--line); margin-top:30px; }
.city { background:#080808; padding:30px 24px; transition:background 0.28s; }
.city:hover { background:#0d0d12; }
.city-n { font-family:Arial,'Arial Black',sans-serif; font-size:48px; font-weight:900; line-height:1; color:rgba(237,235,230,0.05); margin-bottom:14px; }
.city-nm { font-family:Arial,'Arial Black',sans-serif; font-size:22px; font-weight:900; letter-spacing:0.03em; color:var(--white); margin-bottom:4px; }
.city-dt { font-size:8px; font-weight:200; letter-spacing:0.38em; text-transform:uppercase; color:var(--white); opacity:0.65; margin-bottom:14px; }
.city-tag { display:inline-block; padding:3px 9px; font-size:7px; font-weight:200; letter-spacing:0.3em; text-transform:uppercase; }
.city-tag.done { border:1px solid var(--line); color:var(--white); opacity:0.3; }
.city-tag.next { border:1px solid rgba(200,16,10,0.3); color:var(--red); }


/* ── PARALLAX ── */
.parallax-bg {
  position: absolute; inset: 0; z-index: 0;
  will-change: transform;
  transition: transform 0.1s linear;
}
.parallax-bg img, .parallax-bg video {
  width: 100%; height: 120%;
  object-fit: cover;
  position: absolute; top: -10%; left: 0;
}

/* ── SLIDE IN ── */
.rv-left {
  opacity: 0; transform: translateX(-40px);
  transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1);
}
.rv-right {
  opacity: 0; transform: translateX(40px);
  transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1);
}
.rv-left.visible, .rv-right.visible {
  opacity: 1; transform: translateX(0);
}
.rv-up {
  opacity: 0; transform: translateY(30px);
  transition: opacity 0.65s cubic-bezier(0.22,1,0.36,1), transform 0.65s cubic-bezier(0.22,1,0.36,1);
}
.rv-up.visible { opacity: 1; transform: translateY(0); }
.rv-scale {
  opacity: 0; transform: scale(0.94);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
.rv-scale.visible { opacity: 1; transform: scale(1); }

/* ── YOUTUBE THEATER ── */
.yt-theater {
  position: fixed; inset: 0; z-index: 800;
  background: #000;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  visibility: hidden; opacity: 0;
  transition: opacity 0.15s ease, visibility 0s 0.15s;
}
.yt-theater.open {
  visibility: visible; opacity: 1;
  transition: opacity 0.15s ease, visibility 0s;
}
.yt-theater-close {
  position: absolute; top: 56px; left: 20px;
  width: 44px; height: 44px;
  background: rgba(255,255,255,0.1);
  border: none; border-radius: 50%;
  color: #fff; font-size: 20px;
  cursor: pointer; z-index: 801;
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s;
}
.yt-theater-close:hover { background: rgba(255,255,255,0.25); }
.yt-mute-btn {
  position: fixed;
  bottom: 80px;
  right: 20px;
  z-index: 9010;
  background: rgba(0,0,0,0.6);
  border: 1px solid rgba(255,255,255,0.3);
  color: #fff;
  font-size: 22px;
  width: 48px; height: 48px;
  border-radius: 50%;
  cursor: pointer;
  display: none;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
}
.yt-mute-btn.visible { display: flex; }
.yt-mute-btn:hover { background: rgba(0,0,0,0.85); }
.yt-theater-intro {
  position: absolute; inset: 0;
  display: flex; align-items: center; justify-content: center;
  z-index: 1;
}
.yt-theater-intro video,
.yt-theater-intro img {
  width: 100%; height: 100%; object-fit: cover;
}
.yt-theater-intro.fade-out {
  animation: theaterFadeOut 0.4s ease forwards;
}
@keyframes theaterFadeOut {
  0%   { opacity: 1; }
  100% { opacity: 0; pointer-events: none; }
}
.yt-shorts-wrap {
  width: 100%; height: 100%;
  display: flex; align-items: center; justify-content: center;
  opacity: 0; transition: opacity 0.6s 0.3s ease;
  position: relative; z-index: 2;
}
.yt-shorts-wrap.visible { opacity: 1; }
.yt-shorts-frame {
  width: 100%;
  height: 100%;
  max-width: calc(100vh * 9 / 16);
  border: none;
}
.yt-shorts-nav {
  position: absolute; right: 16px; top: 50%;
  transform: translateY(-50%);
  display: flex; flex-direction: column; gap: 8px;
  z-index: 3;
}
.yt-shorts-dot {
  width: 4px; height: 32px; border-radius: 2px;
  background: rgba(255,255,255,0.25);
  cursor: pointer; transition: background 0.2s, transform 0.2s;
}
.yt-shorts-dot.on { background: #fff; transform: scaleY(1.2); }

/* ── MENU ICON ── */
.menu-icon {
  width: auto; height: auto;
  max-width: 100%;
  max-height: 32px;
  object-fit: contain;
  object-position: left center;
  flex-shrink: 0;
  opacity: 0.82;
  transition: opacity 0.2s;
}
.menu-item:hover .menu-icon {
  opacity: 1;
  transform: scale(1.08);
}
.menu-social-icon {
  width: 18px; height: 18px;
  object-fit: contain;
  border-radius: 4px;
  opacity: 0.7;
  vertical-align: middle;
  margin-right: 6px;
}
.menu-social {
  display: flex; align-items: center;
}
.zi-external { cursor: pointer; }
.zi-external .zi-arr { opacity: 0.5; }
.zi-external:hover .zi-arr { opacity: 1; }


/* ── PULL TO PLAY ── */
@keyframes pullBounce {
  0%,100% { transform:translateY(0); opacity:0.4; }
  50%      { transform:translateY(-8px); opacity:0.8; }
}
@keyframes spin {
  to { transform:rotate(360deg); }
}
#ytTriggerScreen { background:#000; }


/* ── PULL TO PLAY ── */
#ytPullBar {
  position: fixed;
  bottom: -80px;
  left: 0; right: 0;
  height: 80px;
  display: flex; align-items: center; justify-content: center;
  z-index: 10000;
  pointer-events: none;
  transition: bottom 0.15s ease;
}
.ptr-spinner {
  width: 32px; height: 32px;
  border: 2.5px solid rgba(237,235,230,0.2);
  border-top: 2.5px solid #E8100A;
  border-radius: 50%;
  opacity: 0;
  transform: scale(0.6);
  transition: opacity 0.2s, transform 0.2s;
}
.ptr-spinner.loading {
  animation: ptrSpin 0.7s linear infinite;
  opacity: 1;
  transform: scale(1);
}
@keyframes ptrSpin {
  to { transform: rotate(360deg) scale(1); }
}


/* HERO panel コンテンツを下から詰める */
.panel[data-active] .panel-content {
  padding-bottom: 40px;
}
@media (max-width: 860px) {
  .panel[data-active] .panel-content {
    padding-bottom: 28px;
  }
}


/* ── HERO セクションアイコン ── */
.hero-section-icon {
  height: auto;
  max-height: 80px;
  width: auto;
  max-width: 70vw;
  display: block;
  object-fit: contain;
  object-position: left center;
  filter: brightness(0) invert(1);
  opacity: 0.95;
}
@media (max-width: 600px) {
  .hero-section-icon { max-height: 60px; }
}


/* ── HERO アイコン横テキスト ── */
.hero-icon-row {
  display: flex;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
}
.hero-section-title {
  font-family: Arial,'Arial Black',sans-serif;
  font-size: clamp(22px, 5.5vw, 40px);
  font-weight: 900;
  color: var(--white);
  line-height: 1;
  letter-spacing: 0.01em;
}

/* ── セクション見出しアイコン ── */
.section-icon-heading {
  display: block;
  height: auto;
  max-height: 52px;
  width: auto;
  max-width: 60vw;
  object-fit: contain;
  object-position: left center;
  filter: brightness(0) invert(1);
  opacity: 0.95;
  margin-top: 4px;
}
@media (max-width: 600px) {
  .section-icon-heading { max-height: 38px; }
  .hero-section-title { font-size: clamp(18px, 5vw, 28px); }
}


/* ── リンクリセット ── */
a { color: inherit; text-decoration: none; }
a:visited { color: inherit; }

/* ── SCカード（GOOD GOODS）リンク ── */
a.sc { color: var(--white); text-decoration: none; }
a.sc .sc-cat { color: var(--white); opacity:0.42; }
a.sc .sc-name { color: var(--white); }
a.sc:hover { background: rgba(237,235,230,0.06); }

/* ── connect-list リンク ── */
.cl a { color: var(--white); text-decoration: none; }
.connect-mail { color: var(--white); }


/* ── SCROLL INDICATOR ── */
.scroll-indicator {
  position:absolute;
  bottom:32px; right:28px;
  display:flex; flex-direction:column;
  align-items:center; gap:8px;
  pointer-events:none; z-index:5;
}
.scroll-indicator-line {
  width:1px; height:36px;
  background:var(--white);
  transform-origin:top center;
  animation:scrollPulse 1.8s ease-in-out infinite;
}
.scroll-indicator-text {
  font-size:8px; letter-spacing:.38em;
  text-transform:uppercase;
  color:rgba(237,235,230,.45);
  writing-mode:vertical-rl;
  text-orientation:mixed;
}
@keyframes scrollPulse {
  0%,100% { transform:scaleY(0.3); opacity:.25; }
  50%      { transform:scaleY(1);   opacity:.7;  }
}

/* ── SCROLL ANIMATIONS ── */
.anim-up {
  opacity: 0;
  transform: translateY(32px);
  transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1);
}
.anim-left {
  opacity: 0;
  transform: translateX(-36px);
  transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1);
}
.anim-right {
  opacity: 0;
  transform: translateX(36px);
  transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1);
}
.anim-up.in, .anim-left.in, .anim-right.in {
  opacity: 1;
  transform: translate(0);
}

::selection { background:var(--red); color:var(--white); }

/* ── ARTIST INDEX ── */
.artist-index-list { list-style:none; margin:0; padding:0; }
.artist-index-item {
  display:flex; align-items:center; gap:12px;
  padding:14px 0;
  border-bottom:1px solid rgba(237,235,230,0.1);
  cursor:pointer;
  transition:opacity 0.2s;
}
.artist-index-item:first-child { border-top:1px solid rgba(237,235,230,0.1); }
.artist-index-item:hover { opacity:0.75; }
.artist-index-name { font-family:Arial,'Arial Black',sans-serif; font-size:clamp(18px,4.5vw,28px); font-weight:900; color:var(--white); flex:1; line-height:1.1; }
.artist-index-genre { font-size:9px; letter-spacing:0.32em; text-transform:uppercase; color:rgba(237,235,230,0.4); flex-shrink:0; }
.artist-index-arr { font-size:14px; color:var(--red); flex-shrink:0; margin-left:8px; }

/* ── ARTIST MODAL ── */
.artist-modal {
  position:fixed; inset:0; z-index:8000;
  background:var(--black);
  display:flex; flex-direction:column; justify-content:flex-end;
  opacity:0; pointer-events:none;
  transform:translateX(100%);
  transition:transform 0.35s cubic-bezier(0.22,1,0.36,1), opacity 0.35s;
}
.artist-modal.open {
  opacity:1; pointer-events:all;
  transform:translateX(0);
}
.artist-modal-bg { position:absolute; inset:0; z-index:0; }
.artist-modal-close {
  position:absolute; top:20px; left:20px; z-index:2;
  background:none; border:none; color:var(--white);
  font-size:22px; cursor:pointer; padding:8px 12px;
  opacity:0.7; transition:opacity 0.2s;
  font-family:Arial,sans-serif;
  letter-spacing:0.1em;
}
.artist-modal-close:hover { opacity:1; }
::-webkit-scrollbar { display:none; }

/* ── MOBILE ── */
@media (max-width: 860px) {
  #amd-header { padding:16px 20px; }
  .panel-content { padding:0 20px 40px; }
  .content-panel .panel-content { padding:64px 20px 52px; }
  .panel-dots { left:20px; bottom:32px; }
  .back-btn { bottom:32px; right:20px; font-size:12px; }
  .two-col { grid-template-columns:1fr; gap:24px; }
  .artists-layout { grid-template-columns:1fr; gap:20px; }
  .artist-strip { grid-template-columns:repeat(3,1fr); }
  .zi-num { font-size:28px; min-width:48px; }
  .zi-ttl { font-size:20px; }
  .zi { padding:12px 0; gap:10px; }
  .zi-desc { display:none; }
  .store-grid { grid-template-columns:1fr 1fr; }
  .sc { min-height:120px; }
  .world-grid { grid-template-columns:1fr 1fr; }
  .connect-grid { grid-template-columns:1fr; gap:10px; }
  .connect-h2 { font-size:22px; margin-bottom:8px; }
  .connect-body { display:none; }
  .connect-mail { margin-top:8px; font-size:10px; }
  .cl { padding:8px 0; }
  .cl a { font-size:18px; }
  .logo-cell { padding:8px 10px; min-height:40px; }
  .logo-cell img { max-height:22px; }
  .logo-cell img.color-logo { max-height:28px; }
  .yt-grid { gap:6px; }
  .h-section { font-size:24px; }
  .h-hero { font-size:clamp(22px,7vw,36px); }
  .btn-fill,.btn-ghost { padding:14px 22px; font-size:12px; letter-spacing:0.16em; white-space:nowrap; }
  .cta-row { gap:8px; }
  .menu-inner { padding:72px 24px 40px; }
  .menu-item { padding:12px 0; gap:12px; }
  .menu-sub { display:none; }
  .menu-footer { flex-wrap:wrap; gap:16px; }
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
</head>
<body>

<!-- GIFプリロード -->
<link rel="preload" href="https://allmustdance.com/wp-content/uploads/2026/03/theater.gif" as="image">
<div id="ytPullBar"><div class="ptr-spinner" id="ptrSpinner"></div></div>

<div id="amd-header">
  <a class="logo" href="/">
    <img loading="eager" src="<?= get_stylesheet_directory_uri() ?>/logos/amdheaderlogo.png"
      alt="ALL MUST DANCE™"
      style="height:20px;width:auto;display:block;mix-blend-mode:screen;">
  </a>
  <div class="header-right">
    <div class="counter-wrap">
      <div id="chap-counter">01 / 06</div>
      <div id="panel-counter"></div>
    </div>
    <button class="lang-toggle" id="langToggle" onclick="amdToggleLang()" aria-label="Language toggle">
      <span class="lang-jp" id="langJp">JP</span>
      <span class="lang-sep">/</span>
      <span class="lang-en" id="langEn">EN</span>
    </button>
    <button class="menu-toggle" id="menuToggle" aria-label="Menu">
      <span class="mt-bar mt-bar1"></span>
      <span class="mt-bar mt-bar2"></span>
      <span class="mt-bar mt-bar3"></span>
    </button>
  </div>
</div><!-- /amd-header -->

<!-- MENU OVERLAY -->
<div class="menu-overlay" id="menuOverlay">
  <div class="menu-inner">
    <?php
    // メニュー用アーティスト画像取得
    $menu_imgs = [];
    if(!empty($party_artists)){
      foreach(array_slice($party_artists, 0, 6) as $ma){
        $mp = get_field('photo', $ma->ID);
        if($mp) $menu_imgs[] = $mp['url'];
      }
    }
    function _menu_img($imgs, $indices){
      $out = '';
      foreach($indices as $i){
        $src = isset($imgs[$i]) ? esc_url($imgs[$i]) : '';
        if($src) $out .= '<img class="menu-row-media" src="'.$src.'" loading="lazy">';
      }
      return $out;
    }
    ?>
    <?php
    // セクションサムネイル定義（1枚のみ）
    $sec_thumbs = [
      'party'   => get_stylesheet_directory_uri().'/logos/amd2026asia.jpg',
      'ws'      => 'https://allmustdance.com/wp-content/uploads/2026/04/20260403_083551.gif',
      'video'   => 'https://img.youtube.com/vi/ya50ucLzGj0/maxresdefault.jpg',
      'zine'    => 'https://allmustdance.com/wp-content/uploads/2026/03/fdoor.jpg',
      'goods'   => 'https://allmustdance.com/wp-content/uploads/2026/03/20260323_152503.gif',
      'contact' => get_stylesheet_directory_uri().'/logos/amd2026asia.jpg',
    ];
    function _sec_thumb($url){
      return '<img class="menu-row-media" src="'.esc_url($url).'" loading="lazy" aria-hidden="true">';
    }
    ?>
    <ul class="menu-nav" id="menuNav">
      <li class="menu-item" data-goto="0,0" data-menu-close>
        <div class="menu-row-inner">
          <img class="menu-icon" src="<?= get_stylesheet_directory_uri() ?>/logos/party.png" alt="PARTY">
          <div class="menu-row-left">
            <span class="menu-label">PARTY</span>
            <span class="menu-sub">May 4 · clubasia</span>
          </div>
          <span class="menu-row-arr">→</span>
          <div class="menu-row-medias"><?= _sec_thumb($sec_thumbs['party']) ?></div>
        </div>
      </li>
      <li class="menu-item" data-goto="1,0" data-menu-close>
        <div class="menu-row-inner">
          <img class="menu-icon" src="<?= get_stylesheet_directory_uri() ?>/logos/workshop.png" alt="WORKSHOP">
          <div class="menu-row-left">
            <span class="menu-label">WORKSHOP</span>
            <span class="menu-sub">64BEAT · Apr 1·8·15</span>
          </div>
          <span class="menu-row-arr">→</span>
          <div class="menu-row-medias"><?= _sec_thumb($sec_thumbs['ws']) ?></div>
        </div>
      </li>
      <li class="menu-item" data-goto="2,0" data-menu-close>
        <div class="menu-row-inner">
          <img class="menu-icon" src="<?= get_stylesheet_directory_uri() ?>/logos/video.png" alt="VIDEO" style="max-height:32px;">
          <div class="menu-row-left">
            <span class="menu-label">VIDEO</span>
            <span class="menu-sub">@allmustdancetokyo</span>
          </div>
          <span class="menu-row-arr">→</span>
          <div class="menu-row-medias"><?= _sec_thumb($sec_thumbs['video']) ?></div>
        </div>
      </li>
      <li class="menu-item" data-href="<?= home_url('/zine-index/') ?>">
        <div class="menu-row-inner">
          <img class="menu-icon" src="<?= get_stylesheet_directory_uri() ?>/logos/zine.png" alt="ZINE" style="max-height:32px;">
          <div class="menu-row-left">
            <span class="menu-label">ZINE</span>
            <span class="menu-sub">Issue Archive</span>
          </div>
          <span class="menu-row-arr">→</span>
          <div class="menu-row-medias"><?= _sec_thumb($sec_thumbs['zine']) ?></div>
        </div>
      </li>
      <li class="menu-item" data-goto="3,0" data-menu-close>
        <div class="menu-row-inner">
          <img class="menu-icon" src="<?= get_stylesheet_directory_uri() ?>/logos/gg.png" alt="GOOD GOODS" style="max-height:32px;">
          <div class="menu-row-left">
            <span class="menu-label">GOOD GOODS</span>
            <span class="menu-sub">Shop</span>
          </div>
          <span class="menu-row-arr">→</span>
          <div class="menu-row-medias"><?= _sec_thumb($sec_thumbs['goods']) ?></div>
        </div>
      </li>
      <li class="menu-item" data-goto="4,0" data-menu-close>
        <div class="menu-row-inner">
          <img class="menu-icon" src="<?= get_stylesheet_directory_uri() ?>/logos/getin.png" alt="GET IN TOUCH" style="max-height:32px;">
          <div class="menu-row-left">
            <span class="menu-label">GET IN TOUCH</span>
            <span class="menu-sub">niko@allmustdance.com</span>
          </div>
          <span class="menu-row-arr">→</span>
          <div class="menu-row-medias"><?= _sec_thumb($sec_thumbs['contact']) ?></div>
        </div>
      </li>
    </ul>
    <div class="menu-footer">
      <a href="https://www.instagram.com/allmustdancetokyo/" target="_blank" class="menu-social">Instagram</a>
      <a href="https://www.youtube.com/@allmustdancetokyo" target="_blank" class="menu-social">YouTube</a>
      <a href="https://allmustdance.com/privacy-policy/" class="menu-social">Privacy Policy</a>
    </div>
  </div>
</div>

<div class="arrow off" id="aPrev">←</div>
<div class="arrow" id="aNext">→</div>
<div class="panel-dots" id="pdots"></div>
<button class="back-btn" id="backBtn">← Back</button>

<!-- SWIPE HINT -->
<div class="swipe-hint" id="swipeHint">
  
  <div class="hint-arrows">
    <span class="hint-arrow-h">←</span>
    <span class="hint-arrow-v">↕</span>
    <span class="hint-arrow-h">→</span>
  </div>
  <div class="hint-label">Swipe</div>
</div>

<div id="deck">
<div id="vtrack">

  <!-- ▸ 0 PARTY HERO -->
  <div class="chapter active" id="c0">
    <div class="panel-track" id="c0-track" style="height:var(--amd-full-h);overflow-x:scroll;overflow-y:hidden;">

      <!-- 0-0: Hero -->
      <div class="panel" id="p0-0" data-active>
        <div class="panel-bg parallax-bg" style="background:linear-gradient(160deg,#0f1428 0%,#0C0F1A 100%);">
          <!-- ポスター背景（動画ロード前の表示） -->
          <img loading="eager" src="<?= get_stylesheet_directory_uri() ?>/logos/amd2026asia.jpg"
            alt="" aria-hidden="true"
            style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center top;opacity:0.85;">
          <?php if($party_video): ?>
          <video id="heroVid" autoplay muted loop playsinline preload="auto"
            style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0;transition:opacity 1.2s ease;">
            <source src="<?= esc_url($party_video['url']) ?>" type="video/mp4">
          </video>
          <?php endif; ?>
          <!-- グロー -->
          <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 70% 60%, rgba(26,46,107,0.35) 0%, transparent 65%);pointer-events:none;"></div>
        </div>
        <div class="vig"></div>
        <div class="panel-content">
          <div class="rv rv-left">
            <img src="<?= get_stylesheet_directory_uri() ?>/logos/party.png" alt="PARTY" class="hero-section-icon">
          </div>
          <div class="rv rv-up eyebrow lang-switchable" data-jp="<?= esc_attr($party_date) ?> · 渋谷東京" data-en="<?= esc_attr($party_date) ?> · Shibuya Tokyo" style="margin-top:12px;"><?= esc_html($party_date) ?> · Shibuya Tokyo</div>
          <div class="rv rv-up cta-row">
            <span class="btn-fill" onclick="amdRedFlash(openTicketOverlay)">Get Tickets</span>
            <span class="btn-ghost" onclick="openArtistPanel()">Artists</span>
          </div>
          <div class="rv rv-up meta-line" style="margin-top:12px;">
            <span class="lang-switchable" data-jp="<?= esc_attr($party_venue) ?> · 開場<?= esc_attr($party_time) ?> · 20歳以上" data-en="<?= esc_attr($party_venue) ?> · Open <?= esc_attr($party_time) ?> · Age 20+"><?= esc_html($party_venue) ?> · Open <?= esc_html($party_time) ?> · Age 20+</span>
          </div>
        </div>
        <!-- SCROLL INDICATOR -->
        <div class="scroll-indicator" id="scrollIndicator">
          <div class="scroll-indicator-line"></div>
          <div class="scroll-indicator-text">Scroll</div>
        </div>
      </div>


      <!-- 0-2: Event + Tickets -->
      <div class="amd-ticket-overlay" id="p0-2">
        <button class="amd-ticket-close" onclick="closeTicketOverlay()">×</button>
        <div class="panel-bg"></div>
        <div class="vig vig-heavy"></div>
        <div class="panel-content" style="height:100%;overflow-y:auto;-webkit-overflow-scrolling:touch;overscroll-behavior-y:contain;padding-top:max(72px, calc(env(safe-area-inset-top) + 60px));padding-bottom:80px;">
          <div class="two-col">
            <div class="rv">
              <div class="eyebrow lang-switchable" data-jp="次のイベント — EP.07" data-en="Next Event — EP.07">Next Event — EP.07</div>
              <div class="h-section lang-switchable" data-jp="ホーム<br>カミング." data-en="HOME<br>COMING.">HOME<br>COMING.</div>
              <p class="body-txt">EP.05でPARCOの屋上へ飛び出し、EP.06でCheekyで実験し——AMD™はclubasia（ホームグラウンド）に戻ってくる。これは帰還であり、次の旅への出発点だ。</p>
              <p class="body-txt-en">After rooftops and experiments, ALL MUST DANCE™ returns home. This is not a comeback. This is a departure.</p>
            </div>
            <div class="rv">
              <div class="info-table">
                <div class="info-row"><span class="ik">Date</span><span class="iv"><?= esc_html($party_date) ?><small class="lang-switchable" data-jp="月曜日 · 祝日" data-en="Monday · National Holiday">Monday · National Holiday</small></span></div>
                <div class="info-row"><span class="ik">Time</span><span class="iv"><?= esc_html($party_time) ?></span></div>
                <div class="info-row"><span class="ik">Venue</span><span class="iv"><?= esc_html($party_venue) ?><small class="lang-switchable" data-jp="円山町, 渋谷" data-en="Maruyamacho, Shibuya">Maruyamacho, Shibuya</small></span></div>
              </div>
              <div class="ticket-section">
                <div class="ticket-head">
                  <span class="ticket-head-lbl">Tickets</span>
                  <span class="ticket-head-note lang-switchable" data-jp="Web only · 電子チケット" data-en="Web only · E-ticket">Web only · 電子チケット</span>
                </div>
                <a class="trow" href="<?= esc_url($party_ticket) ?>" target="_blank">
                  <div class="trow-left"><span class="trow-type">Early Bird</span><span class="trow-price"><?= esc_html($party_eb_price) ?></span></div>
                  <div class="trow-right"><span class="trow-tag">30枚限定</span><span class="trow-arr">→</span></div>
                </a>
                <a class="trow" href="<?= esc_url($party_ticket) ?>" target="_blank">
                  <div class="trow-left"><span class="trow-type">Advance</span><span class="trow-price"><?= esc_html($party_adv_price) ?></span></div>
                  <div class="trow-right"><span class="trow-arr">→</span></div>
                </a>
                <div class="trow disabled">
                  <div class="trow-left"><span class="trow-type">Door</span><span class="trow-price">¥4,500</span></div>
                  <div class="trow-right"><span style="font-size:8px;letter-spacing:0.3em;text-transform:uppercase;color:var(--white)">On the Night</span></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- ▸ 1 WORKSHOP (旧c2) -->
  <div class="chapter" id="c1">
    <div class="panel-track" id="c1-track" style="height:var(--amd-full-h);overflow-x:scroll;overflow-y:hidden;">

      <!-- 2-0: Workshop Hero -->
      <div class="panel" id="p1-0" data-active style="height:var(--amd-full-h);min-height:100svh;justify-content:flex-end;">
        <div class="panel-bg" style="background:linear-gradient(160deg,#0f1428 0%,#0C0F1A 60%,#070A12 100%)">
          <?php if($ws_video): ?>
          <video autoplay muted loop playsinline preload="metadata"
            style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.5;">
            <source src="<?= esc_url($ws_video['url']) ?>" type="video/mp4">
          </video>
          <?php endif; ?>
        </div>
        <div class="vig"></div>
        <div class="panel-content">
          <div class="rv">
            <img src="<?= get_stylesheet_directory_uri() ?>/logos/workshop.png" alt="WORKSHOP" class="hero-section-icon">
          </div>
          <div class="rv eyebrow lang-switchable" data-jp="ワークショップ · 2026" data-en="Workshop · 2026" style="margin-top:8px;">Workshop · 2026</div>
          <div class="rv cta-row">
            <span class="btn-fill" onclick="amdRedFlash(openWsTicketOverlay)">Get Tickets</span>
            <span class="btn-ghost" onclick="openWsArtistOverlay()">Artist Info</span>
          </div>
          <div class="rv meta-line" style="margin-bottom:40px;">
            <?= $ws_date ? esc_html($ws_date) : 'Date TBA' ?> · <?= $ws_venue ? esc_html($ws_venue) : 'Venue TBA' ?>
          </div>
        </div>
      </div>

      <!-- 1-1: Workshop Artist Overlay -->
      <div class="amd-ticket-overlay" id="p1-1" style="background:var(--black);">
        <button class="amd-ticket-close" onclick="closeWsArtistOverlay()">×</button>
        <div class="panel-bg" style="background:linear-gradient(160deg,#0f1428 0%,#0a0d1a 50%,#070A12 100%);"></div>
        <div class="vig-artist"></div>
        <div class="panel-content" style="height:100%;overflow-y:auto;-webkit-overflow-scrolling:touch;padding-top:max(72px, calc(env(safe-area-inset-top) + 60px));padding-bottom:80px;padding-left:32px;padding-right:32px;position:relative;z-index:2;">
          <?php if(!empty($ws_artists)): $wa = $ws_artists[0];
            $wa_photo = get_field('photo',  $wa->ID);
            $wa_genre = get_field('genre',  $wa->ID);
            $wa_bio   = get_field('bio_ja', $wa->ID);
            $wa_bio_en= get_field('bio_en', $wa->ID);
            $wa_role  = get_field('role',   $wa->ID);
          ?>
          <?php if($wa_photo): ?>
          <div style="position:absolute;inset:0;z-index:0">
            <img loading="lazy" src="<?= esc_url($wa_photo['url']) ?>" alt=""
              style="width:100%;height:100%;object-fit:cover;opacity:0.3;">
          </div>
          <?php endif; ?>
          <div class="rv eyebrow"><?= esc_html($wa_role) ?></div>
          <div class="rv">
            <div class="af-genre"><?= esc_html($wa_genre) ?></div>
            <div class="af-name"><?= esc_html($wa->post_title) ?></div>
            <?php if($wa_bio): ?><p class="af-desc"><?= esc_html($wa_bio) ?></p><?php endif; ?>
            <?php if($wa_bio_en): ?><p class="af-desc-en"><?= esc_html($wa_bio_en) ?></p><?php endif; ?>
          </div>
          <?php else: ?>
          <div class="rv eyebrow">Workshop Artist</div>
          <div class="rv">
            <div class="af-genre">Dance · Movement · Expression</div>
            <div class="af-name">ARTIST<br>NAME TBA</div>
            <p class="af-desc">アーティスト情報は近日公開予定。</p>
            <p class="af-desc-en">Artist details coming soon.</p>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- 1-2: Workshop Ticket Overlay -->
      <div class="amd-ticket-overlay" id="p1-2">
        <button class="amd-ticket-close" onclick="closeWsTicketOverlay()">×</button>
        <div class="panel-bg"></div>
        <div class="vig vig-heavy"></div>
        <div class="panel-content" style="height:100%;overflow-y:auto;-webkit-overflow-scrolling:touch;overscroll-behavior-y:contain;padding-top:max(72px, calc(env(safe-area-inset-top) + 60px));padding-bottom:80px;">
          <div class="two-col">
            <div class="rv">
              <div class="eyebrow lang-switchable" data-jp="ワークショップ情報" data-en="Workshop Info">Workshop Info</div>
              <div class="h-section lang-switchable" data-jp="詳細." data-en="DETAILS.">DETAILS.</div>
              <p class="body-txt">ダンス・音楽・表現の境界を溶かす、ALL MUST DANCE™ のワークショップ。身体と音が出会う実験的な時間。参加者全員が主役になる。</p>
              <p class="body-txt-en">A workshop where movement meets music. Experimental, open, and essential.</p>
            </div>
            <div class="rv">
              <div class="info-table">
                <div class="info-row"><span class="ik">Date</span><span class="iv"><?= $ws_date ? esc_html($ws_date) : 'TBA' ?></span></div>
                <div class="info-row"><span class="ik">Time</span><span class="iv"><?= $ws_time ? esc_html($ws_time) : 'TBA' ?></span></div>
                <div class="info-row"><span class="ik">Venue</span><span class="iv"><?= $ws_venue ? esc_html($ws_venue) : 'TBA' ?></span></div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- ▸ 2 YOUTUBE -->
  <div class="chapter" id="c2" data-lazy style="display:none;height:auto;background:#000;">
    <div class="panel-track" id="c2-track" style="height:auto;min-height:100svh;overflow:hidden;scroll-snap-type:none;touch-action:auto;">
      <div class="panel content-panel solo" id="p2-0" style="background:#000;">
        <div class="panel-bg" style="background:#000;"></div>

        <!-- Pull-to-Play トリガー画面（真っ黒） -->
        <div id="ytTriggerScreen" style="position:absolute;inset:0;z-index:2;background:#000;min-height:100svh;"></div>

        <!-- グリッドビュー: シアターを閉じた後に表示 -->
        <div id="ytGridView" style="display:none; position:relative; z-index:3; overflow:visible; background:#000; padding-bottom:40px;">
          <div class="panel-content" style="padding-top:100px;">
            <div class="rv">
              <div class="zine-head">
                <div>
                  <img src="<?= get_stylesheet_directory_uri() ?>/logos/video.png" alt="VIDEO" class="section-icon-heading">
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px;">
                  <a href="https://www.youtube.com/@allmustdancetokyo" target="_blank" class="a-subtle">Channel →</a>
                  <button id="ytOpenBtn" style="background:var(--red);color:var(--white);border:none;padding:10px 18px;font-size:11px;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;cursor:pointer;">▶ PLAY</button>
                </div>
              </div>
            </div>
            <div class="yt-grid rv">
              <a class="yt-card" href="https://youtu.be/ya50ucLzGj0" target="_blank">
                <div class="yt-thumb">
                  <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://img.youtube.com/vi/ya50ucLzGj0/maxresdefault.jpg" alt="EP.04" loading="lazy" class="lazy-img" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                  <div class="yt-play">▶</div>
                </div>
                <div class="yt-info"><div class="yt-title">ALL MUST DANCE™ — ep04</div><div class="yt-sub">Archive</div></div>
              </a>
              <a class="yt-card" href="https://youtu.be/dOABFxAzIpA" target="_blank">
                <div class="yt-thumb">
                  <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://img.youtube.com/vi/dOABFxAzIpA/maxresdefault.jpg" alt="EP.03" loading="lazy" class="lazy-img" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                  <div class="yt-play">▶</div>
                </div>
                <div class="yt-info"><div class="yt-title">ALL MUST DANCE™ — ep03</div><div class="yt-sub">JUL15 2024 TOKYO</div></div>
              </a>
              <a class="yt-card" href="https://www.youtube.com/watch?v=L3-rebmUDvM" target="_blank">
                <div class="yt-thumb">
                  <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://img.youtube.com/vi/L3-rebmUDvM/maxresdefault.jpg" alt="INPLOSIVE THEATER" loading="lazy" class="lazy-img" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                  <div class="yt-play">▶</div>
                </div>
                <div class="yt-info"><div class="yt-title">INPLOSIVE THEATER</div><div class="yt-sub">cro-magnon / nobby</div></div>
              </a>
              <a class="yt-card" href="https://www.youtube.com/watch?v=KhgK2duchUU" target="_blank">
                <div class="yt-thumb">
                  <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://img.youtube.com/vi/KhgK2duchUU/maxresdefault.jpg" alt="INPLOSIVE THEATER" loading="lazy" class="lazy-img" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                  <div class="yt-play">▶</div>
                </div>
                <div class="yt-info"><div class="yt-title">INPLOSIVE THEATER</div><div class="yt-sub">DJ YABE TADASHI / NOBBY</div></div>
              </a>
              <a class="yt-card" href="https://www.youtube.com/shorts/1cSuNZ9y71Q" target="_blank">
                <div class="yt-thumb">
                  <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://img.youtube.com/vi/1cSuNZ9y71Q/maxresdefault.jpg" alt="Short" loading="lazy" class="lazy-img" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                  <div class="yt-play">▶</div>
                </div>
                <div class="yt-info"><div class="yt-title">Short Film</div><div class="yt-sub">ALL MUST DANCE</div></div>
              </a>
              <a class="yt-card" href="https://www.youtube.com/shorts/CM0JmwaVGaU" target="_blank">
                <div class="yt-thumb">
                  <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="https://img.youtube.com/vi/CM0JmwaVGaU/maxresdefault.jpg" alt="Short" loading="lazy" class="lazy-img" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                  <div class="yt-play">▶</div>
                </div>
                <div class="yt-info"><div class="yt-title">Short Film</div><div class="yt-sub">ALL MUST DANCE</div></div>
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ▸ 4 STORE -->
  <div class="chapter" id="c3" data-lazy style="display:none;height:auto;min-height:auto;">
    <div class="panel-track" id="c3-track" style="height:auto;overflow:visible;">
      <div class="panel content-panel solo" id="p3-0" style="height:auto;min-height:0;overflow:visible;">
        <div class="panel-bg"></div>
        <div class="vig vig-heavy"></div>
        <div class="panel-content" style="height:auto;overflow:visible;padding-bottom:60px;">
          <div class="rv">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;">
              <div style="flex:1;">
                <div class="eyebrow">Store</div>
                <img src="<?= get_stylesheet_directory_uri() ?>/logos/gg.png" alt="GOOD GOODS" class="section-icon-heading">
                <div class="anim-up" style="transition-delay:0.1s;margin-top:24px;padding-left:12px;border-left:2px solid rgba(237,235,230,0.15);">
                  <p style="font-size:15px;font-weight:300;line-height:2;color:rgba(237,235,230,0.82);">Keep it wrong.<br>Stay groove.<br>ByUS</p>
                </div>
              </div>
              <div style="flex:0 0 auto;display:flex;flex-direction:column;align-items:flex-end;gap:12px;padding-top:4px;">
                <img src="<?= get_stylesheet_directory_uri() ?>/artwear/giphy 15.GIF" alt="" loading="lazy"
                  style="width:clamp(150px,52vw,220px);height:auto;display:block;">
                <a href="https://zzazz-za.stores.jp/" target="_blank" class="a-subtle">Store All →</a>
              </div>
            </div>
          </div>
          <div style="width:100%;margin:20px 0 8px;">
            <img src="<?= get_stylesheet_directory_uri() ?>/artwear/zzazzcm.GIF" alt="ZZAZZ" loading="lazy" style="width:100%;display:block;">
          </div>
          <div class="store-grid rv" style="margin-top:0;">
            <a class="sc anim-left" style="transition-delay:0s;" href="https://zzazz-za.stores.jp/items/6991f608580447c3fea658e0" target="_blank">
              <div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd01minny.PNG" alt="AMD Minny" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div>
              <div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD MN</div></div>
            </a>
            <a class="sc anim-right" style="transition-delay:0.1s;" href="https://zzazz-za.stores.jp/items/69c00f86ccd49a7f3aa0df6e" target="_blank">
              <div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd02jkt.PNG" alt="AMD Jacket" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div>
              <div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD JKT</div></div>
            </a>
            <a class="sc anim-left" style="transition-delay:0.15s;" href="https://zzazz-za.stores.jp/items/6991f4d9580447c3fea6584c" target="_blank">
              <div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd03best.PNG" alt="AMD Best" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div>
              <div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD Vest</div></div>
            </a>
            <a class="sc anim-right" style="transition-delay:0.2s;" href="https://zzazz-za.stores.jp/items/69564870a6f4f8fadfb809f8" target="_blank">
              <div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/mozyskeylamp.png" alt="Mozys Key Lamp" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div>
              <div class="sc-info"><div class="sc-cat">Artist : Mozyskey</div><div class="sc-name">Lamp (Hand Drawing)</div></div>
            </a>
            <a class="sc anim-left" style="transition-delay:0.25s;" href="https://zzazz-za.stores.jp/items/69c04acbe126f8ad4fcb6b57" target="_blank">
              <div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd10ufotee.png" alt="AMD UFO Tee" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div>
              <div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD UFO Tee</div></div>
            </a>
            <a class="sc anim-right" style="transition-delay:0.3s;" href="https://zzazz-za.stores.jp/items/69c04a31d9171133f7e5e2e1" target="_blank">
              <div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd08grtee.png" alt="AMD GR Tee" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div>
              <div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD GR Tee</div></div>
            </a>
            <a class="sc anim-left" style="transition-delay:0.35s;" href="https://zzazz-za.stores.jp/items/69c04b67e126f8b4d7cb6b58" target="_blank">
              <div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd09kidtee.png" alt="AMD Kid Tee" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div>
              <div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD Kid Tee</div></div>
            </a>
            <a class="sc anim-right" style="transition-delay:0.4s;" href="https://zzazz-za.stores.jp/items/69c04bdbd917113bede5e2fb" target="_blank">
              <div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd09bl.png" alt="AMD BL" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div>
              <div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD BL</div></div>
            </a>
            <a class="sc anim-left" style="transition-delay:0.45s;" href="https://zzazz-za.stores.jp/items/69c04c3bd9171143c6e5e2d0" target="_blank">
              <div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd08blpk.png" alt="AMD BL PK" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div>
              <div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD BL PK</div></div>
            </a>
            <a class="sc anim-right" style="transition-delay:0.5s;" href="https://zzazz-za.stores.jp/items/69c04cb6e126f8b4d7cb6b71" target="_blank">
              <div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd07jktufo_1.PNG" alt="AMD JKT UFO" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div>
              <div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD JKT UFO</div></div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ▸ 5 CONNECT -->
  <div class="chapter" id="c4" data-lazy style="display:none;height:auto;">
    <div class="panel-track" id="c4-track">
      <div class="panel content-panel solo" id="p4-0" style="height:auto;">
        <div class="panel-bg">
          <img loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= get_stylesheet_directory_uri() ?>/logos/getin.png" class="lazy-img"
            alt="" aria-hidden="true"
            style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.18;mix-blend-mode:luminosity;">
        </div>
        <div class="vig vig-heavy"></div>
        <div class="panel-content">

          <div class="connect-grid">
            <div class="rv">
              <img src="<?= get_stylesheet_directory_uri() ?>/logos/getin.png" alt="GET IN TOUCH" class="section-icon-heading anim-left" style="max-height:80px;margin-top:40px;transition-delay:0s;">
              <p class="connect-body anim-up" style="transition-delay:0.1s;">For collaborations, media inquiries, and sponsorships aligned with our cultural and social mission.</p>
              <a href="mailto:niko@allmustdance.com" class="connect-mail anim-up" style="transition-delay:0.2s;">niko@allmustdance.com</a>
            </div>
            <div class="rv">
              <ul class="connect-list">
                <li class="cl anim-right" style="transition-delay:0.1s;"><a href="https://www.instagram.com/allmustdancetokyo/" target="_blank">Instagram</a><span class="cl-type">Social</span></li>
                <li class="cl anim-right" style="transition-delay:0.15s;"><a href="https://www.youtube.com/@allmustdancetokyo" target="_blank">YouTube</a><span class="cl-type">Video</span></li>
                <li class="cl anim-right" style="transition-delay:0.2s;"><a href="<?= home_url('/zine-index/') ?>">Zine</a><span class="cl-type">Archive</span></li>
                <li class="cl anim-right" style="transition-delay:0.25s;"><a href="https://zzazz-za.stores.jp/" target="_blank">Store</a><span class="cl-type">Shop</span></li>
                <li class="cl anim-right" style="transition-delay:0.3s;"><a href="mailto:niko@allmustdance.com">Contact</a><span class="cl-type">Mail</span></li>
              </ul>

              <!-- Partner Logos: EMAILの下 -->
              <div class="partner-logos rv">
                <div class="partner-logos-label">Partners &amp; Venue</div>
                <div class="logo-grid">
                  <div class="logo-cell">
                    <img loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= get_stylesheet_directory_uri() ?>/logos/clubasia.png" class="lazy-img" alt="clubasia" class="color-logo" style="max-height:38px;max-width:80px;">
                  </div>
                  <div class="logo-cell">
                    <img loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= get_stylesheet_directory_uri() ?>/logos/dubla.png" class="lazy-img" alt="DUBLA" style="max-height:24px;max-width:80px;">
                  </div>
                  <div class="logo-cell">
                    <img loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= get_stylesheet_directory_uri() ?>/logos/ufo.png" class="lazy-img" alt="UFO" style="max-height:28px;max-width:80px;filter:brightness(0) invert(1);opacity:0.55;">
                  </div>
                </div>
              </div>

              <div class="connect-footer">
                <span class="copyright">© ALL MUST DANCE™ · Tokyo · 2026</span>
                <a href="https://allmustdance.com/privacy-policy/" class="privacy-link">Privacy Policy</a>
              </div>

              <div class="space-cooking-credit">
                <span class="sc-credit-label">Site Design &amp; Development</span>
                <a href="https://spacecooking.studio" target="_blank" class="sc-credit-logo">
                  <img loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= get_stylesheet_directory_uri() ?>/logos/spacecooking-logo.png" class="lazy-img"
                    alt="SPACE COOKING™"
                    style="height:18px;width:auto;vertical-align:middle;filter:invert(1) hue-rotate(180deg) opacity(0.75);">
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div><!-- /vtrack -->
</div><!-- /deck -->

<!-- ── YOUTUBE THEATER ── -->
<div class="yt-theater" id="ytTheater">
  <button class="yt-theater-close" id="ytClose">×</button>
  <button class="yt-mute-btn" id="ytMuteBtn">🔇</button>

  <!-- イントロ動画 -->
  <div class="yt-theater-intro" id="ytIntro">
    <img id="ytIntroGif" src="https://allmustdance.com/wp-content/uploads/2026/03/theater.gif" alt=""
      style="width:100%;height:100%;object-fit:cover;display:block;">
  </div>

  <!-- ショート動画スワイプ -->
  <div class="yt-shorts-wrap" id="ytShortsWrap">
    <iframe class="yt-shorts-frame" id="ytShortsFrame"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen
      allowfullscreen frameborder="0"></iframe>
    <div class="yt-shorts-nav" id="ytShortsNav"></div>
    <!-- スワイプ検知用オーバーレイ（上下端のみ、中央は再生ボタン操作可） -->
    <div id="ytSwipeOverlay" style="position:absolute;left:0;right:0;top:0;height:25%;z-index:10;background:transparent;"></div>
    <div id="ytSwipeOverlay2" style="position:absolute;left:0;right:0;bottom:0;height:25%;z-index:10;background:transparent;"></div>
  </div>
</div>

<script>
/* ════════════════════════════════════════
   ALL MUST DANCE — Main Script
   CHAPTERS: c0=PARTY c1=WORKSHOP c2=VIDEO c3=STORE c4=CONNECT
════════════════════════════════════════ */

/* ── Constants & State ── */
const CHAPTERS = [
  { id:'c0', panels:['p0-0'] },
  { id:'c1', panels:['p1-0'] },
  { id:'c2', panels:['p2-0'] },
  { id:'c3', panels:['p3-0'] },
  { id:'c4', panels:['p4-0'] },
];
const N = CHAPTERS.length;
const W = () => window.innerWidth;
let cIdx = 0, pIdx = 0;

/* ── DOM refs ── */
const chapEls   = CHAPTERS.map(c => document.getElementById(c.id));
const trackEls  = CHAPTERS.map(c => document.getElementById(c.id + '-track'));
const pdotsEl   = document.getElementById('pdots');
const hintEl    = document.getElementById('swipeHint');
const backBtn   = document.getElementById('backBtn');
const aPrev     = document.getElementById('aPrev');
const aNext     = document.getElementById('aNext');

/* ── UI update ── */
function updateUI(){
  const tp = CHAPTERS[cIdx].panels.length;
  pdotsEl.innerHTML = '';
  if(tp > 1){
    pdotsEl.style.display = 'flex';
    for(let i = 0; i < tp; i++){
      const d = document.createElement('div');
      d.className = 'pdot' + (i === pIdx ? ' on' : '');
      d.onclick = ((_i) => () => snapToPanel(_i))(i);
      pdotsEl.appendChild(d);
    }
  } else {
    pdotsEl.style.display = 'none';
  }
  backBtn.classList.toggle('visible', pIdx > 0);
  chapEls.forEach((el, i) => el.classList.toggle('active', i === cIdx));
}

/* ── Reveal animations ── */
function showRv(ci){
  if(!chapEls[ci]) return;
  if(typeof gsap === 'undefined'){
    // GSAPなしフォールバック
    const rvEls = chapEls[ci].querySelectorAll('.rv,.rv-left,.rv-right,.rv-up,.rv-scale');
    rvEls.forEach(el => el.classList.remove('visible'));
    rvEls.forEach((el,i) => setTimeout(()=>el.classList.add('visible'), 120+i*80));
    return;
  }
  const rvEls = [...chapEls[ci].querySelectorAll('.rv,.rv-left,.rv-right,.rv-up,.rv-scale')];
  if(!rvEls.length) return;
  // まず非表示にリセット
  gsap.set(rvEls, {opacity:0, y:20, clearProps:'none'});
  // staggerでフェードイン（消えてすぐつくにならないよう duration を長めに）
  gsap.to(rvEls, {
    opacity:1, y:0,
    duration:0.65,
    stagger:0.07,
    ease:'power3.out',
    delay:0.1,
    onStart(){ rvEls.forEach(el=>el.classList.add('visible')); }
  });
}

/* ── Chapter jump ── */
let _chapNavLock = false;
function goChapter(newC, newP = 0){
  if(newC < 0 || newC >= N) return;
  if(chapEls[newC].style.display === 'none') return;
  if(_chapNavLock) return;
  _chapNavLock = true;
  cIdx = newC; pIdx = newP;
  chapEls[cIdx].scrollIntoView({ behavior: 'smooth', block: 'start' });
  if(trackEls[cIdx]) trackEls[cIdx].scrollLeft = newP * W();
  updateUI();
  /* 赤ラインエフェクト */
  if(typeof gsap !== 'undefined'){
    const line = document.getElementById('amd-chapter-line');
    if(line){
      gsap.fromTo(line,
        {scaleX:0, opacity:1},
        {scaleX:1, opacity:0, duration:0.6, ease:'power2.inOut',
         onComplete:()=>{ gsap.set(line,{scaleX:0}); }}
      );
    }
  }
  /* スクロール完了後にロック解除 + テキストアニメ */
  setTimeout(() => {
    _chapNavLock = false;
    showRv(cIdx);
  }, 700);
}

/* ── Panel snap ── */
function snapToPanel(newP){
  const tp = CHAPTERS[cIdx].panels.length;
  if(newP < 0 || newP >= tp) return;
  pIdx = newP;
  if(trackEls[cIdx]){
    trackEls[cIdx].style.scrollBehavior = '';
    trackEls[cIdx].scrollTo({ left: newP * W(), behavior: 'smooth' });
  }
  updateUI();
}

/* ── Vertical scroll detection ── */
let scrollTimer;
window.addEventListener('scroll', () => {
  /* SCROLLインジケーターを隠す */
  const si = document.getElementById('scrollIndicator');
  if(si) si.style.opacity = '0';
  clearTimeout(scrollTimer);
  scrollTimer = setTimeout(() => {
    const sy = window.scrollY;
    const vh = window.innerHeight;
    let accumulated = 0;
    let newC = cIdx;
    for(let i = 0; i < N; i++){
      if(!chapEls[i] || chapEls[i].style.display === 'none') continue;
      const h = chapEls[i].offsetHeight || vh;
      if(sy < accumulated + h - 20){ newC = i; break; }
      accumulated += h;
      newC = i;
    }
    if(newC !== cIdx && !_chapNavLock){
      cIdx = newC; pIdx = 0;
      updateUI();
      showRv(cIdx);
    }
  }, 80);
}, { passive: true });

/* ── Horizontal panel scroll sync ── */
trackEls.forEach((tr, ci) => {
  if(!tr) return;
  let pt;
  tr.addEventListener('scroll', () => {
    clearTimeout(pt);
    pt = setTimeout(() => {
      if(ci !== cIdx) return;
      pIdx = Math.round(tr.scrollLeft / W());
      updateUI();
    }, 80);
  }, { passive: true });
});

/* ════════════════════════════════════════
   SWIPE ENGINE v2
   - Touch + Mouse対応（PC Firefox対応）
   - アーティストオーバーレイ表示中は横スワイプ無効
════════════════════════════════════════ */
let swipeX0 = 0, swipeY0 = 0, swipeDir = null, swipeActive = false;

function isArtistOverlayOpen(){
  return !!document.querySelector('.amd-artist-panel.open, .amd-card-stack.open');
}

function getPoint(e){ return e.touches ? e.touches[0] : e; }

function onSwipeStart(e, ci){
  if(isArtistOverlayOpen()) return;
  const p = getPoint(e);
  swipeX0 = p.clientX;
  swipeY0 = p.clientY;
  swipeDir = null;
  swipeActive = true;
}

function onSwipeMove(e, ci){
  if(!swipeActive || isArtistOverlayOpen()) return;
  const p = getPoint(e);
  const dx = p.clientX - swipeX0;
  const dy = p.clientY - swipeY0;
  if(swipeDir === null && (Math.abs(dx) > 8 || Math.abs(dy) > 8)){
    swipeDir = Math.abs(dx) > Math.abs(dy) ? 'h' : 'v';
  }
  const tr = trackEls[ci];
  if(swipeDir === 'h'){
    if(e.cancelable) e.preventDefault();
    tr.style.scrollBehavior = 'auto';
    tr.scrollLeft = pIdx * W() - dx;
  }
  // 縦スクロールは常に許可（e.preventDefaultしない）
}

function onSwipeEnd(e, ci){
  if(!swipeActive) return;
  swipeActive = false;
  if(swipeDir !== 'h'){ swipeDir = null; return; }
  const p = e.changedTouches ? e.changedTouches[0] : e;
  const totalDx = swipeX0 - p.clientX;
  cIdx = ci;
  const tp = CHAPTERS[ci].panels.length;
  if(totalDx > 40 && pIdx < tp - 1)      snapToPanel(pIdx + 1);
  else if(totalDx < -40 && pIdx > 0)     snapToPanel(pIdx - 1);
  else                                    snapToPanel(pIdx);
  swipeDir = null;
}

[0, 1].forEach(ci => {
  const tr = trackEls[ci];
  if(!tr) return;

  // Touch
  tr.addEventListener('touchstart', e => onSwipeStart(e, ci), { passive: true });
  tr.addEventListener('touchmove',  e => onSwipeMove(e, ci),  { passive: false });
  tr.addEventListener('touchend',   e => onSwipeEnd(e, ci),   { passive: true });

  // Mouse（PC Firefox対応）
  let mouseDown = false;
  tr.addEventListener('mousedown', e => { mouseDown = true; onSwipeStart(e, ci); });
  tr.addEventListener('mousemove', e => { if(mouseDown) onSwipeMove(e, ci); });
  tr.addEventListener('mouseup',   e => { if(mouseDown){ mouseDown = false; onSwipeEnd(e, ci); } });
  tr.addEventListener('mouseleave',e => { if(mouseDown){ mouseDown = false; onSwipeEnd(e, ci); } });
});

/* ── data-goto buttons ── */
document.querySelectorAll('[data-goto]').forEach(el => {
  if(el.hasAttribute('data-menu-close')) return;
  el.addEventListener('click', e => {
    e.preventDefault();
    const [c, p] = el.dataset.goto.split(',').map(Number);
    if(c !== cIdx) goChapter(c, p); else snapToPanel(p);
  });
});

/* ── Arrow buttons ── */
if(aPrev) aPrev.onclick = () => { if(pIdx > 0) snapToPanel(pIdx - 1); else goChapter(cIdx - 1); };
if(aNext) aNext.onclick = () => {
  const tp = CHAPTERS[cIdx].panels.length;
  if(pIdx < tp - 1) snapToPanel(pIdx + 1); else goChapter(cIdx + 1);
};
if(backBtn) backBtn.onclick = () => snapToPanel(0);

/* ── Menu ── */
/* ── Body scroll lock ── */
let _scrollLockCount = 0;
let _scrollLockY = 0;
function lockBodyScroll(){
  if(_scrollLockCount === 0){
    _scrollLockY = window.scrollY;
    document.body.style.overflow = 'hidden';
    document.body.style.position = 'fixed';
    document.body.style.top = '-' + _scrollLockY + 'px';
    document.body.style.width = '100%';
  }
  _scrollLockCount++;
}
function unlockBodyScroll(){
  _scrollLockCount = Math.max(0, _scrollLockCount - 1);
  if(_scrollLockCount === 0){
    document.body.style.overflow = '';
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.width = '';
    window.scrollTo(0, _scrollLockY);
  }
}

const menuToggle  = document.getElementById('menuToggle');
const menuOverlay = document.getElementById('menuOverlay');
let menuOpen = false;

/* ── Effect 035 state ── */
let _m035Tls = [], _m035LastIdx = 0;

function _m035Activate(idx){
  const items = menuOverlay.querySelectorAll('.menu-item');
  if(!items.length || !_m035Tls.length) return;
  if(_m035LastIdx !== idx && _m035Tls[_m035LastIdx]){
    _m035Tls[_m035LastIdx].timeScale(3).reverse();
    gsap.to(items[_m035LastIdx], {flex:'1 1 52px', duration:0.22, ease:'power2.inOut'});
    items[_m035LastIdx].classList.remove('active');
  }
  _m035LastIdx = idx;
  if(_m035Tls[idx]) _m035Tls[idx].timeScale(1).play();
  gsap.to(items[idx], {flex:'1 1 80px', duration:0.22, ease:'power2.inOut'});
  items[idx].classList.add('active');
}

function _m035Init(){
  _m035Tls = []; _m035LastIdx = 0;
  const items = menuOverlay.querySelectorAll('.menu-item');
  items.forEach((item, idx) => {
    const medias = item.querySelectorAll('.menu-row-media');
    const tl = gsap.timeline({paused:true});
    if(medias.length){
      tl.to(medias, {y:0, stagger:{each:0.05,from:'random'}, duration:0.4, ease:'power4.out'});
    }
    _m035Tls.push(tl);
    /* PC: hover で画像表示、クリックで移動（既存のdata-menu-closeが処理） */
    item.addEventListener('mouseenter', () => _m035Activate(idx));
    /* Mobile: 1回目tap→画像表示、2回目tap→移動 */
    item.addEventListener('touchend', e => {
      if(_m035LastIdx !== idx || !item.classList.contains('active')){
        e.preventDefault();
        e.stopPropagation();
        _m035Activate(idx);
      }
      /* 2回目はデフォルト動作（クリックイベント）に委ねる */
    }, {passive:false});
  });
  _m035Activate(0);
}

function openMenu(){
  menuOpen = true;
  menuToggle.classList.add('open');
  menuOverlay.classList.add('open');
  lockBodyScroll();
  const items = menuOverlay.querySelectorAll('.menu-item');
  gsap.set(items, {opacity:0, x:-20});
  gsap.to(items, {
    opacity:1, x:0, duration:0.4, stagger:0.06,
    ease:'power3.out', delay:0.15,
    onComplete: _m035Init
  });
}
function closeMenu(){
  menuOpen = false;
  menuToggle.classList.remove('open');
  const items = menuOverlay.querySelectorAll('.menu-item');
  gsap.to(items, {
    opacity:0, x:20, duration:0.2,
    stagger:{each:0.04, from:'end'}, ease:'power2.in',
    onComplete:()=>{
      menuOverlay.classList.remove('open');
      unlockBodyScroll();
      _m035Tls = [];
      items.forEach(i=>{
        i.classList.remove('active');
        gsap.set(i, {flex:'', clearProps:'flex'});
        i.querySelectorAll('.menu-row-media').forEach(m => gsap.set(m,{y:'110%'}));
      });
    }
  });
}
menuToggle.addEventListener('click', () => menuOpen ? closeMenu() : openMenu());
document.addEventListener('keydown', e => { if(e.key === 'Escape' && menuOpen) closeMenu(); });

/* data-menu-close: li要素 */
menuOverlay.addEventListener('click', e => {
  const item = e.target.closest('.menu-item[data-menu-close]');
  const hrefItem = e.target.closest('.menu-item[data-href]');
  if(item){
    e.preventDefault();
    const [mc, mp] = (item.dataset.goto||'0,0').split(',').map(Number);
    closeMenu();
    setTimeout(() => goChapter(mc, mp||0), 300);
  } else if(hrefItem){
    e.preventDefault();
    const href = hrefItem.dataset.href;
    closeMenu();
    setTimeout(() => { window.location.href = href; }, 300);
  }
});

/* ── Hero video ── */
const heroVid = document.getElementById('heroVid');
if(heroVid){
  const showVid = () => { heroVid.style.opacity = '1'; };
  heroVid.addEventListener('canplay', showVid, { once: true });
  if(heroVid.readyState >= 2) showVid();
}

/* ── Swipe hint ── */
if(hintEl){
  setTimeout(() => hintEl.classList.add('hidden'), 4000);
  window.addEventListener('scroll', () => hintEl.classList.add('hidden'), { once: true, passive: true });
}

/* ── Lazy loading ── */
const lazyIO = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if(!entry.isIntersecting) return;
    const ch = entry.target;
    ch.querySelectorAll('img.lazy-img[data-src]').forEach(img => {
      img.src = img.dataset.src;
      img.removeAttribute('data-src');
      img.classList.remove('lazy-img');
    });
    ch.querySelectorAll('iframe[data-src]').forEach(iframe => {
      iframe.src = iframe.dataset.src;
      iframe.removeAttribute('data-src');
    });
    lazyIO.unobserve(ch);
  });
}, { rootMargin: '200px 0px' });
document.querySelectorAll('.chapter[data-lazy]').forEach(ch => lazyIO.observe(ch));

/* ── Scroll animations ── */
const ioAnim = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if(e.isIntersecting){ e.target.classList.add('visible'); ioAnim.unobserve(e.target); }
  });
}, { threshold: 0.12 });
document.querySelectorAll('.rv,.rv-left,.rv-right,.rv-up,.rv-scale').forEach((el, i) => {
  const parent = el.parentElement;
  const siblings = parent ? [...parent.querySelectorAll(':scope > .rv,:scope > .rv-left,:scope > .rv-right,:scope > .rv-up,:scope > .rv-scale')] : [];
  const idx = siblings.indexOf(el);
  if(idx > 0) el.style.transitionDelay = (idx * 0.1) + 's';
  ioAnim.observe(el);
});

/* ── Parallax ── */
const parallaxEls = document.querySelectorAll('.parallax-bg');
let ticking = false;
window.addEventListener('scroll', () => {
  if(ticking) return;
  ticking = true;
  requestAnimationFrame(() => {
    parallaxEls.forEach(el => {
      const section = el.closest('.chapter');
      if(!section) return;
      const progress = -section.getBoundingClientRect().top / window.innerHeight;
      el.style.transform = `translateY(${progress * 40}px)`;
    });
    ticking = false;
  });
}, { passive: true });

/* ════════════════════════════════════════
   YOUTUBE THEATER
════════════════════════════════════════ */
const ytTheater    = document.getElementById('ytTheater');
const ytClose      = document.getElementById('ytClose');
const ytIntro      = document.getElementById('ytIntro');
const ytShortsWrap = document.getElementById('ytShortsWrap');
const ytShortsFrame= document.getElementById('ytShortsFrame');
const ytShortsNav  = document.getElementById('ytShortsNav');
const ytOpenBtn    = document.getElementById('ytOpenBtn');

const GIF_URL      = 'https://allmustdance.com/wp-content/uploads/2026/03/theater.gif';
const GIF_DURATION = 4000;
const SHORTS = [
  { id: 'kP56sTI5bIw', title: 'Short 1' },
  { id: 'g0cKZ3CK84Q', title: 'Short 2' },
  { id: 'kJFir9vlOC0', title: 'Short 3' },
  { id: '1cSuNZ9y71Q', title: 'Short 4' },
];
let ytIdx = 0;
let ytFallbackTimer;
let ytTheaterOpened = false;

function buildYtNav(){
  ytShortsNav.innerHTML = '';
  SHORTS.forEach((_, i) => {
    const d = document.createElement('div');
    d.className = 'yt-shorts-dot' + (i === ytIdx ? ' on' : '');
    d.onclick = () => loadShort(i);
    ytShortsNav.appendChild(d);
  });
}

let ytMuted = true;
function loadShort(idx){
  ytIdx = idx;
  const muteParam = ytMuted ? '1' : '0';
  ytShortsFrame.src = `https://www.youtube.com/embed/${SHORTS[idx].id}?autoplay=1&mute=${muteParam}&rel=0&playsinline=1&loop=1&playlist=${SHORTS[idx].id}&enablejsapi=1`;
  buildYtNav();
  updateMuteBtn();
}

function updateMuteBtn(){
  const btn = document.getElementById('ytMuteBtn');
  if(btn) btn.textContent = ytMuted ? '🔇' : '🔊';
}

const ytMuteBtn = document.getElementById('ytMuteBtn');
if(ytMuteBtn){
  ytMuteBtn.addEventListener('click', () => {
    ytMuted = !ytMuted;
    // 現在の動画を再読み込み（ミュート状態を変更）
    loadShort(ytIdx);
    updateMuteBtn();
  });
}

function showShorts(){
  clearTimeout(ytFallbackTimer);
  ytIntro.classList.add('fade-out');
  setTimeout(() => {
    ytShortsWrap.classList.add('visible');
    loadShort(0);
    buildYtNav();
    const btn = document.getElementById('ytMuteBtn');
    if(btn) btn.classList.add('visible');
  }, 400);
}

function openTheater(){
  ytTheater.classList.add('open');
  ytIntro.classList.remove('fade-out');
  ytShortsWrap.classList.remove('visible');
  document.body.style.overflow = 'hidden';
  const gif = document.getElementById('ytIntroGif');
  if(gif){ gif.src = ''; gif.src = GIF_URL + '?r=' + Math.random(); }
  clearTimeout(ytFallbackTimer);
  ytFallbackTimer = setTimeout(showShorts, GIF_DURATION);
}

function closeTheater(){
  ytTheater.classList.remove('open');
  const gif = document.getElementById('ytIntroGif');
  if(gif) gif.src = '';
  ytShortsFrame.src = '';
  document.body.style.overflow = '';
  clearTimeout(ytFallbackTimer);
  // ミュートボタンを非表示にしてミュート状態リセット
  ytMuted = true;
  const btn = document.getElementById('ytMuteBtn');
  if(btn){ btn.classList.remove('visible'); btn.textContent = '🔇'; }
  // c2・c3・c4を表示
  ['c2','c3','c4'].forEach(id => {
    const ch = document.getElementById(id);
    if(ch) ch.style.display = '';
  });
  // グリッド表示
  const gridView = document.getElementById('ytGridView');
  const triggerScreen = document.getElementById('ytTriggerScreen');
  if(gridView){ gridView.style.display = 'block'; }
  if(triggerScreen){ triggerScreen.style.display = 'none'; }
  // 画像遅延読み込み
  ['ytGridView','c3','c4'].forEach(id => {
    const el = document.getElementById(id);
    if(!el) return;
    el.querySelectorAll('img.lazy-img[data-src]').forEach(img => {
      img.src = img.dataset.src;
      img.removeAttribute('data-src');
      img.classList.remove('lazy-img');
    });
  });
  // VIDEOセクションにスクロール
  const c2El = document.getElementById('c2');
  if(c2El){
    setTimeout(() => {
      c2El.scrollIntoView({ behavior: 'smooth', block: 'start' });
      cIdx = 2; pIdx = 0;
      updateUI();
    }, 100);
  } else {
    updateUI();
  }
}

if(ytOpenBtn) ytOpenBtn.addEventListener('click', openTheater);
if(ytClose)   ytClose.addEventListener('click', closeTheater);

// シアター内縦スワイプでショート切替（透明オーバーレイで検知）
// シアター縦スワイプ：上下オーバーレイで検知
let ytSwipeY0 = 0;
['ytSwipeOverlay','ytSwipeOverlay2'].forEach(id => {
  const el = document.getElementById(id);
  if(!el) return;
  el.addEventListener('touchstart', e => {
    ytSwipeY0 = e.touches[0].clientY;
  }, { passive: true });
  el.addEventListener('touchend', e => {
    const dy = ytSwipeY0 - e.changedTouches[0].clientY;
    if(Math.abs(dy) < 40) return;
    if(dy > 0 && ytIdx < SHORTS.length - 1) loadShort(ytIdx + 1);
    else if(dy < 0 && ytIdx > 0)            loadShort(ytIdx - 1);
  }, { passive: true });
});

/* ════════════════════════════════════════
   PULL TO PLAY
   WORKSHOPの最下部でプル→GIF→YouTube
════════════════════════════════════════ */
const ytPullBar  = document.getElementById('ytPullBar');
const ptrSpinner = document.getElementById('ptrSpinner');
const PTR_CHAPTER = document.getElementById('c1');
const PTR_PX = 100;
let ptrY0 = 0, ptrOn = false, ptrDone = false, ptrReady = false;

// WORKSHOPの最下部にいるか
function atWorkshopEnd(){
  if(ytTheaterOpened || ptrDone) return false;
  // scrollYでc1の下端付近にいるか判定
  const el = document.getElementById('c1');
  if(!el) return false;
  const elTop = el.offsetTop;
  const elH   = el.offsetHeight;
  const sy    = window.scrollY;
  const vh    = window.innerHeight;
  // c1の下端がビューポートの下端付近（±150px）
  return Math.abs((elTop + elH) - (sy + vh)) < 150;
}

window.addEventListener('touchstart', e => {
  if(!atWorkshopEnd()) return;
  ptrY0 = e.touches[0].clientY;
  ptrOn = true;
  ptrReady = false;
}, { passive: true }); // passive:true →縦スクロールをブロックしない

window.addEventListener('touchmove', e => {
  if(!ptrOn) return;
  const dy = ptrY0 - e.touches[0].clientY;
  if(dy <= 0){ ptrOn = false; ptrReady = false; return; }
  // ここではpreventDefaultしない → 縦スクロールを許可
  const p = Math.min(dy / PTR_PX, 1);
  if(PTR_CHAPTER) PTR_CHAPTER.style.transform = `translateY(-${dy * 0.15}px)`;
  if(ytPullBar) ytPullBar.style.bottom = `${-80 + Math.min(dy * 0.8, 72)}px`;
  if(ptrSpinner){
    ptrSpinner.style.opacity = p.toFixed(2);
    if(p < 1){
      ptrSpinner.style.transform = `rotate(${dy * 5}deg) scale(${0.4 + p * 0.6})`;
    } else if(!ptrReady){
      ptrReady = true;
      ptrSpinner.classList.add('loading');
      ptrSpinner.style.transform = '';
    }
  }
}, { passive: true }); // passive:true → ブロックしない

window.addEventListener('touchend', () => {
  if(!ptrOn) return;
  ptrOn = false;
  // バネで戻す
  if(PTR_CHAPTER){
    PTR_CHAPTER.style.transition = 'transform 0.5s cubic-bezier(0.34,1.56,0.64,1)';
    PTR_CHAPTER.style.transform = 'translateY(0)';
    setTimeout(() => { PTR_CHAPTER.style.transition = ''; }, 550);
  }
  if(ytPullBar){
    ytPullBar.style.transition = 'bottom 0.35s ease';
    ytPullBar.style.bottom = '-80px';
    setTimeout(() => { ytPullBar.style.transition = ''; }, 400);
  }
  if(ptrSpinner){
    ptrSpinner.classList.remove('loading');
    ptrSpinner.style.opacity = '0';
    ptrSpinner.style.transform = 'scale(0.4)';
  }
  if(ptrReady && !ytTheaterOpened){
    ptrDone = true;
    ytTheaterOpened = true;
    setTimeout(() => openTheater(), 300);
  }
  ptrReady = false;
}, { passive: true });


/* ── Desktop wheel scroll ── */
let wheelTimer;
let wheelDelta = 0;
window.addEventListener('wheel', e => {
  // オーバーレイ開放中は無視
  if(isArtistOverlayOpen()) return;
  if(document.querySelector('.amd-ticket-overlay.open')) return;
  // panel-track内で横スクロール可能なパネルが複数あるときは無視
  const track = e.target.closest ? e.target.closest('.panel-track') : null;
  if(track){
    const panels = track.querySelectorAll('.panel');
    if(panels.length > 1) return;
  }
  wheelDelta += e.deltaY;
  clearTimeout(wheelTimer);
  wheelTimer = setTimeout(() => {
    if(Math.abs(wheelDelta) < 30){ wheelDelta = 0; return; }
    const visible = [];
    for(let i = 0; i < N; i++){
      if(chapEls[i] && chapEls[i].style.display !== 'none') visible.push(i);
    }
    const curPos = visible.indexOf(cIdx);
    if(wheelDelta > 0 && curPos < visible.length - 1){
      goChapter(visible[curPos + 1]);
    } else if(wheelDelta < 0 && curPos > 0){
      goChapter(visible[curPos - 1]);
    }
    wheelDelta = 0;
  }, 50);
}, { passive: true });


/* ── SCROLL ANIMATION OBSERVER ── */
const animIO = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if(entry.isIntersecting){
      entry.target.classList.add('in');
      animIO.unobserve(entry.target);
    }
  });
}, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.anim-up, .anim-left, .anim-right').forEach(el => {
  animIO.observe(el);
});

/* ── GIFプリキャッシュ ── */
(new Image()).src = GIF_URL;

/* ── JP/EN言語切り替え ── */
function amdToggleLang(){
  const html = document.getElementById('amdHtml');
  const current = html.getAttribute('data-lang') || 'jp';
  const next = current === 'jp' ? 'en' : 'jp';
  amdSetLang(next);
}

function amdSetLang(lang){
  const html = document.getElementById('amdHtml');
  html.setAttribute('data-lang', lang);
  html.lang = lang === 'jp' ? 'ja' : 'en';
  localStorage.setItem('amd-lang', lang);

  /* lang-switchable要素のテキストを切り替え (EN時のみ書き換え) */
  document.querySelectorAll('.lang-switchable').forEach(el => {
    if(lang === 'jp'){
      /* JPはdata-jpを使わず、元のHTMLのまま (デフォルトテキスト) */
      const orig = el.getAttribute('data-orig');
      if(orig !== null) el.innerHTML = orig;
    } else {
      /* EN時: 初回のみdata-origに元のHTML保存 */
      if(!el.hasAttribute('data-orig')) el.setAttribute('data-orig', el.innerHTML);
      const val = el.getAttribute('data-' + lang);
      if(val !== null) el.innerHTML = val;
    }
  });

  /* ボタン表示更新 */
  const jpEl = document.getElementById('langJp');
  const enEl = document.getElementById('langEn');
  if(jpEl) jpEl.style.opacity = lang === 'jp' ? '1' : '0.35';
  if(enEl) enEl.style.opacity = lang === 'en' ? '1' : '0.35';

  /* アーティストカードが開いていたら再アニメ */
  if(typeof _apArtists !== 'undefined' && _apArtists.length){
    const el = document.getElementById('amc-' + _apCurIdx);
    if(el){
      el.querySelectorAll('.af-desc,.af-desc-en').forEach(p => {
        p.dataset.wrapped = '';
        p.querySelectorAll('.amd-word').forEach(w => w.replaceWith(w.textContent));
      });
      if(typeof _amdWrapWords === 'function'){
        el.querySelectorAll('.af-desc,.af-desc-en').forEach(_amdWrapWords);
        const words = el.querySelectorAll('.amd-word');
        if(words.length && typeof gsap !== 'undefined'){
          gsap.set(words,{x:'80vw',opacity:0});
          gsap.to(words,{x:0,opacity:1,duration:0.55,stagger:0.014,ease:'power4.out'});
        }
      }
    }
  }
}

/* ── INIT ── */
chapEls[0].classList.add('active');
updateUI();

/* ── ⑤ Safari Dynamic Viewport Height ── */
(function(){
  function setVH(){
    const h = window.visualViewport ? window.visualViewport.height : window.innerHeight;
    document.documentElement.style.setProperty('--amd-full-h', h + 'px');
  }
  // 即時実行
  setVH();
  // visualViewport（Safari対応）
  if(window.visualViewport){
    window.visualViewport.addEventListener('resize', setVH, {passive:true});
    window.visualViewport.addEventListener('scroll', setVH, {passive:true});
  }
  // フォールバック
  window.addEventListener('resize', setVH, {passive:true});
  // アドレスバー出入りはscrollで検知
  window.addEventListener('scroll', setVH, {passive:true});
  // orientationchange
  window.addEventListener('orientationchange', function(){ setTimeout(setVH, 100); });
})();
setTimeout(() => showRv(0), 300);

/* 保存済み言語を適用 */
(function(){
  const saved = localStorage.getItem('amd-lang') || 'jp';
  amdSetLang(saved);
})();

/* ── ARTIST PANEL & CARD STACK (031) ── */
let _apCurIdx = 0, _apArtists = [];

/* ── フラッシュエフェクト ── */
function amdRedFlash(onComplete){
  if(typeof gsap === 'undefined'){ if(onComplete) onComplete(); return; }
  const el = document.getElementById('amdRedFlash');
  if(!el){ if(onComplete) onComplete(); return; }
  gsap.timeline({onComplete: onComplete || null})
    .set(el, {opacity:0})
    .to(el, {opacity:0.55, duration:0.1, ease:'power2.out'})
    .to(el, {opacity:0, duration:0.4, ease:'power2.in'});
}

function openWsArtistOverlay(){
  document.getElementById('p1-1').classList.add('open');
  lockBodyScroll();
}
function closeWsArtistOverlay(){
  document.getElementById('p1-1').classList.remove('open');
  unlockBodyScroll();
}
function openWsTicketOverlay(){
  document.getElementById('p1-2').classList.add('open');
  lockBodyScroll();
}
function closeWsTicketOverlay(){
  document.getElementById('p1-2').classList.remove('open');
  unlockBodyScroll();
}
function openTicketOverlay(){
  document.getElementById('p0-2').classList.add('open');
  lockBodyScroll();
}
function closeTicketOverlay(){
  document.getElementById('p0-2').classList.remove('open');
  unlockBodyScroll();
}
function openArtistPanel(){
  document.getElementById('artistPanelOverlay').classList.add('open');
  lockBodyScroll();
}
function closeArtistPanel(){
  document.getElementById('artistPanelOverlay').classList.remove('open');
  unlockBodyScroll();
}
/* ap-group touchend: moved to DOMContentLoaded */

function openCardStack(groupKey){
  const overlay  = document.getElementById('cardStackOverlay');
  const stage    = document.getElementById('cardStackStage');
  const titleEl  = document.getElementById('cardStackTitle');
  const labels   = {dj:'DEEP FLOOR', bar:'FDOOR', dresser:'DRESSER BY DANCER'};
  titleEl.textContent = labels[groupKey] || '';

  // ACFデータをPHPから埋め込んだJSON取得
  const data = window._amdArtists ? window._amdArtists[groupKey] : [];
  _apArtists = data || [];
  _apCurIdx  = 0;

  _buildCardStack(stage, _apArtists);
  overlay.classList.add('open');
  lockBodyScroll();
}
function closeCardStack(){
  document.getElementById('cardStackOverlay').classList.remove('open');
  unlockBodyScroll();
}

function _amdWrapWords(el){
  if(!el || el.dataset.wrapped) return;
  el.innerHTML = el.textContent.split(' ').map(w=>`<span class="amd-word">${w}</span>`).join(' ');
  el.dataset.wrapped='1';
}

function _buildCardStack(stage, artists){
  stage.innerHTML = '';
  if(!artists.length){
    stage.innerHTML = '<div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;"><p style="font-size:12px;letter-spacing:.3em;text-transform:uppercase;color:rgba(237,235,230,.3);">Coming Soon</p></div>';
    return;
  }
  artists.forEach((a, i) => {
    const card = document.createElement('div');
    card.className = 'amd-card';
    card.id = 'amc-' + i;
    card.style.cssText = `z-index:${artists.length - i};`;
    const photoHtml = a.photo
      ? `<img src="${a.photo}" alt="${a.name}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.55;">`
      : '';
    const scHtml = a.sc ? `<a href="${a.sc}" target="_blank" class="af-link"><img src="${_amdThemeUrl}/logos/sc.png" style="width:28px;height:28px;object-fit:contain;opacity:0.82;"></a>` : '';
    const igHtml = a.ig ? `<a href="${a.ig}" target="_blank" class="af-link"><img src="${_amdThemeUrl}/logos/insta.png" style="width:28px;height:28px;object-fit:contain;opacity:0.82;"></a>` : '';
    card.innerHTML = `
      <div style="position:absolute;inset:0;">${photoHtml}</div>
      <div class="vig-artist"></div>
      <div class="amd-card-content panel-content">
        <div class="af-genre">${a.genre || ''}</div>
        <div class="af-name">${a.name}</div>
        <div class="af-links" style="margin-bottom:14px;">${scHtml}${igHtml}</div>
        <p class="af-desc">${a.bio_ja || ''}</p>
        <p class="af-desc-en">${a.bio_en || ''}</p>
        <div class="amd-card-num">${String(i+1).padStart(2,'0')} / ${String(artists.length).padStart(2,'0')}</div>
      </div>`;
    stage.appendChild(card);
  });

  // ナビボタン — stageの外(overlay直下)に配置してカードに隠れないようにする
  const overlay = document.getElementById('cardStackOverlay');
  // 既存のnavがあれば削除
  const oldNav = overlay.querySelector('.amd-card-nav');
  if(oldNav) oldNav.remove();
  const nav = document.createElement('div');
  nav.className = 'amd-card-nav';
  nav.innerHTML = `
    <button class="amd-card-nav-btn amd-nav-prev" onclick="amdCardNav(-1)" disabled>↑ PREV</button>
    <button class="amd-card-nav-btn amd-nav-next" onclick="amdCardNav(1)">${_apArtists.length > 1 ? '↓ NEXT' : '✕ CLOSE'}</button>`;
  overlay.appendChild(nav);

  // 1枚目を表示
  _showCard(0, false);
}

function _showCard(idx, animate){
  _apArtists.forEach((_,i)=>{
    const el = document.getElementById('amc-'+i);
    if(!el) return;
    if(i < idx){
      gsap.set(el,{rotationX:40,rotationZ:(i%2===0?5:-5),scale:0.72,opacity:0,transformPerspective:800,transformOrigin:'50% 10%'});
    } else if(i === idx){
      if(animate){
        gsap.fromTo(el,
          {rotationX:-10,rotationZ:0,y:40,opacity:0,scale:0.96,transformPerspective:800,transformOrigin:'50% 10%'},
          {rotationX:0,rotationZ:0,y:0,opacity:1,scale:1,duration:0.52,ease:'power3.out'}
        );
        // Effect 005
        el.querySelectorAll('.af-desc,.af-desc-en').forEach(_amdWrapWords);
        const words = el.querySelectorAll('.amd-word');
        if(words.length){
          gsap.set(words,{x:'80vw',opacity:0});
          gsap.to(words,{x:0,opacity:1,duration:0.65,stagger:0.016,ease:'power4.out',delay:0.3});
        }
      } else {
        gsap.set(el,{rotationX:0,rotationZ:0,y:0,opacity:1,scale:1,transformPerspective:800,transformOrigin:'50% 10%'});
        el.querySelectorAll('.af-desc,.af-desc-en').forEach(_amdWrapWords);
        const words = el.querySelectorAll('.amd-word');
        if(words.length){
          gsap.set(words,{x:'80vw',opacity:0});
          gsap.to(words,{x:0,opacity:1,duration:0.65,stagger:0.016,ease:'power4.out',delay:0.45});
        }
      }
    } else {
      gsap.set(el,{rotationX:0,y:0,opacity:0,scale:1,transformPerspective:800,transformOrigin:'50% 10%'});
    }
  });
  const overlayEl = document.getElementById('cardStackOverlay');
  const prev = overlayEl ? overlayEl.querySelector('.amd-nav-prev') : null;
  const next = overlayEl ? overlayEl.querySelector('.amd-nav-next') : null;
  if(prev){
    prev.disabled = idx === 0;
    prev.style.visibility = idx === 0 ? 'hidden' : 'visible';
  }
  if(next) next.textContent = idx === _apArtists.length-1 ? '✕ CLOSE' : '↓ NEXT';
}

function amdCardNav(dir){
  const next = _apCurIdx + dir;
  if(next < 0) return;
  if(next >= _apArtists.length){
    const el = document.getElementById('amc-'+_apCurIdx);
    if(el) gsap.to(el,{rotationX:40,rotationZ:(_apCurIdx%2===0?5:-5),scale:0.72,opacity:0,duration:0.42,ease:'power2.in',transformPerspective:800,transformOrigin:'50% 10%',onComplete:closeCardStack});
    else closeCardStack();
    return;
  }
  const cur = document.getElementById('amc-'+_apCurIdx);
  if(cur) gsap.to(cur,{rotationX:40,rotationZ:(_apCurIdx%2===0?5:-5),scale:0.72,opacity:0,duration:0.45,ease:'power2.in',transformPerspective:800,transformOrigin:'50% 10%'});
  _apCurIdx = next;
  _showCard(_apCurIdx, true);
}

</script>

<!-- ── FLASH ELEMENTS ── -->
<div id="amd-chapter-line"></div>
<div class="amd-red-flash" id="amdRedFlash"></div>

<!-- ── ARTIST PANEL OVERLAY ── -->
<div class="amd-artist-panel" id="artistPanelOverlay">
  <div class="amd-ap-bg"></div>
  <button class="amd-ap-close" onclick="closeArtistPanel()">×</button>
  <div class="amd-ap-inner">
    <div class="amd-ap-title">Select a floor</div>
    <div class="amd-ap-groups">
      <?php
      $party_artists_list2 = $party_artists ?? [];
      $grouped2 = ['dj'=>[], 'bar'=>[], 'dresser'=>[]];
      foreach($party_artists_list2 as $pa2){
        $role2 = strtolower(get_field('role',$pa2->ID) ?? '');
        if(str_contains($role2,'dresser')||str_contains($role2,'dancer')||str_contains($role2,'dance')||str_contains($role2,'style')){
          $grouped2['dresser'][] = $pa2;
        } elseif(str_contains($role2,'bar')||str_contains($role2,'fdoor')||str_contains($role2,'bartender')){
          $grouped2['bar'][] = $pa2;
        } else {
          $grouped2['dj'][] = $pa2;
        }
      }
      ?>
      <?php
      $mn_dj = implode(' · ', array_map(fn($m)=>esc_html($m->post_title), $grouped2['dj']));
      ?>
      <button type="button" class="amd-ap-group" onclick="openCardStack('dj')" style="display:block;width:100%;text-align:left;background:none;border:none;padding:0;">
        <img loading="lazy" src="https://allmustdance.com/wp-content/uploads/2026/03/deepfloor.jpg" alt="DEEP FLOOR">
        <div class="amd-ap-group-vig"></div>
        <div class="amd-ap-group-info">
          <div class="amd-ap-group-sub">DJ · Music</div>
          <div class="amd-ap-group-name">DEEP FLOOR</div>
          <?php if($mn_dj): ?><div class="amd-ap-group-members"><?= $mn_dj ?></div><?php endif; ?>
        </div>
      </button>
      <?php
      $mn_bar = implode(' · ', array_map(fn($m)=>esc_html($m->post_title), $grouped2['bar']));
      ?>
      <button type="button" class="amd-ap-group" onclick="openCardStack('bar')" style="display:block;width:100%;text-align:left;background:none;border:none;padding:0;">
        <img loading="lazy" src="https://allmustdance.com/wp-content/uploads/2026/03/fdoor.jpg" alt="FDOOR">
        <div class="amd-ap-group-vig"></div>
        <div class="amd-ap-group-info">
          <div class="amd-ap-group-sub">Bar · Serving</div>
          <div class="amd-ap-group-name">FDOOR</div>
          <?php if($mn_bar): ?><div class="amd-ap-group-members"><?= $mn_bar ?></div><?php endif; ?>
        </div>
      </button>
      <?php
      $mn_dr = implode(' · ', array_map(fn($m)=>esc_html($m->post_title), $grouped2['dresser']));
      ?>
      <button type="button" class="amd-ap-group" onclick="openCardStack('dresser')" style="display:block;width:100%;text-align:left;background:none;border:none;padding:0;">
        <img loading="lazy" src="https://allmustdance.com/wp-content/uploads/2026/03/20260323_152503.gif" alt="DRESSER BY DANCER">
        <div class="amd-ap-group-vig"></div>
        <div class="amd-ap-group-info">
          <div class="amd-ap-group-sub">Dance · Style</div>
          <div class="amd-ap-group-name">DRESSER BY DANCER</div>
          <?php if($mn_dr): ?><div class="amd-ap-group-members"><?= $mn_dr ?></div><?php endif; ?>
        </div>
      </button>
    </div>
  </div>
</div>

<!-- ── CARD STACK OVERLAY (031) ── -->
<div class="amd-card-stack" id="cardStackOverlay">
  <button class="amd-cs-close" onclick="closeCardStack()">← Back</button>
  <div class="amd-cs-title" id="cardStackTitle">DEEP FLOOR</div>
  <div id="cardStackStage"></div>
</div>

<!-- ── アーティストデータ (PHP→JS) ── -->
<script>
var _amdThemeUrl = '<?= get_stylesheet_directory_uri() ?>';
var _amdArtists = <?php
$out = ['dj'=>[], 'bar'=>[], 'dresser'=>[]];
$party_artists_all = $party_artists ?? [];
foreach($party_artists_all as $pa_all){
  $role_all = strtolower(get_field('role',$pa_all->ID) ?? '');
  // role値に応じてグループ振り分け
  // dresser: dresser / dancer / dance / style
  // bar: bar / bartender / fdoor
  // dj: dj / その他
  if(str_contains($role_all,'dresser') || str_contains($role_all,'dancer') || str_contains($role_all,'dance') || str_contains($role_all,'style')){
    $group_all = 'dresser';
  } elseif(str_contains($role_all,'bar') || str_contains($role_all,'fdoor') || str_contains($role_all,'bartender')){
    $group_all = 'bar';
  } else {
    $group_all = 'dj'; // DJ / その他
  }
  $photo_all = get_field('photo',$pa_all->ID);
  // role: $role_all -> group: $group_all
  $out[$group_all][] = [
    'name'   => $pa_all->post_title,
    'genre'  => get_field('genre',$pa_all->ID) ?? '',
    'bio_ja' => get_field('bio_ja',$pa_all->ID) ?? '',
    'bio_en' => get_field('bio_en',$pa_all->ID) ?? '',
    'photo'  => $photo_all ? $photo_all['url'] : '',
    'sc'     => get_field('soundcloud',$pa_all->ID) ?? '',
    'ig'     => get_field('instagram_url',$pa_all->ID) ?? '',
  ];
}
echo json_encode($out, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT);
?>;

/* ── ap-groupタップ確実化 (DOM確定後に実行) ── */
(function(){
  document.querySelectorAll('.amd-ap-group').forEach(function(el){
    var _tapY = 0, _didMove = false;

    el.addEventListener('touchstart', function(e){
      _tapY = e.touches[0].clientY;
      _didMove = false;
    }, {passive:true});

    el.addEventListener('touchmove', function(e){
      if(Math.abs(e.touches[0].clientY - _tapY) > 10) _didMove = true;
    }, {passive:true});

    el.addEventListener('touchend', function(e){
      if(_didMove) return;
      /* preventDefault しない → click イベントを自然に発火させる */
      /* ただし 300ms 遅延を回避するため直接呼び出す */
      var key = el.getAttribute('data-group');
      if(key) openCardStack(key);
    }, {passive:true});
  });
})();
</script>

</body>
</html>
