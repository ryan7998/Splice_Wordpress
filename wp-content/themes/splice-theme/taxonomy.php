<?php

/**
 * The template for displaying taxonomy archives
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
                $term = get_queried_object();
                $taxonomy = get_taxonomy($term->taxonomy);

                if ($taxonomy) {
                    printf(
                        /* translators: %1$s: Taxonomy label, %2$s: Term name */
                        esc_html__('%1$s: %2$s', 'splice-theme'),
                        esc_html($taxonomy->labels->singular_name),
                        '<span class="term-name">' . esc_html($term->name) . '</span>'
                    );
                } else {
                    single_term_title();
                }
                ?>
            </h1>

            <?php if ($term->description) : ?>
                <div class="term-description">
                    <?php echo wp_kses_post($term->description); ?>
                </div>
            <?php endif; ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="posts-grid">
                <?php
                while (have_posts()) :
                    the_post();

                    // Use appropriate template part based on post type
                    if (get_post_type() === 'project') {
                        get_template_part('template-parts/content', 'project');
                    } else {
                        get_template_part('template-parts/content', get_post_type());
                    }
                endwhile;
                ?>
            </div>

            <?php
            // Pagination
            the_posts_navigation(array(
                'prev_text' => __('← Previous', 'splice-theme'),
                'next_text' => __('More →', 'splice-theme'),
            ));
            ?>

        <?php else : ?>
            <div class="no-posts">
                <h2><?php esc_html_e('No Posts Found', 'splice-theme'); ?></h2>
                <p><?php esc_html_e('No posts found in this taxonomy. Please check back later or browse other categories.', 'splice-theme'); ?></p>

                <div class="browse-other-terms">
                    <h3><?php esc_html_e('Browse Other Terms', 'splice-theme'); ?></h3>
                    <?php
                    $terms = get_terms(array(
                        'taxonomy' => $term->taxonomy,
                        'hide_empty' => true,
                        'exclude' => array($term->term_id)
                    ));

                    if (!empty($terms) && !is_wp_error($terms)) :
                        echo '<ul class="term-links">';
                        foreach ($terms as $t) :
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
