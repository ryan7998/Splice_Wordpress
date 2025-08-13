<?php

/**
 * Template Name: About
 *
 * This is a custom page template for About/Company pages.
 * It provides a simple layout with company information sections.
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
        ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('about-page'); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>

                    <!-- Company Information Section -->
                    <div class="company-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <h3>Our Mission</h3>
                                <p>To deliver innovative digital solutions that empower businesses to thrive in the digital age.</p>
                            </div>

                            <div class="info-item">
                                <h3>Our Vision</h3>
                                <p>To be the leading digital agency that transforms ideas into exceptional digital experiences.</p>
                            </div>

                            <div class="info-item">
                                <h3>Our Values</h3>
                                <ul>
                                    <li>Innovation & Creativity</li>
                                    <li>Quality & Excellence</li>
                                    <li>Client Success</li>
                                    <li>Continuous Learning</li>
                                </ul>
                            </div>

                            <div class="info-item">
                                <h3>Why Choose Us</h3>
                                <ul>
                                    <li>Expert Team</li>
                                    <li>Proven Results</li>
                                    <li>Personalized Approach</li>
                                    <li>Ongoing Support</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Team Section -->
                    <div class="team-section">
                        <h2>Our Team</h2>
                        <div class="team-grid">
                            <div class="team-member">
                                <div class="member-avatar">
                                    <span class="avatar-placeholder">JD</span>
                                </div>
                                <h3>John Doe</h3>
                                <p class="position">Founder & CEO</p>
                                <p class="bio">Passionate about digital innovation and helping businesses grow.</p>
                            </div>

                            <div class="team-member">
                                <div class="member-avatar">
                                    <span class="avatar-placeholder">JS</span>
                                </div>
                                <h3>Jane Smith</h3>
                                <p class="position">Creative Director</p>
                                <p class="bio">Bringing creative visions to life through exceptional design.</p>
                            </div>

                            <div class="team-member">
                                <div class="member-avatar">
                                    <span class="avatar-placeholder">MJ</span>
                                </div>
                                <h3>Mike Johnson</h3>
                                <p class="position">Lead Developer</p>
                                <p class="bio">Building robust and scalable digital solutions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
?>