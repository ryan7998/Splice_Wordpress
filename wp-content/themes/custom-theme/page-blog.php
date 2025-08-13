<?php

/**
 * Template Name: Blog
 *
 * This is a custom template for displaying blog posts in a grid layout.
 *
 * @package Splice_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
            <?php if (get_the_excerpt()) : ?>
                <div class="page-description">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="blog-content">
            <?php
            // Custom query for blog posts
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $blog_posts = new WP_Query(array(
                'post_type'      => 'post',
                'posts_per_page' => 9,
                'paged'          => $paged,
                'post_status'    => 'publish'
            ));

            if ($blog_posts->have_posts()) :
            ?>
                <div class="posts-grid">
                    <?php
                    while ($blog_posts->have_posts()) : $blog_posts->the_post();
                    ?>
                        <article class="post-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('splice-medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="post-content">
                                <h2 class="post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <div class="post-meta">
                                    <span class="post-date"><?php echo get_the_date(); ?></span>
                                    <span class="post-author"><?php echo get_the_author(); ?></span>
                                    <?php if (has_category()) : ?>
                                        <span class="post-categories"><?php the_category(', '); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="post-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e('Read More', 'splice-theme'); ?></a>
                            </div>
                        </article>
                    <?php
                    endwhile;
                    ?>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <?php
                    echo paginate_links(array(
                        'total'   => $blog_posts->max_num_pages,
                        'current' => $paged,
                        'prev_text' => '&laquo; Previous',
                        'next_text' => 'Next &raquo;',
                    ));
                    ?>
                </div>

            <?php
                wp_reset_postdata();
            else :
            ?>
                <div class="no-posts">
                    <h2><?php esc_html_e('No posts found', 'splice-theme'); ?></h2>
                    <p><?php esc_html_e('It looks like there are no posts yet. Check back later!', 'splice-theme'); ?></p>
                </div>
            <?php
            endif;
            ?>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();
