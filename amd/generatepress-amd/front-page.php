<?php
/**
 * ALL MUST DANCE - front-page.php
 * GeneratePress Child Theme
 * Bug fixes applied: GSAP dedup, YouTube Theater HTML, openTheater(), data-group, CSS cleanup
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
if(!$ws_ticket)   $ws_ticket   = get_post_meta($sid, 'ws_ticket_url', true)   ?: 'https://zzazz-za.stores.jp/items/69bd3dbba499220687ba06f6';
if(!$ws_ticket2)  $ws_ticket2  = get_post_meta($sid, 'ws_ticket_url_2', true)  ?: 'https://zzazz-za.stores.jp/items/69bd3ce63abc001fe0315977';
if(!$ws_date)     $ws_date     = get_post_meta($sid, 'ws_date', true)          ?: '64BEAT · APR 1 · 8 · 15';
if(!$ws_venue)    $ws_venue    = get_post_meta($sid, 'ws_venue', true)         ?: 'noah studio NAKANO · EMOTIONS 高円寺';
if(!$ws_time)     $ws_time     = get_post_meta($sid, 'ws_time', true)          ?: 'Coming soon';

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
<!-- PWA: ホーム画面追加時にアドレスバーなしで表示 -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="AMD™">
<meta name="mobile-web-app-capable" content="yes">
<meta name="theme-color" content="#0C0F1A">
<link rel="manifest" href="<?= get_stylesheet_directory_uri() ?>/manifest.json">
<title>ALL MUST DANCE™</title>
<?php
echo '<link rel="icon" href="' . get_stylesheet_directory_uri() . '/logos/amdheaderlogo.png">' . PHP_EOL;
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet"></noscript>
<!-- FIX 2: preload moved to head -->
<!-- GIF preload removed: use video instead for performance -->
<style>
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
:root {
  --amd-full-h: 100vh;
  --black: #0C0F1A;
  --white: #EDEBE6;
  --red:   #E8100A;
  --blue:  #1A2E6B;
  --line:  rgba(237,235,230,0.09);
}
html { height: 100%; overflow: hidden; overflow: auto; }
body {
  overflow-x: hidden; overflow-y: auto; min-height: 100vh; min-height: -webkit-fill-available;
  background: var(--black); color: var(--white);
  font-family: "Noto Sans JP","Montserrat",sans-serif;
  font-weight: 300; font-feature-settings: "palt";
  -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;
}
#site-header,#site-footer,.site-header,.site-footer,.navigation-bar,#masthead { display:none !important; }
#page,#content,.site-content,#primary,main,article,.entry-content,.content-area { margin:0 !important; padding:0 !important; max-width:100% !important; display:block !important; width:100% !important; }
/* FIX 8: scroll-snap on #deck only */
#deck {
  width:100%;
}
/* Browser mode: snap on html for Safari address bar hide */
html {
  scroll-snap-type: y mandatory;
  -webkit-overflow-scrolling: touch;
  overscroll-behavior-y: none;
}
/* PWA standalone mode: snap on #deck container for tighter control */
html.pwa-mode { overflow:hidden; scroll-snap-type:none; }
html.pwa-mode body { overflow:hidden; }
html.pwa-mode #deck {
  height:100vh; height:calc(100vh - env(safe-area-inset-top));
  overflow-y:scroll; scroll-snap-type:y mandatory;
  -webkit-overflow-scrolling:touch; overscroll-behavior-y:none;
}
#vtrack { width:100%; }
#amd-header {
  position: fixed; top:0; left:0; right:0; z-index:9999;
  display: flex; justify-content:space-between; align-items:center;
  padding: max(20px, calc(env(safe-area-inset-top) + 8px)) 40px 12px;
  background: linear-gradient(to bottom, rgba(12,15,26,0.85) 0%, transparent 100%);
  pointer-events: none;
}
.logo { pointer-events:all; text-decoration:none; }
.logo img { display:block; mix-blend-mode:screen; }
.header-right { display:flex; align-items:center; gap:12px; pointer-events:all; }
.lang-toggle { background:none; border:none; cursor:pointer; display:flex; align-items:center; gap:3px; font-size:10px; font-weight:400; letter-spacing:0.22em; pointer-events:all; padding:4px 2px; }
.lang-jp, .lang-en { color:rgba(237,235,230,0.38); transition:color 0.2s, opacity 0.2s; }
.lang-sep { color:rgba(237,235,230,0.2); }
[data-lang="jp"] .lang-jp { color:var(--white); font-weight:700; }
[data-lang="en"] .lang-en { color:var(--white); font-weight:700; }
#chap-counter, #panel-counter { display:none; }
.chapter { width: 100%; height: 100vh; height: 100vh; min-height: 100vh; min-height: 100vh; position: relative; scroll-snap-align: start; scroll-snap-stop: always; }
#c0, #c1 { max-height: 100vh; max-height: 100vh; min-height: 100vh; min-height: 100vh; overflow: hidden; }
#c2, #c3, #c4 { height: auto; }
.chapter.chapter-auto { height: auto; }
.panel-track { display: flex; width: 100%; height: 100vh; height: 100vh; flex-shrink: 0; overflow-x: scroll; overflow-y: hidden; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
.panel-track::-webkit-scrollbar { display:none; }
.panel { flex: 0 0 100%; width:100%; height:100vh; height:100vh; position: relative; display: flex; flex-direction:column; justify-content: flex-end; overflow: hidden; }
.panel.solo { display: flex; flex-direction: column; height: 100vh; height: 100vh; }
#c2 .panel.solo, #c3 .panel.solo, #c4 .panel.solo { height: auto; overflow: visible; min-height: 0; }
#c3 .panel-content, #c4 .panel-content { position: relative; z-index: 2; height: auto; overflow: visible; min-height: 0; padding: 0 24px max(48px, calc(env(safe-area-inset-bottom) + 24px)) 24px; box-sizing: border-box; }
.panel.content-panel { justify-content: flex-start; }
.panel-bg { position:absolute; inset:0; z-index:0; background: var(--black); }
.vig { position:absolute; inset:0; z-index:1; pointer-events:none; background: linear-gradient(to top, rgba(12,15,26,0.97) 0%, rgba(12,15,26,0.55) 36%, rgba(12,15,26,0.15) 65%, transparent 100%); }
.vig-heavy { position:absolute; inset:0; z-index:1; pointer-events:none; background:rgba(10,13,22,0.92); }
.vig-artist { position:absolute; inset:0; z-index:1; pointer-events:none; background:linear-gradient(to top, rgba(12,15,26,0.97) 0%, rgba(12,15,26,0.78) 40%, rgba(12,15,26,0.5) 70%, rgba(12,15,26,0.25) 100%); }
.panel-content { position:relative; z-index:2; padding: 0 56px max(52px, calc(env(safe-area-inset-bottom) + 52px)); width:100%; box-sizing:border-box; flex-shrink:0; }
.content-panel .panel-content { position:relative; z-index:2; width:100%; height:100%; padding: 80px 56px 60px; overflow-y: hidden; overflow-x: hidden; box-sizing:border-box; }
#p0-1 .panel-content, #p1-1 .panel-content { height: auto; margin-top: auto; padding-top: 0; }
.panel-content p,.panel-content li,.zi-body,.zine-issue-body p,.connect-body,.body-txt { font-family: "Noto Sans JP","Montserrat",sans-serif; }
.eyebrow { font-size:10px; font-weight:500; letter-spacing:0.44em; text-transform:uppercase; color:var(--red); opacity:0.88; margin-bottom:16px; }
.lang-switchable { transition:opacity 0.25s; }
.h-hero { font-family:Arial,"Arial Black",sans-serif; font-size:clamp(26px,7vw,44px); line-height:0.96; letter-spacing:0.01em; color:var(--white); font-weight:900; }
.h-section { font-family:Arial,"Arial Black",sans-serif; font-size:clamp(28px,5.5vw,52px); line-height:0.94; letter-spacing:0.01em; color:var(--white); margin-bottom:20px; font-weight:900; }
.connect-h2 { font-family:Arial,"Arial Black",sans-serif; font-size:clamp(30px,6.5vw,56px); line-height:0.92; letter-spacing:0.01em; color:var(--white); margin-bottom:20px; font-weight:900; }
.connect-h2 span { color:var(--red); }
.af-name { font-family:Arial,"Arial Black",sans-serif; font-size:clamp(32px,7vw,64px); line-height:0.92; letter-spacing:0.01em; color:var(--white); margin-bottom:20px; font-weight:900; max-width:480px; }
.body-txt { font-size:14px; font-weight:300; line-height:2.0; color:var(--white); opacity:0.88; max-width:380px; letter-spacing:0.04em; }
.body-txt-en { font-size:13px; font-weight:300; font-style:italic; line-height:1.9; color:var(--white); opacity:0.72; max-width:380px; margin-top:14px; }
[data-lang="jp"] .body-txt { display:block; }
[data-lang="jp"] .body-txt-en { display:none; }
[data-lang="en"] .body-txt { display:none; }
[data-lang="en"] .body-txt-en { display:block; margin-top:0; font-style:normal; opacity:0.88; }
[data-lang="jp"] .af-desc { display:block; }
[data-lang="jp"] .af-desc-en { display:none; }
[data-lang="en"] .af-desc { display:none; }
[data-lang="en"] .af-desc-en { display:block; font-style:normal; opacity:0.92; }
.cta-row { display:flex; gap:14px; margin-top:32px; }
.btn-fill { display:inline-block; padding:18px 40px; background:var(--red); font-size:14px; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#EDEBE6; text-decoration:none; cursor:pointer; border:2px solid var(--red); position:relative; overflow:hidden; transition:transform 0.2s,box-shadow 0.25s; }
.btn-fill:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(232,16,10,0.35); }
.btn-fill:active { transform:translateY(0); }
.btn-ghost { display:inline-block; padding:16px 36px; border:2px solid rgba(237,235,230,0.6); font-size:13px; font-weight:500; letter-spacing:0.22em; text-transform:uppercase; color:#EDEBE6; text-decoration:none; cursor:pointer; transition:border-color 0.2s,transform 0.2s; }
.btn-ghost:hover { transform:translateY(-2px); border-color:var(--white); }
.meta-line { margin-top:22px; font-size:11px; font-weight:300; letter-spacing:0.32em; text-transform:uppercase; color:var(--white); opacity:0.78; line-height:2.2; }
.a-subtle { font-size:12px; font-weight:400; letter-spacing:0.25em; text-transform:uppercase; color:var(--white); text-decoration:none; opacity:0.8; border-bottom:1px solid rgba(237,235,230,0.25); padding-bottom:2px; transition:opacity 0.2s,color 0.2s; }
.a-subtle:hover { opacity:1; color:var(--red); border-color:var(--red); }
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
.trow-price { font-family:Arial,"Arial Black",sans-serif; font-size:28px; font-weight:900; line-height:1; letter-spacing:0.03em; color:var(--white); transition:color 0.2s; }
.trow:hover .trow-price { color:var(--red); }
.trow-right { display:flex; align-items:center; gap:14px; }
.trow-usd { font-size:9px; font-weight:200; letter-spacing:0.18em; color:var(--white); opacity:0.65; }
.trow-tag { font-size:11px; font-weight:500; letter-spacing:0.18em; text-transform:uppercase; padding:5px 11px; border:1px solid rgba(200,16,10,0.8); color:var(--red); }
.trow-arr { font-size:11px; color:var(--red); opacity:0; transform:translateX(-5px); transition:opacity 0.2s,transform 0.2s; }
.trow:hover .trow-arr { opacity:1; transform:translateX(0); }
/* ARTISTS */
.artists-layout { display:grid; grid-template-columns:220px 1fr; gap:56px; align-items:end; }
.artist-strip { display:grid; grid-template-columns:repeat(6,1fr); gap:1px; background:var(--line); }
.ac { background:#09090c; display:flex; flex-direction:column; overflow:hidden; transition:background 0.28s; }
.ac:hover { background:#0e0e14; }
.ac-img { aspect-ratio:2/3; background:linear-gradient(160deg,#111428 0%,#0C0F1A 100%); position:relative; display:flex; align-items:center; justify-content:center; overflow:hidden; }
.ac-img img { width:100%; height:100%; object-fit:cover; opacity:0; transition:opacity 0.6s; }
.ac-img img.on { opacity:1; }
.ac-initial { font-family:Arial,"Arial Black",sans-serif; font-size:38px; font-weight:900; color:rgba(237,235,230,0.06); }
.ac-info { padding:10px 8px 12px; }
.ac-name { font-size:11px; font-weight:400; letter-spacing:0.05em; color:var(--white); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ac-sub { font-size:7px; font-weight:200; letter-spacing:0.32em; text-transform:uppercase; color:var(--white); opacity:0.65; margin-top:2px; }
.ac.mystery .ac-name { opacity:0.48; }
.ac.mystery .ac-img { background:#080808; }
.af-genre { font-size:11px; font-weight:400; letter-spacing:0.45em; text-transform:uppercase; color:var(--red); opacity:0.95; margin-bottom:18px; text-shadow:0 1px 8px rgba(0,0,0,0.95); }
.af-desc { font-size:14px; font-weight:300; line-height:2.0; color:var(--white); opacity:0.92; max-width:560px; margin-top:0; margin-bottom:8px; text-shadow:0 1px 8px rgba(0,0,0,0.95); overflow:hidden; }
.af-desc-en { font-size:14px; font-weight:400; font-style:italic; line-height:1.75; color:var(--white); opacity:0.88; max-width:560px; text-shadow:0 1px 8px rgba(0,0,0,0.95); overflow:hidden; }
.amd-word { display:inline-block; will-change:transform,opacity; color:inherit; }
.af-links { display:flex; gap:20px; margin-top:12px; align-items:center; }
.art-cover-content { display:flex; flex-direction:column; justify-content:flex-end; height:100%; }
.art-cover-names { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:14px; }
.art-cover-names span { font-size:11px; font-weight:400; letter-spacing:0.22em; color:rgba(237,235,230,0.65); }
.art-group-hint { font-size:10px; letter-spacing:0.38em; text-transform:uppercase; color:rgba(237,235,230,0.4); margin-top:8px; }
.art-detail-overlay { position:absolute; inset:0; z-index:20; display:flex; flex-direction:column; overflow:hidden; transform:translateY(100%); transition:transform 0.42s cubic-bezier(0.32,0,0.2,1); }
.art-detail-overlay.open { transform:translateY(0); }
#amd-chapter-line { position:fixed; top:0; left:0; right:0; height:2px; background:var(--red); z-index:9998; transform-origin:left center; transform:scaleX(0); pointer-events:none; }
.amd-red-flash { position:fixed; inset:0; z-index:9997; background:var(--red); opacity:0; pointer-events:none; }
.amd-ticket-overlay { position:fixed; inset:0; z-index:8900; touch-action:pan-y; transform:translateY(100%); transition:transform 0.45s cubic-bezier(0.32,0,0.2,1); overflow-x:hidden; overflow-y:auto; -webkit-overflow-scrolling:touch; background:var(--black); display:flex; flex-direction:column; padding-top:max(72px, calc(env(safe-area-inset-top) + 60px)); overscroll-behavior-y:contain; isolation:isolate; }
.amd-ticket-overlay .panel-content { padding-left:32px !important; padding-right:32px !important; }
.amd-ticket-overlay .body-txt, .amd-ticket-overlay .body-txt-en { max-width:100%; }
.amd-ticket-overlay .eyebrow, .amd-ticket-overlay .h-section,
.amd-ticket-overlay .info-table, .amd-ticket-overlay .ticket-section,
.amd-ticket-overlay .two-col { padding-left:0; padding-right:0; }
.amd-ticket-overlay .two-col { gap:48px; }
.amd-ticket-overlay .info-row { padding:16px 0; }
@media (max-width:860px) { .amd-ticket-overlay .two-col { grid-template-columns:1fr; } }
.amd-ticket-overlay.open { transform:translateY(0); }
.amd-ticket-close { position:absolute; bottom:max(32px, calc(env(safe-area-inset-bottom) + 24px)); right:28px; z-index:10000; background:rgba(12,15,26,0.6); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); border:1px solid rgba(237,235,230,0.2); color:rgba(237,235,230,0.6); font-size:18px; line-height:1; width:44px; height:44px; display:flex; align-items:center; justify-content:center; cursor:pointer; border-radius:50%; transition:border-color .2s, color .2s; }
.amd-artist-panel { position:fixed; inset:0; z-index:9000; touch-action:pan-y; transform:translateY(100%); transition:transform 0.45s cubic-bezier(0.32,0,0.2,1); display:flex; flex-direction:column; overflow-y:auto; -webkit-overflow-scrolling:touch; overscroll-behavior-y:contain; isolation:isolate; }
.amd-artist-panel.open { transform:translateY(0); }
.amd-ap-bg { position:absolute; inset:0; background:rgba(12,15,26,0.97); }
.amd-ap-inner { position:relative; z-index:2; height:100%; display:flex; flex-direction:column; padding:max(20px, calc(env(safe-area-inset-top) + 8px)) 0 0; overflow-y:auto; }
.amd-ap-close { position:absolute; bottom:max(32px, calc(env(safe-area-inset-bottom) + 24px)); right:28px; z-index:10000; background:rgba(12,15,26,0.6); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); border:1px solid rgba(237,235,230,0.2); color:rgba(237,235,230,0.6); font-size:18px; line-height:1; width:44px; height:44px; display:flex; align-items:center; justify-content:center; cursor:pointer; border-radius:50%; transition:border-color .2s, color .2s; }
.amd-ap-title { font-size:9px; letter-spacing:0.42em; text-transform:uppercase; color:rgba(237,235,230,0.3); padding:0 24px; margin-bottom:16px; }
.amd-ap-groups { display:flex; flex-direction:column; gap:3px; padding:0 0 40px; }
.amd-ap-group { position:relative; height:clamp(200px,28vh,280px); overflow:hidden; cursor:pointer; border-radius:0; }
.amd-ap-group img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:0.65; transition:opacity 0.3s; }
.amd-ap-group:hover img, .amd-ap-group:active img { opacity:0.88; }
.amd-ap-group-vig { position:absolute; inset:0; background:linear-gradient(180deg,transparent 35%,rgba(12,15,26,0.82) 100%); }
.amd-ap-group-info { position:absolute; bottom:0; left:0; right:0; padding:16px 24px; }
.amd-ap-group-sub { font-size:9px; letter-spacing:0.38em; text-transform:uppercase; color:var(--red); margin-bottom:4px; }
.amd-ap-group-name { font-size:24px; font-weight:300; letter-spacing:0.05em; color:var(--white); line-height:1.1; }
.amd-ap-group-members { font-size:10px; letter-spacing:0.18em; color:rgba(237,235,230,0.45); margin-top:6px; line-height:1.8; }
.amd-card-stack { position:fixed; inset:0; z-index:9100; transform:translateY(100%); transition:transform 0.45s cubic-bezier(0.32,0,0.2,1); background:var(--black); touch-action:pan-y; overflow-y:auto; -webkit-overflow-scrolling:touch; overscroll-behavior-y:contain; isolation:isolate; }
.amd-card-stack.open { transform:translateY(0); }
.amd-cs-close { position:absolute; top:max(22px, calc(env(safe-area-inset-top) + 14px)); left:24px; z-index:10000; background:none; border:none; color:rgba(237,235,230,0.5); font-size:11px; letter-spacing:0.28em; text-transform:uppercase; cursor:pointer; }
.amd-cs-title { position:absolute; top:max(24px, calc(env(safe-area-inset-top) + 14px)); left:50%; transform:translateX(-50%); z-index:10000; font-size:9px; letter-spacing:0.38em; text-transform:uppercase; color:rgba(237,235,230,0.3); white-space:nowrap; }
#cardStackStage { position:absolute; inset:0; }
.amd-card { position:absolute; inset:0; will-change:transform,opacity; }
.amd-card-content { position:absolute; inset:0; z-index:2; display:flex; flex-direction:column; justify-content:flex-end; padding:28px 32px 44px; }
.amd-card-num { font-size:10px; letter-spacing:0.32em; color:rgba(237,235,230,0.28); margin-top:16px; }
.amd-card-nav { position:absolute; bottom:0; left:0; right:0; display:flex; justify-content:space-between; align-items:center; padding:14px 24px; z-index:300; border-top:1px solid rgba(237,235,230,0.1); background:rgba(12,15,26,0.6); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); }
.amd-card-nav-btn { background:none; border:none; color:rgba(237,235,230,0.55); font-size:11px; letter-spacing:0.32em; text-transform:uppercase; cursor:pointer; padding:8px 0; transition:color 0.2s; }
.amd-card-nav-btn:hover { color:var(--white); }
.amd-card-nav-btn:disabled { opacity:0; cursor:default; pointer-events:none; }
.art-detail-slide { position:absolute; inset:0; transition:transform 0.35s cubic-bezier(0.32,0,0.2,1); }
.art-detail-bg { position:absolute; inset:0; }
.art-detail-content { position:relative; z-index:2; height:100%; display:flex; flex-direction:column; justify-content:flex-end; }
.art-detail-close { position:absolute; top:72px; left:28px; background:none; border:none; color:var(--white); font-size:12px; letter-spacing:0.28em; text-transform:uppercase; cursor:pointer; opacity:0.65; z-index:5; transition:opacity 0.2s; }
.art-detail-close:hover { opacity:1; }
.art-detail-counter { font-size:10px; letter-spacing:0.32em; color:rgba(237,235,230,0.38); margin-top:12px; text-transform:uppercase; }
.af-link { display:inline-flex; align-items:center; justify-content:center; text-decoration:none; transition:opacity 0.2s, transform 0.2s; }
.af-link:hover { color:var(--white); transform:scale(1.1); }
.af-link svg { width:40px; height:40px; fill:currentColor; }
/* ZINE — Magazine card stack layout (all cards same tall size) */
.zine-feed { display:flex; flex-direction:column; gap:0; position:relative; }
.zine-card, .zine-card-2col { position:sticky; top:0; }
.zine-card { position:relative; overflow:hidden; border-radius:14px; text-decoration:none; color:var(--white); display:block; background:#0d1018; height:85vh; min-height:520px; max-height:720px; }
.zine-card-img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:.75; }
.zine-card-vig { position:absolute; inset:0; background:linear-gradient(to top,rgba(12,15,26,.95) 0%,rgba(12,15,26,.4) 50%,transparent 100%); }
.zine-card-body { position:relative; z-index:2; display:flex; flex-direction:column; justify-content:flex-end; height:100%; padding:28px; box-sizing:border-box; }
.zine-card-cat { font-size:9px; font-weight:600; letter-spacing:.4em; text-transform:uppercase; color:var(--red); margin-bottom:8px; }
.zine-card-title { font-family:Arial,"Arial Black",sans-serif; font-weight:900; line-height:.95; letter-spacing:.01em; color:var(--white); font-size:clamp(26px,7vw,40px); }
.zine-card-meta { font-size:10px; font-weight:300; letter-spacing:.15em; color:rgba(237,235,230,.45); margin-top:8px; }
.zine-card-num { position:absolute; top:18px; right:20px; font-family:Arial,"Arial Black",sans-serif; font-size:clamp(48px,10vw,72px); font-weight:900; color:var(--red); opacity:.7; line-height:1; z-index:2; }
/* 2-col row: same height as other cards */
.zine-card-2col { display:grid; grid-template-columns:1fr 1fr; gap:8px; height:85vh; min-height:520px; max-height:720px; }
.zine-card-md { border-radius:14px; overflow:hidden; position:relative; text-decoration:none; color:var(--white); display:block; background:#0d1018; height:100%; }
.zine-card-md .zine-card-title { font-size:clamp(18px,4.5vw,26px); }
/* ── ZINE Book (HOME COMING special) ── */
.zine-book { position:relative; height:85vh; min-height:520px; max-height:720px; perspective:1800px; cursor:pointer; }
.zine-book.open { cursor:default; }
.zine-book-inner { position:relative; width:100%; height:100%; border-radius:14px; overflow:visible; }
/* Back page (EP03 teaser — always behind) */
.zine-page-back { position:absolute; inset:0; border-radius:14px; overflow:hidden; background:#0a0c14; z-index:1; }
.zine-page-back img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:.8; }
.zine-page-back .zine-card-vig { z-index:2; }
.zine-page-back .zine-card-body { z-index:3; }
.zine-page-back-credit { font-size:9px; font-weight:300; letter-spacing:.2em; color:rgba(237,235,230,.5); margin-top:12px; line-height:1.8; }
/* Front page (cover — flips open) */
.zine-page-front { position:absolute; inset:0; border-radius:14px; overflow:hidden; z-index:5; transform-origin:left center; transition:transform 0.9s cubic-bezier(0.4,0,0.2,1); backface-visibility:hidden; will-change:transform; }
.zine-book.open .zine-page-front { transform:rotateY(-160deg); pointer-events:none; }
/* Close button on back page */
.zine-book-close { position:absolute; top:max(20px,calc(env(safe-area-inset-top)+12px)); right:20px; z-index:10; width:40px; height:40px; border-radius:50%; border:1px solid rgba(237,235,230,.2); background:rgba(12,15,26,.6); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); color:rgba(237,235,230,.6); font-size:16px; display:flex; align-items:center; justify-content:center; cursor:pointer; opacity:0; transition:opacity .3s .6s; pointer-events:none; }
.zine-book.open .zine-book-close { opacity:1; pointer-events:all; }
/* Tap hint on cover */
.zine-tap-hint { position:absolute; bottom:28px; right:28px; z-index:6; font-size:8px; letter-spacing:.4em; text-transform:uppercase; color:rgba(237,235,230,.35); animation:zinePulse 2.5s ease-in-out infinite; }
@keyframes zinePulse { 0%,100%{opacity:.35} 50%{opacity:.7} }
.zine-book.open .zine-tap-hint { opacity:0; transition:opacity .2s; }
/* ZINE header bar (replaces site header in this section) */
.zine-section-header { position:sticky; top:0; z-index:100; display:flex; justify-content:space-between; align-items:center; padding:max(20px,calc(env(safe-area-inset-top)+12px)) 24px 14px; background:linear-gradient(to bottom,rgba(12,15,26,.92) 0%,rgba(12,15,26,.6) 70%,transparent 100%); pointer-events:none; }
.zine-section-header > * { pointer-events:all; }
/* View all link */
.zine-view-all { display:block; text-align:center; padding:28px 0 max(40px,calc(env(safe-area-inset-bottom)+24px)); }
.zine-view-all a { font-size:10px; letter-spacing:.3em; text-transform:uppercase; color:rgba(237,235,230,.4); text-decoration:none; border-bottom:1px solid rgba(237,235,230,.12); padding-bottom:3px; transition:color .2s; }
.zine-view-all a:hover { color:var(--red); border-color:var(--red); }
@media (max-width:480px) {
  .zine-card-2col { grid-template-columns:1fr; }
  .zine-card-body { padding:20px; }
}
/* YOUTUBE */
.yt-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:20px; }
.yt-card { display:block; text-decoration:none; color:var(--white); border:1px solid rgba(237,235,230,0.08); transition:border-color 0.2s; }
.yt-card:hover { border-color:rgba(237,235,230,0.25); }
.yt-thumb { aspect-ratio:16/9; background:linear-gradient(160deg,#111428 0%,#0C0F1A 100%); display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
.yt-play { font-size:20px; color:var(--white); opacity:0.35; transition:opacity 0.2s; position:relative; z-index:2; }
.yt-card:hover .yt-play { opacity:0.8; }
.yt-info { padding:10px 12px 12px; }
.yt-title { font-size:11px; font-weight:400; letter-spacing:0.04em; color:var(--white); margin-bottom:4px; line-height:1.4; }
.yt-sub { font-size:10px; font-weight:300; color:var(--white); opacity:0.42; }
/* STORE */
.store-head { display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:26px; }
.store-grid { display:grid; grid-template-columns:1fr 1fr; gap:1px; background:var(--line); }
.sc { background:#0c0f1a; display:flex; flex-direction:column; overflow:hidden; cursor:pointer; transition:background 0.28s; }
.sc:hover { background:#0d0d0d; }
.sc-vis { flex:1; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; min-height:180px; }
.sc-ghost { font-family:Arial,"Arial Black",sans-serif; font-size:36px; font-weight:900; color:rgba(237,235,230,0.05); text-align:center; line-height:1.25; transition:color 0.28s; }
.sc:hover .sc-ghost { color:rgba(237,235,230,0.09); }
.sc-badge { position:absolute; top:13px; left:13px; font-size:7px; font-weight:300; letter-spacing:0.3em; text-transform:uppercase; color:var(--red); border:1px solid rgba(200,16,10,0.4); padding:3px 8px; }
.sc-info { padding:12px 14px 16px; border-top:1px solid var(--line); }
.sc-cat { font-size:7px; font-weight:200; letter-spacing:0.42em; text-transform:uppercase; color:var(--white); opacity:0.42; margin-bottom:4px; }
.sc-name { font-size:13px; font-weight:300; color:var(--white); margin-bottom:2px; }
.sc-price { font-size:10px; font-weight:200; color:var(--white); opacity:0.65; }
/* CONNECT */
.connect-grid { display:grid; grid-template-columns:1fr 1fr; gap:80px; align-items:end; }
.connect-body { font-size:13px; font-weight:300; line-height:2.0; color:var(--white); opacity:0.82; max-width:300px; }
.connect-mail { display:inline-block; margin-top:24px; font-size:10px; font-weight:300; letter-spacing:0.18em; color:var(--white); text-decoration:none; opacity:0.58; border-bottom:1px solid rgba(237,235,230,0.2); padding-bottom:3px; transition:opacity 0.2s,color 0.2s; }
.connect-mail:hover { opacity:1; color:var(--red); }
.connect-list { list-style:none; }
.cl { display:flex; align-items:center; justify-content:space-between; padding:15px 0; border-bottom:1px solid rgba(237,235,230,0.1); transition:padding-left 0.2s; }
.cl:first-child { border-top:1px solid rgba(237,235,230,0.1); }
.cl:hover { padding-left:6px; }
.cl a { font-family:Arial,"Arial Black",sans-serif; font-size:30px; font-weight:900; letter-spacing:0.04em; color:var(--white); text-decoration:none; opacity:0.82; transition:opacity 0.2s,color 0.2s; }
.cl:hover a { opacity:1; color:var(--red); }
.cl-type { display:none; }
.connect-footer { margin-top:40px; display:flex; align-items:center; gap:24px; }
.copyright { font-size:10px; font-weight:400; letter-spacing:0.12em; color:var(--white); opacity:0.55; }
.privacy-link { font-size:10px; font-weight:400; letter-spacing:0.12em; color:var(--white); text-decoration:none; opacity:0.55; border-bottom:1px solid rgba(237,235,230,0.25); padding-bottom:1px; transition:opacity 0.2s; }
.privacy-link:hover { opacity:0.7; color:var(--red); }
/* LOGOS */
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
/* DOTS */
.panel-dots { position:fixed; bottom:56px; left:56px; z-index:400; display:flex; gap:8px; }
.pdot { width:4px; height:4px; border-radius:50%; background:var(--white); opacity:0.45; cursor:pointer; transition:opacity 0.3s,transform 0.3s,background 0.3s; }
.pdot.on { opacity:1; transform:scale(1.5); background:var(--red); }
.back-btn { position:fixed; bottom:52px; right:56px; z-index:400; display:none; align-items:center; gap:8px; font-family:Arial,"Arial Black",sans-serif; font-size:13px; font-weight:600; letter-spacing:0.18em; text-transform:uppercase; color:var(--white); cursor:pointer; border:none; background:none; padding:8px 0; }
.back-btn.visible { display:flex; animation:backFloat 2.4s ease-in-out infinite; }
.back-btn:hover { animation:none; opacity:1; color:var(--red); }
@keyframes backFloat { 0%,100% { opacity:0.55; transform:translateY(0); } 50% { opacity:0.95; transform:translateY(-5px); } }
.arrow { position:fixed; top:50%; z-index:400; transform:translateY(-50%); font-size:18px; font-weight:200; color:var(--white); opacity:0.52; cursor:pointer; user-select:none; padding:24px 18px; transition:opacity 0.22s; }
.arrow:hover { opacity:0.85; }
.arrow.off { opacity:0 !important; pointer-events:none; }
#aPrev { left:8px; } #aNext { right:8px; }
@media (pointer:coarse) { .arrow { display:none; } }
.rv { opacity:0; transform:translateY(18px); transition:opacity 0.6s cubic-bezier(0.22,1,0.36,1), transform 0.6s cubic-bezier(0.22,1,0.36,1); }
.rv.visible { opacity:1; transform:translateY(0); }
/* MENU TOGGLE */
.menu-toggle { display:flex; flex-direction:column; justify-content:center; gap:5px; width:32px; height:32px; background:none; border:none; cursor:pointer; padding:4px; pointer-events:all; position:relative; z-index:500; }
.mt-bar { display:block; width:100%; height:1.5px; background:var(--white); opacity:0.82; transition:transform 0.3s cubic-bezier(0.23,1,0.32,1), opacity 0.3s; transform-origin:center; }
.mt-bar2 { width:70%; }
.menu-toggle.open .mt-bar1 { transform:translateY(6.5px) rotate(45deg); opacity:1; }
.menu-toggle.open .mt-bar2 { opacity:0; transform:translateX(8px); }
.menu-toggle.open .mt-bar3 { transform:translateY(-6.5px) rotate(-45deg); opacity:1; }
/* MENU OVERLAY */
.menu-overlay { position:fixed; inset:0; z-index:450; background:#0C0F1A; display:flex; flex-direction:column; pointer-events:none; opacity:0; transform:translateY(-12px); transition:opacity 0.35s cubic-bezier(0.23,1,0.32,1), transform 0.35s cubic-bezier(0.23,1,0.32,1); touch-action:pan-y; overflow-y:auto; -webkit-overflow-scrolling:touch; overscroll-behavior-y:contain; }
.menu-overlay.open { opacity:1; transform:translateY(0); pointer-events:all; }
/* FIX 3: merged .menu-item + .menu-inner + .menu-nav (single definition) */
.menu-inner { display:flex; flex-direction:column; height:100%; padding:70px 0 0; }
.menu-nav { flex:1; width:100%; list-style:none; border-bottom:1px solid rgba(237,235,230,0.1); display:flex; flex-direction:column; overflow:hidden; }
.menu-item { flex:1 1 52px; border-top:1px solid rgba(237,235,230,0.1); cursor:pointer; position:relative; overflow:hidden; transition:background 0.2s; opacity:0; transform:translateX(-20px); }
.menu-item:hover, .menu-item.active { background:rgba(237,235,230,0.02); }
.menu-item.active { flex:1 1 80px; }
.menu-row-inner { display:flex; align-items:center; height:100%; padding:0 100px 0 28px; position:relative; }
.menu-row-left { display:flex; flex-direction:column; gap:5px; z-index:2; }
.menu-label { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }
.menu-item.active .menu-label { color:var(--red); }
.menu-sub { position:absolute; width:1px; height:1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }
.menu-row-arr { display:none; }
.menu-item.active .menu-row-arr { color:var(--red); transform:translateX(4px); }
.menu-row-medias { position:absolute; right:20px; top:50%; transform:translateY(-50%); pointer-events:none; z-index:2; }
.menu-row-media { width:64px; height:64px; object-fit:cover; border-radius:3px; transform:translateY(200%); display:block; }
.menu-footer { display:flex; gap:24px; padding-top:24px; opacity:0; transition:opacity 0.4s 0.4s; }
.menu-overlay.open .menu-footer { opacity:1; }
.menu-social { font-size:11px; font-weight:300; letter-spacing:0.22em; text-transform:uppercase; color:rgba(237,235,230,0.42); text-decoration:none; transition:color 0.2s; display:flex; align-items:center; }
.menu-social:hover { color:var(--white); }
.swipe-hint { display:none !important; }
.swipe-hint.hidden { opacity:0; pointer-events:none; }
.hint-hand { display:none; }
.hint-arrows { display:flex; gap:20px; align-items:center; font-size:28px; letter-spacing:0.3em; }
.hint-arrow-h,.hint-arrow-v { font-size:11px; font-weight:200; letter-spacing:0.3em; color:var(--white); opacity:0.5; }
.hint-label { font-size:8px; font-weight:200; letter-spacing:0.4em; text-transform:uppercase; color:var(--white); opacity:0.52; }
/* WORLD */
.world-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:var(--line); margin-top:30px; }
.city { background:#080808; padding:30px 24px; transition:background 0.28s; }
.city:hover { background:#0d0d12; }
.city-n { font-family:Arial,"Arial Black",sans-serif; font-size:48px; font-weight:900; line-height:1; color:rgba(237,235,230,0.05); margin-bottom:14px; }
.city-nm { font-family:Arial,"Arial Black",sans-serif; font-size:22px; font-weight:900; letter-spacing:0.03em; color:var(--white); margin-bottom:4px; }
.city-dt { font-size:8px; font-weight:200; letter-spacing:0.38em; text-transform:uppercase; color:var(--white); opacity:0.65; margin-bottom:14px; }
.city-tag { display:inline-block; padding:3px 9px; font-size:7px; font-weight:200; letter-spacing:0.3em; text-transform:uppercase; }
.city-tag.done { border:1px solid var(--line); color:var(--white); opacity:0.3; }
.city-tag.next { border:1px solid rgba(200,16,10,0.3); color:var(--red); }
/* PARALLAX */
.parallax-bg { position: absolute; inset: 0; z-index: 0; will-change: transform; transition: transform 0.1s linear; }
.parallax-bg img, .parallax-bg video { width: 100%; height: 120%; object-fit: cover; position: absolute; top: -10%; left: 0; }
/* SLIDE IN */
.rv-left { opacity: 0; transform: translateY(20px); transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1); }
.rv-right { opacity: 0; transform: translateY(20px); transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1); }
.rv-left.visible, .rv-right.visible { opacity: 1; transform: translateY(0); }
.rv-up { opacity: 0; transform: translateY(30px); transition: opacity 0.65s cubic-bezier(0.22,1,0.36,1), transform 0.65s cubic-bezier(0.22,1,0.36,1); }
.rv-up.visible { opacity: 1; transform: translateY(0); }
.rv-scale { opacity: 0; transform: scale(0.94); transition: opacity 0.7s ease, transform 0.7s ease; }
.rv-scale.visible { opacity: 1; transform: scale(1); }
/* YOUTUBE THEATER: removed (Pull-to-Play deleted) */
/* MENU ICON */
.menu-icon { width: auto; height: auto; max-width: 100%; max-height: 32px; object-fit: contain; object-position: left center; flex-shrink: 0; opacity: 0.82; transition: opacity 0.2s; }
.menu-item:hover .menu-icon { opacity: 1; transform: scale(1.08); }
.menu-social-icon { width: 18px; height: 18px; object-fit: contain; border-radius: 4px; opacity: 0.7; vertical-align: middle; margin-right: 6px; }
.zi-external { cursor: pointer; }
.zi-external .zi-arr { opacity: 0.5; }
.zi-external:hover .zi-arr { opacity: 1; }
/* PULL TO PLAY: removed */
.panel[data-active] .panel-content { padding-bottom: 40px; }
@media (max-width: 860px) { .panel[data-active] .panel-content { padding-bottom: 28px; } }
/* HERO SECTION ICON */
.hero-section-icon { height: auto; max-height: 80px; width: auto; max-width: 70vw; display: block; object-fit: contain; object-position: left center; filter: brightness(0) invert(1); opacity: 0.95; }
@media (max-width: 600px) { .hero-section-icon { max-height: 60px; } }
.hero-icon-row { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
.hero-section-title { font-family: Arial,"Arial Black",sans-serif; font-size: clamp(22px, 5.5vw, 40px); font-weight: 900; color: var(--white); line-height: 1; letter-spacing: 0.01em; }
.section-icon-heading { display: block; height: auto; max-height: 52px; width: auto; max-width: 60vw; object-fit: contain; object-position: left center; filter: brightness(0) invert(1); opacity: 0.95; margin-top: 4px; }
@media (max-width: 600px) { .section-icon-heading { max-height: 38px; } .hero-section-title { font-size: clamp(18px, 5vw, 28px); } }
a { color: inherit; text-decoration: none; }
a:visited { color: inherit; }
a.sc { color: var(--white); text-decoration: none; }
a.sc .sc-cat { color: var(--white); opacity:0.42; }
a.sc .sc-name { color: var(--white); }
a.sc:hover { background: rgba(237,235,230,0.06); }
.cl a { color: var(--white); text-decoration: none; }
.connect-mail { color: var(--white); }
/* SCROLL INDICATOR */
.scroll-indicator { position:absolute; bottom:32px; right:28px; display:flex; flex-direction:column; align-items:center; gap:8px; pointer-events:none; z-index:5; }
.scroll-indicator-line { width:1px; height:36px; background:var(--white); transform-origin:top center; animation:scrollPulse 1.8s ease-in-out infinite; }
.scroll-indicator-text { font-size:8px; letter-spacing:.38em; text-transform:uppercase; color:rgba(237,235,230,.45); writing-mode:vertical-rl; text-orientation:mixed; }
@keyframes scrollPulse { 0%,100% { transform:scaleY(0.3); opacity:.25; } 50% { transform:scaleY(1); opacity:.7; } }
/* SCROLL ANIMATIONS */
.anim-up { opacity: 0; transform: translateY(32px); transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1); }
.anim-left { opacity: 0; transform: translateY(20px); transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1); }
.anim-right { opacity: 0; transform: translateY(20px); transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1); }
.anim-up.in, .anim-left.in, .anim-right.in { opacity: 1; transform: translate(0); }
::selection { background:var(--red); color:var(--white); }
/* ARTIST INDEX */
.artist-index-list { list-style:none; margin:0; padding:0; }
.artist-index-item { display:flex; align-items:center; gap:12px; padding:14px 0; border-bottom:1px solid rgba(237,235,230,0.1); cursor:pointer; transition:opacity 0.2s; }
.artist-index-item:first-child { border-top:1px solid rgba(237,235,230,0.1); }
.artist-index-item:hover { opacity:0.75; }
.artist-index-name { font-family:Arial,"Arial Black",sans-serif; font-size:clamp(18px,4.5vw,28px); font-weight:900; color:var(--white); flex:1; line-height:1.1; }
.artist-index-genre { font-size:9px; letter-spacing:0.32em; text-transform:uppercase; color:rgba(237,235,230,0.4); flex-shrink:0; }
.artist-index-arr { font-size:14px; color:var(--red); flex-shrink:0; margin-left:8px; }
/* ARTIST MODAL */
.artist-modal { position:fixed; inset:0; z-index:8000; background:var(--black); display:flex; flex-direction:column; justify-content:flex-end; opacity:0; pointer-events:none; transform:translateX(100%); transition:transform 0.35s cubic-bezier(0.22,1,0.36,1), opacity 0.35s; }
.artist-modal.open { opacity:1; pointer-events:all; transform:translateX(0); }
.artist-modal-bg { position:absolute; inset:0; z-index:0; }
.artist-modal-close { position:absolute; top:20px; left:20px; z-index:2; background:none; border:none; color:var(--white); font-size:22px; cursor:pointer; padding:8px 12px; opacity:0.7; transition:opacity 0.2s; font-family:Arial,sans-serif; letter-spacing:0.1em; }
.artist-modal-close:hover { opacity:1; }
::-webkit-scrollbar { display:none; }
/* MOBILE */
@media (max-width: 860px) {
  #amd-header { padding:max(16px, calc(env(safe-area-inset-top) + 6px)) 20px 10px; }
  .panel-content { padding:0 24px max(40px, calc(env(safe-area-inset-bottom) + 40px)); }
  .content-panel .panel-content { padding:64px 24px 52px; }
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
/* CARD STACK OVERRIDES */
.amd-card-stack .af-desc { display:block; color:var(--white); overflow:visible; -webkit-line-clamp:unset; }
.amd-card-stack .af-desc-en { display:none; color:var(--white); }
[data-lang="en"] .amd-card-stack .af-desc { display:none; }
[data-lang="en"] .amd-card-stack .af-desc-en { display:block; }
body.overlay-open #amd-header { opacity:0; pointer-events:none; transition:opacity .2s; }
/* Floating JP/EN switch - always visible */
.lang-float { position:fixed; top:max(16px, calc(env(safe-area-inset-top) + 8px)); right:max(20px,env(safe-area-inset-right)); z-index:10001; }
.lang-float .lang-toggle { pointer-events:all; background:rgba(12,15,26,0.5); backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px); border-radius:20px; padding:6px 12px; border:1px solid rgba(237,235,230,0.08); }
[data-lazy] { visibility: hidden; }
.gsap-ready [data-lazy] { visibility: visible; }
#c2-track, #c3-track, #c4-track { overflow: hidden !important; }
#c2 .panel-content, #c3 .panel-content { padding-left:56px; }
@media (max-width:860px) { #c2 .panel-content, #c3 .panel-content { padding-left:28px; padding-right:28px; } }
#c0 .rv, #c0 .rv-left, #c0 .rv-right, #c0 .rv-up, #c0 .rv-scale,
#c1 .rv, #c1 .rv-left, #c1 .rv-right, #c1 .rv-up, #c1 .rv-scale {
  opacity: 1 !important; transform: none !important; transition: none !important; transition-delay: 0s !important;
}
/* PWA INSTALL BANNER */
.pwa-banner{position:fixed;bottom:0;left:0;right:0;z-index:9500;transform:translateY(100%);transition:transform .5s cubic-bezier(.22,1,.36,1);pointer-events:none;}
.pwa-banner.show{transform:translateY(0);pointer-events:all;}
.pwa-banner-inner{margin:0 12px max(12px,env(safe-area-inset-bottom));background:rgba(12,15,26,.92);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border:1px solid rgba(237,235,230,.08);padding:16px 20px;display:flex;align-items:center;gap:16px;}
.pwa-icon-wrap{flex-shrink:0;width:44px;height:44px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(232,16,10,.3);background:rgba(232,16,10,.06);}
.pwa-icon-wrap img{width:24px;height:24px;object-fit:contain;}
.pwa-text{flex:1;min-width:0;}
.pwa-title{font-family:Arial,"Arial Black",sans-serif;font-size:12px;font-weight:900;letter-spacing:.06em;color:var(--white);margin-bottom:2px;}
.pwa-sub{font-size:9px;font-weight:300;letter-spacing:.18em;color:rgba(237,235,230,.45);text-transform:uppercase;}
.pwa-action{flex-shrink:0;display:flex;align-items:center;gap:10px;}
.pwa-btn{padding:10px 18px;background:var(--red);border:none;font-family:Arial,"Arial Black",sans-serif;font-size:9px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#EDEBE6;cursor:pointer;transition:transform .15s,box-shadow .2s;}
.pwa-btn:active{transform:scale(.96);}
.pwa-dismiss{background:none;border:none;color:rgba(237,235,230,.25);font-size:18px;cursor:pointer;padding:4px 2px;transition:color .2s;}
.pwa-dismiss:hover{color:rgba(237,235,230,.6);}
/* PWA GUIDE MODAL */
.pwa-guide{position:fixed;inset:0;z-index:9600;background:rgba(12,15,26,.95);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);display:flex;flex-direction:column;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .3s;touch-action:none;overscroll-behavior-y:contain;}
.pwa-guide.open{opacity:1;pointer-events:all;}
.pwa-guide-close{position:absolute;top:max(20px,calc(env(safe-area-inset-top)+12px));right:20px;background:none;border:1px solid rgba(237,235,230,.15);color:rgba(237,235,230,.5);font-size:16px;width:40px;height:40px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;}
.pwa-guide-content{text-align:center;padding:0 32px;max-width:340px;}
.pwa-guide-step{margin-bottom:36px;opacity:0;transform:translateY(16px);animation:pwaFadeIn .5s ease forwards;}
.pwa-guide-step:nth-child(2){animation-delay:.15s;}
.pwa-guide-step:nth-child(3){animation-delay:.3s;}
@keyframes pwaFadeIn{to{opacity:1;transform:translateY(0);}}
.pwa-step-num{font-family:Arial,"Arial Black",sans-serif;font-size:48px;font-weight:900;color:var(--red);opacity:.3;line-height:1;margin-bottom:8px;}
.pwa-step-icon{font-size:28px;margin-bottom:8px;display:block;}
.pwa-step-text{font-size:13px;font-weight:300;color:rgba(237,235,230,.8);letter-spacing:.04em;line-height:1.8;}
.pwa-step-text strong{font-weight:500;color:var(--white);}
.pwa-guide-footer{font-size:8px;letter-spacing:.4em;text-transform:uppercase;color:rgba(237,235,230,.2);margin-top:20px;}
/* ZINE ANIMATIONS */
.zine-anim { opacity:0; transform:translateY(24px); transition:opacity .6s cubic-bezier(.22,1,.36,1), transform .6s cubic-bezier(.22,1,.36,1); }
.zine-anim.in { opacity:1; transform:translateY(0); }
</style>
<!-- FIX 1: GSAP removed from head, loaded only before </body> -->
</head>
<body>

<div id="amd-header">
  <a class="logo" href="/" onclick="event.preventDefault();window.location.reload();">
    <img loading="eager" src="<?= get_stylesheet_directory_uri() ?>/logos/amdheaderlogo.png" alt="ALL MUST DANCE™" style="height:20px;width:auto;display:block;mix-blend-mode:screen;">
  </a>
  <div class="header-right">
    <div class="counter-wrap">
      <div id="chap-counter">01 / 06</div>
      <div id="panel-counter"></div>
    </div>
    <button class="menu-toggle" id="menuToggle" aria-label="Menu">
      <span class="mt-bar mt-bar1"></span>
      <span class="mt-bar mt-bar2"></span>
      <span class="mt-bar mt-bar3"></span>
    </button>
  </div>
</div>

<!-- JP/EN SWITCH - always visible, even on overlays -->
<div class="lang-float">
  <button class="lang-toggle" id="langToggle" onclick="amdToggleLang()" aria-label="Language toggle">
    <span class="lang-jp" id="langJp">JP</span>
    <span class="lang-sep">/</span>
    <span class="lang-en" id="langEn">EN</span>
  </button>
</div>

<!-- MENU OVERLAY -->
<div class="menu-overlay" id="menuOverlay">
  <div class="menu-inner">
    <?php
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
      <li class="menu-item" data-goto="0,0" data-menu-close><div class="menu-row-inner"><img class="menu-icon" src="<?= get_stylesheet_directory_uri() ?>/logos/party.png" alt="PARTY"><div class="menu-row-left"><span class="menu-label">PARTY</span><span class="menu-sub">May 4 · clubasia</span></div><span class="menu-row-arr">→</span><div class="menu-row-medias"><?= _sec_thumb($sec_thumbs['party']) ?></div></div></li>
      <li class="menu-item" data-goto="1,0" data-menu-close><div class="menu-row-inner"><img class="menu-icon" src="<?= get_stylesheet_directory_uri() ?>/logos/workshop.png" alt="WORKSHOP"><div class="menu-row-left"><span class="menu-label">WORKSHOP</span><span class="menu-sub">64BEAT · Apr 1·8·15</span></div><span class="menu-row-arr">→</span><div class="menu-row-medias"><?= _sec_thumb($sec_thumbs['ws']) ?></div></div></li>
      <li class="menu-item" data-goto="2,0" data-menu-close><div class="menu-row-inner"><img class="menu-icon" src="<?= get_stylesheet_directory_uri() ?>/logos/zine.png" alt="ZINE" style="max-height:32px;"><div class="menu-row-left"><span class="menu-label">ZINE</span><span class="menu-sub">Issue Archive</span></div><span class="menu-row-arr">→</span><div class="menu-row-medias"><?= _sec_thumb($sec_thumbs['zine']) ?></div></div></li>
      <li class="menu-item" data-goto="3,0" data-menu-close><div class="menu-row-inner"><img class="menu-icon" src="<?= get_stylesheet_directory_uri() ?>/logos/video.png" alt="VIDEO" style="max-height:32px;"><div class="menu-row-left"><span class="menu-label">VIDEO</span><span class="menu-sub">@allmustdancetokyo</span></div><span class="menu-row-arr">→</span><div class="menu-row-medias"><?= _sec_thumb($sec_thumbs['video']) ?></div></div></li>

      <li class="menu-item" data-goto="4,0" data-menu-close><div class="menu-row-inner"><img class="menu-icon" src="<?= get_stylesheet_directory_uri() ?>/logos/gg.png" alt="GOOD GOODS" style="max-height:32px;"><div class="menu-row-left"><span class="menu-label">GOOD GOODS</span><span class="menu-sub">Shop</span></div><span class="menu-row-arr">→</span><div class="menu-row-medias"><?= _sec_thumb($sec_thumbs['goods']) ?></div></div></li>
      <li class="menu-item" data-goto="5,0" data-menu-close><div class="menu-row-inner"><img class="menu-icon" src="<?= get_stylesheet_directory_uri() ?>/logos/getin.png" alt="GET IN TOUCH" style="max-height:32px;"><div class="menu-row-left"><span class="menu-label">GET IN TOUCH</span><span class="menu-sub">niko@allmustdance.com</span></div><span class="menu-row-arr">→</span><div class="menu-row-medias"><?= _sec_thumb($sec_thumbs['contact']) ?></div></div></li>
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

<div class="swipe-hint" id="swipeHint">
  <div class="hint-arrows"><span class="hint-arrow-h">←</span><span class="hint-arrow-v">↕</span><span class="hint-arrow-h">→</span></div>
  <div class="hint-label">Swipe</div>
</div>

<div id="deck">
<div id="vtrack">

  <!-- CHAPTER 0: PARTY HERO -->
  <div class="chapter active" id="c0">
    <div class="panel-track" id="c0-track" style="height:100vh;overflow-x:scroll;overflow-y:hidden;position:sticky;top:0;">
      <div class="panel" id="p0-0" data-active>
        <div class="panel-bg parallax-bg" style="background:linear-gradient(160deg,#0f1428 0%,#0C0F1A 100%);">
          <img loading="eager" src="<?= get_stylesheet_directory_uri() ?>/logos/amd2026asia.jpg" alt="" aria-hidden="true" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center top;opacity:0.85;">
          <?php if($party_video): ?>
          <video id="heroVid" autoplay muted loop playsinline preload="auto" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0;transition:opacity 1.2s ease;">
            <source src="<?= esc_url($party_video['url']) ?>" type="video/mp4">
          </video>
          <?php endif; ?>
          <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 70% 60%, rgba(26,46,107,0.35) 0%, transparent 65%);pointer-events:none;"></div>
        </div>
        <div class="vig"></div>
        <div class="panel-content">
          <div class="rv rv-left hero-icon-row">
            <img src="<?= get_stylesheet_directory_uri() ?>/logos/party.png" alt="PARTY" class="hero-section-icon">
            <span class="hero-section-title">ALL MUST DANCE</span>
          </div>
          <div class="rv rv-left eyebrow lang-switchable" data-jp="<?= esc_attr($party_date) ?> · 渋谷東京" data-en="<?= esc_attr($party_date) ?> · Shibuya Tokyo" style="margin-top:8px;"><?= esc_html($party_date) ?> · Shibuya Tokyo</div>
          <div class="rv rv-up cta-row">
            <span class="btn-fill" onclick="amdRedFlash(openTicketOverlay)">Get Tickets</span>
            <span class="btn-ghost" onclick="openArtistPanel()">Artists</span>
          </div>
          <div class="rv rv-right meta-line">
            <span class="lang-switchable" data-jp="<?= esc_attr($party_venue) ?> · 開場<?= esc_attr($party_time) ?> · 20歳以上" data-en="<?= esc_attr($party_venue) ?> · Open <?= esc_attr($party_time) ?> · Age 20+"><?= esc_html($party_venue) ?> · Open <?= esc_html($party_time) ?> · Age 20+</span>
          </div>
        </div>
        <div class="scroll-indicator" id="scrollIndicator"><div class="scroll-indicator-line"></div><div class="scroll-indicator-text">Scroll</div></div>
      </div>

    </div>
  </div>

  <!-- CHAPTER 1: WORKSHOP -->
  <div class="chapter" id="c1">
    <div class="panel-track" id="c1-track" style="height:100vh;overflow-x:scroll;overflow-y:hidden;">
      <div class="panel" id="p1-0" data-active style="height:100vh;min-height:100vh;justify-content:flex-end;">
        <div class="panel-bg" style="background:#000;">
          <?php $ws_bg_photo = null; if(!empty($ws_artists)){ $ws_bg_photo = get_field('photo', $ws_artists[0]->ID); } if($ws_bg_photo): ?>
          <img src="<?= esc_url($ws_bg_photo['url']) ?>" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:1;">
          <?php endif; ?>
          <?php if($ws_video): ?>
          <video autoplay muted loop playsinline preload="metadata" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:1;">
            <source src="<?= esc_url($ws_video['url']) ?>" type="video/mp4">
          </video>
          <?php endif; ?>
        </div>
        <div class="vig"></div>
        <div class="panel-content">
          <div class="rv"><img src="<?= get_stylesheet_directory_uri() ?>/logos/workshop.png" alt="WORKSHOP" class="hero-section-icon"></div>
          <div class="rv eyebrow lang-switchable" data-jp="ワークショップ · 2026" data-en="Workshop · 2026" style="margin-top:8px;">Workshop · 2026</div>
          <div class="rv cta-row">
            <span class="btn-fill" onclick="amdRedFlash(openWsTicketOverlay)">Get Tickets</span>
            <span class="btn-ghost" onclick="openWsArtistOverlay()">Artist Info</span>
          </div>
          <div class="rv meta-line" style="margin-bottom:40px;"><?= $ws_date ? esc_html($ws_date) : 'Date TBA' ?> · <?= $ws_venue ? esc_html($ws_venue) : 'Venue TBA' ?></div>
        </div>
      </div>

    </div>
  </div>

  <!-- CHAPTER: ZINE (magazine card stack) -->
  <div class="chapter chapter-auto" id="cZine" style="height:auto;scroll-snap-align:start;">
    <div style="background:var(--black);position:relative;">

      <!-- ZINE section header (replaces site header) -->
      <div class="zine-section-header">
        <div>
          <div style="font-family:Arial,'Arial Black',sans-serif;font-size:clamp(22px,5vw,32px);font-weight:900;color:var(--white);line-height:.9;letter-spacing:-.02em;">ZINE</div>
          <div style="font-size:7px;letter-spacing:.4em;text-transform:uppercase;color:rgba(237,235,230,.3);margin-top:3px;">ALL MUST DANCE™ · Archive</div>
        </div>
        <div style="font-size:10px;letter-spacing:.2em;color:rgba(237,235,230,.35);font-weight:300;">2026.05</div>
      </div>

      <!-- Card stack feed -->
      <div class="zine-feed" id="zineFeed" style="padding:0 12px;">

        <!-- HERO: EP.07 — HOME COMING (book flip) -->
        <div class="zine-book" id="zineBook07" data-zine-card onclick="toggleZineBook(event)">
          <div class="zine-book-inner">
            <!-- Back page: EP03 Teaser -->
            <div class="zine-page-back">
              <img loading="lazy" src="https://allmustdance.com/wp-content/uploads/2025/12/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88-2024-07-02-20.13.45.png" alt="AMD EP03 Teaser">
              <div class="zine-card-vig"></div>
              <div class="zine-card-body">
                <div class="zine-card-cat">TEASER · AMD™ EP.03</div>
                <div class="zine-card-title" style="font-size:clamp(22px,6vw,36px);">ALL MUST<br>DANCE™</div>
                <div class="zine-card-meta">EP.03 · Amsterdam</div>
                <div class="zine-page-back-credit">Photo — NOBBY<br>Location — Amsterdam Friend House</div>
              </div>
              <button class="zine-book-close" onclick="event.stopPropagation();closeZineBook()">×</button>
            </div>
            <!-- Front page: Cover (flips open on tap) -->
            <div class="zine-page-front">
              <img class="zine-card-img" loading="lazy" src="<?= get_stylesheet_directory_uri() ?>/logos/amd2026asia.jpg" alt="EP.07">
              <div class="zine-card-vig"></div>
              <div class="zine-card-num">007</div>
              <div class="zine-card-body">
                <div class="zine-card-cat">PARTY · FEATURED</div>
                <div class="zine-card-title">HOME<br>COMING</div>
                <div class="zine-card-meta">clubasia · Shibuya · 2026.05.04</div>
              </div>
            </div>
            <div class="zine-tap-hint">TAP TO OPEN ▸</div>
          </div>
        </div>

        <!-- 2-COL: EP.06 + EP.05 -->
        <div class="zine-card-2col" data-zine-card>
          <a class="zine-card-md" href="https://allmustdance.com/zine-ep06/">
            <img class="zine-card-img" loading="lazy" src="https://allmustdance.com/wp-content/uploads/2026/01/009.jpeg" alt="EP.06">
            <div class="zine-card-vig" style="background:linear-gradient(to top,rgba(12,15,26,.92) 0%,rgba(12,15,26,.4) 100%);"></div>
            <div class="zine-card-body">
              <div class="zine-card-cat">EXPERIMENTAL</div>
              <div class="zine-card-title">Cheeky<br>Session</div>
              <div class="zine-card-meta">EP.06 · 2025</div>
            </div>
          </a>
          <a class="zine-card-md" href="https://allmustdance.com/zine-ep05/">
            <img class="zine-card-img" loading="lazy" src="https://allmustdance.com/wp-content/uploads/2026/01/IMG_7615.jpg" alt="EP.05">
            <div class="zine-card-vig" style="background:linear-gradient(to top,rgba(12,15,26,.92) 0%,rgba(12,15,26,.4) 100%);"></div>
            <div class="zine-card-body">
              <div class="zine-card-cat">ROOFTOP</div>
              <div class="zine-card-title">PARCO<br>Skyline</div>
              <div class="zine-card-meta">EP.05 · Shibuya · 2024</div>
            </div>
          </a>
        </div>

        <!-- FULL: ARTWORK — MOZYSKEY × NOBBY -->
        <a class="zine-card zine-card-full" data-zine-card href="https://allmustdance.com/zine-art01/">
          <img class="zine-card-img" loading="lazy" src="https://allmustdance.com/wp-content/uploads/2026/04/mozyskeyxnobby%E3%81%AE%E3%82%B3%E3%83%94%E3%83%BC.jpg" alt="ARTWORK 01">
          <div class="zine-card-vig" style="background:linear-gradient(to top,rgba(12,15,26,.92) 0%,rgba(12,15,26,.3) 60%,transparent 100%);"></div>
          <div class="zine-card-body">
            <div class="zine-card-cat">ARTWORK · FIGURE</div>
            <div class="zine-card-title">MOZYSKEY<br>× NOBBY</div>
            <div class="zine-card-meta">Artist · Space Cooking™ · 2025</div>
          </div>
        </a>

        <!-- FULL: EP.02 — WARSAW -->
        <a class="zine-card zine-card-full" data-zine-card href="https://allmustdance.com/zine-ep02/">
          <img class="zine-card-img" loading="lazy" src="https://allmustdance.com/wp-content/uploads/2026/01/IMG_6299-scaled-e1769346293771.jpeg" alt="EP.02">
          <div class="zine-card-vig"></div>
          <div class="zine-card-num" style="opacity:.5;">002</div>
          <div class="zine-card-body">
            <div class="zine-card-cat">TOUR</div>
            <div class="zine-card-title">WARSAW</div>
            <div class="zine-card-meta">EP.02 · Europe Tour · 2023</div>
          </div>
        </a>

      </div><!-- /zine-feed -->

      <!-- View all -->
      <div class="zine-view-all">
        <a href="<?= home_url('/zine-index/') ?>">View All Issues →</a>
      </div>

    </div>
  </div>

  <!-- CHAPTER 2: YOUTUBE -->
  <div class="chapter" id="c2" style="height:100vh;">
    <div class="panel-track" id="c2-track" style="height:100vh;overflow:hidden;">
      <div class="panel" id="p2-0" style="height:100vh;position:relative;background:#000;">
        <div class="panel-bg"><img loading="lazy" src="https://allmustdance.com/wp-content/uploads/2026/03/theater.gif" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:1;"></div>
        <div class="vig"></div>
        <div class="panel-content">
          <div class="rv rv-up hero-icon-row"><img src="<?= get_stylesheet_directory_uri() ?>/logos/video.png" alt="VIDEO" class="hero-section-icon"></div>
          <div class="rv rv-up meta-line" style="margin-bottom:8px;"><a href="https://www.youtube.com/@allmustdancetokyo" target="_blank" style="color:rgba(237,235,230,0.6);text-decoration:none;font-size:11px;letter-spacing:0.22em;">@allmustdancetokyo</a></div>
          <div class="rv rv-up cta-row">
            <span class="btn-fill" onclick="openVideoOverlay()">▶ PLAY</span>
            <a href="https://www.youtube.com/@allmustdancetokyo" target="_blank" class="btn-ghost">CHANNEL</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CHAPTER 3: GOOD GOODS -->
  <div class="chapter" id="c3" style="height:100vh;">
    <div class="panel-track" id="c3-track" style="height:100vh;overflow:hidden;">
      <div class="panel" id="p3-0" style="height:100vh;position:relative;">
        <div class="panel-bg"><img loading="lazy" src="https://allmustdance.com/wp-content/uploads/2026/01/DSC5571.jpg" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:1;"></div>
        <div class="panel-bg" style="background:none;"><img loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= get_stylesheet_directory_uri() ?>/artwear/gg.png" class="lazy-img" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:contain;opacity:0.15;mix-blend-mode:screen;"></div>
        <div class="vig"></div>
        <div class="panel-content">
          <div class="rv rv-up hero-icon-row"><img src="<?= get_stylesheet_directory_uri() ?>/logos/gg.png" alt="GOOD GOODS" class="hero-section-icon"></div>
          <div class="rv rv-up meta-line" style="margin-bottom:8px;"><a href="https://zzazz-za.stores.jp/" target="_blank" style="color:rgba(237,235,230,0.6);text-decoration:none;font-size:11px;letter-spacing:0.22em;">zzazz-za.stores.jp</a></div>
          <div class="rv rv-up cta-row">
            <span class="btn-fill" onclick="openGoodsOverlay()">SHOP</span>
            <a href="https://zzazz-za.stores.jp/" target="_blank" class="btn-ghost">STORE ALL</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CHAPTER 4: CONNECT -->
  <div class="chapter" id="c4" style="height:100vh;">
    <div class="panel-track" id="c4-track" style="height:100vh;">
      <div class="panel content-panel solo" id="p4-0" style="height:100vh;">
        <div class="panel-bg"><img loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= get_stylesheet_directory_uri() ?>/logos/getin.png" class="lazy-img" alt="" aria-hidden="true" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.18;mix-blend-mode:luminosity;"></div>
        <div class="vig vig-heavy"></div>
        <div class="panel-content" style="padding-top:max(80px,calc(env(safe-area-inset-top)+64px));overflow-y:auto;overscroll-behavior-y:contain;height:100%;box-sizing:border-box;">
          <div class="connect-grid">
            <div class="rv">
              <img src="<?= get_stylesheet_directory_uri() ?>/logos/getin.png" alt="GET IN TOUCH" class="section-icon-heading anim-up" style="max-height:80px;margin-top:80px;transition-delay:0s;">
              <p class="connect-body anim-up" style="transition-delay:0.1s;">For collaborations, media inquiries, and sponsorships aligned with our cultural and social mission.</p>
              <a href="mailto:niko@allmustdance.com" class="connect-mail anim-up" style="transition-delay:0.2s;">niko@allmustdance.com</a>
            </div>
            <div class="rv">
              <ul class="connect-list">
                <li class="cl anim-up" style="transition-delay:0.1s;"><a href="https://www.instagram.com/allmustdancetokyo/" target="_blank">Instagram</a><span class="cl-type">Social</span></li>
                <li class="cl anim-up" style="transition-delay:0.15s;"><a href="https://www.youtube.com/@allmustdancetokyo" target="_blank">YouTube</a><span class="cl-type">Video</span></li>
                <li class="cl anim-up" style="transition-delay:0.2s;"><a href="<?= home_url('/zine-index/') ?>">Zine</a><span class="cl-type">Archive</span></li>
                <li class="cl anim-up" style="transition-delay:0.25s;"><a href="https://zzazz-za.stores.jp/" target="_blank">Store</a><span class="cl-type">Shop</span></li>
                <li class="cl anim-up" style="transition-delay:0.3s;"><a href="mailto:niko@allmustdance.com">Contact</a><span class="cl-type">Mail</span></li>
              </ul>
              <div class="partner-logos rv">
                <div class="partner-logos-label">Partners &amp; Venue</div>
                <div class="logo-grid">
                  <div class="logo-cell"><img loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= get_stylesheet_directory_uri() ?>/logos/clubasia.png" class="lazy-img" alt="clubasia" style="max-height:38px;max-width:80px;"></div>
                  <div class="logo-cell"><img loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= get_stylesheet_directory_uri() ?>/logos/dubla.png" class="lazy-img" alt="DUBLA" style="max-height:24px;max-width:80px;"></div>
                  <div class="logo-cell"><img loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= get_stylesheet_directory_uri() ?>/logos/ufo.png" class="lazy-img" alt="UFO" style="max-height:28px;max-width:80px;filter:brightness(0) invert(1);opacity:0.55;"></div>
                </div>
              </div>
              <div class="connect-footer">
                <span class="copyright">© ALL MUST DANCE™ · Tokyo · 2026</span>
                <a href="https://allmustdance.com/privacy-policy/" class="privacy-link">Privacy Policy</a>
              </div>
              <div class="space-cooking-credit">
                <span class="sc-credit-label">Site Design &amp; Development</span>
                <a href="https://spacecooking.studio" target="_blank" class="sc-credit-logo"><img loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= get_stylesheet_directory_uri() ?>/logos/spacecooking-logo.png" class="lazy-img" alt="SPACE COOKING™" style="height:18px;width:auto;vertical-align:middle;filter:invert(1) hue-rotate(180deg) opacity(0.75);"></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div><!-- /vtrack -->
</div><!-- /deck -->

<!-- PARTY TICKET OVERLAY (moved outside deck) -->
<div class="amd-ticket-overlay" id="p0-2">
  <button class="amd-ticket-close" onclick="closeTicketOverlay()">×</button>
  <div class="panel-bg"></div><div class="vig vig-heavy"></div>
  <div class="panel-content" style="height:100%;overflow-y:auto;-webkit-overflow-scrolling:touch;overscroll-behavior-y:contain;padding:max(80px,calc(env(safe-area-inset-top)+64px)) 32px 100px;">
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
          <div class="ticket-head"><span class="ticket-head-lbl">Tickets</span><span class="ticket-head-note lang-switchable" data-jp="Web only · 電子チケット" data-en="Web only · E-ticket">Web only · 電子チケット</span></div>
          <a class="trow" href="<?= esc_url($party_ticket) ?>" target="_blank"><div class="trow-left"><span class="trow-type">Early Bird</span><span class="trow-price"><?= esc_html($party_eb_price) ?></span></div><div class="trow-right"><span class="trow-tag">30枚限定</span><span class="trow-arr">→</span></div></a>
          <a class="trow" href="<?= esc_url($party_ticket) ?>" target="_blank"><div class="trow-left"><span class="trow-type">Advance</span><span class="trow-price"><?= esc_html($party_adv_price) ?></span></div><div class="trow-right"><span class="trow-arr">→</span></div></a>
          <div class="trow disabled"><div class="trow-left"><span class="trow-type">Door</span><span class="trow-price">¥4,500</span></div><div class="trow-right"><span style="font-size:8px;letter-spacing:0.3em;text-transform:uppercase;color:var(--white)">On the Night</span></div></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- WS ARTIST OVERLAY (moved outside deck) -->
<div class="amd-ticket-overlay" id="p1-1" style="background:var(--black);padding-top:0;overflow:hidden;">
  <?php if(!empty($ws_artists)): $wa = $ws_artists[0]; $wa_photo = get_field('photo',$wa->ID); $wa_genre = get_field('genre',$wa->ID); $wa_bio = get_field('bio_ja',$wa->ID); $wa_bio_en = get_field('bio_en',$wa->ID); $wa_role = get_field('role',$wa->ID); ?>
  <?php if($wa_photo): ?>
  <div style="position:absolute;inset:0;z-index:0;"><img loading="lazy" src="<?= esc_url($wa_photo['url']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;"></div>
  <?php else: ?>
  <div class="panel-bg" style="background:#000;"></div>
  <?php endif; ?>
  <div class="vig-artist"></div>
  <div style="position:relative;z-index:2;height:100%;overflow-y:auto;-webkit-overflow-scrolling:touch;overscroll-behavior-y:contain;display:flex;flex-direction:column;justify-content:flex-end;">
    <div style="padding:max(80px,calc(env(safe-area-inset-top)+60px)) 32px max(32px,calc(env(safe-area-inset-bottom)+24px));max-width:560px;">
      <div class="eyebrow"><?= esc_html($wa_role) ?></div>
      <div class="af-genre"><?= esc_html($wa_genre) ?></div>
      <div class="af-name"><?= esc_html($wa->post_title) ?></div>
      <?php if($wa_bio): ?><p class="af-desc"><?= esc_html($wa_bio) ?></p><?php endif; ?>
      <?php if($wa_bio_en): ?><p class="af-desc-en"><?= esc_html($wa_bio_en) ?></p><?php endif; ?>
      <div style="margin-top:24px;text-align:right;"><button class="amd-ticket-close" onclick="closeWsArtistOverlay()" style="position:static;display:inline-flex;">×</button></div>
    </div>
  </div>
  <?php else: ?>
  <div class="panel-bg" style="background:#000;"></div>
  <div class="vig-artist"></div>
  <div style="position:relative;z-index:2;height:100%;overflow-y:auto;-webkit-overflow-scrolling:touch;overscroll-behavior-y:contain;display:flex;flex-direction:column;justify-content:flex-end;">
    <div style="padding:max(80px,calc(env(safe-area-inset-top)+60px)) 32px max(32px,calc(env(safe-area-inset-bottom)+24px));max-width:560px;">
      <div class="eyebrow">Workshop Artist</div>
      <div class="af-genre">Dance · Movement · Expression</div>
      <div class="af-name">ARTIST<br>NAME TBA</div>
      <p class="af-desc">アーティスト情報は近日公開予定。</p>
      <p class="af-desc-en">Artist details coming soon.</p>
      <div style="margin-top:24px;text-align:right;"><button class="amd-ticket-close" onclick="closeWsArtistOverlay()" style="position:static;display:inline-flex;">×</button></div>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- WS TICKET OVERLAY (moved outside deck) -->
<div class="amd-ticket-overlay" id="p1-2">
  <button class="amd-ticket-close" onclick="closeWsTicketOverlay()">×</button>
  <div class="panel-bg"></div><div class="vig vig-heavy"></div>
  <div class="panel-content" style="height:100%;overflow-y:auto;-webkit-overflow-scrolling:touch;overscroll-behavior-y:contain;padding:max(80px,calc(env(safe-area-inset-top)+64px)) 32px 100px;">
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

<!-- VIDEO OVERLAY -->
<div class="amd-ticket-overlay" id="videoOverlay" style="background:#000;padding-top:max(16px,calc(env(safe-area-inset-top) + 8px));">
  <button class="amd-ticket-close" onclick="closeVideoOverlay()">×</button>
  <div style="flex-shrink:0;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:4px 24px 12px;">
      <img src="<?= get_stylesheet_directory_uri() ?>/logos/video.png" alt="VIDEO" style="max-height:28px;">
      <a href="https://www.youtube.com/@allmustdancetokyo" target="_blank" style="font-size:10px;letter-spacing:0.2em;color:rgba(237,235,230,0.55);text-decoration:none;">CHANNEL →</a>
    </div>
    <a href="https://youtu.be/ya50ucLzGj0" target="_blank" style="display:block;margin:0;aspect-ratio:16/9;background:#111;position:relative;overflow:hidden;">
      <img src="https://img.youtube.com/vi/ya50ucLzGj0/maxresdefault.jpg" alt="EP.04" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.85;">
      <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.8) 0%,transparent 50%);"></div>
      <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;"><div style="width:52px;height:52px;border-radius:50%;border:2px solid rgba(255,255,255,0.7);display:flex;align-items:center;justify-content:center;"><div style="width:0;height:0;border-style:solid;border-width:9px 0 9px 16px;border-color:transparent transparent transparent rgba(255,255,255,0.9);margin-left:3px;"></div></div></div>
      <div style="position:absolute;bottom:0;left:0;right:0;padding:14px 16px;">
        <div style="font-family:Arial,'Arial Black',sans-serif;font-size:15px;font-weight:900;color:var(--white);margin-bottom:3px;">ALL MUST DANCE™ EP.04</div>
        <div style="font-size:9px;letter-spacing:0.16em;color:rgba(237,235,230,0.5);text-transform:uppercase;">club asia · 2024.07.14</div>
      </div>
    </a>
  </div>
  <div style="flex:1;overflow-y:auto;-webkit-overflow-scrolling:touch;overscroll-behavior-y:contain;">
    <div style="font-size:9px;font-weight:500;letter-spacing:0.42em;text-transform:uppercase;color:rgba(237,235,230,0.3);padding:16px 24px 8px;">ARCHIVE</div>
    <?php
    $vd_items = [
      ['id'=>'ya50ucLzGj0','title'=>'ALL MUST DANCE™ — ep04','meta'=>'club asia · Archive'],
      ['id'=>'dOABFxAzIpA','title'=>'ALL MUST DANCE™ — ep03','meta'=>'JUL15 2024 TOKYO'],
      ['id'=>'L3-rebmUDvM','title'=>'INPLOSIVE THEATER','meta'=>'cro-magnon / nobby'],
      ['id'=>'KhgK2duchUU','title'=>'INPLOSIVE THEATER','meta'=>'DJ YABE TADASHI / NOBBY'],
      ['id'=>'1cSuNZ9y71Q','title'=>'Short Film','meta'=>'ALL MUST DANCE™'],
      ['id'=>'CM0JmwaVGaU','title'=>'Short Film','meta'=>'ALL MUST DANCE™'],
    ];
    foreach($vd_items as $vd): ?>
    <a href="https://www.youtube.com/watch?v=<?= esc_attr($vd['id']) ?>" target="_blank" style="display:flex;align-items:center;gap:14px;padding:10px 24px;border-top:1px solid rgba(237,235,230,0.07);text-decoration:none;color:var(--white);">
      <div style="flex-shrink:0;width:80px;height:50px;background:#111;position:relative;overflow:hidden;">
        <img src="https://img.youtube.com/vi/<?= esc_attr($vd['id']) ?>/default.jpg" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.8;">
        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;"><div style="width:0;height:0;border-style:solid;border-width:6px 0 6px 11px;border-color:transparent transparent transparent rgba(255,255,255,0.7);"></div></div>
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-family:Arial,'Arial Black',sans-serif;font-size:13px;font-weight:900;color:var(--white);margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc_html($vd['title']) ?></div>
        <div style="font-size:9px;letter-spacing:0.12em;color:rgba(237,235,230,0.4);"><?= esc_html($vd['meta']) ?></div>
      </div>
    </a>
    <?php endforeach; ?>
    <div style="padding:16px 24px 80px;text-align:center;"><a href="https://www.youtube.com/@allmustdancetokyo" target="_blank" style="font-size:10px;letter-spacing:0.2em;color:rgba(237,235,230,0.4);text-decoration:none;">↗ YouTube Channel</a></div>
  </div>
</div>

<!-- YouTube Theater: Pull-to-Play removed -->

<!-- GOOD GOODS OVERLAY -->
<div class="amd-ticket-overlay" id="goodsOverlay" style="padding-top:0;">
  <button class="amd-ticket-close" onclick="closeGoodsOverlay()">×</button>
  <!-- GIF banner at very top (above header) -->
  <div style="flex-shrink:0;width:100%;"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/zzazzcm.GIF" alt="ZZAZZ" loading="lazy" style="width:100%;display:block;"></div>
  <!-- Header row: icon + STORE ALL -->
  <div style="flex-shrink:0;padding:12px 24px;display:flex;align-items:center;justify-content:space-between;">
    <img src="<?= get_stylesheet_directory_uri() ?>/logos/gg.png" alt="GOOD GOODS" style="max-height:28px;">
    <a href="https://zzazz-za.stores.jp/" target="_blank" class="a-subtle" style="font-size:11px;">Store All →</a>
  </div>
  <!-- Products grid -->
  <div style="flex:1;overflow-y:auto;-webkit-overflow-scrolling:touch;overscroll-behavior-y:contain;">
    <div class="store-grid" style="padding:4px 8px 100px;">
      <a class="sc" href="https://zzazz-za.stores.jp/items/6991f608580447c3fea658e0" target="_blank"><div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd01minny.PNG" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div><div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD MN</div></div></a>
      <a class="sc" href="https://zzazz-za.stores.jp/items/69c00f86ccd49a7f3aa0df6e" target="_blank"><div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd02jkt.PNG" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div><div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD JKT</div></div></a>
      <a class="sc" href="https://zzazz-za.stores.jp/items/6991f4d9580447c3fea6584c" target="_blank"><div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd03best.PNG" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div><div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD Vest</div></div></a>
      <a class="sc" href="https://zzazz-za.stores.jp/items/69564870a6f4f8fadfb809f8" target="_blank"><div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/mozyskeylamp.png" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div><div class="sc-info"><div class="sc-cat">Artist : Mozyskey</div><div class="sc-name">Lamp (Hand Drawing)</div></div></a>
      <a class="sc" href="https://zzazz-za.stores.jp/items/69c04acbe126f8ad4fcb6b57" target="_blank"><div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd10ufotee.png" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div><div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD UFO Tee</div></div></a>
      <a class="sc" href="https://zzazz-za.stores.jp/items/69c04a31d9171133f7e5e2e1" target="_blank"><div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd08grtee.png" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div><div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD GR Tee</div></div></a>
      <a class="sc" href="https://zzazz-za.stores.jp/items/69c04b67e126f8b4d7cb6b58" target="_blank"><div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd09kidtee.png" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div><div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD Kid Tee</div></div></a>
      <a class="sc" href="https://zzazz-za.stores.jp/items/69c04bdbd917113bede5e2fb" target="_blank"><div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd09bl.png" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div><div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD BL</div></div></a>
      <a class="sc" href="https://zzazz-za.stores.jp/items/69c04c3bd9171143c6e5e2d0" target="_blank"><div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd08blpk.png" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div><div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD BL PK</div></div></a>
      <a class="sc" href="https://zzazz-za.stores.jp/items/69c04cb6e126f8b4d7cb6b71" target="_blank"><div class="sc-vis"><img src="<?= get_stylesheet_directory_uri() ?>/artwear/amd07jktufo_1.PNG" loading="lazy" style="width:100%;height:100%;object-fit:cover;"></div><div class="sc-info"><div class="sc-cat">Artwork Wear</div><div class="sc-name">AMD JKT UFO</div></div></a>
    </div>
  </div>
</div>

<!-- FLASH ELEMENTS -->
<div id="amd-chapter-line"></div>
<div class="amd-red-flash" id="amdRedFlash"></div>

<!-- ARTIST PANEL OVERLAY -->
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
      $group_defs2 = [
        'dj'      => ['img'=>'https://allmustdance.com/wp-content/uploads/2026/03/deepfloor.jpg','title'=>'DEEP FLOOR','sub'=>'DJ · Music'],
        'bar'     => ['img'=>'https://allmustdance.com/wp-content/uploads/2026/03/fdoor.jpg','title'=>'FDOOR','sub'=>'Bar · Serving'],
        'dresser' => ['img'=>'https://allmustdance.com/wp-content/uploads/2026/03/20260323_152503.gif','title'=>'DRESSER BY DANCER','sub'=>'Dance · Style'],
      ];
      foreach($group_defs2 as $gk2 => $gd2):
        $members2 = $grouped2[$gk2];
        $member_names = implode(' · ', array_map(fn($m)=>esc_html($m->post_title), $members2));
      ?>
      <!-- FIX 6: data-group attribute added for iOS Safari tap fix -->
      <div class="amd-ap-group" data-group="<?= esc_attr($gk2) ?>" onclick="openCardStack('<?= $gk2 ?>')">
        <img loading="lazy" src="<?= esc_url($gd2['img']) ?>" alt="<?= esc_attr($gd2['title']) ?>">
        <div class="amd-ap-group-vig"></div>
        <div class="amd-ap-group-info">
          <div class="amd-ap-group-sub"><?= esc_html($gd2['sub']) ?></div>
          <div class="amd-ap-group-name"><?= esc_html($gd2['title']) ?></div>
          <?php if($member_names): ?><div class="amd-ap-group-members"><?= $member_names ?></div><?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- CARD STACK OVERLAY -->
<div class="amd-card-stack" id="cardStackOverlay">
  <button class="amd-cs-close" onclick="closeCardStack()">← Back</button>
  <div class="amd-cs-title" id="cardStackTitle">DEEP FLOOR</div>
  <div id="cardStackStage"></div>
</div>

<!-- ARTIST DATA (PHP→JS) -->
<script>
var _amdThemeUrl = '<?= get_stylesheet_directory_uri() ?>';
var _amdArtists = <?php
$out = ['dj'=>[], 'bar'=>[], 'dresser'=>[]];
$party_artists_all = $party_artists ?? [];
foreach($party_artists_all as $pa_all){
  $role_all = strtolower(get_field('role',$pa_all->ID) ?? '');
  if(str_contains($role_all,'dresser') || str_contains($role_all,'dancer') || str_contains($role_all,'dance') || str_contains($role_all,'style')){
    $group_all = 'dresser';
  } elseif(str_contains($role_all,'bar') || str_contains($role_all,'fdoor') || str_contains($role_all,'bartender')){
    $group_all = 'bar';
  } else {
    $group_all = 'dj';
  }
  $photo_all = get_field('photo',$pa_all->ID);
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
</script>

<!-- GSAP (loaded once here, FIX 1: removed from head) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script>
/* ════════════════════════════════════════
   ALL MUST DANCE — Main Script
   CHAPTERS: c0=PARTY c1=WORKSHOP c2=VIDEO c3=STORE c4=CONNECT
════════════════════════════════════════ */
if(typeof gsap!=='undefined'&&typeof ScrollTrigger!=='undefined') gsap.registerPlugin(ScrollTrigger);
const isPWA = window.navigator.standalone === true || window.matchMedia('(display-mode: standalone)').matches;
if(isPWA){ document.documentElement.classList.add('pwa-mode'); }
function getScrollEl(){ return isPWA ? document.getElementById('deck') : document.documentElement; }
function getScrollTop(){ return isPWA ? (document.getElementById('deck')||{}).scrollTop||0 : window.scrollY||document.documentElement.scrollTop; }
const CHAPTERS = [
  { id:'c0', panels:['p0-0'] },
  { id:'c1', panels:['p1-0'] },
  { id:'cZine', panels:[] },
  { id:'c2', panels:['p2-0'] },
  { id:'c3', panels:['p3-0'] },
  { id:'c4', panels:['p4-0'] },
];
const N = CHAPTERS.length;
const W = () => window.innerWidth;
let cIdx = 0, pIdx = 0;

const chapEls   = CHAPTERS.map(c => document.getElementById(c.id));
const trackEls  = CHAPTERS.map(c => document.getElementById(c.id + '-track'));
const pdotsEl   = document.getElementById('pdots');
const hintEl    = document.getElementById('swipeHint');
const backBtn   = document.getElementById('backBtn');
const aPrev     = document.getElementById('aPrev');
const aNext     = document.getElementById('aNext');

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
  } else { pdotsEl.style.display = 'none'; }
  backBtn.classList.toggle('visible', pIdx > 0);
  chapEls.forEach((el, i) => el.classList.toggle('active', i === cIdx));
}

function showRv(ci){
  if(!chapEls[ci]) return;
  if(typeof gsap === 'undefined'){
    const rvEls = chapEls[ci].querySelectorAll('.rv,.rv-left,.rv-right,.rv-up,.rv-scale');
    rvEls.forEach(el => el.classList.remove('visible'));
    rvEls.forEach((el,i) => setTimeout(()=>el.classList.add('visible'), 120+i*80));
    return;
  }
  const rvEls = [...chapEls[ci].querySelectorAll('.rv,.rv-left,.rv-right,.rv-up,.rv-scale')];
  if(!rvEls.length) return;
  gsap.set(rvEls, {opacity:0, y:20, clearProps:'none'});
  gsap.to(rvEls, { opacity:1, y:0, duration:0.65, stagger:0.07, ease:'power3.out', delay:0.1, onStart(){ rvEls.forEach(el=>el.classList.add('visible')); } });
}

let _chapNavLock = false;
function goChapter(newC, newP = 0){
  if(newC < 0 || newC >= N) return;
  if(chapEls[newC].style.display === 'none') return;
  if(_chapNavLock) return;
  _chapNavLock = true;
  cIdx = newC; pIdx = newP;
  var scrollEl = getScrollEl();
  if(isPWA && scrollEl.scrollTo){ scrollEl.scrollTo({ top: chapEls[cIdx].offsetTop, behavior: 'smooth' }); }
  else { window.scrollTo({ top: chapEls[cIdx].offsetTop, behavior: 'smooth' }); }
  if(trackEls[cIdx]) trackEls[cIdx].scrollLeft = newP * W();
  updateUI();
  if(typeof gsap !== 'undefined'){
    const line = document.getElementById('amd-chapter-line');
    if(line){ gsap.fromTo(line,{scaleX:0,opacity:1},{scaleX:1,opacity:0,duration:0.6,ease:'power2.inOut',onComplete:()=>{gsap.set(line,{scaleX:0});}}); }
  }
  setTimeout(() => { _chapNavLock = false; showRv(cIdx); }, 700);
}

function snapToPanel(newP){
  const tp = CHAPTERS[cIdx].panels.length;
  if(newP < 0 || newP >= tp) return;
  pIdx = newP;
  if(trackEls[cIdx]){ trackEls[cIdx].style.scrollBehavior = ''; trackEls[cIdx].scrollTo({ left: newP * W(), behavior: 'smooth' }); }
  updateUI();
}

let scrollTimer;
var _scrollTarget = isPWA ? document.getElementById('deck') : window;
(_scrollTarget||window).addEventListener('scroll', () => {
  const si = document.getElementById('scrollIndicator');
  if(si) si.style.opacity = '0';
  clearTimeout(scrollTimer);
  scrollTimer = setTimeout(() => {
    const sy = getScrollTop(); const vh = window.innerHeight;
    let accumulated = 0, newC = cIdx;
    for(let i = 0; i < N; i++){
      if(!chapEls[i] || chapEls[i].style.display === 'none') continue;
      const h = chapEls[i].offsetHeight || vh;
      if(sy < accumulated + h - 20){ newC = i; break; }
      accumulated += h; newC = i;
    }
    if(newC !== cIdx && !_chapNavLock){ cIdx = newC; pIdx = 0; updateUI(); showRv(cIdx); }
  }, 80);
}, { passive: true });

trackEls.forEach((tr, ci) => {
  if(!tr) return;
  let pt;
  tr.addEventListener('scroll', () => {
    clearTimeout(pt);
    pt = setTimeout(() => { if(ci !== cIdx) return; pIdx = Math.round(tr.scrollLeft / W()); updateUI(); }, 80);
  }, { passive: true });
});

/* BODY SCROLL LOCK */
let _scrollLockCount = 0;
function lockBodyScroll(){ _scrollLockCount++; if(_scrollLockCount===1){ if(isPWA){var d=document.getElementById('deck');if(d)d.style.overflowY='hidden';} else{document.documentElement.style.overflow='hidden';document.body.style.overflow='hidden';} } }
function unlockBodyScroll(){ _scrollLockCount=Math.max(0,_scrollLockCount-1); if(_scrollLockCount===0){ if(isPWA){var d=document.getElementById('deck');if(d)d.style.overflowY='scroll';} else{document.documentElement.style.overflow='';document.body.style.overflow='';} } }

/* SWIPE ENGINE */
let swipeX0=0, swipeY0=0, swipeDir=null, swipeActive=false;
function isArtistOverlayOpen(){ return !!document.querySelector('.amd-artist-panel.open, .amd-card-stack.open'); }
function getPoint(e){ return e.touches ? e.touches[0] : e; }
function onSwipeStart(e,ci){ if(isArtistOverlayOpen()) return; const p=getPoint(e); swipeX0=p.clientX; swipeY0=p.clientY; swipeDir=null; swipeActive=true; }
function onSwipeMove(e,ci){ if(!swipeActive||isArtistOverlayOpen()) return; const p=getPoint(e); const dx=p.clientX-swipeX0; const dy=p.clientY-swipeY0; if(swipeDir===null&&(Math.abs(dx)>8||Math.abs(dy)>8)) swipeDir=Math.abs(dx)>Math.abs(dy)?'h':'v'; const tr=trackEls[ci]; if(swipeDir==='h'){ if(e.cancelable) e.preventDefault(); tr.style.scrollBehavior='auto'; tr.scrollLeft=pIdx*W()-dx; } }
function onSwipeEnd(e,ci){ if(!swipeActive) return; swipeActive=false; if(swipeDir!=='h'){swipeDir=null;return;} const p=e.changedTouches?e.changedTouches[0]:e; const totalDx=swipeX0-p.clientX; cIdx=ci; const tp=CHAPTERS[ci].panels.length; if(totalDx>40&&pIdx<tp-1) snapToPanel(pIdx+1); else if(totalDx<-40&&pIdx>0) snapToPanel(pIdx-1); else snapToPanel(pIdx); swipeDir=null; }

[0,1].forEach(ci => {
  const tr=trackEls[ci]; if(!tr) return;
  tr.addEventListener('touchstart',e=>onSwipeStart(e,ci),{passive:true});
  tr.addEventListener('touchmove',e=>onSwipeMove(e,ci),{passive:false});
  tr.addEventListener('touchend',e=>onSwipeEnd(e,ci),{passive:true});
  let mouseDown=false;
  tr.addEventListener('mousedown',e=>{mouseDown=true;onSwipeStart(e,ci);});
  tr.addEventListener('mousemove',e=>{if(mouseDown) onSwipeMove(e,ci);});
  tr.addEventListener('mouseup',e=>{if(mouseDown){mouseDown=false;onSwipeEnd(e,ci);}});
  tr.addEventListener('mouseleave',e=>{if(mouseDown){mouseDown=false;onSwipeEnd(e,ci);}});
});

document.querySelectorAll('[data-goto]').forEach(el => {
  if(el.hasAttribute('data-menu-close')) return;
  el.addEventListener('click', e => { e.preventDefault(); const [c,p]=el.dataset.goto.split(',').map(Number); if(c!==cIdx) goChapter(c,p); else snapToPanel(p); });
});

if(aPrev) aPrev.onclick=()=>{if(pIdx>0) snapToPanel(pIdx-1); else goChapter(cIdx-1);};
if(aNext) aNext.onclick=()=>{const tp=CHAPTERS[cIdx].panels.length; if(pIdx<tp-1) snapToPanel(pIdx+1); else goChapter(cIdx+1);};
if(backBtn) backBtn.onclick=()=>snapToPanel(0);

/* MENU */
const menuToggle=document.getElementById('menuToggle');
const menuOverlay=document.getElementById('menuOverlay');
let menuOpen=false;
let _m035Tls=[], _m035LastIdx=0;

function _m035Activate(idx){
  const items=menuOverlay.querySelectorAll('.menu-item');
  if(!items.length||!_m035Tls.length) return;
  if(_m035LastIdx!==idx&&_m035Tls[_m035LastIdx]){
    _m035Tls[_m035LastIdx].timeScale(3).reverse();
    gsap.to(items[_m035LastIdx],{flex:'1 1 52px',duration:0.22,ease:'power2.inOut'});
    items[_m035LastIdx].classList.remove('active');
  }
  _m035LastIdx=idx;
  if(_m035Tls[idx]) _m035Tls[idx].timeScale(1).play();
  gsap.to(items[idx],{flex:'1 1 80px',duration:0.22,ease:'power2.inOut'});
  items[idx].classList.add('active');
}

function _m035Init(){
  _m035Tls=[]; _m035LastIdx=0;
  const items=menuOverlay.querySelectorAll('.menu-item');
  items.forEach((item,idx)=>{
    const medias=item.querySelectorAll('.menu-row-media');
    const tl=gsap.timeline({paused:true});
    if(medias.length) tl.to(medias,{y:0,stagger:{each:0.05,from:'random'},duration:0.4,ease:'power4.out'});
    _m035Tls.push(tl);
    item.addEventListener('mouseenter',()=>_m035Activate(idx));
    item.addEventListener('touchend',e=>{_m035Activate(idx);},{passive:true});
  });
  _m035Activate(0);
}

function openMenu(){
  menuOpen=true; menuToggle.classList.add('open'); menuOverlay.classList.add('open'); lockBodyScroll();
  const items=menuOverlay.querySelectorAll('.menu-item');
  gsap.set(items,{opacity:0,x:-20});
  gsap.to(items,{opacity:1,x:0,duration:0.4,stagger:0.06,ease:'power3.out',delay:0.15,onComplete:_m035Init});
}
function closeMenu(){
  menuOpen=false; menuToggle.classList.remove('open');
  const items=menuOverlay.querySelectorAll('.menu-item');
  gsap.to(items,{opacity:0,x:20,duration:0.2,stagger:{each:0.04,from:'end'},ease:'power2.in',onComplete:()=>{
    menuOverlay.classList.remove('open'); unlockBodyScroll(); _m035Tls=[];
    items.forEach(i=>{i.classList.remove('active');gsap.set(i,{flex:'',clearProps:'flex'});i.querySelectorAll('.menu-row-media').forEach(m=>gsap.set(m,{y:'110%'}));});
  }});
}
menuToggle.addEventListener('click',()=>menuOpen?closeMenu():openMenu());
document.addEventListener('keydown',e=>{if(e.key==='Escape'&&menuOpen) closeMenu();});

/* Fix 12: 1tap = show image, 2tap = navigate */
let _menuLastTapped = null, _menuTapTimer = null;
menuOverlay.addEventListener('click',e=>{
  const item=e.target.closest('.menu-item');
  if(!item) return;
  e.preventDefault();
  /* First tap: activate (show image) */
  if(_menuLastTapped !== item){
    _menuLastTapped = item;
    _m035Activate([...menuOverlay.querySelectorAll('.menu-item')].indexOf(item));
    clearTimeout(_menuTapTimer);
    _menuTapTimer = setTimeout(()=>{ _menuLastTapped=null; }, 3000);
    return;
  }
  /* Second tap: navigate */
  _menuLastTapped = null;
  clearTimeout(_menuTapTimer);
  if(item.hasAttribute('data-href')){
    const href=item.dataset.href; closeMenu(); setTimeout(()=>{window.location.href=href;},300);
  } else if(item.hasAttribute('data-goto')){
    const[mc,mp]=(item.dataset.goto||'0,0').split(',').map(Number); closeMenu(); setTimeout(()=>goChapter(mc,mp||0),300);
  }
});

/* HERO VIDEO */
const heroVid=document.getElementById('heroVid');
if(heroVid){ const showVid=()=>{heroVid.style.opacity='1';}; heroVid.addEventListener('canplay',showVid,{once:true}); if(heroVid.readyState>=2) showVid(); }

if(hintEl){ setTimeout(()=>hintEl.classList.add('hidden'),4000); window.addEventListener('scroll',()=>hintEl.classList.add('hidden'),{once:true,passive:true}); }

/* LAZY LOADING */
const lazyIO=new IntersectionObserver(entries=>{entries.forEach(entry=>{if(!entry.isIntersecting)return;const ch=entry.target;ch.querySelectorAll('img.lazy-img[data-src]').forEach(img=>{img.src=img.dataset.src;img.removeAttribute('data-src');img.classList.remove('lazy-img');});ch.querySelectorAll('iframe[data-src]').forEach(iframe=>{iframe.src=iframe.dataset.src;iframe.removeAttribute('data-src');});lazyIO.unobserve(ch);});},{rootMargin:'200px 0px'});
document.querySelectorAll('.chapter[data-lazy]').forEach(ch=>lazyIO.observe(ch));

/* SCROLL ANIMATIONS */
const ioAnim=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('visible');ioAnim.unobserve(e.target);}});},{threshold:0.12});
document.querySelectorAll('.rv,.rv-left,.rv-right,.rv-up,.rv-scale').forEach((el,i)=>{
  if(el.closest('#c0')||el.closest('#c1')) return;
  const parent=el.parentElement;
  const siblings=parent?[...parent.querySelectorAll(':scope > .rv,:scope > .rv-left,:scope > .rv-right,:scope > .rv-up,:scope > .rv-scale')]:[];
  const idx=siblings.indexOf(el);
  if(idx>0) el.style.transitionDelay=(idx*0.1)+'s';
  ioAnim.observe(el);
});

