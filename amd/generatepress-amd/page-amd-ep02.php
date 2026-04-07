<?php /* Template Name: ZINE EP.02 */ ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ALL MUST DANCE ZINE EP.02 — WARSAW</title>
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
.zp-next-issues { margin-top:48px; border-top:1px solid rgba(237,235,230,0.1); padding-top:32px; }
.zp-next-label { font-size:10px;font-weight:500;letter-spacing:0.42em;text-transform:uppercase;color:var(--red);margin-bottom:20px; }
.zp-next-grid { display:flex; flex-direction:column; gap:0; }
.zp-next-card { display:flex;align-items:center;gap:16px;padding:18px 0;border-bottom:1px solid rgba(237,235,230,0.08);text-decoration:none;color:var(--white);transition:opacity 0.2s; }
.zp-next-card:hover { opacity:0.75; }
.zp-next-card img { width:56px;height:68px;object-fit:cover;flex-shrink:0; }
.zp-next-card-body { flex:1; }
.zp-next-ep { font-size:9px;letter-spacing:0.38em;color:var(--red);margin-bottom:4px; }
.zp-next-title { font-family:Arial,'Arial Black',sans-serif;font-size:16px;font-weight:900;line-height:1; }
.zp-next-arr { color:rgba(237,235,230,0.3);font-size:16px;flex-shrink:0; }
</style>
</head>
<body>
<div class="zp-header">
  <a class="zp-logo" href="<?= home_url('/') ?>"><img src="<?= get_stylesheet_directory_uri() ?>/logos/amdheaderlogo.png" alt="ALL MUST DANCE™"></a>
  <span class="zp-issue">EP · 02 — PARTY · NIGHT · PEOPLE</span>
  <a class="zp-back" href="<?= home_url('/zine-index/') ?>">← ZINE</a>
</div>

<div class="zp-hero">
  <div class="zp-hero-bg">
    <img src="https://allmustdance.com/wp-content/uploads/2026/01/IMG_6289-scaled-e1769346275211.jpeg" alt="EP.02 Warsaw" loading="eager">
  </div>
  <div class="zp-hero-overlay"></div>
  <div class="zp-hero-content">
    <div class="zp-ep-label">ALL MUST DANCE™ ZINE — EP · 02 · PARTY</div>
    <div class="zp-title">WARSAW</div>
    <div class="zp-date">2024 · Berlin → Warsaw, Poland</div>
  </div>
</div>

