/**
 * Project Filters JavaScript
 * Handles enhanced filtering functionality with AJAX support
 *
 * @package Splice_Theme
 */

(function($) {
    'use strict';

    // Initialize filters when DOM is ready
    $(document).ready(function() {
        initializeProjectFilters();
    });

    /**
     * Initialize project filters functionality
     */
    function initializeProjectFilters() {
        // Add real-time search (optional)
        addRealTimeSearch();
        
        // Add date range validation
        addDateValidation();
        
        // Add filter persistence
        addFilterPersistence();
        
        // Add reset functionality
        addResetFunctionality();
    }

    /**
     * Add real-time search functionality (optional enhancement)
     */
    function addRealTimeSearch() {
        const $searchInput = $('#project_search');
        let searchTimeout;
        
        $searchInput.on('input', function() {
            const searchTerm = $(this).val();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Set new timeout for search
            searchTimeout = setTimeout(function() {
                if (searchTerm.length >= 2 || searchTerm.length === 0) {
                    // Show loading indicator
                    showLoadingIndicator();
                    // Auto-submit form for search terms >= 2 characters or empty
                    $('#project-filters-form').submit();
                }
            }, 800); // 800ms delay
        });
    }

    /**
     * Add date range validation
     */
    function addDateValidation() {
        const $startDate = $('#start_date');
        const $endDate = $('#end_date');
        
        // Validate end date is after start date
        $endDate.on('change', function() {
            const startDate = $startDate.val();
            const endDate = $(this).val();
            
            if (startDate && endDate && startDate > endDate) {
                alert('End date must be after start date');
                $(this).val('');
                return false;
            }
            
            // Show loading indicator and submit
            showLoadingIndicator();
        });
        
        // Validate start date is before end date
        $startDate.on('change', function() {
            const startDate = $(this).val();
            const endDate = $endDate.val();
            
            if (startDate && endDate && startDate > endDate) {
                alert('Start date must be before end date');
                $(this).val('');
                return false;
            }
            
            // Show loading indicator and submit
            showLoadingIndicator();
        });
    }
    
    /**
     * Show loading indicator
     */
    function showLoadingIndicator() {
        $('.filter-loading').show();
    }
    
    /**
     * Hide loading indicator
     */
    function hideLoadingIndicator() {
        $('.filter-loading').hide();
    }

    /**
     * Add filter persistence to localStorage
     */
    function addFilterPersistence() {
        const $form = $('#project-filters-form');
        const storageKey = 'splice_theme_project_filters';
        
        // Load saved filters on page load
        loadSavedFilters();
        
        // Save filters when form changes
        $form.on('change', 'input, select', function() {
            saveFilters();
        });
        
        /**
         * Save current filter values to localStorage
         */
        function saveFilters() {
            const formData = new FormData($form[0]);
            const filters = {};
            
            for (let [key, value] of formData.entries()) {
                if (value) {
                    filters[key] = value;
                }
            }
            
            localStorage.setItem(storageKey, JSON.stringify(filters));
        }
        
        /**
         * Load saved filters from localStorage
         */
        function loadSavedFilters() {
            const savedFilters = localStorage.getItem(storageKey);
            
            if (savedFilters) {
                try {
                    const filters = JSON.parse(savedFilters);
                    
                    // Apply saved filters to form
                    Object.keys(filters).forEach(key => {
                        const $field = $form.find(`[name="${key}"]`);
                        if ($field.length) {
                            $field.val(filters[key]);
                        }
                    });
                    
                    // Check if we should auto-submit (only if filters are different from URL)
                    const urlParams = new URLSearchParams(window.location.search);
                    let shouldSubmit = false;
                    
                    Object.keys(filters).forEach(key => {
                        if (urlParams.get(key) !== filters[key]) {
                            shouldSubmit = true;
                        }
                    });
                    
                    if (shouldSubmit) {
                        // Auto-submit form to apply saved filters
                        setTimeout(() => {
                            $form.submit();
                        }, 100);
                    }
                } catch (e) {
                    console.error('Error loading saved filters:', e);
                    localStorage.removeItem(storageKey);
                }
            }
        }
    }

    /**
     * AJAX filtering function (optional enhancement)
     */
    function ajaxFilterProjects(filters) {
        if (typeof spliceThemeFilters === 'undefined') {
            return;
        }
        
        $.ajax({
            url: spliceThemeFilters.ajaxUrl,
            type: 'POST',
            data: {
                action: 'filter_projects',
                nonce: spliceThemeFilters.nonce,
                ...filters
            },
            success: function(response) {
                if (response.success) {
                    updateProjectsDisplay(response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('Filter error:', error);
            }
        });
    }

    /**
     * Update projects display with AJAX results
     */
    function updateProjectsDisplay(data) {
        const $projectsGrid = $('.projects-grid');
        
        if (!$projectsGrid.length) {
            return;
        }
        
        // Clear current projects
        $projectsGrid.empty();
        
        // Add new projects
        if (data.projects && data.projects.length > 0) {
            data.projects.forEach(project => {
                const projectHtml = generateProjectHTML(project);
                $projectsGrid.append(projectHtml);
            });
        } else {
            $projectsGrid.html('<div class="no-projects"><h2>No projects found</h2><p>Try adjusting your filters.</p></div>');
        }
        
        // Update results count
        updateResultsCount(data.found_posts);
    }

    /**
     * Generate HTML for a project item
     */
    function generateProjectHTML(project) {
        const categories = project.categories ? project.categories.map(cat => `<a href="${cat.link}" class="project-category">${cat.name}</a>`).join('') : '';
        const tags = project.tags ? project.tags.map(tag => `<a href="${tag.link}" class="project-tag">${tag.name}</a>`).join('') : '';
        
        return `
            <article class="project-card fade-in-up">
                <div class="project-image">
                    ${project.thumbnail ? `<img src="${project.thumbnail}" alt="${project.title}" />` : ''}
                </div>
                <div class="project-content">
                    <h2 class="project-title">
                        <a href="${project.permalink}">${project.title}</a>
                    </h2>
                    <div class="project-excerpt">
                        ${project.excerpt}
                    </div>
                    <div class="project-meta">
                        ${categories}
                        ${tags}
                    </div>
                    <div class="project-dates">
                        ${project.start_date ? `<span class="start-date">Started: ${formatDate(project.start_date)}</span>` : ''}
                        ${project.end_date ? `<span class="end-date">Completed: ${formatDate(project.end_date)}</span>` : ''}
                    </div>
                </div>
            </article>
        `;
    }

    /**
     * Format date for display
     */
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    /**
     * Update results count display
     */
    function updateResultsCount(count) {
        const $countDisplay = $('.filtered-results-count');
        
        if ($countDisplay.length) {
            $countDisplay.text(`Showing ${count} projects`);
        }
    }

    /**
     * Add reset functionality
     */
    function addResetFunctionality() {
        const $resetButton = $('#reset-filters-btn');
        if ($resetButton.length) {
            $resetButton.on('click', function(e) {
                e.preventDefault();
                // Clear localStorage
                localStorage.removeItem('splice_theme_project_filters');
                // Redirect to clean archive URL
                window.location.href = $(this).attr('href');
            });
        }
    }

})(jQuery);