/* PARALLAX */
const parallaxEls=document.querySelectorAll('.parallax-bg');
let ticking=false;
window.addEventListener('scroll',()=>{if(ticking)return;ticking=true;requestAnimationFrame(()=>{parallaxEls.forEach(el=>{const section=el.closest('.chapter');if(!section)return;const progress=-section.getBoundingClientRect().top/window.innerHeight;el.style.transform=`translateY(${progress*40}px)`;});ticking=false;});},{passive:true});

/* YOUTUBE THEATER: removed */

function openVideoOverlay(){
  document.body.classList.add('overlay-open');
  document.getElementById('videoOverlay').classList.add('open');
  lockBodyScroll();
}

/* FIX 7: removed references to non-existent ytGridView/ytTriggerScreen */
function closeVideoOverlay(){
  document.body.classList.remove('overlay-open');
  document.getElementById('videoOverlay').classList.remove('open');
  unlockBodyScroll();
  updateUI();
}

/* Theater swipe: removed */

/* PULL TO PLAY: removed */

/* WHEEL */
let wheelTimer, wheelDelta=0;
window.addEventListener('wheel',e=>{
  if(isArtistOverlayOpen()) return;
  if(document.querySelector('.amd-ticket-overlay.open')) return;
  const track=e.target.closest?e.target.closest('.panel-track'):null;
  if(track){const panels=track.querySelectorAll('.panel');if(panels.length>1)return;}
  wheelDelta+=e.deltaY; clearTimeout(wheelTimer);
  wheelTimer=setTimeout(()=>{
    if(Math.abs(wheelDelta)<30){wheelDelta=0;return;}
    const visible=[];
    for(let i=0;i<N;i++){if(chapEls[i]&&chapEls[i].style.display!=='none')visible.push(i);}
    const curPos=visible.indexOf(cIdx);
    if(wheelDelta>0&&curPos<visible.length-1) goChapter(visible[curPos+1]);
    else if(wheelDelta<0&&curPos>0) goChapter(visible[curPos-1]);
    wheelDelta=0;
  },50);
},{passive:true});

