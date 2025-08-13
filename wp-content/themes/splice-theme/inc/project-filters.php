<?php

/**
 * Project Filters Functionality
 * Handles custom queries for filtering projects by date ranges and other criteria
 *
 * @package Splice_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Modify the main query for project archives to include custom filtering
 */
function splice_theme_modify_project_query($query)
{
    // Only modify the main query on project archive pages
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('project')) {

        // Get filter parameters
        $search = isset($_GET['project_search']) ? sanitize_text_field($_GET['project_search']) : '';
        $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
        $end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';
        $category = isset($_GET['project_category']) ? sanitize_text_field($_GET['project_category']) : '';
        $tag = isset($_GET['project_tag']) ? sanitize_text_field($_GET['project_tag']) : '';

        // Apply search filter
        if (!empty($search)) {
            $query->set('s', $search);
        }

        // Apply category filter
        if (!empty($category)) {
            $tax_query = array(
                array(
                    'taxonomy' => 'project_category',
                    'field' => 'slug',
                    'terms' => $category
                )
            );
            $query->set('tax_query', $tax_query);
        }

        // Apply tag filter
        if (!empty($tag)) {
            if (!empty($category)) {
                // If both category and tag are set, combine them with AND relation
                $existing_tax_query = $query->get('tax_query');
                $existing_tax_query[] = array(
                    'taxonomy' => 'project_tag',
                    'field' => 'slug',
                    'terms' => $tag
                );
                $existing_tax_query['relation'] = 'AND';
                $query->set('tax_query', $existing_tax_query);
            } else {
                $tax_query = array(
                    array(
                        'taxonomy' => 'project_tag',
                        'field' => 'slug',
                        'terms' => $tag
                    )
                );
                $query->set('tax_query', $tax_query);
            }
        }

        // Apply date filters using meta query
        if (!empty($start_date) || !empty($end_date)) {
            $meta_query = array();

            if (!empty($start_date)) {
                $meta_query[] = array(
                    'key' => '_project_start_date',
                    'value' => $start_date,
                    'compare' => '>=',
                    'type' => 'DATE'
                );
            }

            if (!empty($end_date)) {
                $meta_query[] = array(
                    'key' => '_project_end_date',
                    'value' => $end_date,
                    'compare' => '<=',
                    'type' => 'DATE'
                );
            }

            if (count($meta_query) > 1) {
                $meta_query['relation'] = 'AND';
            }

            $query->set('meta_query', $meta_query);
        }

        // Set orderby to date (newest first) if no search is active
        if (empty($search)) {
            $query->set('orderby', 'meta_value');
            $query->set('meta_key', '_project_start_date');
            $query->set('order', 'DESC');
        }

        // Log the filter application for security
        if (!empty($search) || !empty($start_date) || !empty($end_date) || !empty($category) || !empty($tag)) {
            $filter_details = array(
                'search' => $search,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'category' => $category,
                'tag' => $tag,
                'ip' => splice_theme_get_client_ip()
            );
            splice_theme_log_security_event('Project filters applied', $filter_details, 'info');
        }
    }
}
add_action('pre_get_posts', 'splice_theme_modify_project_query');

/**
 * Add custom meta query support for date filtering
 */
function splice_theme_add_date_meta_query_support($query)
{
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('project')) {
        // Ensure meta queries are properly handled
        $query->set('meta_query', $query->get('meta_query'));
    }
}
add_action('pre_get_posts', 'splice_theme_add_date_meta_query_support', 20);

/**
 * Get filtered project count for display
 */
