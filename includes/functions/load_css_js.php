<?php
add_action( 'wp_enqueue_scripts', 'wp_my_enqueue' );
function wp_my_enqueue() {
  $script_src = get_template_directory_uri() . '/assets/lib/jquery.min.js';
  $css_src = get_template_directory_uri() . '/assets/css/style.css';
  wp_enqueue_style( 'css', $css_src );
  wp_enqueue_script( 'jquery', $script_src , '', '', false );
}
