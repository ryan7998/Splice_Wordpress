<?php

/**
 * Project Seeder for Splice Theme
 * Creates sample projects with categories and tags for testing
 *
 * @package Splice_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Create sample projects
 */
function splice_theme_create_sample_projects()
{
    // Check if projects already exist
    $existing_projects = get_posts(array(
        'post_type' => 'project',
        'post_status' => 'publish',
        'numberposts' => 1
    ));

    if (!empty($existing_projects)) {
        return 'Projects already exist. Skipping seeder.';
    }

    // Create project categories
    $categories = array(
        'web-development' => 'Web Development',
        'mobile-apps' => 'Mobile Apps',
        'ui-ux-design' => 'UI/UX Design',
        'e-commerce' => 'E-Commerce',
        'wordpress' => 'WordPress',
        'react-apps' => 'React Applications'
    );

    $category_ids = array();
    foreach ($categories as $slug => $name) {
        $term = term_exists($slug, 'project_category');
        if (!$term) {
            $term = wp_insert_term($name, 'project_category', array('slug' => $slug));
        }
        if (!is_wp_error($term)) {
            $category_ids[$slug] = $term['term_id'];
        }
    }

    // Create project tags
    $tags = array(
        'javascript' => 'JavaScript',
        'php' => 'PHP',
        'react' => 'React',
        'vue' => 'Vue.js',
        'nodejs' => 'Node.js',
        'mysql' => 'MySQL',
        'api' => 'API Development',
        'responsive' => 'Responsive Design',
        'seo' => 'SEO Optimized',
        'performance' => 'Performance'
    );

    $tag_ids = array();
    foreach ($tags as $slug => $name) {
        $term = term_exists($slug, 'project_tag');
        if (!$term) {
            $term = wp_insert_term($name, 'project_tag', array('slug' => $slug));
        }
        if (!is_wp_error($term)) {
            $tag_ids[$slug] = $term['term_id'];
        }
    }

    // Sample project data
    $sample_projects = array(
        array(
            'title' => 'Modern E-Commerce Platform',
            'content' => 'A full-featured e-commerce platform built with React and Node.js. Features include user authentication, product management, shopping cart, payment integration, and admin dashboard.',
            'project_name' => 'ShopHub Pro',
            'project_description' => 'Complete e-commerce solution for online retailers',
            'start_date' => '2024-01-15',
            'end_date' => '2024-06-30',
            'project_url' => 'https://shophub-pro.com',
            'categories' => array('e-commerce', 'react-apps'),
            'tags' => array('react', 'nodejs', 'api', 'responsive')
        ),
        array(
            'title' => 'Portfolio Website Redesign',
            'content' => 'Complete redesign of a creative agency portfolio website. Focused on modern design principles, smooth animations, and excellent user experience across all devices.',
            'project_name' => 'CreativeFlow Portfolio',
            'project_description' => 'Modern portfolio website for creative professionals',
            'start_date' => '2024-02-01',
            'end_date' => '2024-04-15',
            'project_url' => 'https://creativeflow-portfolio.com',
            'categories' => array('ui-ux-design', 'web-development'),
            'tags' => array('responsive', 'seo', 'performance')
        ),
        array(
            'title' => 'Mobile Banking App',
            'content' => 'Cross-platform mobile banking application built with React Native. Includes secure authentication, account management, transaction history, and real-time notifications.',
            'project_name' => 'SecureBank Mobile',
            'project_description' => 'Secure mobile banking application',
            'start_date' => '2023-09-01',
            'end_date' => '2024-03-31',
            'project_url' => 'https://securebank-mobile.com',
            'categories' => array('mobile-apps'),
            'tags' => array('react', 'api', 'performance')
        ),
        array(
            'title' => 'WordPress Custom Theme',
            'content' => 'Custom WordPress theme built from scratch for a restaurant business. Features include online ordering, table reservations, menu management, and customer reviews.',
            'project_name' => 'RestaurantPro Theme',
            'project_description' => 'Custom WordPress theme for restaurants',
            'start_date' => '2024-01-01',
            'end_date' => '2024-02-28',
            'project_url' => 'https://restaurantpro-theme.com',
            'categories' => array('wordpress', 'web-development'),
            'tags' => array('php', 'mysql', 'responsive', 'seo')
        ),
        array(
            'title' => 'API Management Dashboard',
            'content' => 'Comprehensive dashboard for managing multiple APIs. Features include API documentation, usage analytics, rate limiting, and developer portal.',
            'project_name' => 'APIMaster Dashboard',
            'project_description' => 'Centralized API management platform',
            'start_date' => '2023-11-01',
            'end_date' => '2024-05-31',
            'project_url' => 'https://apimaster-dashboard.com',
            'categories' => array('web-development'),
            'tags' => array('api', 'javascript', 'nodejs', 'performance')
        ),
        array(
            'title' => 'E-Learning Platform',
            'content' => 'Online learning management system with course creation, student progress tracking, video streaming, and interactive assessments.',
            'project_name' => 'LearnHub Platform',
            'project_description' => 'Comprehensive e-learning solution',
            'start_date' => '2024-03-01',
            'end_date' => '2024-08-31',
            'project_url' => 'https://learnhub-platform.com',
            'categories' => array('web-development', 'e-commerce'),
            'tags' => array('vue', 'php', 'mysql', 'responsive', 'seo')
        ),
        array(
            'title' => 'Social Media Analytics Tool',
            'content' => 'Real-time analytics dashboard for social media platforms. Tracks engagement, follower growth, content performance, and provides actionable insights.',
            'project_name' => 'SocialMetrics Pro',
            'project_description' => 'Social media analytics and insights',
            'start_date' => '2024-02-15',
            'end_date' => '2024-07-31',
            'project_url' => 'https://socialmetrics-pro.com',
            'categories' => array('web-development'),
            'tags' => array('react', 'api', 'performance', 'responsive')
        ),
        array(
            'title' => 'Inventory Management System',
            'content' => 'Comprehensive inventory management solution for retail businesses. Features include stock tracking, purchase orders, supplier management, and reporting.',
            'project_name' => 'InventoryTracker',
            'project_description' => 'Retail inventory management system',
            'start_date' => '2023-12-01',
            'end_date' => '2024-04-30',
            'project_url' => 'https://inventory-tracker.com',
            'categories' => array('web-development'),
            'tags' => array('php', 'mysql', 'api', 'responsive')
        ),
        array(
            'title' => 'Weather Forecast App',
            'content' => 'Beautiful weather application with 7-day forecasts, hourly predictions, weather maps, and location-based services.',
            'project_name' => 'WeatherWise',
            'project_description' => 'Accurate weather forecasting app',
            'start_date' => '2024-01-01',
            'end_date' => '2024-03-15',
            'project_url' => 'https://weatherwise-app.com',
            'categories' => array('mobile-apps', 'ui-ux-design'),
            'tags' => array('react', 'api', 'responsive', 'performance')
        ),
        array(
            'title' => 'Blog Management Platform',
            'content' => 'Advanced blog management system with content scheduling, SEO optimization, social media integration, and analytics.',
            'project_name' => 'BlogMaster Pro',
            'project_description' => 'Professional blog management platform',
            'start_date' => '2024-02-01',
            'end_date' => '2024-05-31',
            'project_url' => 'https://blogmaster-pro.com',
            'categories' => array('wordpress', 'web-development'),
            'tags' => array('php', 'mysql', 'seo', 'responsive')
        )
    );

    $created_count = 0;
    foreach ($sample_projects as $project_data) {
        // Create post
        $post_data = array(
            'post_title' => $project_data['title'],
            'post_content' => $project_data['content'],
            'post_status' => 'publish',
            'post_type' => 'project',
            'post_author' => 1
        );

        $post_id = wp_insert_post($post_data);

        if (!is_wp_error($post_id)) {
            // Add custom fields
            update_post_meta($post_id, '_project_name', $project_data['project_name']);
            update_post_meta($post_id, '_project_description', $project_data['project_description']);
            update_post_meta($post_id, '_project_start_date', $project_data['start_date']);
            update_post_meta($post_id, '_project_end_date', $project_data['end_date']);
            update_post_meta($post_id, '_project_url', $project_data['project_url']);

            // Set categories
            if (!empty($project_data['categories'])) {
                $category_terms = array();
                foreach ($project_data['categories'] as $cat_slug) {
                    if (isset($category_ids[$cat_slug])) {
                        $category_terms[] = $category_ids[$cat_slug];
                    }
                }
                if (!empty($category_terms)) {
                    wp_set_object_terms($post_id, $category_terms, 'project_category');
                }
            }

            // Set tags
            if (!empty($project_data['tags'])) {
                $tag_terms = array();
                foreach ($project_data['tags'] as $tag_slug) {
                    if (isset($tag_ids[$tag_slug])) {
                        $tag_terms[] = $tag_ids[$tag_slug];
                    }
                }
                if (!empty($tag_terms)) {
                    wp_set_object_terms($post_id, $tag_terms, 'project_tag');
                }
            }

            $created_count++;
        }
    }

    // Flush rewrite rules to ensure new project post type is recognized
    flush_rewrite_rules();

    return sprintf('Successfully created %d sample projects with categories and tags.', $created_count);
}

