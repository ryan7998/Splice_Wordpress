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
                <div class="header-content">
                    <div class="site-branding">
                        <div class="site-logo">S</div>
                        <div class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </div>
                    </div>

                    <nav id="site-navigation" class="site-navigation">
                        <div class="menu-wrapper">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'menu-1',
                                'menu_id'        => 'primary-menu',
                                'container'      => false,
                                'fallback_cb'    => 'splice_theme_fallback_menu',
                                'walker'         => new Splice_Theme_Walker_Nav_Menu()
                            ));
                            ?>
                        </div>
                    </nav>
                </div>
            </div>
        </header>