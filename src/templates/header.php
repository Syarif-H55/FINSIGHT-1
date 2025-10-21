<?php
/**
 * FINSIGHT - Header Template
 * 
 * This file contains the common header HTML for all pages.
 * 
 * @author FINSIGHT Development Team
 * @version 1.0
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - FINSIGHT' : 'FINSIGHT'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>/index.php?module=reports&action=dashboard">
                <i class="fas fa-wallet me-2"></i>FINSIGHT
            </a>
            
            <?php if (is_authenticated()): ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/index.php?module=reports&action=dashboard">
                            <i class="fas fa-chart-line me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/index.php?module=transactions&action=list">
                            <i class="fas fa-exchange-alt me-1"></i>Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/index.php?module=budgets&action=manage">
                            <i class="fas fa-money-bill-wave me-1"></i>Budgets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/index.php?module=goals&action=track">
                            <i class="fas fa-bullseye me-1"></i>Goals
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            <?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'], ENT_QUOTES); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/modules/auth/logout.php">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </nav>
    
    <div class="container-fluid mt-4">
        <?php if (isset($_GET['message']) && $_GET['message'] === 'logged_out'): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                You have been successfully logged out.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>