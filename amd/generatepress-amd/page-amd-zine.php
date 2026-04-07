<?php /* Template Name: ZINE INDEX */ ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ALL MUST DANCE ZINE — Issue Archive</title>
<link rel="icon" href="<?= get_stylesheet_directory_uri() ?>/logos/amdheaderlogo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{--black:#0C0F1A;--white:#EDEBE6;--red:#E8100A;--line:rgba(237,235,230,0.09)}
html{scroll-behavior:smooth}
body{background:var(--black);color:var(--white);font-family:'Noto Sans JP','Montserrat',sans-serif;font-weight:300;-webkit-font-smoothing:antialiased;min-height:100vh}
#site-header,#site-footer,.site-header,.site-footer,.navigation-bar,#masthead{display:none!important}
#page,#content,.site-content,#primary,main{margin:0!important;padding:0!important;max-width:100%!important}

/* HEADER */
.zp-header{position:fixed;top:0;left:0;right:0;z-index:999;display:flex;align-items:center;justify-content:space-between;padding:18px 28px;background:rgba(12,15,26,0.92);border-bottom:1px solid rgba(237,235,230,0.06)}
.zp-logo img{height:22px;width:auto;display:block;mix-blend-mode:screen}
.zp-back{font-size:11px;font-weight:400;letter-spacing:0.28em;text-transform:uppercase;color:var(--white);text-decoration:none;opacity:0.6;transition:opacity 0.2s,color 0.2s}
.zp-back:hover{opacity:1;color:var(--red)}
.zp-issue{font-size:9px;font-weight:500;letter-spacing:0.2em;text-transform:uppercase;color:var(--red);opacity:0.8;max-width:45vw;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;text-align:center}

/* HERO */
.zp-hero{position:relative;height:70svh;overflow:hidden;display:flex;align-items:flex-end}
.zp-hero-bg{position:absolute;inset:0;z-index:0}
.zp-hero-bg img{width:100%;height:100%;object-fit:cover;opacity:0.55}
.zp-hero-overlay{position:absolute;inset:0;z-index:1;background:linear-gradient(to top,rgba(12,15,26,1) 0%,rgba(12,15,26,0.5) 50%,transparent 100%)}
.zp-hero-content{position:relative;z-index:2;padding:40px 28px 36px}
.zp-ep-label{font-size:10px;font-weight:500;letter-spacing:0.42em;text-transform:uppercase;color:var(--red);margin-bottom:10px;opacity:0.9}
.zp-title{font-family:Arial,'Arial Black',sans-serif;font-size:clamp(36px,8vw,64px);font-weight:900;line-height:0.92;color:var(--white);margin-bottom:12px}
.zp-date{font-size:12px;font-weight:300;letter-spacing:0.18em;color:rgba(237,235,230,0.55)}

/* CONTENT */
.zp-body{max-width:720px;margin:0 auto;padding:48px 28px 100px}
.zp-lead{font-size:16px;font-weight:300;font-style:italic;color:rgba(237,235,230,0.88);line-height:1.75;margin-bottom:28px;padding-bottom:28px;border-bottom:1px solid rgba(237,235,230,0.1)}
.zp-body p{font-size:15px;font-weight:300;line-height:1.9;color:rgba(237,235,230,0.82);margin-bottom:18px}
.zp-section{font-size:10px;font-weight:600;letter-spacing:0.42em;text-transform:uppercase;color:var(--red);opacity:0.9;margin:32px 0 12px}
.zp-body img{width:100%;height:auto;display:block;margin:12px 0;opacity:0.92}
.zp-photo-grid{display:grid;grid-template-columns:1fr 1fr;gap:4px;margin:12px 0}
.zp-photo-grid img{width:100%;aspect-ratio:1/1;object-fit:cover;margin:0}
.zp-credits{margin-top:40px;border-top:1px solid rgba(237,235,230,0.1);padding-top:24px}
.zp-credit-row{display:flex;justify-content:space-between;align-items:baseline;padding:9px 0;border-bottom:1px solid rgba(237,235,230,0.06);gap:16px}
.zp-credit-row span:first-child{font-size:10px;font-weight:500;letter-spacing:0.32em;text-transform:uppercase;color:var(--red);opacity:0.85;white-space:nowrap;flex-shrink:0}
.zp-credit-row span:last-child{font-size:13px;font-weight:300;color:rgba(237,235,230,0.78);text-align:right}
.zp-music-list{margin:10px 0}
.zp-music-row{font-size:13px;font-weight:300;color:rgba(237,235,230,0.72);padding:8px 0;border-bottom:1px solid rgba(237,235,230,0.07)}

/* FOOTER */
.zp-footer{text-align:center;padding:24px;font-size:10px;letter-spacing:0.2em;color:rgba(237,235,230,0.25);border-top:1px solid rgba(237,235,230,0.06)}
.zp-footer a{color:rgba(237,235,230,0.4);text-decoration:none}

