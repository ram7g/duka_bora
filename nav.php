<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<style>
    .glass-sidebar {
        width: 250px;
        background: #1e3a8a;
        color: #fff;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-sizing: border-box; 
    }

    .brand-container h2 {
        text-align: center;
        margin-top: 0;
        margin-bottom: 5px;
    }

    .sub-brand {
        display: block;
        text-align: center;
        font-size: 12px;
        color: #dbeafe;
        margin-bottom: 30px;
    }

    .nav-links a {
        display: block;
        padding: 12px;
        margin-bottom: 8px;
        color: #fff;
        border-radius: 6px;
        transition: .3s;
        text-decoration: none;
    }

    .nav-links a:hover,
    .nav-links .active {
        background: #2563eb;
    }

    .nav-footer {
        text-align: center;
        font-size: 12px;
        color: #dbeafe;
    }

    @media(max-width: 768px) {
        .glass-sidebar {
            width: 100%;
        }
    }
</style>

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