/* SCROLL ANIMATION OBSERVER */
const animIO=new IntersectionObserver(entries=>{entries.forEach(entry=>{if(entry.isIntersecting){entry.target.classList.add('in');animIO.unobserve(entry.target);}});},{threshold:0.15,rootMargin:'0px 0px -40px 0px'});
document.querySelectorAll('.anim-up, .anim-left, .anim-right').forEach(el=>animIO.observe(el));

/* ZINE section staggered entrance animation */
const zineAnimIO = new IntersectionObserver(entries=>{
  entries.forEach(entry=>{
    if(entry.isIntersecting){
      const els = document.querySelectorAll('.zine-anim');
      els.forEach((el,i)=>{ setTimeout(()=>el.classList.add('in'), i*100); });
      zineAnimIO.unobserve(entry.target);
    }
  });
},{threshold:0.1});
const zineEl = document.getElementById('cZine');
if(zineEl) zineAnimIO.observe(zineEl);

/* JP/EN LANGUAGE */
function amdToggleLang(){
  const html=document.getElementById('amdHtml');
  const current=html.getAttribute('data-lang')||'jp';
  amdSetLang(current==='jp'?'en':'jp');
}

function amdSetLang(lang){
  const html=document.getElementById('amdHtml');
  html.setAttribute('data-lang',lang);
  html.lang=lang==='jp'?'ja':'en';
  localStorage.setItem('amd-lang',lang);
  document.querySelectorAll('.lang-switchable').forEach(el=>{const val=el.getAttribute('data-'+lang);if(val!==null) el.innerHTML=val;});
  const jpEl=document.getElementById('langJp'), enEl=document.getElementById('langEn');
  if(jpEl) jpEl.style.opacity=lang==='jp'?'1':'0.35';
  if(enEl) enEl.style.opacity=lang==='en'?'1':'0.35';
  if(typeof _apArtists!=='undefined'&&_apArtists.length){
    const el=document.getElementById('amc-'+_apCurIdx);
    if(el){
      el.querySelectorAll('.af-desc,.af-desc-en').forEach(p=>{p.dataset.wrapped='';p.querySelectorAll('.amd-word').forEach(w=>w.replaceWith(w.textContent));});
      if(typeof _amdWrapWords==='function'){
        el.querySelectorAll('.af-desc,.af-desc-en').forEach(_amdWrapWords);
        const words=el.querySelectorAll('.amd-word');
        if(words.length&&typeof gsap!=='undefined'){gsap.set(words,{x:'80vw',opacity:0});gsap.to(words,{x:0,opacity:1,duration:0.55,stagger:0.014,ease:'power4.out'});}
      }
    }
  }
}

