<?php
/**
 * FINSIGHT - Transactions List Page
 * 
 * This file displays a list of user transactions.
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
$page_title = 'Transactions';

// Include header template
require_once TEMPLATES_PATH . '/header.php';
?>

<div class="row">
    <?php require_once TEMPLATES_PATH . '/sidebar.php'; ?>
    
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-exchange-alt me-2"></i>Transactions</h1>
            <a href="<?php echo BASE_URL; ?>/index.php?module=transactions&action=add" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add Transaction
            </a>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample data - in a real app, this would be populated from database -->
                            <tr>
                                <td>2023-10-20</td>
                                <td>Salary Payment</td>
                                <td>Salary</td>
                                <td><span class="badge bg-success">Income</span></td>
                                <td class="text-success">+Rp 5,000,000.00</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2023-10-19</td>
                                <td>Grocery Shopping</td>
                                <td>Food</td>
                                <td><span class="badge bg-danger">Expense</span></td>
                                <td class="text-danger">-Rp 350,000.00</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2023-10-18</td>
                                <td>Taxi Fare</td>
                                <td>Transport</td>
                                <td><span class="badge bg-danger">Expense</span></td>
                                <td class="text-danger">-Rp 80,000.00</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination would go here -->
                <nav aria-label="Transactions pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer template
require_once TEMPLATES_PATH . '/footer.php';
?>