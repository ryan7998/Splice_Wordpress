<?php

/**
 * Project Filters Template Part
 * Provides date range filtering and search functionality for projects
 *
 * @package Splice_Theme
 */

// Get current filter values from URL parameters
$current_search = isset($_GET['project_search']) ? sanitize_text_field($_GET['project_search']) : '';
$current_start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
$current_end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';
$current_category = isset($_GET['project_category']) ? sanitize_text_field($_GET['project_category']) : '';
$current_tag = isset($_GET['project_tag']) ? sanitize_text_field($_GET['project_tag']) : '';

// Get all project categories and tags for filter dropdowns
$project_categories = get_terms(array(
    'taxonomy' => 'project_category',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC'
));

$project_tags = get_terms(array(
    'taxonomy' => 'project_tag',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC'
));
?>

<div class="project-filters">
    <div class="filters-header">
        <h3><?php esc_html_e('Filter Projects', 'splice-theme'); ?></h3>
    </div>

    <form method="get" class="filters-form" id="project-filters-form">
        <div class="filters-grid">
            <!-- Search Field -->
            <div class="filter-group">
                <label for="project_search"><?php esc_html_e('Search Projects', 'splice-theme'); ?></label>
                <input
                    type="text"
                    id="project_search"
                    name="project_search"
                    value="<?php echo esc_attr($current_search); ?>"
                    placeholder="<?php esc_attr_e('Search project titles, descriptions...', 'splice-theme'); ?>"
                    class="filter-input" />
            </div>

            <!-- Start Date Filter -->
            <div class="filter-group">
                <label for="start_date"><?php esc_html_e('Start Date From', 'splice-theme'); ?></label>
                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    value="<?php echo esc_attr($current_start_date); ?>"
                    class="filter-input" />
            </div>

            <!-- End Date Filter -->
            <div class="filter-group">
                <label for="end_date"><?php esc_html_e('End Date To', 'splice-theme'); ?></label>
                <input
                    type="date"
                    id="end_date"
                    name="end_date"
                    value="<?php echo esc_attr($current_end_date); ?>"
                    class="filter-input" />
            </div>

            <!-- Category Filter -->
            <div class="filter-group">
                <label for="project_category"><?php esc_html_e('Category', 'splice-theme'); ?></label>
                <select id="project_category" name="project_category" class="filter-input">
                    <option value=""><?php esc_html_e('All Categories', 'splice-theme'); ?></option>
                    <?php if (!is_wp_error($project_categories)) : ?>
                        <?php foreach ($project_categories as $category) : ?>
                            <option
                                value="<?php echo esc_attr($category->slug); ?>"
                                <?php selected($current_category, $category->slug); ?>>
                                <?php echo esc_html($category->name); ?> (<?php echo $category->count; ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Tag Filter -->
            <div class="filter-group">
                <label for="project_tag"><?php esc_html_e('Tag', 'splice-theme'); ?></label>
                <select id="project_tag" name="project_tag" class="filter-input">
                    <option value=""><?php esc_html_e('All Tags', 'splice-theme'); ?></option>
                    <?php if (!is_wp_error($project_tags)) : ?>
                        <?php foreach ($project_tags as $tag) : ?>
                            <option
                                value="<?php echo esc_attr($tag->slug); ?>"
                                <?php selected($current_tag, $tag->slug); ?>>
                                <?php echo esc_html($tag->name); ?> (<?php echo $tag->count; ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- Loading indicator -->
        <div class="filter-loading" style="display: none;">
            <span class="spinner">⏳</span>
            <span><?php esc_html_e('Applying filters...', 'splice-theme'); ?></span>
        </div>

        <div class="filters-actions">
            <a href="<?php echo esc_url(get_post_type_archive_link('project')); ?>" class="filter-reset" id="reset-filters-btn">
                <span class="filter-icon">🔄</span>
                <?php esc_html_e('Reset Filters', 'splice-theme'); ?>
            </a>
        </div>

        <!-- Active Filters Display -->
        <?php if ($current_search || $current_start_date || $current_end_date || $current_category || $current_tag) : ?>
            <div class="active-filters">
                <h4><?php esc_html_e('Active Filters:', 'splice-theme'); ?></h4>
                <div class="filter-tags">
                    <?php if ($current_search) : ?>
                        <span class="filter-tag">
                            <?php esc_html_e('Search:', 'splice-theme'); ?> "<?php echo esc_html($current_search); ?>"
                            <a href="<?php echo esc_url(remove_query_arg('project_search', $_SERVER['REQUEST_URI'])); ?>" class="remove-filter" aria-label="<?php esc_attr_e('Remove search filter', 'splice-theme'); ?>">×</a>
                        </span>
                    <?php endif; ?>

                    <?php if ($current_start_date) : ?>
                        <span class="filter-tag">
                            <?php esc_html_e('From:', 'splice-theme'); ?> <?php echo esc_html(date('M j, Y', strtotime($current_start_date))); ?>
                            <a href="<?php echo esc_url(remove_query_arg('start_date', $_SERVER['REQUEST_URI'])); ?>" class="remove-filter" aria-label="<?php esc_attr_e('Remove start date filter', 'splice-theme'); ?>">×</a>
                        </span>
                    <?php endif; ?>

                    <?php if ($current_end_date) : ?>
                        <span class="filter-tag">
                            <?php esc_html_e('To:', 'splice-theme'); ?> <?php echo esc_html(date('M j, Y', strtotime($current_end_date))); ?>
                            <a href="<?php echo esc_url(remove_query_arg('end_date', $_SERVER['REQUEST_URI'])); ?>" class="remove-filter" aria-label="<?php esc_attr_e('Remove end date filter', 'splice-theme'); ?>">×</a>
                        </span>
                    <?php endif; ?>

                    <?php if ($current_category) : ?>
                        <span class="filter-tag">
                            <?php esc_html_e('Category:', 'splice-theme'); ?> <?php echo esc_html(get_term_by('slug', $current_category, 'project_category')->name); ?>
                            <a href="<?php echo esc_url(remove_query_arg('project_category', $_SERVER['REQUEST_URI'])); ?>" class="remove-filter" aria-label="<?php esc_attr_e('Remove category filter', 'splice-theme'); ?>">×</a>
                        </span>
                    <?php endif; ?>

                    <?php if ($current_tag) : ?>
                        <span class="filter-tag">
                            <?php esc_html_e('Tag:', 'splice-theme'); ?> <?php echo esc_html(get_term_by('slug', $current_tag, 'project_tag')->name); ?>
                            <a href="<?php echo esc_url(remove_query_arg('project_tag', $_SERVER['REQUEST_URI'])); ?>" class="remove-filter" aria-label="<?php esc_attr_e('Remove tag filter', 'splice-theme'); ?>">×</a>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when date inputs change
        const dateInputs = document.querySelectorAll('#start_date, #end_date');
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Show loading indicator
                const loadingIndicator = document.querySelector('.filter-loading');
                if (loadingIndicator) {
                    loadingIndicator.style.display = 'flex';
                }
                // Add a small delay to allow both dates to be set
                setTimeout(() => {
                    document.getElementById('project-filters-form').submit();
                }, 500);
            });
        });

        // Auto-submit form when category or tag changes
        const selectInputs = document.querySelectorAll('#project_category, #project_tag');
        selectInputs.forEach(select => {
            select.addEventListener('change', function() {
                // Show loading indicator
                const loadingIndicator = document.querySelector('.filter-loading');
                if (loadingIndicator) {
                    loadingIndicator.style.display = 'flex';
                }
                document.getElementById('project-filters-form').submit();
            });
        });
    });
</script>