/* INIT */
chapEls[0].classList.add('active'); updateUI();
setTimeout(()=>showRv(0),300);
(function(){
  const saved=localStorage.getItem('amd-lang')||'jp';
  /* Fix 5: JP=default, only apply lang-switchable transforms when EN */
  const html=document.getElementById('amdHtml');
  html.setAttribute('data-lang',saved);
  html.lang=saved==='jp'?'ja':'en';
  if(saved==='en'){
    document.querySelectorAll('.lang-switchable').forEach(el=>{const val=el.getAttribute('data-en');if(val!==null) el.innerHTML=val;});
  }
  var jpEl=document.getElementById('langJp'), enEl=document.getElementById('langEn');
  if(jpEl) jpEl.style.opacity=saved==='jp'?'1':'0.35';
  if(enEl) enEl.style.opacity=saved==='en'?'1':'0.35';
})();

/* FLASH EFFECT */
function amdRedFlash(onComplete){
  if(typeof gsap==='undefined'){if(onComplete) onComplete();return;}
  const el=document.getElementById('amdRedFlash');
  if(!el){if(onComplete) onComplete();return;}
  gsap.timeline({onComplete:onComplete||null}).set(el,{opacity:0}).to(el,{opacity:0.55,duration:0.1,ease:'power2.out'}).to(el,{opacity:0,duration:0.4,ease:'power2.in'});
}

