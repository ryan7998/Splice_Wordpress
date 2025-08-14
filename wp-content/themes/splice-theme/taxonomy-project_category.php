<?php

/**
 * The template for displaying project category archives
 *
 * @package Splice_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">
                <?php
                $category = get_queried_object();
                printf(
                    /* translators: %s: Category name */
                    esc_html__('Projects in %s', 'splice-theme'),
                    '<span class="category-name">' . esc_html($category->name) . '</span>'
                );
                ?>
            </h1>

            <?php if ($category->description) : ?>
                <div class="category-description">
                    <?php echo wp_kses_post($category->description); ?>
                </div>
            <?php endif; ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="projects-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', 'project');
                endwhile;
                ?>
            </div>

            <?php
            // Pagination
            the_posts_navigation(array(
                'prev_text' => __('← Previous Projects', 'splice-theme'),
                'next_text' => __('More Projects →', 'splice-theme'),
            ));
            ?>

        <?php else : ?>
            <div class="no-projects">
                <h2><?php esc_html_e('No Projects Found', 'splice-theme'); ?></h2>
                <p><?php esc_html_e('No projects found in this category. Please check back later or browse other categories.', 'splice-theme'); ?></p>

                <div class="browse-other-categories">
                    <h3><?php esc_html_e('Browse Other Categories', 'splice-theme'); ?></h3>
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'project_category',
                        'hide_empty' => true,
                        'exclude' => array($category->term_id)
                    ));

                    if (!empty($categories) && !is_wp_error($categories)) :
                        echo '<ul class="category-links">';
                        foreach ($categories as $cat) :
                            echo '<li><a href="' . esc_url(get_term_link($cat)) . '">' . esc_html($cat->name) . '</a></li>';
                        endforeach;
                        echo '</ul>';
                    endif;
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();
?>