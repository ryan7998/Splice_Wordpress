    /**
    * Add remove filter functionality for individual filter tags
    */
    function addRemoveFilterFunctionality() {
    $(document).on('click', '.remove-filter', function(e) {
    e.preventDefault();

    const $filterTag = $(this).closest('.filter-tag');
    const filterType = $filterTag.data('filter-type');

    if (filterType) {
    // Remove the specific filter
    removeSpecificFilter(filterType);
    }
    });
    }

    /**
    * Remove a specific filter and update the form
    */
    function removeSpecificFilter(filterType) {
    const $form = $('#project-filters-form');
    const $field = $form.find(`[name="${filterType}"]`);

    if ($field.length) {
    // Clear the field value
    $field.val('');

    // Update localStorage
    updateLocalStorageAfterRemoval(filterType);

    // Show loading indicator
    showLoadingIndicator();

    // Submit the form to apply the updated filters
    setTimeout(() => {
    $form.submit();
    }, 100);
    }
    }