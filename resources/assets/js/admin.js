/**
 * Laravel Admin Panel JavaScript
 */

(function() {
    'use strict';

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initConfirmDialogs();
        initAutoSlug();
        initCharacterCounter();
        initFormValidation();
        initTooltips();
    });

    /**
     * Initialize confirmation dialogs for delete actions
     */
    function initConfirmDialogs() {
        const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const message = this.getAttribute('onsubmit').match(/'([^']+)'/)[1];
                if (!confirm(message)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    }

    /**
     * Auto-generate slug from title
     */
    function initAutoSlug() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function() {
                if (slugInput.value === '' || slugInput.hasAttribute('data-auto')) {
                    const slug = generateSlug(this.value);
                    slugInput.value = slug;
                    slugInput.setAttribute('data-auto', 'true');
                }
            });

            slugInput.addEventListener('input', function() {
                if (this.value !== '') {
                    this.removeAttribute('data-auto');
                }
            });
        }
    }

    /**
     * Generate URL-friendly slug from string
     */
    function generateSlug(text) {
        return text
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    /**
     * Character counter for textareas
     */
    function initCharacterCounter() {
        const textareas = document.querySelectorAll('textarea[data-max-length]');
        
        textareas.forEach(textarea => {
            const maxLength = parseInt(textarea.getAttribute('data-max-length'));
            const counter = document.createElement('div');
            counter.className = 'text-sm text-gray-500 mt-1';
            textarea.parentNode.appendChild(counter);

            function updateCounter() {
                const remaining = maxLength - textarea.value.length;
                counter.textContent = `${remaining} characters remaining`;
                
                if (remaining < 0) {
                    counter.classList.add('text-red-500');
                    counter.classList.remove('text-gray-500');
                } else {
                    counter.classList.add('text-gray-500');
                    counter.classList.remove('text-red-500');
                }
            }

            textarea.addEventListener('input', updateCounter);
            updateCounter();
        });
    }

    /**
     * Client-side form validation
     */
    function initFormValidation() {
        const forms = document.querySelectorAll('form[data-validate]');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                        showFieldError(field, 'This field is required');
                    } else {
                        field.classList.remove('border-red-500');
                        hideFieldError(field);
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    scrollToFirstError();
                }
            });
        });
    }

    /**
     * Show field error message
     */
    function showFieldError(field, message) {
        hideFieldError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-500 text-sm mt-1 field-error';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    /**
     * Hide field error message
     */
    function hideFieldError(field) {
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
    }

    /**
     * Scroll to first error on page
     */
    function scrollToFirstError() {
        const firstError = document.querySelector('.border-red-500');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }

    /**
     * Initialize tooltips
     */
    function initTooltips() {
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        
        tooltipElements.forEach(element => {
            const tooltipText = element.getAttribute('data-tooltip');
            
            element.addEventListener('mouseenter', function() {
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute bg-gray-900 text-white text-xs rounded py-1 px-2 z-50';
                tooltip.textContent = tooltipText;
                tooltip.style.top = '-30px';
                tooltip.style.left = '50%';
                tooltip.style.transform = 'translateX(-50%)';
                
                this.style.position = 'relative';
                this.appendChild(tooltip);
            });

            element.addEventListener('mouseleave', function() {
                const tooltip = this.querySelector('.absolute');
                if (tooltip) {
                    tooltip.remove();
                }
            });
        });
    }

    /**
     * Show success message
     */
    window.showSuccessMessage = function(message) {
        showMessage(message, 'success');
    };

    /**
     * Show error message
     */
    window.showErrorMessage = function(message) {
        showMessage(message, 'error');
    };

    /**
     * Show message notification
     */
    function showMessage(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} fade-in`;
        alertDiv.textContent = message;
        
        const container = document.querySelector('.max-w-7xl');
        if (container) {
            container.insertBefore(alertDiv, container.firstChild);
            
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 300);
            }, 5000);
        }
    }

    /**
     * AJAX helper function
     */
    window.adminAjax = function(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        return fetch(url, { ...defaultOptions, ...options })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            });
    };

})();
