<?php

/**
 * The template for displaying single project posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Splice_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            get_template_part('template-parts/content', 'single-project');
        endwhile;
        ?>

        <!-- Project Navigation -->
        <nav class="project-navigation">
            <div class="nav-links">
                <div class="nav-previous">
                    <?php previous_post_link('%link', '<span class="nav-subtitle">' . esc_html__('Previous Project:', 'splice-theme') . '</span> <span class="nav-title">%title</span>'); ?>
                </div>
                <div class="nav-next">
                    <?php next_post_link('%link', '<span class="nav-subtitle">' . esc_html__('Next Project:', 'splice-theme') . '</span> <span class="nav-title">%title</span>'); ?>
                </div>
            </div>
        </nav>

        <!-- Related Projects -->
        <?php
        $related_projects = new WP_Query(array(
            'post_type' => 'project',
            'posts_per_page' => 3,
            'post__not_in' => array(get_the_ID()),
            'meta_query' => array(
                array(
                    'key' => '_project_category',
                    'value' => wp_get_post_terms(get_the_ID(), 'project_category', array('fields' => 'slugs')),
                    'compare' => 'IN'
                )
            )
        ));

        if ($related_projects->have_posts()) :
        ?>
            <section class="related-projects">
                <h2><?php esc_html_e('Related Projects', 'splice-theme'); ?></h2>
                <div class="related-projects-grid">
                    <?php
                    while ($related_projects->have_posts()) : $related_projects->the_post();
                        get_template_part('template-parts/content', 'project');
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer();
