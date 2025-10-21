<?php
/**
 * FINSIGHT - Main Application Entry Point
 * 
 * This is the main entry point for the FINSIGHT application.
 * It handles routing based on the requested module and action.
 * 
 * @author FINSIGHT Development Team
 * @version 1.0
 */

// Include core files
require_once __DIR__ . '/core/config.php';
require_once __DIR__ . '/core/database.php';
require_once __DIR__ . '/core/auth.php';
require_once __DIR__ . '/core/helpers.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'use_strict_mode' => true,
        // 'cookie_secure' => true, // Enable in production with HTTPS
    ]);
}

// Get the requested module and action from URL
$module = $_GET['module'] ?? 'reports';
$action = $_GET['action'] ?? 'dashboard';

// Define allowed modules for security
$allowed_modules = [
    'auth' => [
        'login', 'logout', 'register'
    ],
    'transactions' => [
        'list', 'add', 'edit', 'delete'
    ],
    'budgets' => [
        'manage', 'report', 'create', 'update', 'delete'
    ],
    'goals' => [
        'create', 'track', 'update', 'delete'
    ],
    'reports' => [
        'dashboard', 'monthly', 'yearly', 'export'
    ]
];

// Validate module and action
if (!isset($allowed_modules[$module]) || !in_array($action, $allowed_modules[$module])) {
    // Default to dashboard if invalid module/action
    $module = 'reports';
    $action = 'dashboard';
}

// Check authentication for non-auth modules
if ($module !== 'auth') {
    if (!is_authenticated()) {
        redirect('/modules/auth/login.php?error=unauthorized');
        exit();
    }
    
    // Check session timeout
    if ((time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        logout_user();
        redirect('/modules/auth/login.php?error=timeout');
        exit();
    }
}

// Set the file path based on module and action
$module_path = MODULES_PATH . '/' . $module . '/' . $action . '.php';

// Check if the requested file exists
if (file_exists($module_path)) {
    // Include the requested module file
    require_once $module_path;
} else {
    // If file doesn't exist, show error or redirect
    http_response_code(404);
    echo '<h1>Page Not Found</h1>';
    echo '<p>The requested page does not exist.</p>';
    echo '<a href="' . BASE_URL . '/index.php">Go to Dashboard</a>';
}
?>