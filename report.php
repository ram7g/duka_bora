<?php
require_once("error_handler.php");
require_once('db_connection.php');

$today = date('Y-m-d');

// Get today's revenue
$today_calc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as gross FROM sales WHERE DATE(sale_date) = '$today'"));
$gross = $today_calc['gross'] ?? 0;

// Get top 3 best-selling products
$top = mysqli_query($conn, "SELECT p.name, SUM(s.qty_sold) as units FROM sales s JOIN products p ON s.product_id = p.product_id GROUP BY s.product_id ORDER BY units DESC LIMIT 3");

// Get low stock items
$critical = mysqli_query($conn, "SELECT name, stock_qty FROM products WHERE stock_qty < 5 ORDER BY stock_qty ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Management Report - Duka Bora</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app-container">
    
    <?php include('nav.php'); ?>
    
    <main>
        <h2>Business Performance Dashboard</h2>
        <br>
        
        <div class="strip">
            <div class="card">
                <h4>Today's Revenue</h4>
                <h2 class="metric metric-primary">TSh <?php echo number_format($gross, 2); ?></h2>
            </div>
            
            <div class="card card-alert">
                <h4>Low Stock Alerts</h4>
                <h2 class="metric metric-danger"><?php echo mysqli_num_rows($critical); ?> Critical Items</h2>
            </div>
        </div>

        <div class="report-columns">
            
            <div class="table-container report-panel">
                <h3 class="panel-title">Top 3 Best-Selling Products</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Units Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($t = mysqli_fetch_assoc($top)): ?>
                            <tr>
                                <td><?php echo $t['name']; ?></td>
                                <td><?php echo $t['units']; ?> Pcs</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="table-container report-panel">
                <h3 class="panel-title panel-title-danger">Low Inventory List (&lt; 5)</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Units Left</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($c = mysqli_fetch_assoc($critical)): ?>
                            <tr>
                                <td><?php echo $c['name']; ?></td>
                                <td class="stock-critical"><?php echo $c['stock_qty']; ?> Left</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</div>

</body>
</html>