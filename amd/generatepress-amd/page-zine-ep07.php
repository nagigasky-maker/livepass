<?php
/**
 * ZINE EP.07 — HOME COMING
 * Template: page-amd-ep07.php
 * Slug: zine-ep07
 * Matches front-page.php spec: 100vh, safe-area, no header/address bar, PWA
 */
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="AMD™">
<meta name="mobile-web-app-capable" content="yes">
<meta name="theme-color" content="#0C0F1A">
<link rel="manifest" href="<?= get_stylesheet_directory_uri() ?>/manifest.json">
<title>ZINE EP.07 — HOME COMING | ALL MUST DANCE™</title>
<?php echo '<link rel="icon" href="' . get_stylesheet_directory_uri() . '/logos/amdheaderlogo.png">' . PHP_EOL; ?>
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
  --line:  rgba(237,235,230,0.09);
}
html { height:100%; }
body {
  overflow-x:hidden; overflow-y:auto; min-height:100vh; min-height:-webkit-fill-available;
  background:var(--black); color:var(--white);
  font-family:"Noto Sans JP","Montserrat",sans-serif;
  font-weight:300; font-feature-settings:"palt";
  -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;
}
#site-header,#site-footer,.site-header,.site-footer,.navigation-bar,#masthead { display:none !important; }
#page,#content,.site-content,#primary,main,article,.entry-content,.content-area { margin:0 !important; padding:0 !important; max-width:100% !important; display:block !important; width:100% !important; }
::-webkit-scrollbar { display:none; }

/* Back button */
.zine-back { position:fixed; top:max(16px,calc(env(safe-area-inset-top)+8px)); left:max(16px,env(safe-area-inset-left)); z-index:9999; display:flex; align-items:center; gap:6px; background:rgba(12,15,26,0.6); backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px); border:1px solid rgba(237,235,230,0.1); border-radius:24px; padding:8px 16px 8px 12px; text-decoration:none; color:rgba(237,235,230,0.6); font-size:11px; font-weight:400; letter-spacing:0.15em; transition:color .2s, border-color .2s; }
.zine-back:hover { color:var(--white); border-color:rgba(237,235,230,0.3); }
.zine-back svg { width:16px; height:16px; fill:currentColor; opacity:0.7; }

/* Hero cover */
.zine-hero { position:relative; width:100%; height:100vh; min-height:100vh; overflow:hidden; }
.zine-hero img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
.zine-hero-vig { position:absolute; inset:0; background:linear-gradient(to top, rgba(12,15,26,0.95) 0%, rgba(12,15,26,0.3) 50%, transparent 100%); }
.zine-hero-body { position:absolute; bottom:0; left:0; right:0; z-index:2; padding:0 32px max(40px,calc(env(safe-area-inset-bottom)+24px)); }
.zine-hero-ep { font-size:9px; font-weight:600; letter-spacing:0.5em; text-transform:uppercase; color:var(--red); margin-bottom:10px; }
.zine-hero-title { font-family:Arial,"Arial Black",sans-serif; font-size:clamp(36px,10vw,64px); font-weight:900; line-height:0.9; letter-spacing:-0.02em; color:var(--white); margin-bottom:12px; }
.zine-hero-meta { font-size:10px; font-weight:300; letter-spacing:0.2em; color:rgba(237,235,230,0.45); }

/* Article body */
.zine-article { padding:48px 32px max(60px,calc(env(safe-area-inset-bottom)+40px)); max-width:640px; }
.zine-section-label { font-size:10px; font-weight:600; letter-spacing:0.4em; text-transform:uppercase; color:var(--red); margin:40px 0 12px; opacity:0.9; }
.zine-section-label:first-child { margin-top:0; }
.zine-body p { font-size:14px; font-weight:300; line-height:2.1; color:rgba(237,235,230,0.85); margin-bottom:16px; }
.zine-body p em { font-style:italic; opacity:0.75; }
.zine-lead { font-size:15px; font-weight:300; font-style:italic; color:rgba(237,235,230,0.88); margin-bottom:20px; line-height:1.8; }
.zine-photo { width:100%; margin:24px 0; border-radius:8px; overflow:hidden; }
.zine-photo img { width:100%; display:block; opacity:0.9; }
.zine-photo-grid { display:grid; grid-template-columns:1fr 1fr; gap:4px; margin:24px 0; }
.zine-photo-grid img { width:100%; aspect-ratio:1/1; object-fit:cover; opacity:0.9; border-radius:4px; }
.zine-music-list { margin:16px 0; }
.zine-music-row { font-size:12px; font-weight:300; letter-spacing:0.06em; color:rgba(237,235,230,0.72); padding:8px 0; border-bottom:1px solid rgba(237,235,230,0.07); }
.zine-credit-block { margin-top:32px; border-top:1px solid rgba(237,235,230,0.1); padding-top:20px; }
.zine-credit-row { display:flex; justify-content:space-between; align-items:baseline; padding:9px 0; border-bottom:1px solid rgba(237,235,230,0.06); gap:16px; }
.zine-credit-row span:first-child { font-size:10px; font-weight:500; letter-spacing:0.32em; text-transform:uppercase; color:var(--red); opacity:0.85; white-space:nowrap; flex-shrink:0; }
.zine-credit-row span:last-child { font-size:13px; font-weight:300; color:rgba(237,235,230,0.78); text-align:right; }

