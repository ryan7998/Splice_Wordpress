<?php

/**
 * The template for displaying project archives
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Splice_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Projects', 'splice-theme'); ?></h1>
            <?php
            $project_archive_description = get_the_archive_description();
            if ($project_archive_description) :
            ?>
                <div class="archive-description"><?php echo $project_archive_description; ?></div>
            <?php endif; ?>
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
                    <h2><?php esc_html_e('No projects found', 'splice-theme'); ?></h2>
                    <p><?php esc_html_e('It looks like there are no projects yet. Check back later!', 'splice-theme'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer();
