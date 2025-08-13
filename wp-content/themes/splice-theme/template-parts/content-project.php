<?php

/**
 * Template part for displaying project posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Splice_Theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('project-card'); ?>>
    <div class="project-thumbnail">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('splice-medium'); ?>
            </a>
        <?php else : ?>
            <div class="project-placeholder">
                <span class="placeholder-icon">📁</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="project-content">
        <header class="project-header">
            <?php the_title('<h2 class="project-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>

            <?php if (get_post_meta(get_the_ID(), '_project_name', true)) : ?>
                <div class="project-meta">
                    <span class="project-name"><?php echo esc_html(get_post_meta(get_the_ID(), '_project_name', true)); ?></span>
                </div>
            <?php endif; ?>
        </header>

        <div class="project-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <div class="project-details">
            <?php
            $start_date = get_post_meta(get_the_ID(), '_project_start_date', true);
            $end_date = get_post_meta(get_the_ID(), '_project_end_date', true);
            $project_url = get_post_meta(get_the_ID(), '_project_url', true);
            ?>

            <?php if ($start_date || $end_date) : ?>
                <div class="project-dates">
                    <?php if ($start_date) : ?>
                        <span class="start-date"><?php esc_html_e('Start:', 'splice-theme'); ?> <?php echo esc_html(date('M Y', strtotime($start_date))); ?></span>
                    <?php endif; ?>
                    <?php if ($end_date) : ?>
                        <span class="end-date"><?php esc_html_e('End:', 'splice-theme'); ?> <?php echo esc_html(date('M Y', strtotime($end_date))); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($project_url) : ?>
                <div class="project-link">
                    <a href="<?php echo esc_url($project_url); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-secondary">
                        <?php esc_html_e('View Project', 'splice-theme'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <footer class="project-footer">
            <?php
            $project_categories = get_the_terms(get_the_ID(), 'project_category');
            $project_tags = get_the_terms(get_the_ID(), 'project_tag');
            ?>

            <?php if ($project_categories && !is_wp_error($project_categories)) : ?>
                <div class="project-categories">
                    <?php
                    foreach ($project_categories as $category) {
                        echo '<a href="' . esc_url(get_term_link($category)) . '" class="project-category">' . esc_html($category->name) . '</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($project_tags && !is_wp_error($project_tags)) : ?>
                <div class="project-tags">
                    <?php
                    foreach ($project_tags as $tag) {
                        echo '<a href="' . esc_url(get_term_link($tag)) . '" class="project-tag">' . esc_html($tag->name) . '</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </footer>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->