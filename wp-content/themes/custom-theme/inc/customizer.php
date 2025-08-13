<?php

/**
 * Splice Theme Theme Customizer
 *
 * @package Splice_Theme
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function splice_theme_customize_register($wp_customize)
{
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'splice_theme_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'splice_theme_customize_partial_blogdescription',
            )
        );
    }

    // Add custom section for theme options
    $wp_customize->add_section(
        'splice_theme_options',
        array(
            'title'    => __('Theme Options', 'splice-theme'),
            'priority' => 30,
        )
    );

    // Add setting for hero background color
    $wp_customize->add_setting(
        'hero_background_color',
        array(
            'default'           => '#667eea',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'hero_background_color',
            array(
                'label'   => __('Hero Background Color', 'splice-theme'),
                'section' => 'splice_theme_options',
            )
        )
    );

    // Add setting for primary button color
    $wp_customize->add_setting(
        'primary_button_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'primary_button_color',
            array(
                'label'   => __('Primary Button Color', 'splice-theme'),
                'section' => 'splice_theme_options',
            )
        )
    );
}
add_action('customize_register', 'splice_theme_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function splice_theme_customize_partial_blogname()
{
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function splice_theme_customize_partial_blogdescription()
{
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function splice_theme_customize_preview_js()
{
    wp_enqueue_script('splice-theme-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), _S_VERSION, true);
}
add_action('customize_preview_init', 'splice_theme_customize_preview_js');
