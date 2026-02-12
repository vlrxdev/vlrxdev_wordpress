<?php
/**
 * Функции темы vlrxdev.
 */

defined( 'ABSPATH' ) || exit;

require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/meta-project.php';

add_action( 'after_setup_theme', 'vlrxdev_setup' );

function vlrxdev_setup() {
  add_theme_support( 'title-tag' );
  add_theme_support( 'automatic-feed-links' );
  add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
}

add_action( 'wp_enqueue_scripts', 'vlrxdev_scripts' );

function vlrxdev_scripts() {
  wp_enqueue_style( 'vlrxdev-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
  wp_enqueue_script(
    'vlrxdev-tabs',
    get_template_directory_uri() . '/js/tabs.js',
    array(),
    wp_get_theme()->get( 'Version' ),
    true
  );
}
