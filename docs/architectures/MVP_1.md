# FINSIGHT - System Architecture Design

## 📋 Deskripsi Sistem

**FINSIGHT** adalah web aplikasi manajemen keuangan berbasis universitas yang menyediakan:

### Fungsi Utama
- **Manajemen Transaksi**: Pencatatan pemasukan dan pengeluaran
- **Manajemen Anggaran**: Pembuatan dan monitoring budget
- **Tujuan Keuangan**: Setting target dan tracking progress
- **Analitik Sederhana**: Dashboard dan laporan keuangan

### Jenis Pengguna
1. **Admin Universitas**: Super user dengan akses penuh
2. **Staf Keuangan**: Manage transaksi institusional dan laporan
3. **Mahasiswa**: Manage keuangan pribadi dan monitoring

## 🏗️ Rancangan Arsitektur Aplikasi

### Arsitektur 3-Tier
```
+-------------+    +----------------+    +---------------+
|   Client    | -> |   PHP Backend  | -> | MySQL Database|
| (Browser)   |    | (Apache/PHP)   |    |               |
+-------------+    +----------------+    +---------------+
       ↑                  ↑                      ↑
    HTML/CSS/JS      PHP Modules           Database Queries
    JavaScript        Session Mgmt          Data Persistence
```

### Alur Request-Response
```
User Request → index.php → Router → Module Controller → Database → Response
      ↑                                                                  ↓
      +---------------------- Session Check -----------------------------+
```

### Diagram Komponen
```
+----------------+      +-----------------+      +-----------------+
|    FRONTEND    |      |    BACKEND      |      |   DATABASE      |
|                |      |                 |      |                 |
| HTML/CSS/JS    |<---->| PHP Modules     |<---->| MySQL Tables    |
| - Dashboard    | HTTP | - Auth          | SQL  | - users         |
| - Forms        |      | - Transactions  |      | - transactions  |
| - Charts       |      | - Budgets       |      | - budgets       |
|                |      | - Reports       |      | - goals         |
+----------------+      +-----------------+      +-----------------+
```

## 🗃️ Rancangan Basis Data

### ERD - Entity Relationship Diagram
```
users
├── transactions (1:N)
├── budgets (1:N)  
└── goals (1:N)
```

### Skema SQL

```sql
-- Tabel Users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin', 'staff', 'student') NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    department VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- Tabel Transactions
CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    transaction_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_date (user_id, transaction_date)
);

-- Tabel Budgets
CREATE TABLE budgets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    category VARCHAR(50) NOT NULL,
    allocated_amount DECIMAL(10,2) NOT NULL,
    period ENUM('monthly', 'yearly') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_budget (user_id, category, period)
);

-- Tabel Goals
CREATE TABLE goals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    target_amount DECIMAL(10,2) NOT NULL,
    current_amount DECIMAL(10,2) DEFAULT 0,
    deadline DATE NOT NULL,
    description TEXT,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## 🔄 Alur Fitur Utama

### 1. Proses Login
```php
// Pseudocode Login Flow
START login_process
    GET username, password from form
    VALIDATE input not empty
    QUERY users WHERE username = input_username AND is_active = TRUE
    IF user_exists AND password_verify THEN
        CREATE session with user_id, role, permissions
        UPDATE last_login timestamp
        REDIRECT to dashboard
    ELSE
        SET error message
        REDIRECT back to login page
    END IF
END
```

### 2. Input Transaksi
```php
// Pseudocode Transaction Flow
START add_transaction
    VERIFY user session
    GET transaction_data from form
    SANITIZE and VALIDATE inputs
    CALCULATE new balance
    INSERT into transactions table
    UPDATE related budgets if expense
    CHECK and UPDATE goals if income
    RETURN success/error message
END
```

### 3. Perhitungan Saldo Otomatis
```php
// Function: Calculate Current Balance
function calculate_balance($user_id) {
    $income = SUM(amount) FROM transactions 
              WHERE user_id = $user_id AND type = 'income'
    
    $expense = SUM(amount) FROM transactions 
               WHERE user_id = $user_id AND type = 'expense'
    
    return $income - $expense;
}

