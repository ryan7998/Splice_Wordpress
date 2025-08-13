<?php

/**
 * Splice Theme - Minimal Functions
 *
 * @package Splice_Theme
 */

if (! defined('_S_VERSION')) {
    define('_S_VERSION', '1.0.0');
}

/**
 * Basic theme setup
 */
function splice_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));

    register_nav_menus(array(
        'menu-1' => esc_html__('Primary', 'splice-theme'),
    ));
}
add_action('after_setup_theme', 'splice_theme_setup');

/**
 * Enqueue styles
 */
function splice_theme_scripts()
{
    wp_enqueue_style('splice-theme-style', get_stylesheet_uri(), array(), _S_VERSION);
}
add_action('wp_enqueue_scripts', 'splice_theme_scripts');
