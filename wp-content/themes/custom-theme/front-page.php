<?php

/**
 * The front page template file
 *
 * This is the template that displays the front page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Splice_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title"><?php echo esc_html(get_bloginfo('name')); ?></h1>
                <p class="hero-description"><?php echo esc_html(get_bloginfo('description')); ?></p>
                <div class="hero-buttons">
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn"><?php esc_html_e('View Our Blog', 'splice-theme'); ?></a>
                    <a href="<?php echo esc_url(home_url('/projects/')); ?>" class="btn btn-secondary"><?php esc_html_e('View Projects', 'splice-theme'); ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Posts Section -->
    <section class="featured-posts">
        <div class="container">
            <h2 class="section-title"><?php esc_html_e('Latest Posts', 'splice-theme'); ?></h2>
            <div class="posts-grid">
                <?php
                $featured_posts = new WP_Query(array(
                    'posts_per_page' => 3,
                    'post_status'    => 'publish',
                    'ignore_sticky_posts' => true
                ));

                if ($featured_posts->have_posts()) :
                    while ($featured_posts->have_posts()) : $featured_posts->the_post();
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
                                <h3 class="post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <div class="post-meta">
                                    <span class="post-date"><?php echo get_the_date(); ?></span>
                                    <span class="post-author"><?php echo get_the_author(); ?></span>
                                </div>
                                <div class="post-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e('Read More', 'splice-theme'); ?></a>
                            </div>
                        </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
            <div class="text-center">
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn"><?php esc_html_e('View All Posts', 'splice-theme'); ?></a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2 class="section-title"><?php esc_html_e('About Us', 'splice-theme'); ?></h2>
                    <p><?php esc_html_e('We are passionate about creating beautiful, functional websites that help businesses grow and succeed in the digital world.', 'splice-theme'); ?></p>
                    <p><?php esc_html_e('Our team combines creativity with technical expertise to deliver exceptional results that exceed expectations.', 'splice-theme'); ?></p>
                    <a href="<?php echo esc_url(home_url('/about/')); ?>" class="btn"><?php esc_html_e('Learn More', 'splice-theme'); ?></a>
                </div>
                <div class="about-image">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/about-placeholder.jpg'); ?>" alt="<?php esc_attr_e('About Us', 'splice-theme'); ?>">
                </div>
            </div>
        </div>
    </section>
</main><!-- #main -->

<?php
get_footer();