/**
 * Clear all sample projects
 */
function splice_theme_clear_sample_projects()
{
    $projects = get_posts(array(
        'post_type' => 'project',
        'post_status' => 'any',
        'numberposts' => -1
    ));

    $deleted_count = 0;
    foreach ($projects as $project) {
        if (wp_delete_post($project->ID, true)) {
            $deleted_count++;
        }
    }

    // Clear categories and tags if they're empty
    $categories = get_terms(array(
        'taxonomy' => 'project_category',
        'hide_empty' => false
    ));

    foreach ($categories as $category) {
        if ($category->count == 0) {
            wp_delete_term($category->term_id, 'project_category');
        }
    }

    $tags = get_terms(array(
        'taxonomy' => 'project_tag',
        'hide_empty' => false
    ));

    foreach ($tags as $tag) {
        if ($tag->count == 0) {
            wp_delete_term($tag->term_id, 'project_tag');
        }
    }

    return sprintf('Successfully deleted %d projects and cleaned up empty taxonomies.', $deleted_count);
}

/**
 * Add admin menu for seeder
 */
function splice_theme_add_seeder_menu()
{
    add_submenu_page(
        'tools.php',
        'Project Seeder',
        'Project Seeder',
        'manage_options',
        'splice-project-seeder',
        'splice_theme_seeder_page'
    );
}
add_action('admin_menu', 'splice_theme_add_seeder_menu');

