<?php
/**
 * FINSIGHT - Footer Template
 * 
 * This file contains the common footer HTML for all pages.
 * 
 * @author FINSIGHT Development Team
 * @version 1.0
 */
?>
    </div>
    
    <footer class="footer bg-light mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>FINSIGHT</h5>
                    <p>Financial Management System for Universities</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; <?php echo date('Y'); ?> FINSIGHT. All rights reserved.</p>
                    <p>Version <?php echo APP_VERSION; ?></p>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
</body>
</html>