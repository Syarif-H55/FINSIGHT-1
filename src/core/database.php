<?php
/**
 * FINSIGHT - Database Connection File
 * 
 * This file handles the database connection using PDO with prepared statements.
 * It provides a singleton pattern for database access throughout the application.
 * 
 * @author FINSIGHT Development Team
 * @version 1.0
 */

// Include configuration file
require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    public $pdo;

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {
        try {
            // Create PDO connection with MySQL
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch associative arrays by default
                PDO::ATTR_EMULATE_PREPARES => false, // Use actual prepared statements
            ];

            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log the error and show a generic message to prevent information disclosure
            error_log("Database connection failed: " . $e->getMessage());
            die("A database error occurred. Please contact system administrator.");
        }
    }

    /**
     * Get singleton instance of Database class
     * 
     * @return Database The database instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Prevent cloning of the Database instance
     */
    private function __clone() {}

    /**
     * Prevent unserialization of the Database instance
     */
    private function __wakeup() {}

    /**
     * Get PDO instance for direct use
     * 
     * @return PDO The PDO connection object
     */
    public function getConnection() {
        return $this->pdo;
    }
}

// Create a global database connection instance for easy access
$database = Database::getInstance();
$pdo = $database->getConnection();

/**
 * Helper function to execute prepared statements safely
 * 
 * @param string $sql The SQL query with placeholders
 * @param array $params The parameters to bind to the query
 * @return PDOStatement The executed statement
 */
function execute_query($sql, $params = []) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Database query error: " . $e->getMessage());
        throw new Exception("Database query failed");
    }
}

/**
 * Helper function to safely fetch all results from a prepared statement
 * 
 * @param string $sql The SQL query with placeholders
 * @param array $params The parameters to bind to the query
 * @return array The fetched results
 */
function fetch_all($sql, $params = []) {
    $stmt = execute_query($sql, $params);
    return $stmt->fetchAll();
}

/**
 * Helper function to safely fetch a single result from a prepared statement
 * 
 * @param string $sql The SQL query with placeholders
 * @param array $params The parameters to bind to the query
 * @return array|null The fetched result or null if not found
 */
function fetch_one($sql, $params = []) {
    $stmt = execute_query($sql, $params);
    return $stmt->fetch();
}

/**
 * Helper function to get the last inserted ID after an INSERT operation
 * 
 * @return string The last inserted ID
 */
function get_last_insert_id() {
    global $pdo;
    return $pdo->lastInsertId();
}

/**
 * Helper function to get the number of affected rows from the last query
 * 
 * @param PDOStatement $stmt The statement object
 * @return int The number of affected rows
 */
function get_affected_rows($stmt) {
    return $stmt->rowCount();
}
?>