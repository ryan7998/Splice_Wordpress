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
        'footer-menu' => esc_html__('Footer Menu', 'splice-theme'),
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

    // Add essential navigation styles to ensure proper display
    wp_add_inline_style('splice-theme-style', '
        /* Essential Navigation Styles */
        #primary-menu {
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
            display: flex !important;
            gap: 2rem !important;
            align-items: center !important;
        }
        
        #primary-menu li {
            margin: 0 !important;
            position: relative !important;
        }
        
        #primary-menu a {
            color: #333 !important;
            text-decoration: none !important;
            padding: 0.75rem 1rem !important;
            background: transparent !important;
            border-radius: 4px !important;
            transition: all 0.3s ease !important;
            display: block !important;
            font-weight: 500 !important;
        }
        
        #primary-menu a:hover {
            background: #f8f9fa !important;
            color: #007cba !important;
        }
        
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            min-width: 220px;
            z-index: 1000;
            list-style: none;
            margin: 0;
            padding: 0.75rem 0;
        }
        
        .dropdown.open .dropdown-menu {
            display: block !important;
        }
        
        .dropdown-menu li {
            margin: 0;
        }
        
        .dropdown-menu a {
            padding: 0.75rem 1.5rem;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
            border-radius: 0;
            font-size: 0.95rem;
            background: transparent;
        }
        
        .dropdown-menu a:hover {
            background: #f8f9fa;
            color: #007cba;
        }
        
        .dropdown-menu li:last-child a {
            border-bottom: none;
        }
        
        .dropdown-toggle {
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            padding-right: 1.5rem !important;
        }
        
        .dropdown-arrow {
            font-size: 0.75rem !important;
            color: #666 !important;
            transition: transform 0.3s ease !important;
            margin-left: 0.25rem !important;
        }
        
        .dropdown.open .dropdown-arrow {
            transform: rotate(180deg) !important;
        }
        
        /* Mobile Navigation */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block !important;
            }
            
            .menu-wrapper {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #fff;
                border: 1px solid #e5e5e5;
                border-top: none;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                opacity: 0;
                visibility: hidden;
                transform: translateY(-10px);
                transition: all 0.3s ease;
                border-radius: 0 0 8px 8px;
            }
            
            .site-navigation.toggled .menu-wrapper {
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateY(0) !important;
            }
            
            #primary-menu {
                flex-direction: column !important;
                gap: 0 !important;
                padding: 1rem 0 !important;
                width: 100% !important;
            }
            
            #primary-menu li {
                width: 100% !important;
            }
            
            #primary-menu a {
                padding: 1rem 1.5rem !important;
                border-bottom: 1px solid #f0f0f0 !important;
                border-radius: 0 !important;
                width: 100% !important;
                text-align: left !important;
            }
            
            .dropdown-menu {
                position: static !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: none !important;
                box-shadow: none !important;
                border: none !important;
                background: #f8f9fa !important;
                margin-left: 1rem !important;
                display: none !important;
                border-radius: 0 !important;
                min-width: auto !important;
            }
            
            .dropdown.open .dropdown-menu {
                display: block !important;
            }
        }
    ');
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
 * Include Jetpack compatibility
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}
