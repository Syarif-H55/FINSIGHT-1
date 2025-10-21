<?php
/**
 * FINSIGHT - Logout Handler
 * 
 * This file handles user logout functionality.
 * 
 * @author FINSIGHT Development Team
 * @version 1.0
 */

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/auth.php';

// Process logout
logout_user();

// Redirect to login page with success message
header('Location: ' . BASE_URL . '/modules/auth/login.php?message=logged_out');
exit();
?>