// Function: Budget Utilization
function budget_utilization($user_id, $category, $month) {
    $budget = SELECT allocated_amount FROM budgets 
              WHERE user_id = $user_id AND category = $category 
              AND period = 'monthly'
    
    $spent = SUM(amount) FROM transactions 
             WHERE user_id = $user_id AND category = $category 
             AND type = 'expense' AND MONTH(transaction_date) = $month
    
    return ($spent / $budget) * 100;
}
```

## 📁 Struktur Folder dan File

```
/finsight/
├── /docs/
│   └── /architecture/
│       └── system_design.md
|   └── /ai_output/
├── /src/
│   ├── /core/
│   │   ├── config.php
│   │   ├── database.php
│   │   ├── auth.php
│   │   └── helpers.php
│   ├── /modules/
│   │   ├── /auth/
│   │   │   ├── login.php
│   │   │   ├── logout.php
│   │   │   └── register.php
│   │   ├── /transactions/
│   │   │   ├── add.php
│   │   │   ├── list.php
│   │   │   └── delete.php
│   │   ├── /budgets/
│   │   │   ├── manage.php
│   │   │   └── report.php
│   │   ├── /goals/
│   │   │   ├── create.php
│   │   │   └── track.php
│   │   └── /reports/
│   │       ├── dashboard.php
│   │       └── export.php
│   ├── /templates/
│   │   ├── header.php
│   │   ├── footer.php
│   │   ├── sidebar.php
│   │   └── /partials/
│   ├── /assets/
│   │   ├── /css/
│   │   ├── /js/
│   │   └── /images/
│   └── index.php
├── /uploads/ (if needed for receipts)
├── docker-compose.yml
└── README.md
```

## 🔌 Komunikasi Antar Modul & API

### API Internal Structure
```php
// Example: Analytics Module Endpoint
// /src/modules/analytics/dashboard_data.php

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$month = $_GET['month'] ?? date('Y-m');

$data = [
    'current_balance' => calculate_balance($user_id),
    'monthly_income' => get_monthly_total($user_id, 'income', $month),
    'monthly_expense' => get_monthly_total($user_id, 'expense', $month),
    'budget_utilization' => get_budget_utilization($user_id, $month),
    'goals_progress' => get_goals_progress($user_id)
];

echo json_encode($data);
```

### Suggestion for AI Analytics Integration
```php
// Future: AI Spending Prediction Endpoint
// POST /api/analytics/predict-spending
{
    "user_id": 123,
    "historical_data": "6_months",
    "prediction_period": "next_30_days"
}

// Response
{
    "predicted_spending": 1500.00,
    "confidence_level": 0.85,
    "risk_categories": ["dining", "entertainment"]
}
```

## 🐳 Konfigurasi Docker

### docker-compose.yml
```yaml
version: '3.8'

services:
  web:
    image: php:8.1-apache
    container_name: finsight-web
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
      - ./uploads:/var/www/html/uploads
    networks:
      - finsight-network
    depends_on:
      - db

  db:
    image: mysql:8.0
    container_name: finsight-db
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: finsight
      MYSQL_USER: finsight_user
      MYSQL_PASSWORD: finsight_pass
    volumes:
      - db_data:/var/lib/mysql
      - ./database/init.sql:/docker-entrypoint-initdb.d/init.sql
    ports:
      - "3306:3306"
    networks:
      - finsight-network

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: finsight-pma
    environment:
      PMA_HOST: db
      PMA_PORT: 3306
    ports:
      - "8081:80"
    networks:
      - finsight-network
    depends_on:
      - db

volumes:
  db_data:

networks:
  finsight-network:
    driver: bridge
```

## 🔒 Standar Keamanan Dasar

### 1. Validasi Input
```php
function validate_transaction_input($data) {
    $errors = [];
    
    // Amount validation
    if (!is_numeric($data['amount']) || $data['amount'] <= 0) {
        $errors[] = "Amount must be a positive number";
    }
    
    // Date validation
    if (!strtotime($data['transaction_date'])) {
        $errors[] = "Invalid date format";
    }
    
    // Category validation against whitelist
    $allowed_categories = ['food', 'transport', 'entertainment', 'education'];
    if (!in_array($data['category'], $allowed_categories)) {
        $errors[] = "Invalid category";
    }
    
    return $errors;
}
```

### 2. Sanitasi Query SQL
```php
// Using prepared statements
function get_user_transactions($user_id, $limit = 50) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT * FROM transactions 
        WHERE user_id = :user_id 
        ORDER BY transaction_date DESC 
        LIMIT :limit
    ");
    
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

### 3. Session Management
```php
// Session configuration
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true, // Enable in production with HTTPS
    'use_strict_mode' => true
]);

// Regenerate session ID after login
function secure_session_regenerate() {
    session_regenerate_id(true);
    $_SESSION['last_activity'] = time();
}

// Session timeout (30 minutes)
function check_session_timeout() {
    $timeout = 1800; // 30 minutes in seconds
    if (isset($_SESSION['last_activity']) && 
        (time() - $_SESSION['last_activity']) > $timeout) {
        session_unset();
        session_destroy();
        header('Location: /login.php?timeout=1');
        exit;
    }
    $_SESSION['last_activity'] = time();
}
```

## 🚀 Implementasi Langkah Selanjutnya

1. **Setup Environment**: Clone repo dan run `docker-compose up`
2. **Database Initialization**: Eksekusi script SQL untuk membuat tabel
3. **Core Development**: Implementasi modul auth dan database connection
4. **Module Development**: Bangun modul transactions, budgets, goals secara berurutan
5. **Testing**: Test setiap modul dan integrasi
6. **Deployment**: Deploy ke environment kampus

Arsitektur ini memberikan fondasi yang solid untuk pengembangan FINSIGHT dengan skalabilitas dan keamanan yang memadai untuk lingkungan kampus.