/**
 * Navigation functionality for Splice Theme
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const siteNavigation = document.querySelector('.site-navigation');
    
    if (mobileMenuToggle && siteNavigation) {
        mobileMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            siteNavigation.classList.toggle('toggled');
            this.setAttribute('aria-expanded', 
                this.getAttribute('aria-expanded') === 'false' ? 'true' : 'false'
            );
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (siteNavigation && !siteNavigation.contains(e.target) && !mobileMenuToggle?.contains(e.target)) {
            siteNavigation.classList.remove('toggled');
            if (mobileMenuToggle) {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
            }
        }
    });

    // Dropdown functionality
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(function(dropdown) {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (toggle && menu) {
            // Desktop hover
            if (window.innerWidth > 768) {
                dropdown.addEventListener('mouseenter', function() {
                    this.classList.add('open');
                });
                
                dropdown.addEventListener('mouseleave', function() {
                    this.classList.remove('open');
                });
            }
            
            // Mobile click
            if (window.innerWidth <= 768) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropdown.classList.toggle('open');
                });
            }
        }
    });

    // Add loading states to buttons
    const buttons = document.querySelectorAll('.cta-button, .project-link, .category-link');
    
    buttons.forEach(function(button) {
        button.addEventListener('click', function() {
            if (!this.classList.contains('loading')) {
                this.classList.add('loading');
                
                // Remove loading state after navigation
                setTimeout(() => {
                    this.classList.remove('loading');
                }, 2000);
            }
        });
    });

    // Responsive navigation adjustments
    function handleResize() {
        const isMobile = window.innerWidth <= 768;
        
        dropdowns.forEach(function(dropdown) {
            if (isMobile) {
                dropdown.classList.remove('open');
            }
        });
        
        if (siteNavigation && isMobile) {
            siteNavigation.classList.remove('toggled');
        }
    }

    // Debounced resize handler
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(handleResize, 250);
    });

    // Initialize on page load
    handleResize();

    // Intersection Observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Observe all fade-in-up elements
    const fadeElements = document.querySelectorAll('.fade-in-up');
    fadeElements.forEach(function(element) {
        observer.observe(element);
    });
});

// Add mobile menu toggle button to header if not present
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.header-content');
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    
    if (header && !mobileToggle) {
        const toggleButton = document.createElement('button');
        toggleButton.className = 'mobile-menu-toggle';
        toggleButton.setAttribute('aria-controls', 'primary-menu');
        toggleButton.setAttribute('aria-expanded', 'false');
        toggleButton.innerHTML = `
            <span class="screen-reader-text">Menu</span>
            <span class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </span>
        `;
        
        header.appendChild(toggleButton);
        
        // Add event listener to the newly created button
        toggleButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const siteNavigation = document.querySelector('.site-navigation');
            if (siteNavigation) {
                siteNavigation.classList.toggle('toggled');
                this.setAttribute('aria-expanded', 
                    this.getAttribute('aria-expanded') === 'false' ? 'true' : 'false'
                );
            }
        });
    }
});
