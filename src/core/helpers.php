<?php
/**
 * FINSIGHT - Helper Functions
 * 
 * This file contains utility functions used throughout the application.
 * These functions help with input validation, data sanitization, 
 * and other common tasks.
 * 
 * @author FINSIGHT Development Team
 * @version 1.0
 */

// Include configuration file
require_once __DIR__ . '/config.php';

/**
 * Sanitize user input to prevent XSS attacks
 * 
 * @param string $input The input to sanitize
 * @return string The sanitized input
 */
function sanitize_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

/**
 * Validate email format
 * 
 * @param string $email The email to validate
 * @return bool True if valid, false otherwise
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate that a string is not empty
 * 
 * @param string $value The value to validate
 * @return bool True if valid, false otherwise
 */
function validate_not_empty($value) {
    return !empty(trim($value));
}

/**
 * Validate that an amount is numeric and positive
 * 
 * @param mixed $amount The amount to validate
 * @return bool True if valid, false otherwise
 */
function validate_amount($amount) {
    return is_numeric($amount) && $amount > 0;
}

/**
 * Validate date format
 * 
 * @param string $date The date to validate
 * @param string $format The expected format (default: Y-m-d)
 * @return bool True if valid, false otherwise
 */
function validate_date($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Redirect user to a specific page
 * 
 * @param string $location The location to redirect to
 */
function redirect($location) {
    header("Location: " . BASE_URL . $location);
    exit();
}

/**
 * Generate a random string for security tokens
 * 
 * @param int $length The length of the string to generate
 * @return string The generated random string
 */
function generate_random_string($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Check if user is logged in
 * 
 * @return bool True if logged in, false otherwise
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
}

/**
 * Check if user has specific role
 * 
 * @param string $role The role to check
 * @return bool True if user has the role, false otherwise
 */
function has_role($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Get current user ID
 * 
 * @return int|null The user ID if logged in, null otherwise
 */
function get_current_user_id() {
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
}

/**
 * Get current user role
 * 
 * @return string|null The user role if logged in, null otherwise
 */
function get_current_user_role() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}

/**
 * Check if current session is active
 * 
 * @return bool True if session is valid, false otherwise
 */
function is_session_valid() {
    if (!isset($_SESSION['last_activity'])) {
        return false;
    }
    
    // Check if session has timed out
    if ((time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        return false;
    }
    
    return true;
}

/**
 * Format currency for display
 * 
 * @param float $amount The amount to format
 * @param string $currency The currency code (default: IDR)
 * @return string Formatted currency string
 */
function format_currency($amount, $currency = 'IDR') {
    switch ($currency) {
        case 'IDR':
            return 'Rp ' . number_format($amount, 2, ',', '.');
        case 'USD':
            return '$' . number_format($amount, 2);
        default:
            return number_format($amount, 2);
    }
}

/**
 * Format date for display
 * 
 * @param string $date The date to format
 * @param string $format The output format (default: d/m/Y)
 * @return string Formatted date string
 */
function format_date($date, $format = 'd/m/Y') {
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return $date; // Return original if invalid
    }
    return date($format, $timestamp);
}

/**
 * Generate a CSRF token for form security
 * 
 * @return string The CSRF token
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = generate_random_string(32);
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate a CSRF token
 * 
 * @param string $token The token to validate
 * @return bool True if valid, false otherwise
 */
function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>