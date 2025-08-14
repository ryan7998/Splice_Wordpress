<?php

/**
 * Template part for displaying single project posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Splice_Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-project'); ?>>
    <!-- Hero Header Section -->
    <header class="page-header">
        <div class="project-meta">
            <?php
            $project_categories = get_the_terms(get_the_ID(), 'project_category');
            if ($project_categories && !is_wp_error($project_categories)) :
                foreach ($project_categories as $category) {
                    echo '<a href="' . esc_url(get_term_link($category)) . '" class="project-category">' . esc_html($category->name) . '</a>';
                }
            endif;
            ?>
        </div>

        <?php the_title('<h1 class="page-title">', '</h1>'); ?>

        <?php if (get_post_meta(get_the_ID(), '_project_name', true)) : ?>
            <div class="project-subtitle">
                <?php echo esc_html(get_post_meta(get_the_ID(), '_project_name', true)); ?>
            </div>
        <?php endif; ?>
    </header>

    <!-- Main Content Section -->
    <div class="project-content">
        <!-- Project Summary -->
        <?php if (get_post_meta(get_the_ID(), '_project_description', true)) : ?>
            <div class="project-summary">
                <h2><?php esc_html_e('Project Summary', 'splice-theme'); ?></h2>
                <p><?php echo esc_html(get_post_meta(get_the_ID(), '_project_description', true)); ?></p>
            </div>
        <?php endif; ?>

        <!-- Project Details -->
        <div class="project-details">
            <h2><?php esc_html_e('Project Details', 'splice-theme'); ?></h2>

            <?php
            $start_date = get_post_meta(get_the_ID(), '_project_start_date', true);
            $end_date = get_post_meta(get_the_ID(), '_project_end_date', true);
            $project_url = get_post_meta(get_the_ID(), '_project_url', true);
            ?>

            <?php if ($start_date) : ?>
                <div class="project-detail">
                    <strong><?php esc_html_e('Start Date:', 'splice-theme'); ?></strong>
                    <span><?php echo esc_html(date('F j, Y', strtotime($start_date))); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($end_date) : ?>
                <div class="project-detail">
                    <strong><?php esc_html_e('End Date:', 'splice-theme'); ?></strong>
                    <span><?php echo esc_html(date('F j, Y', strtotime($end_date))); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($project_url) : ?>
                <div class="project-detail">
                    <strong><?php esc_html_e('Project Link:', 'splice-theme'); ?></strong>
                    <a href="<?php echo esc_url($project_url); ?>" target="_blank" rel="noopener noreferrer" class="project-link">
                        <?php esc_html_e('Visit Project', 'splice-theme'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Project Tags -->
        <?php
        $project_tags = get_the_terms(get_the_ID(), 'project_tag');
        if ($project_tags && !is_wp_error($project_tags)) :
        ?>
            <div class="project-tags">
                <h3><?php esc_html_e('Tags', 'splice-theme'); ?></h3>
                <?php
                foreach ($project_tags as $tag) {
                    echo '<a href="' . esc_url(get_term_link($tag)) . '" class="project-tag">' . esc_html($tag->name) . '</a>';
                }
                ?>
            </div>
        <?php endif; ?>

        <!-- Project Content -->
        <?php if (get_the_content()) : ?>
            <div class="project-content-text">
                <h2><?php esc_html_e('Project Description', 'splice-theme'); ?></h2>
                <?php the_content(); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Featured Image -->
    <?php if (has_post_thumbnail()) : ?>
        <div class="project-image">
            <?php the_post_thumbnail('large'); ?>
        </div>
    <?php endif; ?>


</article>