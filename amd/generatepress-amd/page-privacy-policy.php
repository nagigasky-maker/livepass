<?php
/*
 * Template Name: Privacy Policy Dark
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
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

html, body {
  background: #07070A !important;
  color: #EDEBE6 !important;
  font-family: 'Montserrat', sans-serif;
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
.pp-logo {
  display: block;
}
.pp-logo img {
  height: 26px; width: auto;
  mix-blend-mode: screen;
}
.pp-back {
  font-size: 11px; font-weight: 400;
  letter-spacing: 0.28em; text-transform: uppercase;
  color: #EDEBE6; text-decoration: none; opacity: 0.65;
  transition: opacity 0.2s;
}
.pp-back:hover { opacity: 1; color: #C8100A; }

/* ── CONTENT ── */
.pp-wrap {
  max-width: 680px;
  margin: 0 auto;
  padding: 100px 28px 80px;
}

.pp-title {
  font-size: clamp(24px, 5vw, 38px);
  font-weight: 300;
  line-height: 1.25;
  letter-spacing: -0.01em;
  color: #EDEBE6;
  margin-bottom: 48px;
  opacity: 0.9;
}

.pp-wrap h2 {
  font-size: 13px;
  font-weight: 500;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: #C8100A;
  margin: 36px 0 12px;
}

.pp-wrap p {
  font-size: 14px;
  font-weight: 300;
  line-height: 1.9;
  color: rgba(237,235,230,0.82);
  margin-bottom: 16px;
}

.pp-wrap strong {
  font-weight: 500;
  color: #EDEBE6;
}

.pp-wrap ul, .pp-wrap ol {
  padding-left: 1.4em;
  margin-bottom: 16px;
}

.pp-wrap li {
  font-size: 14px;
  font-weight: 300;
  line-height: 1.85;
  color: rgba(237,235,230,0.78);
  margin-bottom: 8px;
}

.pp-wrap a {
  color: #C8100A;
  text-decoration: none;
  border-bottom: 1px solid rgba(200,16,10,0.3);
}
.pp-wrap a:hover { border-color: #C8100A; }

.pp-divider {
  border: none;
  border-top: 1px solid rgba(237,235,230,0.08);
  margin: 40px 0;
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
.navigation-bar, #page > header,
#page > footer { display: none !important; }
#page, #content, .site-content, #primary, main {
  margin: 0 !important; padding: 0 !important;
  max-width: 100% !important;
  background: #07070A !important;
}
</style>
</head>
<body class="privacy-policy-dark">

<div class="pp-header">
  <a class="pp-logo" href="<?= home_url('/') ?>">
    <img src="<?= get_stylesheet_directory_uri() ?>/logos/amdheaderlogo.png"
      alt="ALL MUST DANCE™">
  </a>
  <a class="pp-back" href="<?= home_url('/') ?>">← Back</a>
</div>

<div id="page">
  <main id="primary">
    <?php while(have_posts()): the_post(); ?>
    <div class="pp-wrap">
      <h1 class="pp-title"><?php the_title(); ?></h1>
      <div class="pp-entry">
        <?php the_content(); ?>
      </div>
    </div>
    <?php endwhile; ?>
  </main>
</div>

<div class="pp-footer">
  © ALL MUST DANCE™ · Tokyo · 2026
</div>

<?php wp_footer(); ?>
</body>
</html>
