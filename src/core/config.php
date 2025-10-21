<?php
/**
 * FINSIGHT - Configuration File
 * 
 * This file contains all the configuration settings for the FINSIGHT application.
 * It defines database credentials, application settings, and other constants.
 * 
 * @author FINSIGHT Development Team
 * @version 1.0
 */

// Application Settings
define('APP_NAME', 'FINSIGHT');
define('APP_VERSION', '1.0');
define('BASE_URL', 'http://localhost:8080');

// Database Configuration - Using environment variables if available, defaults otherwise
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'finsight');
define('DB_USER', getenv('DB_USER') ?: 'finsight_user');
define('DB_PASS', getenv('DB_PASS') ?: 'finsight_pass');
define('DB_PORT', getenv('DB_PORT') ?: '3306');

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
// Note: session.cookie_secure should be enabled in production with HTTPS

// Security Settings
define('SESSION_TIMEOUT', 1800); // 30 minutes in seconds

// Application Paths
define('CORE_PATH', __DIR__);
define('MODULES_PATH', dirname(__DIR__) . '/modules');
define('TEMPLATES_PATH', dirname(__DIR__) . '/templates');
define('ASSETS_PATH', dirname(__DIR__) . '/assets');

// Error Reporting - only show errors in development
if (getenv('APP_ENV') === 'development' || !getenv('APP_ENV')) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}
?>