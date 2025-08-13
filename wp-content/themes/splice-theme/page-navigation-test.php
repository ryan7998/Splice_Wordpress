<?php

/**
 * Template Name: Navigation Test
 *
 * @package Splice_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
        </header>

        <div class="navigation-test-content">
            <h2>Multi-Level Navigation Test</h2>
            <p>This page demonstrates the enhanced navigation system with multi-level dropdowns.</p>

            <div class="test-sections">
                <section class="test-section">
                    <h3>Navigation Features</h3>
                    <ul>
                        <li>✅ Multi-level dropdown support</li>
                        <li>✅ Hover activation on desktop</li>
                        <li>✅ Click activation on mobile</li>
                        <li>✅ Keyboard navigation support</li>
                        <li>✅ Screen reader accessibility</li>
                        <li>✅ Responsive design</li>
                        <li>✅ Smooth animations</li>
                    </ul>
                </section>

                <section class="test-section">
                    <h3>How to Test</h3>
                    <ol>
                        <li><strong>Desktop:</strong> Hover over menu items with dropdown arrows (▼)</li>
                        <li><strong>Mobile:</strong> Click the hamburger menu, then tap dropdown items</li>
                        <li><strong>Keyboard:</strong> Use Tab to navigate, Enter to open dropdowns</li>
                        <li><strong>Projects Menu:</strong> Should show project categories as sub-items</li>
                    </ol>
                </section>

                <section class="test-section">
                    <h3>Menu Structure</h3>
                    <div class="menu-structure">
                        <ul>
                            <li>Home</li>
                            <li>Projects ▼
                                <ul>
                                    <li>All Projects</li>
                                    <li>Project Category 1</li>
                                    <li>Project Category 2</li>
                                </ul>
                            </li>
                            <li>Blog</li>
                            <li>About</li>
                            <li>Contact</li>
                        </ul>
                    </div>
                </section>
            </div>

            <div class="test-actions">
                <h3>Test Actions</h3>
                <p>Try these actions to test the navigation:</p>
                <ul>
                    <li>Navigate to <a href="<?php echo esc_url(home_url('/projects/')); ?>">Projects</a> to see the custom post type</li>
                    <li>Check the <a href="<?php echo esc_url(home_url('/')); ?>">Home page</a> for the full navigation</li>
                    <li>Resize your browser to test responsive behavior</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<?php
get_sidebar();
get_footer();