/* OVERLAY FUNCTIONS */
function openGoodsOverlay(){document.body.classList.add('overlay-open');document.getElementById('goodsOverlay').classList.add('open');lockBodyScroll();}
function closeGoodsOverlay(){document.body.classList.remove('overlay-open');document.getElementById('goodsOverlay').classList.remove('open');unlockBodyScroll();}
function openWsArtistOverlay(){document.body.classList.add('overlay-open');document.getElementById('p1-1').classList.add('open');lockBodyScroll();}
function closeWsArtistOverlay(){document.body.classList.remove('overlay-open');document.getElementById('p1-1').classList.remove('open');unlockBodyScroll();}
function openWsTicketOverlay(){var d=document.getElementById('deck'),s=d?d.scrollTop:0;document.body.classList.add('overlay-open');document.getElementById('p1-2').classList.add('open');lockBodyScroll();if(d)d.scrollTop=s;}
function closeWsTicketOverlay(){var d=document.getElementById('deck'),s=d?d.scrollTop:0;document.body.classList.remove('overlay-open');document.getElementById('p1-2').classList.remove('open');unlockBodyScroll();if(d)d.scrollTop=s;}
function openTicketOverlay(){var d=document.getElementById('deck'),s=d?d.scrollTop:0;document.body.classList.add('overlay-open');document.getElementById('p0-2').classList.add('open');lockBodyScroll();if(d)d.scrollTop=s;}
function closeTicketOverlay(){var d=document.getElementById('deck'),s=d?d.scrollTop:0;document.body.classList.remove('overlay-open');document.getElementById('p0-2').classList.remove('open');unlockBodyScroll();if(d)d.scrollTop=s;}
function openArtistPanel(){document.body.classList.add('overlay-open');document.getElementById('artistPanelOverlay').classList.add('open');lockBodyScroll();}
function closeArtistPanel(){document.body.classList.remove('overlay-open');document.getElementById('artistPanelOverlay').classList.remove('open');unlockBodyScroll();}

