<?php /* Template Name: ARTWORK.01 · MOZYSKEY × NOBBY */ ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ALL MUST DANCE™ ZINE · ARTWORK.01 — MOZYSKEY × NOBBY</title>
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
.zp-header{position:fixed;top:0;left:0;right:0;z-index:999;display:flex;align-items:center;justify-content:space-between;padding:18px 28px;background:rgba(12,15,26,0.92);border-bottom:1px solid rgba(237,235,230,0.06);position:fixed;}
.zp-logo img{height:22px;width:auto;display:block;mix-blend-mode:screen}
.zp-back{font-size:11px;font-weight:400;letter-spacing:0.28em;text-transform:uppercase;color:var(--white);text-decoration:none;opacity:0.6;transition:opacity 0.2s,color 0.2s}
.zp-back:hover{opacity:1;color:var(--red)}
.zp-issue{font-size:10px;font-weight:500;letter-spacing:0.38em;text-transform:uppercase;color:var(--red);opacity:0.8;position:absolute;left:50%;transform:translateX(-50%);white-space:nowrap;max-width:60vw;overflow:hidden;text-overflow:ellipsis;text-align:center}

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
/* Instagram embed */
.ig-wrap {
  margin: 24px 0;
  display: flex;
  justify-content: center;
}
.ig-wrap blockquote.instagram-media {
  margin: 0 auto !important;
  min-width: 0 !important;
  max-width: 100% !important;
  width: 100% !important;
  border-radius: 8px !important;
}
/* YouTube placeholder */
.yt-placeholder {
  width: 100%;
  aspect-ratio: 9/16;
  background: rgba(237,235,230,0.05);
  border: 1px solid rgba(237,235,230,0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  gap: 12px;
  color: rgba(237,235,230,0.4);
  font-size: 12px;
  letter-spacing: 0.2em;
  border-radius: 4px;
  margin: 20px 0;
}
.yt-placeholder span { font-size: 32px; }
/* next issues */
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
  <span class="zp-issue">ARTWORK · 01 — ART · INSTALLATION · SHADOW</span>
  <a class="zp-back" href="<?= home_url('/zine-index/') ?>">← ZINE</a>
</div>

<div class="zp-hero">
  <div class="zp-hero-bg">
    <img src="<?= get_stylesheet_directory_uri() ?>/logos/mozyskeyxnobby.jpg" alt="MOZYSKEY × NOBBY" loading="eager" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.85;">
  </div>
  <div class="zp-hero-overlay"></div>
  <div class="zp-hero-content">
    <div class="zp-ep-label">ALL MUST DANCE™ ZINE — ARTWORK · 01</div>
    <div class="zp-title">MOZYSKEY<br>× NOBBY</div>
    <div class="zp-date">光と影の対話 · Creative Experiment · 伊勢丹新宿店</div>
    <div style="margin-top:12px;display:inline-block;font-size:10px;letter-spacing:0.32em;text-transform:uppercase;color:rgba(237,235,230,0.5);border:1px solid rgba(237,235,230,0.25);padding:4px 10px;">Past Exhibition · Archive</div>
  </div>
</div>

<div class="zp-body">

  <p class="zp-lead fade-in">mozyskeyと僕の呼吸が混ざり合い<br>光と影の奥行きはさらに深く、さらに自由になった——。</p>

  <div class="zp-section fade-in">TRACE : 3</div>

  <p class="fade-in">ALL MUST DANCE™の実験は、既存のフォーマットを解体しながら、新たな手法をいくつも編み出してきた。その過程で生まれたのが、光と影を彫刻のように扱うMOZYSKEYとNOBBY（ALL MUST DANCE™）による映像作品である。</p>

  <p class="fade-in">空間に差し込む光、そこに生まれる影。それらをただの演出ではなく"素材"として再構築したその映像には、エネルギーと発見がそのまま封じ込められている。</p>

  <p class="fade-in">この作品は、伊勢丹新宿店のショーウインドウにて、約1ヶ月にわたり大画面で公開された。都市の中を行き交う人々の視線の中で、日常のリズムにわずかな歪みを与えるように。</p>

  <p class="fade-in">そのきっかけとなったのは、B GALLERYで開催された展覧会「TRACE:3」。同展示のプロモーションとして、ウインドウという"街に開かれたメディア"を通じて、この映像は新たな文脈を獲得した。</p>

  <p class="fade-in" style="font-style:italic;opacity:0.75;border-left:2px solid var(--red);padding-left:16px;margin:24px 0;">実験は、場所を変え、かたちを変えながら、都市の中へと滲み出していく。</p>

  <div class="zp-section fade-in" style="margin-top:40px;">EN</div>

  <p class="fade-in" style="opacity:0.72;">The experiments of ALL MUST DANCE™ have continuously deconstructed existing formats, generating new methods along the way. Out of this process emerged a video work by MOZYSKEY&NOBBY, where light and shadow are treated as sculptural elements.</p>

  <p class="fade-in" style="opacity:0.72;">Light cutting through space, shadows taking form— not as mere effects, but as raw material, reassembled into a new visual language. The energy and discoveries from the process are captured intact within the piece.</p>

  <p class="fade-in" style="opacity:0.72;">The work was presented on a large-scale screen at the show window of Isetan Shinjuku for approximately one month, subtly disrupting the rhythm of everyday city life.</p>

  <p class="fade-in" style="opacity:0.72;">The opportunity originated from the exhibition "TRACE:3" held at B GALLERY. As part of its promotion, the piece expanded beyond the gallery, inhabiting the storefront window—an open medium within the city.</p>

  <p class="fade-in" style="font-style:italic;opacity:0.55;border-left:2px solid rgba(237,235,230,0.2);padding-left:16px;margin:24px 0;">The experiment shifts form, relocates, and gradually seeps into the urban landscape.</p>

  <div class="zp-section fade-in">映像作品</div>

  <!-- YouTube 本編 -->
  <div class="fade-in" style="position:relative;width:100%;aspect-ratio:16/9;margin:20px 0;background:#000;">
    <iframe
      src="https://www.youtube.com/embed/XGUnLE2PVoM?rel=0&playsinline=1"
      style="position:absolute;inset:0;width:100%;height:100%;border:none;"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
      allowfullscreen>
    </iframe>
  </div>

  <div class="zp-section fade-in">INSTAGRAM</div>
  <p class="fade-in" style="font-size:12px;opacity:0.55;margin-bottom:16px;">NOBBY @fnkmdns より</p>

  <!-- Instagram Reel - NOBBY -->
  <div class="ig-wrap fade-in">
    <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/reel/DN0mP8JQqB-/?utm_source=ig_embed&utm_campaign=loading" data-instgrm-version="14" style="background:#FFF;border:0;border-radius:3px;box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15);margin:1px;max-width:540px;min-width:326px;padding:0;width:99.375%;width:calc(100% - 2px);">
      <div style="padding:16px;">
        <a href="https://www.instagram.com/reel/DN0mP8JQqB-/?utm_source=ig_embed&utm_campaign=loading" style="background:#FFFFFF;line-height:0;padding:0;text-align:center;text-decoration:none;width:100%;" target="_blank">
          <div style="padding:19% 0;"></div>
          <div style="padding-top:8px;color:#3897f0;font-family:Arial,sans-serif;font-size:14px;font-weight:550;line-height:18px;">この投稿をInstagramで見る</div>
        </a>
        <p style="color:#c9c8cd;font-family:Arial,sans-serif;font-size:14px;line-height:17px;margin-bottom:0;margin-top:8px;overflow:hidden;padding:8px 0 7px;text-align:center;text-overflow:ellipsis;white-space:nowrap;">
          <a href="https://www.instagram.com/reel/DN0mP8JQqB-/?utm_source=ig_embed&utm_campaign=loading" style="color:#c9c8cd;font-family:Arial,sans-serif;font-size:14px;font-style:normal;font-weight:normal;line-height:17px;text-decoration:none;" target="_blank">NOBBY(@fnkmdns)がシェアした投稿</a>
        </p>
      </div>
    </blockquote>
  </div>

  <p class="fade-in" style="font-size:12px;opacity:0.55;margin:24px 0 16px;">MOZYSKEY @mozyskey_ より</p>

  <!-- Instagram Post - MOZYSKEY -->
  <div class="ig-wrap fade-in">
    <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DMPomBdSrHw/?utm_source=ig_embed&utm_campaign=loading" data-instgrm-version="14" style="background:#FFF;border:0;border-radius:3px;box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15);margin:1px;max-width:540px;min-width:326px;padding:0;width:99.375%;width:calc(100% - 2px);">
      <div style="padding:16px;">
        <a href="https://www.instagram.com/p/DMPomBdSrHw/?utm_source=ig_embed&utm_campaign=loading" style="background:#FFFFFF;line-height:0;padding:0;text-align:center;text-decoration:none;width:100%;" target="_blank">
          <div style="padding:19% 0;"></div>
          <div style="padding-top:8px;color:#3897f0;font-family:Arial,sans-serif;font-size:14px;font-weight:550;line-height:18px;">この投稿をInstagramで見る</div>
        </a>
        <p style="color:#c9c8cd;font-family:Arial,sans-serif;font-size:14px;line-height:17px;margin-bottom:0;margin-top:8px;overflow:hidden;padding:8px 0 7px;text-align:center;text-overflow:ellipsis;white-space:nowrap;">
          <a href="https://www.instagram.com/p/DMPomBdSrHw/?utm_source=ig_embed&utm_campaign=loading" style="color:#c9c8cd;font-family:Arial,sans-serif;font-size:14px;font-style:normal;font-weight:normal;line-height:17px;text-decoration:none;" target="_blank">MOZYSKEY(@mozyskey_)がシェアした投稿</a>
        </p>
      </div>
    </blockquote>
  </div>

  <p class="fade-in" style="font-size:12px;opacity:0.55;margin:24px 0 16px;">B GALLERY @b_gallery_official より</p>

  <!-- Instagram Post - B GALLERY -->
  <div class="ig-wrap fade-in">
    <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DNw4fIZXo9F/?utm_source=ig_embed&utm_campaign=loading" data-instgrm-version="14" style="background:#FFF;border:0;border-radius:3px;box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15);margin:1px;max-width:540px;min-width:326px;padding:0;width:99.375%;width:calc(100% - 2px);">
      <div style="padding:16px;">
        <a href="https://www.instagram.com/p/DNw4fIZXo9F/?utm_source=ig_embed&utm_campaign=loading" style="background:#FFFFFF;line-height:0;padding:0;text-align:center;text-decoration:none;width:100%;" target="_blank">
          <div style="padding:19% 0;"></div>
          <div style="padding-top:8px;color:#3897f0;font-family:Arial,sans-serif;font-size:14px;font-weight:550;line-height:18px;">この投稿をInstagramで見る</div>
        </a>
        <p style="color:#c9c8cd;font-family:Arial,sans-serif;font-size:14px;line-height:17px;margin-bottom:0;margin-top:8px;overflow:hidden;padding:8px 0 7px;text-align:center;text-overflow:ellipsis;white-space:nowrap;">
          <a href="https://www.instagram.com/p/DNw4fIZXo9F/?utm_source=ig_embed&utm_campaign=loading" style="color:#c9c8cd;font-family:Arial,sans-serif;font-size:14px;font-style:normal;font-weight:normal;line-height:17px;text-decoration:none;" target="_blank">B GALLERY(@b_gallery_official)がシェアした投稿</a>
        </p>
      </div>
    </blockquote>
  </div>

  <div class="zp-credits fade-in">
    <div class="zp-credit-row"><span>VIDEO / EDIT</span><span>ALL MUST DANCE™ / ByUS</span></div>
    <div class="zp-credit-row"><span>ARTWORK</span><span>MOZYSKEY</span></div>
    <div class="zp-credit-row"><span>DANCE</span><span>NOBBY</span></div>
    <div class="zp-credit-row"><span>MUSIC SELECT</span><span>FnKMDNS</span></div>
    <div class="zp-credit-row"><span>EXHIBITION</span><span>MOZYSKEY EXBT. TRACE:3 · B GALLERY</span></div>
    <div class="zp-credit-row"><span>SCREEN</span><span>伊勢丹新宿店 ショーウインドウ</span></div>
  </div>

  <div class="zp-next-issues">
    <div class="zp-next-label">Other Issues</div>
    <div class="zp-next-grid">
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
      <a class="zp-next-card" href="<?= home_url('/zine-ep02/') ?>">
        <img src="https://allmustdance.com/wp-content/uploads/2026/01/IMG_6289-scaled-e1769346275211.jpeg" alt="EP.02" loading="lazy">
        <div class="zp-next-card-body">
          <div class="zp-next-ep">EP · 02 — PARTY</div>
          <div class="zp-next-title">WARSAW</div>
        </div>
        <div class="zp-next-arr">→</div>
      </a>
    </div>
  </div>

</div>

<div class="zp-footer">
  <a href="<?= home_url('/zine-index/') ?>">← ZINE INDEX</a> · © 2026 ALL MUST DANCE™
</div>

<script async src="//www.instagram.com/embed.js"></script>
<script>
const io = new IntersectionObserver(entries => {
  entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('visible'); io.unobserve(e.target); } });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-in').forEach((el,i) => {
  el.style.transitionDelay = (i%4*0.08)+'s';
  io.observe(el);
});
</script>
</body>
</html>
