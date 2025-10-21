<?php
/**
 * FINSIGHT - Authentication System
 * 
 * This file handles user authentication including login, logout, 
 * session management, and access control.
 * 
 * @author FINSIGHT Development Team
 * @version 1.0
 */

// Include required files
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/helpers.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'use_strict_mode' => true,
        // 'cookie_secure' => true, // Enable in production with HTTPS
    ]);
}

/**
 * Process user login
 * 
 * @param string $username The username provided by user
 * @param string $password The password provided by user
 * @return array Result of login attempt with success status and message
 */
function login_user($username, $password) {
    global $pdo;
    
    // Validate input
    if (!validate_not_empty($username) || !validate_not_empty($password)) {
        return [
            'success' => false,
            'message' => 'Username and password are required'
        ];
    }
    
    try {
        // Query user from database using prepared statement
        $stmt = $pdo->prepare("
            SELECT id, username, password_hash, role, full_name, is_active 
            FROM users 
            WHERE username = :username AND is_active = 1
        ");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if user exists and password is correct
        if ($user && password_verify($password, $user['password_hash'])) {
            // Regenerate session ID for security
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['last_activity'] = time();
            
            // Update last login in database
            $update_stmt = $pdo->prepare("
                UPDATE users 
                SET last_login = NOW() 
                WHERE id = :user_id
            ");
            $update_stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
            $update_stmt->execute();
            
            return [
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'full_name' => $user['full_name']
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Invalid username or password'
            ];
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Login failed due to system error'
        ];
    }
}

/**
 * Process user logout
 * 
 * @return void
 */
function logout_user() {
    // Unset all session variables
    $_SESSION = array();
    
    // Delete session cookie if it exists
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
}

/**
 * Check if user is authenticated and session is valid
 * 
 * @return bool True if user is logged in and session is valid, false otherwise
 */
function is_authenticated() {
    // Check if user ID is in session
    if (!isset($_SESSION['user_id']) || !$_SESSION['user_id']) {
        return false;
    }
    
    // Check if session has timed out
    if ((time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        logout_user();
        return false;
    }
    
    // Update last activity
    $_SESSION['last_activity'] = time();
    
    return true;
}

/**
 * Verify user has specific role access
 * 
 * @param string $required_role The required role
 * @return bool True if user has required role, false otherwise
 */
function require_role($required_role) {
    if (!is_authenticated()) {
        redirect('/modules/auth/login.php?error=unauthorized');
        return false;
    }
    
    if ($_SESSION['role'] !== $required_role) {
        redirect('/modules/auth/login.php?error=access_denied');
        return false;
    }
    
    return true;
}

/**
 * Verify user has access based on role hierarchy
 * 
 * @param string $min_role The minimum required role ('admin', 'staff', 'student')
 * @return bool True if user has access, false otherwise
 */
function require_access($min_role) {
    if (!is_authenticated()) {
        redirect('/modules/auth/login.php?error=unauthorized');
        return false;
    }
    
    $roles = ['student' => 1, 'staff' => 2, 'admin' => 3];
    $user_role_level = isset($roles[$_SESSION['role']]) ? $roles[$_SESSION['role']] : 0;
    $required_role_level = isset($roles[$min_role]) ? $roles[$min_role] : 0;
    
    if ($user_role_level < $required_role_level) {
        redirect('/modules/auth/login.php?error=access_denied');
        return false;
    }
    
    return true;
}

/**
 * Hash a password using PHP's password_hash function
 * 
 * @param string $password The plain text password
 * @return string The hashed password
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Validate and sanitize login form data
 * 
 * @param array $data The form data
 * @return array Validation errors or empty array if valid
 */
function validate_login_data($data) {
    $errors = [];
    
    if (!validate_not_empty($data['username'] ?? '')) {
        $errors[] = 'Username is required';
    }
    
    if (!validate_not_empty($data['password'] ?? '')) {
        $errors[] = 'Password is required';
    }
    
    return $errors;
}
?>