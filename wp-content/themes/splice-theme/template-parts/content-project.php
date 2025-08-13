<?php

/**
 * Template part for displaying project posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Splice_Theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('project-card fade-in-up'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="project-image">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        </div>
    <?php endif; ?>

    <header class="project-header">
        <h2 class="project-title">
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>
    </header>

    <div class="project-content">
        <div class="project-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <?php
        // Get custom fields
        $project_name = get_post_meta(get_the_ID(), '_project_name', true);
        $project_description = get_post_meta(get_the_ID(), '_project_description', true);
        $start_date = get_post_meta(get_the_ID(), '_project_start_date', true);
        $end_date = get_post_meta(get_the_ID(), '_project_end_date', true);
        $project_url = get_post_meta(get_the_ID(), '_project_url', true);
        ?>

        <?php if ($project_name || $project_description) : ?>
            <div class="project-details">
                <?php if ($project_name) : ?>
                    <div class="project-detail">
                        <strong><?php esc_html_e('Project Name:', 'splice-theme'); ?></strong>
                        <span><?php echo esc_html($project_name); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($project_description) : ?>
                    <div class="project-detail">
                        <strong><?php esc_html_e('Description:', 'splice-theme'); ?></strong>
                        <span><?php echo esc_html($project_description); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($start_date || $end_date) : ?>
                    <div class="project-detail">
                        <strong><?php esc_html_e('Timeline:', 'splice-theme'); ?></strong>
                        <span>
                            <?php
                            if ($start_date && $end_date) {
                                printf(
                                    esc_html__('%1$s - %2$s', 'splice-theme'),
                                    date('M Y', strtotime($start_date)),
                                    date('M Y', strtotime($end_date))
                                );
                            } elseif ($start_date) {
                                printf(esc_html__('Started %s', 'splice-theme'), date('M Y', strtotime($start_date)));
                            } elseif ($end_date) {
                                printf(esc_html__('Completed %s', 'splice-theme'), date('M Y', strtotime($end_date)));
                            }
                            ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="project-meta">
            <?php
            // Project categories
            $categories = get_the_terms(get_the_ID(), 'project_category');
            if ($categories && !is_wp_error($categories)) :
                foreach ($categories as $category) : ?>
                    <a href="<?php echo esc_url(get_term_link($category)); ?>" class="project-category">
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endforeach;
            endif;

            // Project tags
            $tags = get_the_terms(get_the_ID(), 'project_tag');
            if ($tags && !is_wp_error($tags)) :
                foreach ($tags as $tag) : ?>
                    <a href="<?php echo esc_url(get_term_link($tag)); ?>" class="project-tag">
                        <?php echo esc_html($tag->name); ?>
                    </a>
            <?php endforeach;
            endif;
            ?>
        </div>

        <div class="project-actions">
            <a href="<?php the_permalink(); ?>" class="project-link">
                <?php esc_html_e('View Project', 'splice-theme'); ?>
                <span>→</span>
            </a>

            <?php if ($project_url) : ?>
                <a href="<?php echo esc_url($project_url); ?>" class="project-link project-external" target="_blank" rel="noopener noreferrer">
                    <?php esc_html_e('Visit Site', 'splice-theme'); ?>
                    <span>↗</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</article>