<div class="zp-body">

  <p class="zp-lead fade-in" style="font-style:italic;">war / saw — a city that has seen war.<br>This was not a tour. It was a test.</p>

  <p class="fade-in">Not a slogan. Not a party.<br>But a way of facing reality.</p>

  <p class="fade-in">これはツアーではなかった。テストだった。スローガンでも、パーティーでもない。現実と向き合うための言葉だった。</p>

  <div class="zp-section fade-in">WITH RESPECT</div>
  <p class="fade-in">CZUŁOŚĆ の赤木楠平がプランニングしてくれたからこそ、いわゆる"ストリートダンスシーン"とは違う角度でヨーロッパの文化と現実を受け取れた。ALL MUST DANCE™ を共に立ち上げ支えてくれている彼に最大限のリスペクトを。</p>

  <div class="zp-section fade-in">BERLIN</div>
  <img src="https://allmustdance.com/wp-content/uploads/2026/01/IMG_6210-scaled-e1769346206647.jpeg" alt="Berlin" loading="lazy" class="fade-in">
  <p class="fade-in">Reality arrived first. Before rhythm. Before movement.</p>
  <p class="fade-in">ベルリンでは、リズムより先に現実がやってきた。ウクライナから亡命してきたメンバーたちと過ごし、"すべてが狂っている"という言葉が身体に落ちてきた。</p>

  <p class="fade-in" style="font-style:italic;opacity:0.6;">TRANSIT / BERLIN → POLAND<br>The city changed. The temperature changed. The silence deepened.</p>
  <p class="fade-in">都市が変わり、温度が変わり、沈黙が深くなった。</p>

  <div class="zp-section fade-in">POLAND</div>
  <img src="https://allmustdance.com/wp-content/uploads/2026/01/IMG_6550-scaled-e1769411117784.jpeg" alt="Poland" loading="lazy" class="fade-in">
  <p class="fade-in">Nature is not gentle. It is honest.</p>
  <p class="fade-in">合宿所の自然はどこか日本にも似ていた。でもそこには美しさだけでなく厳しさがあって、だからこそ作品のドープさが理解できた。</p>

  <div class="zp-section fade-in">WARSAW</div>
  <img src="https://allmustdance.com/wp-content/uploads/2026/01/photo-output-1-e1769346616331.png" alt="Warsaw stage" loading="lazy" class="fade-in">
  <p class="fade-in">旅の途中、CZUŁOŚĆ のリーダーであるヤネックが、何気なく言った。</p>
  <p class="fade-in" style="font-style:italic;">"Let's do <strong>ALL MUST DANCE™</strong> in Warsaw."</p>
  <p class="fade-in">それは提案ではなかった。もう、決まっていることのようだった。</p>
  <p class="fade-in">その瞬間、ALL MUST DANCE™ は僕たちの言葉ではなくなった。彼らの言葉になった。</p>
  <p class="fade-in" style="font-style:italic;opacity:0.7;"><strong>WARSAW.</strong> Destroyed. Rebuilt. Destroyed again. Rebuilt again.<br>ALL MUST DANCE™ was not performed here. It was proven.</p>

  <div class="zp-section fade-in">MUSIC WE SHARED</div>
  <div style="margin:16px 0;" class="fade-in">
    <div style="padding:12px 0;border-bottom:1px solid rgba(237,235,230,0.08);font-size:13px;">Everybody Loves the Sunshine — Roy Ayers</div>
    <div style="padding:12px 0;border-bottom:1px solid rgba(237,235,230,0.08);font-size:13px;">Footprints on the Moon — Jackie McLean</div>
    <div style="padding:12px 0;border-bottom:1px solid rgba(237,235,230,0.08);font-size:13px;">Why Can't We Live Together — Timmy Thomas</div>
    <div style="padding:12px 0;border-bottom:1px solid rgba(237,235,230,0.08);font-size:13px;">Loud Minority — United Future Organization</div>
  </div>
  <p class="fade-in" style="font-size:11px;opacity:0.45;font-style:italic;">We listened before moving. / 踊る前に、聴いた。</p>

  <div class="zp-credits fade-in">
    <div class="zp-credit-row"><span>ROUTE</span><span>Berlin → Warsaw, Poland</span></div>
    <div class="zp-credit-row"><span>YEAR</span><span>2024</span></div>
    <div class="zp-credit-row"><span>PLANNING</span><span>CZUŁOŚĆ / 赤木楠平</span></div>
  </div>

  <div class="zp-next-issues">
    <div class="zp-next-label">Other Issues</div>
    <div class="zp-next-grid">
      <a class="zp-next-card" href="<?= home_url('/zine-ep07/') ?>">
        <img src="<?= get_stylesheet_directory_uri() ?>/logos/mozyskeyxnobby.jpg" alt="ARTWORK.01" loading="lazy">
        <div class="zp-next-card-body">
          <div class="zp-next-ep">ARTWORK · 01 — ART · INSTALLATION</div>
          <div class="zp-next-title">MOZYSKEY × NOBBY</div>
        </div>
        <div class="zp-next-arr">→</div>
      </a>
      <a class="zp-next-card" href="<?= home_url('/zine-ep06/') ?>">
        <img src="https://allmustdance.com/wp-content/uploads/2026/01/031.jpeg" alt="EP.06" loading="lazy">
        <div class="zp-next-card-body">
          <div class="zp-next-ep">EP · 06 — PARTY</div>
          <div class="zp-next-title">TOKYO CHEEKY</div>
        </div>
        <div class="zp-next-arr">→</div>
      </a>
      <a class="zp-next-card" href="<?= home_url('/zine-ep05/') ?>">
        <img src="https://allmustdance.com/wp-content/uploads/2026/01/DSC5526.jpg" alt="EP.05" loading="lazy">
        <div class="zp-next-card-body">
          <div class="zp-next-ep">EP · 05 — PARTY</div>
          <div class="zp-next-title">ARTWORK WEAR EXHIBITION</div>
        </div>
        <div class="zp-next-arr">→</div>
      </a>
    </div>
  </div>

</div>

<div class="zp-footer">
  <a href="<?= home_url('/zine-index/') ?>">← ZINE INDEX</a> · © 2026 ALL MUST DANCE™
</div>

<script>
const io = new IntersectionObserver(entries => {
  entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('visible'); io.unobserve(e.target); } });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-in').forEach((el,i) => {
  el.style.transitionDelay = (i % 4 * 0.08) + 's';
  io.observe(el);
});
</script>
</body>
</html>
