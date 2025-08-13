<?php

/**
 * The template for displaying project tag archives
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Splice_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">
                <?php
                printf(
                    /* translators: %s: tag name. */
                    esc_html__('Project Tag: %s', 'splice-theme'),
                    '<span>' . single_term_title('', false) . '</span>'
                );
                ?>
            </h1>
            <?php
            $term_description = term_description();
            if (!empty($term_description)) :
                printf('<div class="archive-description">%s</div>', $term_description);
            endif;
            ?>
        </header>

        <div class="projects-content">
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
                the_posts_pagination(array(
                    'prev_text' => esc_html__('Previous', 'splice-theme'),
                    'next_text' => esc_html__('Next', 'splice-theme'),
                ));
                ?>

            <?php else : ?>
                <div class="no-projects">
                    <h2><?php esc_html_e('No projects found with this tag', 'splice-theme'); ?></h2>
                    <p><?php esc_html_e('It looks like there are no projects with this tag yet.', 'splice-theme'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer();
