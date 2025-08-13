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
 * Enqueue styles and scripts
 */
function splice_theme_scripts()
{
    wp_enqueue_style('splice-theme-style', get_stylesheet_uri(), array(), _S_VERSION);

    // Enqueue navigation JavaScript
    wp_enqueue_script('splice-theme-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), _S_VERSION, true);
}
add_action('wp_enqueue_scripts', 'splice_theme_scripts');

/**
 * Include custom navigation walker
 */
require get_template_directory() . '/inc/class-splice-theme-walker-nav-menu.php';

/**
 * Include template tags
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Include template functions
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Include customizer
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Include custom post types
 */
require get_template_directory() . '/inc/custom-post-types.php';

/**
 * Include custom API endpoints
 */
require get_template_directory() . '/inc/custom-api.php';

/**
 * Include project seeder
 */
require get_template_directory() . '/inc/project-seeder.php';
