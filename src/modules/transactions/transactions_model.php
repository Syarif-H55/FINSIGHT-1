<?php
/**
 * FINSIGHT - Transactions Model
 * 
 * This file contains database operations for transactions using prepared statements.
 * This is an example of how database operations should be structured in the application.
 * 
 * @author FINSIGHT Development Team
 * @version 1.0
 */

// Include core files
require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/database.php';
require_once __DIR__ . '/../core/helpers.php';

/**
 * Get all transactions for a specific user
 * 
 * @param int $user_id The ID of the user
 * @param int $limit Number of records to return (default 50)
 * @param int $offset Number of records to skip (for pagination)
 * @return array Array of transaction records
 */
function get_user_transactions($user_id, $limit = 50, $offset = 0) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT * FROM transactions 
        WHERE user_id = :user_id 
        ORDER BY transaction_date DESC, created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get a specific transaction by ID for a user
 * 
 * @param int $transaction_id The ID of the transaction
 * @param int $user_id The ID of the user (for security)
 * @return array|null The transaction record or null if not found
 */
function get_transaction_by_id($transaction_id, $user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT * FROM transactions 
        WHERE id = :transaction_id AND user_id = :user_id
    ");
    
    $stmt->bindValue(':transaction_id', $transaction_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Add a new transaction to the database
 * 
 * @param array $data Transaction data including user_id, amount, type, category, etc.
 * @return int The ID of the newly inserted transaction
 */
function add_transaction($data) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        INSERT INTO transactions 
        (user_id, amount, type, category, description, transaction_date) 
        VALUES (:user_id, :amount, :type, :category, :description, :transaction_date)
    ");
    
    $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':amount', $data['amount'], PDO::PARAM_STR); // Using STR to preserve decimal precision
    $stmt->bindValue(':type', $data['type'], PDO::PARAM_STR);
    $stmt->bindValue(':category', $data['category'], PDO::PARAM_STR);
    $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
    $stmt->bindValue(':transaction_date', $data['transaction_date'], PDO::PARAM_STR);
    
    $stmt->execute();
    
    return $pdo->lastInsertId();
}

/**
 * Update an existing transaction
 * 
 * @param int $transaction_id The ID of the transaction to update
 * @param array $data Updated transaction data
 * @param int $user_id The ID of the user (for security)
 * @return bool True if successful, false otherwise
 */
function update_transaction($transaction_id, $data, $user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        UPDATE transactions 
        SET amount = :amount, type = :type, category = :category, 
            description = :description, transaction_date = :transaction_date
        WHERE id = :transaction_id AND user_id = :user_id
    ");
    
    $stmt->bindValue(':transaction_id', $transaction_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':amount', $data['amount'], PDO::PARAM_STR);
    $stmt->bindValue(':type', $data['type'], PDO::PARAM_STR);
    $stmt->bindValue(':category', $data['category'], PDO::PARAM_STR);
    $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
    $stmt->bindValue(':transaction_date', $data['transaction_date'], PDO::PARAM_STR);
    
    $stmt->execute();
    
    return $stmt->rowCount() > 0;
}

/**
 * Delete a transaction by ID
 * 
 * @param int $transaction_id The ID of the transaction to delete
 * @param int $user_id The ID of the user (for security)
 * @return bool True if successful, false otherwise
 */
function delete_transaction($transaction_id, $user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        DELETE FROM transactions 
        WHERE id = :transaction_id AND user_id = :user_id
    ");
    
    $stmt->bindValue(':transaction_id', $transaction_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    
    $stmt->execute();
    
    return $stmt->rowCount() > 0;
}

/**
 * Get transaction statistics for a user
 * 
 * @param int $user_id The ID of the user
 * @param string $date_from Start date (Y-m-d format)
 * @param string $date_to End date (Y-m-d format)
 * @return array Statistics about income and expenses
 */
function get_transaction_stats($user_id, $date_from = null, $date_to = null) {
    global $pdo;
    
    $sql = "
        SELECT 
            COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
            COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense,
            (COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) - 
             COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0)) as balance
        FROM transactions 
        WHERE user_id = :user_id
    ";
    
    // Add date filters if provided
    if ($date_from && $date_to) {
        $sql .= " AND transaction_date BETWEEN :date_from AND :date_to";
    } elseif ($date_from) {
        $sql .= " AND transaction_date >= :date_from";
    } elseif ($date_to) {
        $sql .= " AND transaction_date <= :date_to";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    
    if ($date_from) {
        $stmt->bindValue(':date_from', $date_from, PDO::PARAM_STR);
    }
    if ($date_to) {
        $stmt->bindValue(':date_to', $date_to, PDO::PARAM_STR);
    }
    
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get total amount by category for a user
 * 
 * @param int $user_id The ID of the user
 * @param string $type Transaction type ('income' or 'expense')
 * @param string $date_from Start date (Y-m-d format)
 * @param string $date_to End date (Y-m-d format)
 * @return array Array of category totals
 */
function get_category_totals($user_id, $type, $date_from = null, $date_to = null) {
    global $pdo;
    
    $sql = "
        SELECT category, SUM(amount) as total
        FROM transactions 
        WHERE user_id = :user_id AND type = :type
    ";
    
    // Add date filters if provided
    if ($date_from && $date_to) {
        $sql .= " AND transaction_date BETWEEN :date_from AND :date_to";
    } elseif ($date_from) {
        $sql .= " AND transaction_date >= :date_from";
    } elseif ($date_to) {
        $sql .= " AND transaction_date <= :date_to";
    }
    
    $sql .= " GROUP BY category ORDER BY total DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':type', $type, PDO::PARAM_STR);
    
    if ($date_from) {
        $stmt->bindValue(':date_from', $date_from, PDO::PARAM_STR);
    }
    if ($date_to) {
        $stmt->bindValue(':date_to', $date_to, PDO::PARAM_STR);
    }
    
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>