/* CARD STACK */
let _apCurIdx=0, _apArtists=[];

function openCardStack(groupKey){
  const overlay=document.getElementById('cardStackOverlay');
  const stage=document.getElementById('cardStackStage');
  const titleEl=document.getElementById('cardStackTitle');
  const labels={dj:'DEEP FLOOR',bar:'FDOOR',dresser:'DRESSER BY DANCER'};
  titleEl.textContent=labels[groupKey]||'';
  const data=window._amdArtists?window._amdArtists[groupKey]:[];
  _apArtists=data||[]; _apCurIdx=0;
  _buildCardStack(stage,_apArtists);
  overlay.classList.add('open');
  document.body.classList.add('overlay-open');
  lockBodyScroll();
}
function closeCardStack(){document.body.classList.remove('overlay-open');document.getElementById('cardStackOverlay').classList.remove('open');unlockBodyScroll();}

function _amdWrapWords(el){
  if(!el||el.dataset.wrapped) return;
  el.innerHTML=el.textContent.split(' ').map(w=>`<span class="amd-word">${w}</span>`).join(' ');
  el.dataset.wrapped='1';
}

function _buildCardStack(stage,artists){
  stage.innerHTML='';
  if(!artists.length){stage.innerHTML='<div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;"><p style="font-size:12px;letter-spacing:.3em;text-transform:uppercase;color:rgba(237,235,230,.3);">Coming Soon</p></div>';return;}
  artists.forEach((a,i)=>{
    const card=document.createElement('div');
    card.className='amd-card'; card.id='amc-'+i;
    card.style.cssText=`z-index:${artists.length-i};`;
    const photoHtml=a.photo?`<img src="${a.photo}" alt="${a.name}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:1;">`:'';
    const scHtml=a.sc?`<a href="${a.sc}" target="_blank" class="af-link"><img src="${_amdThemeUrl}/logos/sc.png" style="width:28px;height:28px;object-fit:contain;opacity:0.82;"></a>`:'';
    const igHtml=a.ig?`<a href="${a.ig}" target="_blank" class="af-link"><img src="${_amdThemeUrl}/logos/insta.png" style="width:28px;height:28px;object-fit:contain;opacity:0.82;"></a>`:'';
    card.innerHTML=`<div style="position:absolute;inset:0;">${photoHtml}</div><div class="vig-artist"></div><div class="amd-card-content"><div class="af-genre">${a.genre||''}</div><div class="af-name">${a.name}</div><div class="af-links" style="margin-bottom:14px;">${scHtml}${igHtml}</div><p class="af-desc">${a.bio_ja||''}</p><p class="af-desc-en">${a.bio_en||''}</p><div class="amd-card-num">${String(i+1).padStart(2,'0')} / ${String(artists.length).padStart(2,'0')}</div></div>`;
    stage.appendChild(card);
  });
  const overlay=document.getElementById('cardStackOverlay');
  const oldNav=overlay.querySelector('.amd-card-nav'); if(oldNav) oldNav.remove();
  const nav=document.createElement('div'); nav.className='amd-card-nav';
  nav.innerHTML=`<button class="amd-card-nav-btn amd-nav-prev" onclick="amdCardNav(-1)" disabled>↑ PREV</button><button class="amd-card-nav-btn amd-nav-next" onclick="amdCardNav(1)">${_apArtists.length>1?'↓ NEXT':'✕ CLOSE'}</button>`;
  overlay.appendChild(nav);
  _showCard(0,false);
}

