<?php
require_once("error_handler.php");
require_once('db_connection.php');   
$res = mysqli_query($conn, "SELECT s.sale_id, p.name, s.qty_sold, s.sale_date, s.total_price FROM sales s JOIN products p ON s.product_id = p.product_id ORDER BY s.sale_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Sales History</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="app-container">
    <?php include('nav.php'); ?>
    <main>
        <h2>Sales Transactions</h2>
        <div class="table-container">
            <table>
                <thead><tr><th>Invoice ID</th><th>Product Description</th><th>Quantity Sold</th><th>Date Stamp</th><th>Total Earned</th></tr></thead>
                <tbody>
                    <?php if(mysqli_num_rows($res)==0): ?><tr><td colspan="5" style="text-align:center;">No entries generated.</td></tr>
                    <?php else: while($row = mysqli_fetch_assoc($res)): ?>
                        <tr><td>#INV-<?php echo $row['sale_id']; ?></td><td><?php echo htmlspecialchars($row['name']); ?></td><td><?php echo $row['qty_sold']; ?> Pcs</td><td><?php echo $row['sale_date']; ?></td><td style="font-weight:bold; color:var(--primary);">TSh <?php echo number_format($row['total_price'], 2); ?></td></tr>
                    <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>