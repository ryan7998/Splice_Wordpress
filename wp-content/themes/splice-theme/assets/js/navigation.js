/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
(function() {
    'use strict';
    
    const siteNavigation = document.getElementById('site-navigation');
    const menuToggle = document.querySelector('.menu-toggle');
    const primaryMenu = document.getElementById('primary-menu');

    // Return early if the navigation doesn't exist.
    if (!siteNavigation) {
        console.warn('Site navigation element not found');
        return;
    }

    // Return early if the menu toggle button doesn't exist.
    if (!menuToggle) {
        console.warn('Menu toggle button not found');
        return;
    }

    // Return early if the primary menu doesn't exist.
    if (!primaryMenu) {
        console.warn('Primary menu element not found');
        return;
    }

    // Toggle the .toggled class and the aria-expanded value each time the button is clicked.
    menuToggle.addEventListener('click', function() {
        siteNavigation.classList.toggle('toggled');
        primaryMenu.classList.toggle('toggled');

        if (menuToggle.getAttribute('aria-expanded') === 'true') {
            menuToggle.setAttribute('aria-expanded', 'false');
        } else {
            menuToggle.setAttribute('aria-expanded', 'true');
        }
    });

    // Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
    document.addEventListener('click', function(event) {
        const isClickInside = siteNavigation.contains(event.target);

        if (!isClickInside) {
            siteNavigation.classList.remove('toggled');
            primaryMenu.classList.remove('toggled');
            menuToggle.setAttribute('aria-expanded', 'false');
        }
    });

    // Handle dropdown menus
    const dropdownItems = primaryMenu.querySelectorAll('.dropdown');
    
    dropdownItems.forEach(function(dropdown) {
        const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
        const dropdownMenu = dropdown.querySelector('.dropdown-menu');
        
        if (dropdownToggle && dropdownMenu) {
            // Desktop: Hover functionality
            dropdown.addEventListener('mouseenter', function() {
                if (window.innerWidth > 768) {
                    dropdown.classList.add('open');
                    dropdownToggle.setAttribute('aria-expanded', 'true');
                }
            });
            
            dropdown.addEventListener('mouseleave', function() {
                if (window.innerWidth > 768) {
                    dropdown.classList.remove('open');
                    dropdownToggle.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Mobile: Click functionality
            dropdownToggle.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    dropdown.classList.toggle('open');
                    
                    const isExpanded = dropdownToggle.getAttribute('aria-expanded') === 'true';
                    dropdownToggle.setAttribute('aria-expanded', !isExpanded);
                }
            });
        }
    });

    // Get all the link elements within the menu.
    const links = primaryMenu.getElementsByTagName('a');

    // Get all the link elements with children within the menu.
    const linksWithChildren = primaryMenu.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a');

    // Toggle focus each time a menu link is focused or blurred.
    for (const link of links) {
        link.addEventListener('focus', toggleFocus, true);
        link.addEventListener('blur', toggleFocus, true);
    }

    // Toggle focus each time a menu link with children receive a touch event.
    for (const link of linksWithChildren) {
        link.addEventListener('touchstart', toggleFocus, false);
    }

    /**
     * Sets or removes .focus class on an element.
     */
    function toggleFocus() {
        if (event.type === 'focus' || event.type === 'blur') {
            let self = this;
            // Move up through the ancestors of the current link until we hit .nav-menu.
            while (!self.classList.contains('nav-menu')) {
                // On li elements toggle the class .focus.
                if ('li' === self.tagName.toLowerCase()) {
                    self.classList.toggle('focus');
                }
                self = self.parentNode;
            }
        }

        if (event.type === 'touchstart') {
            const menuItem = this.parentNode;
            event.preventDefault();
            for (const link of menuItem.parentNode.children) {
                if (menuItem !== link) {
                    link.classList.remove('focus');
                }
            }
            menuItem.classList.toggle('focus');
        }
    }

    // Close mobile menu when clicking on a link
    for (const link of links) {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                // Don't close if it's a dropdown toggle
                if (!this.classList.contains('dropdown-toggle')) {
                    siteNavigation.classList.remove('toggled');
                    primaryMenu.classList.remove('toggled');
                    menuToggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            // Reset mobile menu state on desktop
            siteNavigation.classList.remove('toggled');
            primaryMenu.classList.remove('toggled');
            menuToggle.setAttribute('aria-expanded', 'false');
            
            // Close all dropdowns on desktop resize
            dropdownItems.forEach(function(dropdown) {
                dropdown.classList.remove('open');
                const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
                if (dropdownToggle) {
                    dropdownToggle.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });

    // Initialize navigation on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Navigation initialized successfully');
        
        // Add some visual feedback for dropdowns
        dropdownItems.forEach(function(dropdown) {
            const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
            if (dropdownToggle) {
                // Add a subtle indicator that this is a dropdown
                dropdownToggle.style.cursor = 'pointer';
            }
        });
    });
})();