/**
 * Seeder admin page
 */
function splice_theme_seeder_page()
{
    $message = '';
    $message_type = 'info';

    if (isset($_POST['action'])) {
        // Verify nonce and user capabilities
        if (!wp_verify_nonce($_POST['seeder_nonce'], 'splice_theme_seeder')) {
            $message = 'Security check failed. Please try again.';
            $message_type = 'error';
            splice_theme_log_security_event('Invalid nonce in seeder form', array('user_id' => get_current_user_id()), 'warning');
        } elseif (!current_user_can('manage_options')) {
            $message = 'Insufficient permissions to perform this action.';
            $message_type = 'error';
            splice_theme_log_security_event('Unauthorized seeder access attempt', array('user_id' => get_current_user_id()), 'warning');
        } else {
            // Sanitize action
            $action = sanitize_text_field($_POST['action']);

            switch ($action) {
                case 'create_projects':
                    $message = splice_theme_create_sample_projects();
                    $message_type = 'success';
                    splice_theme_log_security_event('Sample projects created via seeder', array('user_id' => get_current_user_id()), 'info');
                    break;
                case 'clear_projects':
                    $message = splice_theme_clear_sample_projects();
                    $message_type = 'warning';
                    splice_theme_log_security_event('All projects cleared via seeder', array('user_id' => get_current_user_id()), 'warning');
                    break;
                case 'flush_rules':
                    flush_rewrite_rules();
                    $message = 'Rewrite rules flushed successfully. Project URLs should now work.';
                    $message_type = 'success';
                    splice_theme_log_security_event('Rewrite rules flushed via seeder', array('user_id' => get_current_user_id()), 'info');
                    break;
                default:
                    $message = 'Invalid action specified.';
                    $message_type = 'error';
                    splice_theme_log_security_event('Invalid action in seeder form', array('action' => $action, 'user_id' => get_current_user_id()), 'warning');
                    break;
            }
        }
    }

    $project_count = wp_count_posts('project');
    $category_count = wp_count_terms('project_category');
    $tag_count = wp_count_terms('project_tag');
?>
    <div class="wrap">
        <h1>Project Seeder</h1>

        <?php if ($message) : ?>
            <div class="notice notice-<?php echo $message_type; ?> is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2>Current Status</h2>
            <p><strong>Projects:</strong> <?php echo $project_count->publish; ?> published, <?php echo $project_count->draft; ?> drafts</p>
            <p><strong>Categories:</strong> <?php echo $category_count; ?></p>
            <p><strong>Tags:</strong> <?php echo $tag_count; ?></p>
        </div>

        <div class="card">
            <h2>Actions</h2>

            <form method="post" style="margin-bottom: 20px;">
                <?php wp_nonce_field('splice_theme_seeder', 'seeder_nonce'); ?>
                <input type="hidden" name="action" value="create_projects">
                <button type="submit" class="button button-primary">
                    Create Sample Projects
                </button>
                <p class="description">Creates 10 sample projects with realistic data, categories, and tags.</p>
            </form>

            <form method="post">
                <?php wp_nonce_field('splice_theme_seeder', 'seeder_nonce'); ?>
                <input type="hidden" name="action" value="clear_projects">
                <button type="submit" class="button button-secondary" onclick="return confirm('Are you sure? This will delete ALL projects!')">
                    Clear All Projects
                </button>
                <p class="description">Deletes all projects and cleans up empty categories/tags. Use with caution!</p>
            </form>

            <form method="post">
                <?php wp_nonce_field('splice_theme_seeder', 'seeder_nonce'); ?>
                <input type="hidden" name="action" value="flush_rules">
                <button type="submit" class="button button-secondary" onclick="return confirm('Are you sure? This will flush rewrite rules. Project URLs might break temporarily.')">
                    Flush Rewrite Rules
                </button>
                <p class="description">Flushes rewrite rules to ensure new project post type is recognized immediately.</p>
            </form>
        </div>

        <div class="card">
            <h2>Sample Data Preview</h2>
            <p>The seeder will create projects like:</p>
            <ul>
                <li><strong>Modern E-Commerce Platform</strong> - React & Node.js e-commerce solution</li>
                <li><strong>Portfolio Website Redesign</strong> - Modern UI/UX design project</li>
                <li><strong>Mobile Banking App</strong> - React Native cross-platform app</li>
                <li><strong>WordPress Custom Theme</strong> - Restaurant business theme</li>
                <li><strong>API Management Dashboard</strong> - Centralized API platform</li>
                <li>And 5 more diverse projects...</li>
            </ul>
        </div>

        <div class="card">
            <h2>API Testing</h2>
            <p>After creating projects, test the API endpoints:</p>
            <ul>
                <li><code><?php echo esc_url(home_url('/wp-json/splice-theme/v1/projects')); ?></code> - All projects</li>
                <li><code><?php echo esc_url(home_url('/wp-json/splice-theme/v1/project-categories')); ?></code> - All categories</li>
                <li><code><?php echo esc_url(home_url('/wp-json/splice-theme/v1/project-tags')); ?></code> - All tags</li>
            </ul>
        </div>
    </div>
<?php
}

/**
 * Auto-run seeder on theme activation (optional)
 */
function splice_theme_auto_seeder()
{
    // Only run if no projects exist
    $existing_projects = get_posts(array(
        'post_type' => 'project',
        'post_status' => 'publish',
        'numberposts' => 1
    ));

    if (empty($existing_projects)) {
        // Add a small delay to ensure theme is fully loaded
        wp_schedule_single_event(time() + 10, 'splice_theme_run_auto_seeder');
    }
}
add_action('after_switch_theme', 'splice_theme_auto_seeder');

/**
 * Scheduled seeder execution
 */
function splice_theme_run_auto_seeder()
{
    if (current_user_can('manage_options')) {
        splice_theme_create_sample_projects();
    }
}
add_action('splice_theme_run_auto_seeder', 'splice_theme_run_auto_seeder');
