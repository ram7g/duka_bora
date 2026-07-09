<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<nav class="glass-sidebar">
    <div class="brand-container">
        <h2>Duka Bora</h2>
        <span class="sub-brand">INVENTORY MANAGEMENT</span>
    </div>
    <div class="nav-links">
        <a href="product.php" class="<?php echo $current_page == 'product.php' ? 'active' : ''; ?>">📦 Products</a>
        <a href="add_product.php" class="<?php echo $current_page == 'add_product.php' ? 'active' : ''; ?>">➕ Add Product</a>
        <a href="record_sale.php" class="<?php echo $current_page == 'record_sale.php' ? 'active' : ''; ?>">💸 Record Sale</a>
        <a href="sales_history.php" class="<?php echo $current_page == 'sales_history.php' ? 'active' : ''; ?>">📜 Sales History</a>
        <a href="report.php" class="<?php echo $current_page == 'report.php' ? 'active' : ''; ?>">📊 Report Dashboard</a>
    </div>
    <div class="nav-footer">
        <p>© 2026 Duka Bora Retail</p>
    </div>
</nav>