/* ANIM */
.fade-in{opacity:0;transform:translateY(20px);transition:opacity 0.65s cubic-bezier(0.22,1,0.36,1),transform 0.65s cubic-bezier(0.22,1,0.36,1)}
.fade-in.visible{opacity:1;transform:translateY(0)}

@media(max-width:600px){
  .zp-header{padding:14px 18px}
  .zp-hero-content{padding:28px 18px 24px}
  .zp-body{padding:32px 18px 80px}
}
</style>
<style>
.zi-index { display:flex; flex-direction:column; gap:0; margin-top:40px; }
.zi-card {
  display:flex; align-items:stretch;
  text-decoration:none; color:var(--white);
  border-bottom:1px solid rgba(237,235,230,0.1);
  padding:24px 0;
  transition:background 0.2s;
  gap:20px;
}
.zi-card:first-child { border-top:1px solid rgba(237,235,230,0.1); }
.zi-card:hover { background:rgba(237,235,230,0.03); }
.zi-card-img {
  width:90px; height:110px;
  object-fit:cover; flex-shrink:0;
  background:rgba(237,235,230,0.05);
}
.zi-card-body { flex:1; display:flex; flex-direction:column; justify-content:center; gap:6px; }
.zi-card-ep { font-size:10px; font-weight:500; letter-spacing:0.42em; text-transform:uppercase; color:var(--red); }
.zi-card-title { font-family:Arial,'Arial Black',sans-serif; font-size:clamp(18px,5vw,28px); font-weight:900; line-height:1; }
.zi-card-date { font-size:11px; font-weight:300; letter-spacing:0.18em; color:rgba(237,235,230,0.45); }
.zi-card-desc { font-size:13px; font-weight:300; color:rgba(237,235,230,0.65); line-height:1.6; margin-top:4px; }
.zi-card-arr { font-size:18px; color:rgba(237,235,230,0.3); align-self:center; flex-shrink:0; transition:color 0.2s; }
.zi-card:hover .zi-card-arr { color:var(--red); }
.zi-coming { opacity:0.35; pointer-events:none; }
/* ロック済みカード（解禁待ち） */
.zi-locked {
  opacity:0.5;
  pointer-events:none;
  cursor:default;
}
.zi-card-lock-img {
  width:90px; height:110px;
  background:rgba(237,235,230,0.04);
  border:1px solid rgba(237,235,230,0.08);
  flex-shrink:0;
  display:flex; align-items:center; justify-content:center;
}
.zi-lock-icon {
  font-size:22px;
  color:rgba(237,235,230,0.12);
  letter-spacing:0.05em;
}
.zi-card-tease {
  color:var(--red) !important;
  opacity:0.6;
  font-size:11px !important;
  letter-spacing:0.22em;
  text-transform:uppercase;
}
</style>
</head>
<body>
<div class="zp-header">
  <a class="zp-logo" href="<?= home_url('/') ?>"><img src="<?= get_stylesheet_directory_uri() ?>/logos/amdheaderlogo.png" alt="ALL MUST DANCE™"></a>
  <span class="zp-issue">ZINE</span>
  <a class="zp-back" href="<?= home_url('/') ?>">← Back</a>
</div>

