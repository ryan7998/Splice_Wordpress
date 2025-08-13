<?php

/**
 * Custom REST API Endpoints for Splice Theme
 *
 * @package Splice_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register custom REST API routes
 */
function splice_theme_register_api_routes()
{
    // Register projects endpoint
    register_rest_route('splice-theme/v1', '/projects', array(
        'methods' => 'GET',
        'callback' => 'splice_theme_get_projects',
        'permission_callback' => '__return_true', // Public endpoint
        'args' => array(
            'per_page' => array(
                'default' => 10,
                'sanitize_callback' => 'absint',
                'validate_callback' => function ($param) {
                    return is_numeric($param) && $param > 0 && $param <= 100;
                }
            ),
            'page' => array(
                'default' => 1,
                'sanitize_callback' => 'absint',
                'validate_callback' => function ($param) {
                    return is_numeric($param) && $param > 0;
                }
            ),
            'category' => array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field'
            ),
            'tag' => array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field'
            )
        )
    ));

    // Register single project endpoint
    register_rest_route('splice-theme/v1', '/projects/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'splice_theme_get_single_project',
        'permission_callback' => '__return_true',
        'args' => array(
            'id' => array(
                'validate_callback' => function ($param) {
                    return is_numeric($param);
                }
            )
        )
    ));

    // Register project categories endpoint
    register_rest_route('splice-theme/v1', '/project-categories', array(
        'methods' => 'GET',
        'callback' => 'splice_theme_get_project_categories',
        'permission_callback' => '__return_true'
    ));

    // Register project tags endpoint
    register_rest_route('splice-theme/v1', '/project-tags', array(
        'methods' => 'GET',
        'callback' => 'splice_theme_get_project_tags',
        'permission_callback' => '__return_true'
    ));
}
add_action('rest_api_init', 'splice_theme_register_api_routes');

/**
 * Get projects with pagination and filtering
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response|WP_Error
 */
function splice_theme_get_projects($request)
{
    // Get query parameters
    $per_page = $request->get_param('per_page');
    $page = $request->get_param('page');
    $category = $request->get_param('category');
    $tag = $request->get_param('tag');

    // Build query arguments
    $args = array(
        'post_type' => 'project',
        'post_status' => 'publish',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC'
    );

    // Add category filter
    if (!empty($category)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'project_category',
            'field' => 'slug',
            'terms' => $category
        );
    }

    // Add tag filter
    if (!empty($tag)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'project_tag',
            'field' => 'slug',
            'terms' => $tag
        );
    }

    // Handle multiple tax queries
    if (isset($args['tax_query']) && count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }

    // Query projects
    $projects_query = new WP_Query($args);
    $projects = array();

    if ($projects_query->have_posts()) {
        while ($projects_query->have_posts()) {
            $projects_query->the_post();
            $projects[] = splice_theme_format_project_data(get_post());
        }
        wp_reset_postdata();
    }

    // Prepare response data
    $response_data = array(
        'success' => true,
        'data' => $projects,
        'pagination' => array(
            'current_page' => $page,
            'per_page' => $per_page,
            'total_posts' => $projects_query->found_posts,
            'total_pages' => $projects_query->max_num_pages
        )
    );

    return new WP_REST_Response($response_data, 200);
}

/**
 * Get single project by ID
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response|WP_Error
 */
function splice_theme_get_single_project($request)
{
    $project_id = $request->get_param('id');
    $project = get_post($project_id);

    if (!$project || $project->post_type !== 'project') {
        return new WP_Error(
            'project_not_found',
            'Project not found',
            array('status' => 404)
        );
    }

    $response_data = array(
        'success' => true,
        'data' => splice_theme_format_project_data($project, true)
    );

    return new WP_REST_Response($response_data, 200);
}

/**
 * Get project categories
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function splice_theme_get_project_categories($request)
{
    $categories = get_terms(array(
        'taxonomy' => 'project_category',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC'
    ));

    $formatted_categories = array();
    foreach ($categories as $category) {
        $formatted_categories[] = array(
            'id' => $category->term_id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'count' => $category->count,
            'link' => get_term_link($category)
        );
    }

    $response_data = array(
        'success' => true,
        'data' => $formatted_categories
    );

    return new WP_REST_Response($response_data, 200);
}

/**
 * Get project tags
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function splice_theme_get_project_tags($request)
{
    $tags = get_terms(array(
        'taxonomy' => 'project_tag',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC'
    ));

    $formatted_tags = array();
    foreach ($tags as $tag) {
        $formatted_tags[] = array(
            'id' => $tag->term_id,
            'name' => $tag->name,
            'slug' => $tag->slug,
            'description' => $tag->description,
            'count' => $tag->count,
            'link' => get_term_link($tag)
        );
    }

    $response_data = array(
        'success' => true,
        'data' => $formatted_tags
    );

    return new WP_REST_Response($response_data, 200);
}

/**
 * Format project data for API response
 *
 * @param WP_Post $post
 * @param bool $include_content
 * @return array
 */
function splice_theme_format_project_data($post, $include_content = false)
{
    // Get custom fields
    $project_name = get_post_meta($post->ID, '_project_name', true);
    $project_description = get_post_meta($post->ID, '_project_description', true);
    $start_date = get_post_meta($post->ID, '_project_start_date', true);
    $end_date = get_post_meta($post->ID, '_project_end_date', true);
    $project_url = get_post_meta($post->ID, '_project_url', true);

    // Get taxonomies
    $categories = get_the_terms($post->ID, 'project_category');
    $tags = get_the_terms($post->ID, 'project_tag');

    // Format dates
    $formatted_start_date = $start_date ? date('Y-m-d', strtotime($start_date)) : null;
    $formatted_end_date = $end_date ? date('Y-m-d', strtotime($end_date)) : null;

    // Format categories
    $formatted_categories = array();
    if ($categories && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            $formatted_categories[] = array(
                'id' => $category->term_id,
                'name' => $category->name,
                'slug' => $category->slug,
                'link' => get_term_link($category)
            );
        }
    }

    // Format tags
    $formatted_tags = array();
    if ($tags && !is_wp_error($tags)) {
        foreach ($tags as $tag) {
            $formatted_tags[] = array(
                'id' => $tag->term_id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'link' => get_term_link($tag)
            );
        }
    }

    // Build response data
    $project_data = array(
        'id' => $post->ID,
        'title' => $post->post_title,
        'slug' => $post->post_name,
        'url' => get_permalink($post->ID),
        'project_name' => $project_name,
        'project_description' => $project_description,
        'start_date' => $formatted_start_date,
        'end_date' => $formatted_end_date,
        'project_url' => $project_url,
        'date_created' => $post->post_date,
        'date_modified' => $post->post_modified,
        'categories' => $formatted_categories,
        'tags' => $formatted_tags,
        'featured_image' => get_the_post_thumbnail_url($post->ID, 'medium')
    );

    // Include full content if requested
    if ($include_content) {
        $project_data['content'] = apply_filters('the_content', $post->post_content);
        $project_data['excerpt'] = $post->post_excerpt;
    }

    return $project_data;
}

/**
 * Add CORS headers for API requests
 */
function splice_theme_add_cors_headers()
{
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400'); // 24 hours
    }

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        }

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }

        exit(0);
    }
}
add_action('init', 'splice_theme_add_cors_headers');
