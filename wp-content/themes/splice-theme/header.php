<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="site">
        <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'splice-theme'); ?></a>

        <header id="masthead" class="site-header">
            <div class="container">
                <div class="main-navigation">
                    <div class="site-branding">
                        <?php
                        the_custom_logo();
                        if (is_front_page() && is_home()) :
                        ?>
                            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                        <?php
                        else :
                        ?>
                            <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                        <?php
                        endif;
                        $splice_theme_description = get_bloginfo('description', 'display');
                        if ($splice_theme_description || is_customize_preview()) :
                        ?>
                            <p class="site-description"><?php echo $splice_theme_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                                        ?></p>
                        <?php endif; ?>
                    </div><!-- .site-branding -->

                    <nav id="site-navigation">
                        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                            <span class="screen-reader-text"><?php esc_html_e('Menu', 'splice-theme'); ?></span>
                            <span class="hamburger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>

                        <div class="menu-wrapper">
                            <?php
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'menu-1',
                                    'menu_id'        => 'primary-menu',
                                    'menu_class'     => '',
                                    'container'      => false,
                                    'fallback_cb'    => 'splice_theme_fallback_menu',
                                    'walker'         => new Splice_Theme_Walker_Nav_Menu(),
                                )
                            );
                            ?>
                        </div>
                    </nav><!-- #site-navigation -->
                </div>
            </div>
        </header><!-- #masthead -->