<?php

/**
 * Custom Post Types and Taxonomies
 *
 * @package Splice_Theme
 */

/**
 * Register Custom Post Type: Projects
 */
function splice_theme_register_project_post_type()
{
    $labels = array(
        'name'                  => _x('Projects', 'Post Type General Name', 'splice-theme'),
        'singular_name'         => _x('Project', 'Post Type Singular Name', 'splice-theme'),
        'menu_name'             => __('Projects', 'splice-theme'),
        'name_admin_bar'        => __('Project', 'splice-theme'),
        'archives'              => __('Project Archives', 'splice-theme'),
        'attributes'            => __('Project Attributes', 'splice-theme'),
        'parent_item_colon'     => __('Parent Project:', 'splice-theme'),
        'all_items'             => __('All Projects', 'splice-theme'),
        'add_new_item'          => __('Add New Project', 'splice-theme'),
        'add_new'               => __('Add New', 'splice-theme'),
        'new_item'              => __('New Project', 'splice-theme'),
        'edit_item'             => __('Edit Project', 'splice-theme'),
        'update_item'           => __('Update Project', 'splice-theme'),
        'view_item'             => __('View Project', 'splice-theme'),
        'view_items'            => __('View Projects', 'splice-theme'),
        'search_items'          => __('Search Projects', 'splice-theme'),
        'not_found'             => __('Not found', 'splice-theme'),
        'not_found_in_trash'    => __('Not found in Trash', 'splice-theme'),
        'featured_image'        => __('Featured Image', 'splice-theme'),
        'set_featured_image'    => __('Set featured image', 'splice-theme'),
        'remove_featured_image' => __('Remove featured image', 'splice-theme'),
        'use_featured_image'    => __('Use as featured image', 'splice-theme'),
        'insert_into_item'      => __('Insert into project', 'splice-theme'),
        'uploaded_to_this_item' => __('Uploaded to this project', 'splice-theme'),
        'items_list'            => __('Projects list', 'splice-theme'),
        'items_list_navigation' => __('Projects list navigation', 'splice-theme'),
        'filter_items_list'     => __('Filter projects list', 'splice-theme'),
    );

    $args = array(
        'label'                 => __('Project', 'splice-theme'),
        'description'           => __('Portfolio projects and work examples', 'splice-theme'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'taxonomies'            => array('project_category', 'project_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array('slug' => 'projects'),
    );

    register_post_type('project', $args);
}
add_action('init', 'splice_theme_register_project_post_type', 0);

/**
 * Register Custom Taxonomy: Project Categories
 */
function splice_theme_register_project_taxonomies()
{
    // Project Categories
    $labels = array(
        'name'              => _x('Project Categories', 'taxonomy general name', 'splice-theme'),
        'singular_name'     => _x('Project Category', 'taxonomy singular name', 'splice-theme'),
        'search_items'      => __('Search Project Categories', 'splice-theme'),
        'all_items'         => __('All Project Categories', 'splice-theme'),
        'parent_item'       => __('Parent Project Category', 'splice-theme'),
        'parent_item_colon' => __('Parent Project Category:', 'splice-theme'),
        'edit_item'         => __('Edit Project Category', 'splice-theme'),
        'update_item'       => __('Update Project Category', 'splice-theme'),
        'add_new_item'      => __('Add New Project Category', 'splice-theme'),
        'new_item_name'     => __('New Project Category Name', 'splice-theme'),
        'menu_name'         => __('Categories', 'splice-theme'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'project-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('project_category', array('project'), $args);

    // Project Tags
    $labels = array(
        'name'              => _x('Project Tags', 'taxonomy general name', 'splice-theme'),
        'singular_name'     => _x('Project Tag', 'taxonomy singular name', 'splice-theme'),
        'search_items'      => __('Search Project Tags', 'splice-theme'),
        'all_items'         => __('All Project Tags', 'splice-theme'),
        'parent_item'       => __('Parent Project Tag', 'splice-theme'),
        'parent_item_colon' => __('Parent Project Tag:', 'splice-theme'),
        'edit_item'         => __('Edit Project Tag', 'splice-theme'),
        'update_item'       => __('Update Project Tag', 'splice-theme'),
        'add_new_item'      => __('Add New Project Tag', 'splice-theme'),
        'new_item_name'     => __('New Project Tag Name', 'splice-theme'),
        'menu_name'         => __('Tags', 'splice-theme'),
    );

    $args = array(
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'project-tag'),
        'show_in_rest'      => true,
    );

    register_taxonomy('project_tag', array('project'), $args);
}
add_action('init', 'splice_theme_register_project_taxonomies', 0);

/**
 * Add custom meta boxes for project fields
 */
function splice_theme_add_project_meta_boxes()
{
    add_meta_box(
        'project_details',
        __('Project Details', 'splice-theme'),
        'splice_theme_project_details_callback',
        'project',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'splice_theme_add_project_meta_boxes');

/**
 * Project details meta box callback
 */
function splice_theme_project_details_callback($post)
{
    wp_nonce_field('splice_theme_save_project_details', 'splice_theme_project_details_nonce');

    $project_name = get_post_meta($post->ID, '_project_name', true);
    $project_description = get_post_meta($post->ID, '_project_description', true);
    $project_start_date = get_post_meta($post->ID, '_project_start_date', true);
    $project_end_date = get_post_meta($post->ID, '_project_end_date', true);
    $project_url = get_post_meta($post->ID, '_project_url', true);

?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="project_name"><?php _e('Project Name', 'splice-theme'); ?></label>
            </th>
            <td>
                <input type="text" id="project_name" name="project_name" value="<?php echo esc_attr($project_name); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="project_description"><?php _e('Project Description', 'splice-theme'); ?></label>
            </th>
            <td>
                <textarea id="project_description" name="project_description" rows="3" class="large-text"><?php echo esc_textarea($project_description); ?></textarea>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="project_start_date"><?php _e('Start Date', 'splice-theme'); ?></label>
            </th>
            <td>
                <input type="date" id="project_start_date" name="project_start_date" value="<?php echo esc_attr($project_start_date); ?>" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="project_end_date"><?php _e('End Date', 'splice-theme'); ?></label>
            </th>
            <td>
                <input type="date" id="project_end_date" name="project_end_date" value="<?php echo esc_attr($project_end_date); ?>" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="project_url"><?php _e('Project URL', 'splice-theme'); ?></label>
            </th>
            <td>
                <input type="url" id="project_url" name="project_url" value="<?php echo esc_url($project_url); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
<?php
}

/**
 * Save project meta data
 */
function splice_theme_save_project_details($post_id)
{
    // Check if our nonce is set
    if (! isset($_POST['splice_theme_project_details_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (! wp_verify_nonce($_POST['splice_theme_project_details_nonce'], 'splice_theme_save_project_details')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type']) && 'project' == $_POST['post_type']) {
        if (! current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Sanitize and save the data
    $fields = array(
        'project_name',
        'project_description',
        'project_start_date',
        'project_end_date',
        'project_url'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = '';
            switch ($field) {
                case 'project_url':
                    $value = esc_url_raw($_POST[$field]);
                    break;
                case 'project_description':
                    $value = sanitize_textarea_field($_POST[$field]);
                    break;
                default:
                    $value = sanitize_text_field($_POST[$field]);
                    break;
            }
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
}
add_action('save_post', 'splice_theme_save_project_details');

/**
 * Flush rewrite rules on theme activation
 */
function splice_theme_flush_rewrite_rules()
{
    splice_theme_register_project_post_type();
    splice_theme_register_project_taxonomies();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'splice_theme_flush_rewrite_rules');
