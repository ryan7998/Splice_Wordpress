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
    <header class="project-header">
        <div class="project-meta-top">
            <?php
            $project_categories = get_the_terms(get_the_ID(), 'project_category');
            if ($project_categories && !is_wp_error($project_categories)) :
            ?>
                <div class="project-categories">
                    <?php
                    foreach ($project_categories as $category) {
                        echo '<a href="' . esc_url(get_term_link($category)) . '" class="project-category">' . esc_html($category->name) . '</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <?php the_title('<h1 class="project-title">', '</h1>'); ?>

        <?php if (get_post_meta(get_the_ID(), '_project_name', true)) : ?>
            <div class="project-subtitle">
                <?php echo esc_html(get_post_meta(get_the_ID(), '_project_name', true)); ?>
            </div>
        <?php endif; ?>
    </header>

    <?php if (has_post_thumbnail()) : ?>
        <div class="project-featured-image">
            <?php the_post_thumbnail('splice-large'); ?>
        </div>
    <?php endif; ?>

    <div class="project-content-wrapper">
        <div class="project-main-content">
            <div class="project-description">
                <?php if (get_post_meta(get_the_ID(), '_project_description', true)) : ?>
                    <div class="project-summary">
                        <h3><?php esc_html_e('Project Summary', 'splice-theme'); ?></h3>
                        <p><?php echo esc_html(get_post_meta(get_the_ID(), '_project_description', true)); ?></p>
                    </div>
                <?php endif; ?>

                <div class="project-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>

        <aside class="project-sidebar">
            <div class="project-details-card">
                <h3><?php esc_html_e('Project Details', 'splice-theme'); ?></h3>

                <?php
                $start_date = get_post_meta(get_the_ID(), '_project_start_date', true);
                $end_date = get_post_meta(get_the_ID(), '_project_end_date', true);
                $project_url = get_post_meta(get_the_ID(), '_project_url', true);
                ?>

                <?php if ($start_date || $end_date) : ?>
                    <div class="project-dates">
                        <h4><?php esc_html_e('Timeline', 'splice-theme'); ?></h4>
                        <?php if ($start_date) : ?>
                            <div class="date-item">
                                <span class="date-label"><?php esc_html_e('Start Date:', 'splice-theme'); ?></span>
                                <span class="date-value"><?php echo esc_html(date('F j, Y', strtotime($start_date))); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($end_date) : ?>
                            <div class="date-item">
                                <span class="date-label"><?php esc_html_e('End Date:', 'splice-theme'); ?></span>
                                <span class="date-value"><?php echo esc_html(date('F j, Y', strtotime($end_date))); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($project_url) : ?>
                    <div class="project-external-link">
                        <h4><?php esc_html_e('Project Link', 'splice-theme'); ?></h4>
                        <a href="<?php echo esc_url($project_url); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                            <?php esc_html_e('Visit Project', 'splice-theme'); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php
                $project_tags = get_the_terms(get_the_ID(), 'project_tag');
                if ($project_tags && !is_wp_error($project_tags)) :
                ?>
                    <div class="project-tags">
                        <h4><?php esc_html_e('Tags', 'splice-theme'); ?></h4>
                        <div class="tags-list">
                            <?php
                            foreach ($project_tags as $tag) {
                                echo '<a href="' . esc_url(get_term_link($tag)) . '" class="project-tag">' . esc_html($tag->name) . '</a>';
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </aside>
    </div>

    <footer class="project-footer">
        <div class="project-navigation-links">
            <div class="project-prev-next">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>

                <?php if ($prev_post) : ?>
                    <div class="project-prev">
                        <span class="nav-label"><?php esc_html_e('Previous Project', 'splice-theme'); ?></span>
                        <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>"><?php echo esc_html($prev_post->post_title); ?></a>
                    </div>
                <?php endif; ?>

                <?php if ($next_post) : ?>
                    <div class="project-next">
                        <span class="nav-label"><?php esc_html_e('Next Project', 'splice-theme'); ?></span>
                        <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>"><?php echo esc_html($next_post->post_title); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </footer>
</article><!-- #post-<?php the_ID(); ?> -->