<?php
/**
 * FINSIGHT - Dashboard Page
 * 
 * This file displays the main dashboard for authenticated users.
 * It shows financial summary, recent transactions, and quick stats.
 * 
 * @author FINSIGHT Development Team
 * @version 1.0
 */

// Include core files
require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/database.php';
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../core/helpers.php';

// Check authentication
if (!is_authenticated()) {
    redirect('/modules/auth/login.php?error=unauthorized');
    exit();
}

// Update last activity
$_SESSION['last_activity'] = time();

// Set page title
$page_title = 'Dashboard';

// Include header template
require_once TEMPLATES_PATH . '/header.php';
?>

<div class="row">
    <?php require_once TEMPLATES_PATH . '/sidebar.php'; ?>
    
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
            <div class="btn-group">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-1"></i>Period
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Week</a></li>
                    <li><a class="dropdown-item active" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Financial Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Current Balance</h6>
                                <h3>Rp 3,500,000</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-wallet fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Monthly Income</h6>
                                <h3>Rp 5,000,000</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-arrow-down fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Monthly Expense</h6>
                                <h3>Rp 1,500,000</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-arrow-up fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Budget Used</h6>
                                <h3>42%</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chart-pie fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts and Recent Activity -->
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar me-2"></i>Monthly Financial Overview</h5>
                    </div>
                    <div class="card-body">
                        <div id="financial-chart" style="height: 300px;">
                            <!-- Chart would be rendered here in a real implementation -->
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-chart-line fa-3x mb-3"></i>
                                <p>Financial charts will be displayed here</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-list me-2"></i>Recent Transactions</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Salary Payment</h6>
                                    <small class="text-muted">Today</small>
                                </div>
                                <p class="mb-1 text-success">+Rp 5,000,000</p>
                                <small>Salary for October</small>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Grocery Shopping</h6>
                                    <small class="text-muted">Yesterday</small>
                                </div>
                                <p class="mb-1 text-danger">-Rp 350,000</p>
                                <small>Weekly groceries</small>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Taxi Fare</h6>
                                    <small class="text-muted">2 days ago</small>
                                </div>
                                <p class="mb-1 text-danger">-Rp 80,000</p>
                                <small>Office transportation</small>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Lunch with Colleagues</h6>
                                    <small class="text-muted">3 days ago</small>
                                </div>
                                <p class="mb-1 text-danger">-Rp 120,000</p>
                                <small>Restaurant</small>
                            </div>
                            <div class="list-group-item">
                                <a href="<?php echo BASE_URL; ?>/index.php?module=transactions&action=list" class="btn btn-outline-primary w-100">
                                    View All Transactions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Budget Overview -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-money-bill-wave me-2"></i>Budget Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Budget Category</th>
                                        <th>Allocated</th>
                                        <th>Spent</th>
                                        <th>Remaining</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Food & Dining</td>
                                        <td>Rp 1,000,000</td>
                                        <td>Rp 650,000</td>
                                        <td>Rp 350,000</td>
                                        <td>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-success" style="width: 65%"></div>
                                            </div>
                                            <small>65%</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Transportation</td>
                                        <td>Rp 500,000</td>
                                        <td>Rp 280,000</td>
                                        <td>Rp 220,000</td>
                                        <td>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-info" style="width: 56%"></div>
                                            </div>
                                            <small>56%</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Entertainment</td>
                                        <td>Rp 300,000</td>
                                        <td>Rp 180,000</td>
                                        <td>Rp 120,000</td>
                                        <td>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-warning" style="width: 60%"></div>
                                            </div>
                                            <small>60%</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end">
                            <a href="<?php echo BASE_URL; ?>/index.php?module=budgets&action=manage" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i>Manage Budgets
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer template
require_once TEMPLATES_PATH . '/footer.php';
?>