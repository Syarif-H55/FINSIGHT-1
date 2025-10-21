/**
 * FINSIGHT - Main JavaScript
 * 
 * This file contains the main JavaScript functionality for the FINSIGHT application.
 * 
 * @author FINSIGHT Development Team
 * @version 1.0
 */

// Document ready function equivalent
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Handle active navigation links
    updateActiveNav();
    
    // Set up auto-refresh for important data
    setupAutoRefresh();
});

/**
 * Update active navigation based on current page
 */
function updateActiveNav() {
    var currentUrl = window.location.href;
    var navLinks = document.querySelectorAll('.navbar-nav a, .list-group-item');
    
    navLinks.forEach(function(link) {
        if (currentUrl.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });
}

/**
 * Set up auto-refresh for important data
 */
function setupAutoRefresh() {
    // In a real application, this would periodically fetch updated data
    // For now, we'll just set up a placeholder
    
    // Refresh balance data every 5 minutes
    setInterval(function() {
        console.log('Refreshing balance data...');
    }, 5 * 60 * 1000);
}

/**
 * Show a notification message
 * @param {string} message - The message to display
 * @param {string} type - The type of message (success, info, warning, danger)
 */
function showNotification(message, type) {
    // Create notification element
    var notification = document.createElement('div');
    notification.className = 'alert alert-' + (type || 'info') + ' alert-dismissible fade show position-fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.innerHTML = message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    
    // Add to body
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(function() {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

/**
 * Format currency for display
 * @param {number} amount - The amount to format
 * @return {string} - Formatted currency string
 */
function formatCurrency(amount) {
    return 'Rp ' + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

/**
 * Validate form inputs
 * @param {HTMLElement} form - The form element to validate
 * @return {boolean} - True if form is valid, false otherwise
 */
function validateForm(form) {
    var inputs = form.querySelectorAll('[required]');
    var isValid = true;
    
    inputs.forEach(function(input) {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

/**
 * Handle logout with confirmation
 */
function confirmLogout() {
    if (confirm('Are you sure you want to log out?')) {
        window.location.href = 'modules/auth/logout.php';
    }
}

// Export functions if using modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        showNotification: showNotification,
        formatCurrency: formatCurrency,
        validateForm: validateForm
    };
}