function _showCard(idx,animate){
  _apArtists.forEach((_,i)=>{
    const el=document.getElementById('amc-'+i); if(!el) return;
    if(i<idx) gsap.set(el,{rotationX:40,rotationZ:0,scale:0.72,opacity:0,transformPerspective:800,transformOrigin:'50% 10%'});
    else if(i===idx){
      if(animate){
        gsap.fromTo(el,{rotationX:-10,rotationZ:0,y:40,opacity:0,scale:0.96,transformPerspective:800,transformOrigin:'50% 10%'},{rotationX:0,rotationZ:0,y:0,opacity:1,scale:1,duration:0.52,ease:'power3.out'});
        el.querySelectorAll('.af-desc,.af-desc-en').forEach(_amdWrapWords);
        const words=el.querySelectorAll('.amd-word');
        if(words.length){gsap.set(words,{x:'80vw',opacity:0});gsap.to(words,{x:0,opacity:1,duration:0.65,stagger:0.016,ease:'power4.out',delay:0.3});}
      } else {
        gsap.set(el,{rotationX:0,rotationZ:0,y:0,opacity:1,scale:1,transformPerspective:800,transformOrigin:'50% 10%'});
        el.querySelectorAll('.af-desc,.af-desc-en').forEach(_amdWrapWords);
        const words=el.querySelectorAll('.amd-word');
        if(words.length){gsap.set(words,{x:'80vw',opacity:0});gsap.to(words,{x:0,opacity:1,duration:0.65,stagger:0.016,ease:'power4.out',delay:0.45});}
      }
    } else gsap.set(el,{rotationX:0,y:0,opacity:0,scale:1,transformPerspective:800,transformOrigin:'50% 10%'});
  });
  const overlayEl=document.getElementById('cardStackOverlay');
  const prev=overlayEl?overlayEl.querySelector('.amd-nav-prev'):null;
  const next=overlayEl?overlayEl.querySelector('.amd-nav-next'):null;
  if(prev){prev.disabled=idx===0;prev.style.visibility=idx===0?'hidden':'visible';}
  if(next) next.textContent=idx===_apArtists.length-1?'✕ CLOSE':'↓ NEXT';
}

