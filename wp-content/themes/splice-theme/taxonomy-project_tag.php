<?php

/**
 * The template for displaying project tag archives
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
                $tag = get_queried_object();
                printf(
                    /* translators: %s: Tag name */
                    esc_html__('Projects tagged with %s', 'splice-theme'),
                    '<span class="tag-name">' . esc_html($tag->name) . '</span>'
                );
                ?>
            </h1>

            <?php if ($tag->description) : ?>
                <div class="tag-description">
                    <?php echo wp_kses_post($tag->description); ?>
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
                <p><?php esc_html_e('No projects found with this tag. Please check back later or browse other tags.', 'splice-theme'); ?></p>

                <div class="browse-other-tags">
                    <h3><?php esc_html_e('Browse Other Tags', 'splice-theme'); ?></h3>
                    <?php
                    $tags = get_terms(array(
                        'taxonomy' => 'project_tag',
                        'hide_empty' => true,
                        'exclude' => array($tag->term_id)
                    ));

                    if (!empty($tags) && !is_wp_error($tags)) :
                        echo '<ul class="tag-links">';
                        foreach ($tags as $t) :
                            echo '<li><a href="' . esc_url(get_term_link($t)) . '">' . esc_html($t->name) . '</a></li>';
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
