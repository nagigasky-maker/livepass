<?php
// ── フロントページ以外のCSS読み込み ──
add_action('wp_enqueue_scripts', function() {
  if(is_front_page()) return; // フロントページでは何も読み込まない
  wp_enqueue_style('parent-style', get_template_directory_uri().'/style.css');
  wp_enqueue_style('child-style', get_stylesheet_directory_uri().'/style.css', ['parent-style'], filemtime(get_stylesheet_directory().'/style.css'));
});

// ── フロントページ: GP関連CSS/JSを全てブロック ──
add_action('wp_enqueue_scripts', function() {
  if(!is_front_page()) return;
  // GeneratePress CSS
  wp_dequeue_style('generate-style');
  wp_deregister_style('generate-style');
  wp_dequeue_style('generate-fonts');
  wp_deregister_style('generate-fonts');
  wp_dequeue_style('parent-style');
  wp_deregister_style('parent-style');
  wp_dequeue_style('child-style');
  wp_deregister_style('child-style');
  // WordPress標準CSS
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('global-styles');
  wp_dequeue_style('classic-theme-styles');
  // GeneratePress JS
  wp_dequeue_script('generate-menu');
  wp_dequeue_script('generate-a11y');
  wp_dequeue_script('generate-back-to-top');
}, 100);

// ── フロントページ: bodyクラスをシンプルに ──
add_filter('body_class', function($classes) {
  if(is_front_page()) return ['amd-front'];
  return $classes;
});

// ── 不要なhead要素を削除 ──
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