/* Divider */
.zine-divider { height:1px; background:linear-gradient(to right, var(--red), rgba(237,235,230,0.08) 60%, transparent); margin:40px 0; }

/* Footer nav */
.zine-footer-nav { display:flex; justify-content:space-between; align-items:center; padding:24px 32px max(40px,calc(env(safe-area-inset-bottom)+24px)); border-top:1px solid rgba(237,235,230,0.08); }
.zine-footer-nav a { font-size:10px; font-weight:400; letter-spacing:0.25em; text-transform:uppercase; color:rgba(237,235,230,0.4); text-decoration:none; transition:color .2s; }
.zine-footer-nav a:hover { color:var(--red); }

@media (max-width:860px) {
  .zine-hero-body { padding:0 24px max(32px,calc(env(safe-area-inset-bottom)+20px)); }
  .zine-article { padding:36px 24px max(48px,calc(env(safe-area-inset-bottom)+32px)); }
}
</style>
<?php wp_head(); ?>
</head>
<body class="amd-front amd-zine-page">

<!-- Back button -->
<a href="<?= home_url() ?>" class="zine-back">
  <svg viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
  AMD™
</a>

<!-- Hero cover -->
<div class="zine-hero">
  <img src="<?= get_stylesheet_directory_uri() ?>/logos/amd2026asia.jpg" alt="HOME COMING">
  <div class="zine-hero-vig"></div>
  <div class="zine-hero-body">
    <div class="zine-hero-ep">EP.07</div>
    <div class="zine-hero-title">HOME<br>COMING</div>
    <div class="zine-hero-meta">clubasia · Shibuya · 2026.05.04 · ALL MUST DANCE™</div>
  </div>
</div>

<!-- Article content -->
<div class="zine-article zine-body">

  <div class="zine-section-label">ABOUT</div>
  <p class="zine-lead">EP.05でPARCOの屋上へ飛び出し、EP.06でCheekyで実験し——AMD™はclubasia（ホームグラウンド）に戻ってくる。</p>
  <p>これは帰還であり、次の旅への出発点だ。ALL MUST DANCE™ EP.07 "HOME COMING" — 2026年5月4日、clubasia渋谷。</p>

  <div class="zine-divider"></div>

  <div class="zine-section-label">INFO</div>
  <div class="zine-credit-block" style="margin-top:0;border-top:none;padding-top:0;">
    <div class="zine-credit-row"><span>Date</span><span>2026.05.04 (Mon/Holiday)</span></div>
    <div class="zine-credit-row"><span>Time</span><span>OPEN 23:00 — CLOSE 05:00</span></div>
    <div class="zine-credit-row"><span>Venue</span><span>clubasia · Maruyamacho, Shibuya</span></div>
    <div class="zine-credit-row"><span>Early Bird</span><span>¥2,500</span></div>
    <div class="zine-credit-row"><span>Advance</span><span>¥3,500</span></div>
    <div class="zine-credit-row"><span>Door</span><span>¥4,500</span></div>
  </div>

  <?php
  while(have_posts()): the_post();
    the_content();
  endwhile;
  ?>

  <div class="zine-divider"></div>

  <div class="zine-credit-block">
    <div class="zine-credit-row"><span>Presented by</span><span>ALL MUST DANCE™</span></div>
    <div class="zine-credit-row"><span>Design</span><span>Space Cooking™</span></div>
  </div>

</div>

<!-- Footer navigation -->
<div class="zine-footer-nav">
  <a href="<?= home_url() ?>">← HOME</a>
  <a href="<?= home_url('/zine-ep06/') ?>">EP.06 →</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
