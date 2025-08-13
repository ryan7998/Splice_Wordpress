<?php

/**
 * The front page template file
 *
 * @package Splice_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <!-- Hero Section -->
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Welcome to Splice Theme', 'splice-theme'); ?></h1>
            <div class="category-description">
                <?php esc_html_e('A showcase of custom WordPress development, featuring modern design principles and powerful functionality.', 'splice-theme'); ?>
            </div>
        </header>

        <!-- Top CTA Cards -->
        <section class="cta-section">
            <div class="cta-grid">
                <div class="cta-card">
                    <div class="cta-icon">🚀</div>
                    <h2 class="cta-title"><?php esc_html_e('Ready to get started?', 'splice-theme'); ?></h2>
                    <p class="cta-description"><?php esc_html_e('Explore our portfolio of custom WordPress projects and see what we can build for you.', 'splice-theme'); ?></p>
                    <a href="<?php echo esc_url(home_url('/projects/')); ?>" class="cta-button">
                        <?php esc_html_e('View Projects', 'splice-theme'); ?>
                        <span>→</span>
                    </a>
                </div>

                <div class="cta-card">
                    <div class="cta-icon">📚</div>
                    <h2 class="cta-title"><?php esc_html_e('New to WordPress?', 'splice-theme'); ?></h2>
                    <p class="cta-description"><?php esc_html_e('Learn about custom theme development and how we can help bring your vision to life.', 'splice-theme'); ?></p>
                    <a href="https://developer.wordpress.org/" class="cta-button" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e('Learn More', 'splice-theme'); ?>
                        <span>→</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Project Categories Grid -->
        <section class="category-section">
            <h2 class="section-title text-center mb-5"><?php esc_html_e('Explore Our Project Categories', 'splice-theme'); ?></h2>

            <div class="category-cards">
                <?php
                // Get project categories
                $categories = get_terms(array(
                    'taxonomy' => 'project_category',
                    'hide_empty' => true,
                    'orderby' => 'name',
                    'order' => 'ASC'
                ));

                if (!empty($categories) && !is_wp_error($categories)) :
                    foreach ($categories as $category) :
                        $category_link = get_term_link($category);
                        $project_count = $category->count;
                        $category_icon = get_term_meta($category->term_id, 'category_icon', true);

                        // Default icons for each category
                        $default_icons = array(
                            'web-development' => '🌐',
                            'mobile-apps' => '📱',
                            'ui-ux-design' => '🎨',
                            'e-commerce' => '🛒',
                            'wordpress' => '📝',
                            'react-apps' => '⚛️'
                        );

                        $icon = $category_icon ?: ($default_icons[$category->slug] ?? '📁');
                ?>

                        <div class="category-card fade-in-up">
                            <div class="category-icon"><?php echo $icon; ?></div>
                            <h3 class="category-title"><?php echo esc_html($category->name); ?></h3>
                            <div class="category-count">
                                <span>→</span>
                                <?php printf(esc_html(_n('%d PROJECT', '%d PROJECTS', $project_count, 'splice-theme')), $project_count); ?>
                            </div>
                            <div class="category-description">
                                <?php echo esc_html($category->description ?: sprintf(__('Explore our %s projects and see our expertise in action.', 'splice-theme'), strtolower($category->name))); ?>
                            </div>
                            <a href="<?php echo esc_url($category_link); ?>" class="category-link">
                                <?php esc_html_e('Browse Category', 'splice-theme'); ?>
                                <span>→</span>
                            </a>
                        </div>

                    <?php endforeach;
                else : ?>
                    <div class="category-card text-center">
                        <div class="category-icon">📁</div>
                        <h3 class="category-title"><?php esc_html_e('No Categories Yet', 'splice-theme'); ?></h3>
                        <div class="category-description">
                            <?php esc_html_e('Project categories will appear here once you create some projects.', 'splice-theme'); ?>
                        </div>
                        <a href="<?php echo esc_url(admin_url('tools.php?page=splice-project-seeder')); ?>" class="category-link">
                            <?php esc_html_e('Create Sample Projects', 'splice-theme'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Bottom CTA Section -->
        <section class="cta-section">
            <div class="cta-card" style="grid-column: 1 / -1;">
                <div class="cta-icon">🚀</div>
                <h2 class="cta-title"><?php esc_html_e('Ready to get started?', 'splice-theme'); ?></h2>
                <p class="cta-description"><?php esc_html_e('Step-by-step guides to setting up your system and installing the library. Step-by-step guides to setting up your system and installing the library.', 'splice-theme'); ?></p>
                <a href="<?php echo esc_url(home_url('/projects/')); ?>" class="cta-button">
                    <?php esc_html_e('Quick Start', 'splice-theme'); ?>
                    <span>→</span>
                </a>
            </div>
        </section>

        <!-- Recent Projects Preview -->
        <?php
        $recent_projects = get_posts(array(
            'post_type' => 'project',
            'post_status' => 'publish',
            'numberposts' => 3,
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        if (!empty($recent_projects)) : ?>
            <section class="recent-projects">
                <h2 class="section-title text-center mb-5"><?php esc_html_e('Recent Projects', 'splice-theme'); ?></h2>

                <div class="projects-grid">
                    <?php foreach ($recent_projects as $project) : ?>
                        <div class="project-card fade-in-up">
                            <?php if (has_post_thumbnail($project->ID)) : ?>
                                <div class="project-image">
                                    <?php echo get_the_post_thumbnail($project->ID, 'medium'); ?>
                                </div>
                            <?php endif; ?>

                            <h3 class="project-title"><?php echo esc_html($project->post_title); ?></h3>
                            <div class="project-excerpt">
                                <?php echo wp_trim_words($project->post_content, 20); ?>
                            </div>

                            <?php
                            $project_categories = get_the_terms($project->ID, 'project_category');
                            if ($project_categories && !is_wp_error($project_categories)) : ?>
                                <div class="project-meta">
                                    <?php foreach ($project_categories as $cat) : ?>
                                        <span class="project-category"><?php echo esc_html($cat->name); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <a href="<?php echo esc_url(get_permalink($project->ID)); ?>" class="project-link">
                                <?php esc_html_e('View Project', 'splice-theme'); ?>
                                <span>→</span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="text-center mt-5">
                    <a href="<?php echo esc_url(home_url('/projects/')); ?>" class="cta-button">
                        <?php esc_html_e('View All Projects', 'splice-theme'); ?>
                        <span>→</span>
                    </a>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();
