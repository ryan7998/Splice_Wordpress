    <footer id="colophon" class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-widgets">
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <div class="footer-widget-area">
                            <?php dynamic_sidebar('footer-1'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="footer-navigation">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer-menu',
                            'menu_id'        => 'footer-menu',
                            'menu_class'     => 'footer-menu',
                            'container'      => false,
                            'fallback_cb'    => false,
                        )
                    );
                    ?>
                </div>

                <div class="site-info">
                    <div class="copyright">
                        &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>.
                        <?php esc_html_e('All rights reserved.', 'splice-theme'); ?>
                    </div>
                    <div class="powered-by">
                        <?php
                        printf(
                            esc_html__('Proudly powered by %s', 'splice-theme'),
                            '<a href="https://wordpress.org/">WordPress</a>'
                        );
                        ?>
                    </div>
                </div><!-- .site-info -->
            </div>
        </div>
    </footer><!-- #colophon -->
    </div><!-- #page -->

    <?php wp_footer(); ?>

    </body>

    </html>