function amdCardNav(dir){
  const next=_apCurIdx+dir;
  if(next<0) return;
  if(next>=_apArtists.length){
    const el=document.getElementById('amc-'+_apCurIdx);
    if(el) gsap.to(el,{rotationX:40,rotationZ:0,scale:0.72,opacity:0,duration:0.42,ease:'power2.in',transformPerspective:800,transformOrigin:'50% 10%',onComplete:closeCardStack});
    else closeCardStack();
    return;
  }
  const cur=document.getElementById('amc-'+_apCurIdx);
  if(cur) gsap.to(cur,{rotationX:40,rotationZ:0,scale:0.72,opacity:0,duration:0.45,ease:'power2.in',transformPerspective:800,transformOrigin:'50% 10%'});
  _apCurIdx=next; _showCard(_apCurIdx,true);
}
</script>

<script>
(function(){
/* Viewport: use CSS 100vh (= large viewport in Safari, no dynamic resize) */

/* Show chapters + lazy load */
['c1','cZine','c2','c3','c4'].forEach(function(id){
  var el=document.getElementById(id); if(!el) return;
  el.style.display=''; el.style.visibility='visible';
  el.querySelectorAll('img[data-src]').forEach(function(img){
    img.src=img.getAttribute('data-src');img.removeAttribute('data-src');img.classList.remove('lazy-img');
  });
});
document.body.classList.add('gsap-ready');

if(typeof gsap==='undefined') return;

/* 3D exit animation on deck scroll — vertical only, no lateral rotation */
var defs=[{id:'c1'},{id:'cZine'},{id:'c2'},{id:'c3'}];
var targets={};
defs.forEach(function(d){
  var tgt=document.getElementById(d.id+'-track');
  if(tgt){tgt.style.transformOrigin='50% 10%';tgt.style.willChange='transform,opacity';targets[d.id]={el:tgt};}
});
var _raf=null, _vh=window.innerHeight;

function onDeckScroll(){
  if(_raf) return;
  _raf=requestAnimationFrame(function(){
    _raf=null; _vh=window.innerHeight;
    var scrollTop=typeof getScrollTop==='function'?getScrollTop():(window.scrollY||document.documentElement.scrollTop);
    defs.forEach(function(d){
      var ch=document.getElementById(d.id); var info=targets[d.id];
      if(!ch||!info) return;
      var chTop=ch.offsetTop; var offset=scrollTop-chTop; var ratio=offset/_vh;
      if(ratio<=0) gsap.set(info.el,{rotationX:0,scale:1,opacity:1,transformPerspective:900});
      else if(ratio<1){
        var p=Math.max(0,(ratio-0.45)/0.55); p=Math.min(1,p);
        gsap.set(info.el,{rotationX:32*p,scale:1-0.26*p,opacity:1-p,transformPerspective:900});
      } else gsap.set(info.el,{rotationX:32,scale:0.74,opacity:0,transformPerspective:900});
    });
  });
}
var _pwa2=typeof isPWA!=='undefined'&&isPWA;
(_pwa2?document.getElementById('deck'):window).addEventListener('scroll',onDeckScroll,{passive:true});

/* Text reveal on deck scroll */
var _revealed={};
function checkReveal(){
  var scrollTop=typeof getScrollTop==='function'?getScrollTop():(window.scrollY||0);
  defs.forEach(function(d){
    if(d.id==='c0') return; if(_revealed[d.id]) return;
    var ch=document.getElementById(d.id); if(!ch) return;
    if(scrollTop>=ch.offsetTop-_vh*0.3){
      _revealed[d.id]=true;
      gsap.to(ch.querySelectorAll('.rv,.rv-left,.rv-right,.rv-up,.rv-scale'),{opacity:1,y:0,duration:0.6,stagger:0.07,ease:'power2.out',delay:0.1});
    }
  });
  var c4=document.getElementById('c4');
  if(c4&&!_revealed['c4']){
    if(scrollTop>=c4.offsetTop-_vh*0.3){
      _revealed['c4']=true;
      gsap.fromTo(c4.querySelectorAll('.rv,.rv-left,.rv-right,.rv-up,.rv-scale,.connect-body,.connect-mail,.cl'),{opacity:0,y:20},{opacity:1,y:0,duration:0.6,stagger:0.06,ease:'power2.out',delay:0.15});
    }
  }
}
(_pwa2?document.getElementById('deck'):window).addEventListener('scroll',checkReveal,{passive:true});

setTimeout(function(){ _revealed['c0']=true; },300);

/* ================================================
   ZINE Book — HOME COMING page flip
   ================================================ */
function toggleZineBook(e){
  var book = document.getElementById('zineBook07');
  if(!book) return;
  if(book.classList.contains('open')){
    /* If open, tap on back page goes to zine */
    if(!e.target.closest('.zine-book-close')){
      window.location.href = 'https://allmustdance.com/zine-ep07/';
    }
    return;
  }
  e.preventDefault();
  book.classList.add('open');
  /* Disable scroll while book is open */
  document.body.style.overflow = 'hidden';
  var deck = document.getElementById('deck');
  if(deck) deck.style.overflow = 'hidden';
}
function closeZineBook(){
  var book = document.getElementById('zineBook07');
  if(!book) return;
  book.classList.remove('open');
  document.body.style.overflow = '';
  var deck = document.getElementById('deck');
  if(deck) deck.style.overflow = '';
}

/* ================================================
   GSAP 031 — ZINE Card Stack Effect
   Next card overlaps 40% before rear card starts spinning
   Rear card: 3 rotations on X axis (vertical flip), no lateral
   Cards stay pinned — they do NOT scroll upward
   ================================================ */
(function initZineStack(){
  var feed = document.getElementById('zineFeed');
  if(!feed) return;
  var cards = feed.querySelectorAll('[data-zine-card]');
  if(!cards.length || typeof ScrollTrigger === 'undefined') return;
  var scroller = _pwa2 ? document.getElementById('deck') : window;

  cards.forEach(function(card, i){
    if(i >= cards.length - 1) return; /* last card doesn't exit */

    card.style.transformOrigin = '50% 50%';
    card.style.willChange = 'transform, opacity';

    /* Pin the card so it stays in place while next card scrolls over it */
    ScrollTrigger.create({
      trigger: card,
      scroller: scroller,
      start: 'top top',
      /* Total scroll distance: card height (next card fully covers) */
      end: function(){ return '+=' + card.offsetHeight; },
      pin: true,
      pinSpacing: false,
      scrub: 0.3,
      onUpdate: function(self){
        var progress = self.progress; /* 0→1 over full card height */

        /* Phase 1 (0–0.4): next card is approaching, no effect yet */
        if(progress <= 0.4){
          gsap.set(card, { rotationX:0, scale:1, opacity:1, transformPerspective:1200 });
          return;
        }

        /* Phase 2 (0.4–1.0): next card has overlapped 40%+, start spinning */
        var p = (progress - 0.4) / 0.6; /* normalize to 0→1 */
        p = Math.min(1, Math.max(0, p));

        /* 3 full rotations on X axis (1080°) — vertical flip only */
        var rotX = p * 1080;
        var sc   = 1 - (0.45 * p);
        var op   = 1 - p;

        gsap.set(card, {
          rotationX: rotX,
          scale: sc,
          opacity: op,
          transformPerspective: 1200
        });
      }
    });
  });
})();

/* Hide site header when ZINE section is in view */
(function(){
  var zSec = document.getElementById('cZine');
  var hdr  = document.getElementById('amd-header');
  if(!zSec || !hdr) return;
  var scroller = _pwa2 ? document.getElementById('deck') : window;

  ScrollTrigger.create({
    trigger: zSec,
    scroller: scroller,
    start: 'top 80%',
    end: 'bottom 20%',
    onEnter:     function(){ hdr.style.opacity='0'; hdr.style.pointerEvents='none'; },
    onLeave:     function(){ hdr.style.opacity=''; hdr.style.pointerEvents=''; },
    onEnterBack: function(){ hdr.style.opacity='0'; hdr.style.pointerEvents='none'; },
    onLeaveBack: function(){ hdr.style.opacity=''; hdr.style.pointerEvents=''; }
  });
})();

})();
</script>

<!-- iOS Safari tap fix + panel scroll isolation -->
<script>
document.addEventListener('DOMContentLoaded', function(){
  /* tap fix for artist groups */
  document.querySelectorAll('.amd-ap-group[data-group]').forEach(function(el){
    var moved = false;
    el.addEventListener('touchstart', function(){ moved = false; }, {passive:true});
    el.addEventListener('touchmove',  function(){ moved = true;  }, {passive:true});
    el.addEventListener('touchend', function(e){
      if(moved) return;
      e.preventDefault();
      var key = el.getAttribute('data-group');
      if(key && typeof openCardStack === 'function') openCardStack(key);
    }, {passive:false});
  });

  /* Prevent scroll chaining from panels to body/deck */
  var panelSelectors = '.amd-ticket-overlay, .amd-artist-panel, .amd-card-stack, .menu-overlay, #videoOverlay, #goodsOverlay';
  document.querySelectorAll(panelSelectors).forEach(function(panel){
    panel.addEventListener('touchmove', function(e){
      /* Only prevent if panel is open and scrollable content at boundary */
      if(!panel.classList.contains('open')) return;
      var scrollable = panel.querySelector('[style*="overflow-y:auto"], [style*="overflow-y: auto"]') || panel;
      var st = scrollable.scrollTop;
      var sh = scrollable.scrollHeight;
      var ch = scrollable.clientHeight;
      /* At top and scrolling up, or at bottom and scrolling down → block */
      if(sh <= ch) { e.preventDefault(); return; }
    }, {passive:false});
  });
});
</script>

<!-- PWA INSTALL BANNER -->
<div class="pwa-banner" id="pwaBanner">
  <div class="pwa-banner-inner">
    <div class="pwa-icon-wrap">
      <img src="<?= get_stylesheet_directory_uri() ?>/logos/amdheaderlogo.png" alt="">
    </div>
    <div class="pwa-text">
      <div class="pwa-title">FULLSCREEN MODE</div>
      <div class="pwa-sub">Add to Home Screen</div>
    </div>
    <div class="pwa-action">
      <button class="pwa-btn" onclick="openPwaGuide()">HOW</button>
      <button class="pwa-dismiss" onclick="dismissPwaBanner()" aria-label="Close">×</button>
    </div>
  </div>
</div>

<!-- PWA GUIDE MODAL -->
<div class="pwa-guide" id="pwaGuide">
  <button class="pwa-guide-close" onclick="closePwaGuide()">×</button>
  <div class="pwa-guide-content">
    <div class="pwa-guide-step">
      <div class="pwa-step-num">01</div>
      <div class="pwa-step-icon">⎋</div>
      <div class="pwa-step-text">画面下の<strong>共有ボタン（□↑）</strong>をタップ</div>
    </div>
    <div class="pwa-guide-step">
      <div class="pwa-step-num">02</div>
      <div class="pwa-step-icon">＋</div>
      <div class="pwa-step-text"><strong>「ホーム画面に追加」</strong>を選択</div>
    </div>
    <div class="pwa-guide-step">
      <div class="pwa-step-num">03</div>
      <div class="pwa-step-icon">◉</div>
      <div class="pwa-step-text">ホーム画面のアイコンから起動<br><strong>フルスクリーン — No Address Bar</strong></div>
    </div>
    <div class="pwa-guide-footer">ALL MUST DANCE™ · Fullscreen Experience</div>
  </div>
</div>

<script>
(function(){
  /* Only show on iOS Safari, not in standalone mode */
  var isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
  var isStandalone = window.navigator.standalone === true;
  var dismissed = localStorage.getItem('amd-pwa-dismiss');
  if(!isIOS || isStandalone || dismissed) return;

  /* Show banner after 4 seconds */
  setTimeout(function(){
    var b = document.getElementById('pwaBanner');
    if(b) b.classList.add('show');
  }, 4000);
})();

function openPwaGuide(){
  document.getElementById('pwaBanner').classList.remove('show');
  document.getElementById('pwaGuide').classList.add('open');
}
function closePwaGuide(){
  document.getElementById('pwaGuide').classList.remove('open');
}
function dismissPwaBanner(){
  document.getElementById('pwaBanner').classList.remove('show');
  localStorage.setItem('amd-pwa-dismiss','1');
}
</script>

</body>
</html>
