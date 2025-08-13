<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Splice_Theme
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function splice_theme_body_classes($classes)
{
    // Adds a class of hfeed to non-singular pages.
    if (! is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (! is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}
add_filter('body_class', 'splice_theme_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function splice_theme_pingback_header()
{
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'splice_theme_pingback_header');

/**
 * Customize the excerpt length
 */
function splice_theme_excerpt_length($length)
{
    return 25;
}
add_filter('excerpt_length', 'splice_theme_excerpt_length', 999);

/**
 * Customize the excerpt more string
 */
function splice_theme_excerpt_more($more)
{
    return '...';
}
add_filter('excerpt_more', 'splice_theme_excerpt_more');

/**
 * Add custom image sizes
 */
function splice_theme_image_sizes()
{
    add_image_size('splice-thumbnail', 300, 200, true);
    add_image_size('splice-medium', 600, 400, true);
    add_image_size('splice-large', 1200, 800, true);
}
add_action('after_setup_theme', 'splice_theme_image_sizes');

/**
 * Add preconnect for Google Fonts
 */
function splice_theme_resource_hints($urls, $relation_type)
{
    if (wp_style_is('splice-theme-fonts', 'queue') && 'preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}
add_filter('wp_resource_hints', 'splice_theme_resource_hints', 10, 2);