<div style="padding:100px 28px 60px;">
  <div style="font-size:10px;font-weight:500;letter-spacing:0.42em;text-transform:uppercase;color:var(--red);margin-bottom:12px;">Issue Archive</div>
  <img src="<?= get_stylesheet_directory_uri() ?>/logos/zine.png" alt="ZINE" style="max-height:64px;width:auto;display:block;margin-bottom:16px;">
  <p style="margin-top:20px;font-size:13px;font-weight:300;color:rgba(237,235,230,0.55);line-height:1.9;">ALL MUST DANCE™の記録。パーティーの夜、旅、展示——すべてが一冊になる。</p>

  <div class="zi-index">

    <!-- EP.07 · Coming Soon (今回のパーティー) -->
    <div class="zi-card zi-coming">
      <div style="width:90px;height:110px;background:rgba(237,235,230,0.05);flex-shrink:0;display:flex;align-items:center;justify-content:center;">
        <span style="font-size:10px;letter-spacing:0.2em;opacity:0.4;">SOON</span>
      </div>
      <div class="zi-card-body">
        <div class="zi-card-ep">EP · 07 — PARTY</div>
        <div class="zi-card-title">ALL MUST DANCE<br>clubasia</div>
        <div class="zi-card-date">2026.5.4 · clubasia, Shibuya</div>
        <div class="zi-card-desc">Coming Soon</div>
      </div>
      <div class="zi-card-arr">→</div>
    </div>

    <!-- ARTWORK.01 · MOZYSKEY×NOBBY -->
    <a class="zi-card fade-in" href="<?= home_url('/zine-ep07/') ?>">
      <img class="zi-card-img" src="<?= get_stylesheet_directory_uri() ?>/logos/mozyskeyxnobby.jpg" alt="ARTWORK.01" loading="lazy">
      <div class="zi-card-body">
        <div class="zi-card-ep">ARTWORK · 01 — ART · INSTALLATION · SHADOW</div>
        <div class="zi-card-title">MOZYSKEY<br>× NOBBY</div>
        <div class="zi-card-date">光と影の対話 · Creative Experiment</div>
        <div class="zi-card-desc">mozyskeyと僕の呼吸が混ざり合い、光と影の奥行きはさらに深く。</div>
      </div>
      <div class="zi-card-arr">→</div>
    </a>

    <!-- EP.06 -->
    <a class="zi-card fade-in" href="<?= home_url('/zine-ep06/') ?>">
      <img class="zi-card-img" src="https://allmustdance.com/wp-content/uploads/2026/01/031.jpeg" alt="EP.06" loading="lazy">
      <div class="zi-card-body">
        <div class="zi-card-ep">EP · 06 — PARTY</div>
        <div class="zi-card-title">TOKYO<br>CHEEKY</div>
        <div class="zi-card-date">2025.12.30 · Cheeky, Tokyo</div>
        <div class="zi-card-desc">Where all experiments converge, and something new quietly begins.</div>
      </div>
      <div class="zi-card-arr">→</div>
    </a>

    <!-- EP.05 -->
    <a class="zi-card fade-in" href="<?= home_url('/zine-ep05/') ?>">
      <img class="zi-card-img" src="https://allmustdance.com/wp-content/uploads/2026/01/DSC5526.jpg" alt="EP.05" loading="lazy">
      <div class="zi-card-body">
        <div class="zi-card-ep">EP · 05 — PARTY</div>
        <div class="zi-card-title">ARTWORK WEAR<br>EXHIBITION</div>
        <div class="zi-card-date">2024.11 · PARCO Shibuya</div>
      </div>
      <div class="zi-card-arr">→</div>
    </a>

    <!-- EP.04 · Coming Soon (矢部直追悼) -->
    <div class="zi-card zi-locked fade-in">
      <div class="zi-card-lock-img">
        <span class="zi-lock-icon">▓</span>
      </div>
      <div class="zi-card-body">
        <div class="zi-card-ep">EP · 04 — PARTY · NIGHT · PEOPLE</div>
        <div class="zi-card-title">——</div>
        <div class="zi-card-date">clubasia, Shibuya</div>
        <div class="zi-card-desc zi-card-tease">MAY 4以前に解禁</div>
      </div>
      <div class="zi-card-arr">▓</div>
    </div>

    <!-- EP.03 · Coming Soon (矢部直 最後のプレイ) -->
    <div class="zi-card zi-locked fade-in">
      <img class="zi-card-img" src="<?= get_stylesheet_directory_uri() ?>/logos/yabe.gif" alt="EP.03" loading="lazy" style="opacity:0.5;filter:grayscale(1);">
      <div class="zi-card-body">
        <div class="zi-card-ep">EP · 03 — PARTY · NIGHT · PEOPLE</div>
        <div class="zi-card-title">——</div>
        <div class="zi-card-date">clubasia, Shibuya</div>
        <div class="zi-card-desc zi-card-tease">MAY 4以前に解禁</div>
      </div>
      <div class="zi-card-arr">▓</div>
    </div>

    <!-- EP.02 -->
    <a class="zi-card fade-in" href="<?= home_url('/zine-ep02/') ?>">
      <img class="zi-card-img" src="https://allmustdance.com/wp-content/uploads/2026/01/IMG_6289-scaled-e1769346275211.jpeg" alt="EP.02" loading="lazy">
      <div class="zi-card-body">
        <div class="zi-card-ep">EP · 02 — PARTY</div>
        <div class="zi-card-title">WARSAW</div>
        <div class="zi-card-date">2024 · Berlin → Poland</div>
      </div>
      <div class="zi-card-arr">→</div>
    </a>

    <!-- EP.01 · Coming Soon -->
    <div class="zi-card zi-locked fade-in">
      <div class="zi-card-lock-img">
        <span class="zi-lock-icon">▓</span>
      </div>
      <div class="zi-card-body">
        <div class="zi-card-ep">EP · 01 — PARTY · NIGHT · PEOPLE</div>
        <div class="zi-card-title">——</div>
        <div class="zi-card-date">clubasia, Shibuya</div>
        <div class="zi-card-desc zi-card-tease">MAY 4以前に解禁</div>
      </div>
      <div class="zi-card-arr">▓</div>
    </div>

  </div>
</div>

<div class="zp-footer"><a href="<?= home_url('/') ?>">← ALL MUST DANCE™</a> · © 2026 ALL MUST DANCE™</div>

<script>
const io = new IntersectionObserver(entries => {
  entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('visible'); io.unobserve(e.target); } });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-in').forEach((el,i) => { el.style.transitionDelay = (i*0.1)+'s'; io.observe(el); });
</script>
</body>
</html>
