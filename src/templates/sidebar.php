<?php
/**
 * FINSIGHT - Sidebar Template
 * 
 * This file contains the sidebar navigation for logged-in users.
 * 
 * @author FINSIGHT Development Team
 * @version 1.0
 */
?>

<div class="col-md-3">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-user me-2"></i>Menu</h5>
        </div>
        <div class="card-body">
            <div class="list-group">
                <a href="<?php echo BASE_URL; ?>/index.php?module=reports&action=dashboard" 
                   class="list-group-item list-group-item-action <?php echo ($module === 'reports' && $action === 'dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>/index.php?module=transactions&action=list" 
                   class="list-group-item list-group-item-action <?php echo ($module === 'transactions') ? 'active' : ''; ?>">
                    <i class="fas fa-exchange-alt me-2"></i>Transactions
                </a>
                <a href="<?php echo BASE_URL; ?>/index.php?module=budgets&action=manage" 
                   class="list-group-item list-group-item-action <?php echo ($module === 'budgets') ? 'active' : ''; ?>">
                    <i class="fas fa-money-bill-wave me-2"></i>Budgets
                </a>
                <a href="<?php echo BASE_URL; ?>/index.php?module=goals&action=track" 
                   class="list-group-item list-group-item-action <?php echo ($module === 'goals') ? 'active' : ''; ?>">
                    <i class="fas fa-bullseye me-2"></i>Financial Goals
                </a>
                <a href="<?php echo BASE_URL; ?>/index.php?module=reports&action=monthly" 
                   class="list-group-item list-group-item-action <?php echo ($module === 'reports' && $action !== 'dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar me-2"></i>Reports
                </a>
            </div>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            <h5><i class="fas fa-info-circle me-2"></i>Quick Stats</h5>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>Balance:</span>
                <strong id="current-balance">Loading...</strong>
            </div>
            <div class="d-flex justify-content-between">
                <span>This Month:</span>
                <strong id="monthly-summary">Loading...</strong>
            </div>
        </div>
    </div>
</div>

<script>
    // Load current balance and monthly summary
    document.addEventListener('DOMContentLoaded', function() {
        // In a real application, this would be replaced with an AJAX call to get actual data
        document.getElementById('current-balance').textContent = 'Rp 3,500,000';
        document.getElementById('monthly-summary').textContent = 'Income: Rp 5,000,000 | Expense: Rp 1,500,000';
    });
</script>