function splice_theme_get_filtered_project_count()
{
    $args = array(
        'post_type' => 'project',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids'
    );

    // Apply the same filters as the main query
    $search = isset($_GET['project_search']) ? sanitize_text_field($_GET['project_search']) : '';
    $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
    $end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';
    $category = isset($_GET['project_category']) ? sanitize_text_field($_GET['project_category']) : '';
    $tag = isset($_GET['project_tag']) ? sanitize_text_field($_GET['project_tag']) : '';

    if (!empty($search)) {
        $args['s'] = $search;
    }

    if (!empty($category) || !empty($tag)) {
        $tax_query = array();

        if (!empty($category)) {
            $tax_query[] = array(
                'taxonomy' => 'project_category',
                'field' => 'slug',
                'terms' => $category
            );
        }

        if (!empty($tag)) {
            $tax_query[] = array(
                'taxonomy' => 'project_tag',
                'field' => 'slug',
                'terms' => $tag
            );
        }

        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }

        $args['tax_query'] = $tax_query;
    }

    if (!empty($start_date) || !empty($end_date)) {
        $meta_query = array();

        if (!empty($start_date)) {
            $meta_query[] = array(
                'key' => '_project_start_date',
                'value' => $start_date,
                'compare' => '>=',
                'type' => 'DATE'
            );
        }

        if (!empty($end_date)) {
            $meta_query[] = array(
                'key' => '_project_end_date',
                'value' => $end_date,
                'compare' => '<=',
                'type' => 'DATE'
            );
        }

        if (count($meta_query) > 1) {
            $meta_query['relation'] = 'AND';
        }

        $args['meta_query'] = $meta_query;
    }

    $query = new WP_Query($args);
    return $query->found_posts;
}

/**
 * Display filtered results count
 */
function splice_theme_display_filtered_results_count()
{
    $total_count = wp_count_posts('project')->publish;
    $filtered_count = splice_theme_get_filtered_project_count();

    if ($filtered_count !== $total_count) {
        echo '<div class="filtered-results-count">';
        printf(
            esc_html__('Showing %1$d of %2$d projects', 'splice-theme'),
            $filtered_count,
            $total_count
        );
        echo '</div>';
    }
}

/**
 * Add AJAX support for dynamic filtering (optional enhancement)
 */
function splice_theme_ajax_filter_projects()
{
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'splice_theme_filter_nonce')) {
        wp_send_json_error('Security check failed');
    }

    // Get filter parameters
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
    $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';

    // Build query arguments
    $args = array(
        'post_type' => 'project',
        'post_status' => 'publish',
        'posts_per_page' => 10,
        'paged' => 1
    );

    if (!empty($search)) {
        $args['s'] = $search;
    }

    if (!empty($category) || !empty($tag)) {
        $tax_query = array();

        if (!empty($category)) {
            $tax_query[] = array(
                'taxonomy' => 'project_category',
                'field' => 'slug',
                'terms' => $category
            );
        }

        if (!empty($tag)) {
            $tax_query[] = array(
                'taxonomy' => 'project_tag',
                'field' => 'slug',
                'terms' => $tag
            );
        }

        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }

        $args['tax_query'] = $tax_query;
    }

    if (!empty($start_date) || !empty($end_date)) {
        $meta_query = array();

        if (!empty($start_date)) {
            $meta_query[] = array(
                'key' => '_project_start_date',
                'value' => $start_date,
                'compare' => '>=',
                'type' => 'DATE'
            );
        }

        if (!empty($end_date)) {
            $meta_query[] = array(
                'key' => '_project_end_date',
                'value' => $end_date,
                'compare' => '<=',
                'type' => 'DATE'
            );
        }

        if (count($meta_query) > 1) {
            $meta_query['relation'] = 'AND';
        }

        $args['meta_query'] = $meta_query;
    }

    $query = new WP_Query($args);
    $projects = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $projects[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'permalink' => get_permalink(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                'start_date' => get_post_meta(get_the_ID(), '_project_start_date', true),
                'end_date' => get_post_meta(get_the_ID(), '_project_end_date', true),
                'categories' => get_the_terms(get_the_ID(), 'project_category'),
                'tags' => get_the_terms(get_the_ID(), 'project_tag')
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success(array(
        'projects' => $projects,
        'found_posts' => $query->found_posts,
        'max_num_pages' => $query->max_num_pages
    ));
}
add_action('wp_ajax_filter_projects', 'splice_theme_ajax_filter_projects');
add_action('wp_ajax_nopriv_filter_projects', 'splice_theme_ajax_filter_projects');

/**
 * Enqueue AJAX script for dynamic filtering
 */
function splice_theme_enqueue_filter_script()
{
    if (is_post_type_archive('project')) {
        wp_enqueue_script(
            'splice-theme-filters',
            get_template_directory_uri() . '/assets/js/project-filters.js',
            array('jquery'),
            _S_VERSION,
            true
        );

        wp_localize_script('splice-theme-filters', 'spliceThemeFilters', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('splice_theme_filter_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'splice_theme_enqueue_filter_script');
