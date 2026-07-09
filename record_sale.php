<?php
session_start();
error_reporting(0);
include('db_connection.php');
$err = ""; $succ = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_id = intval($_POST['product_id']);
    $qty = intval($_POST['qty_sold']);

    if ($p_id <= 0 || $qty <= 0) {
        $err = "Quantity selection validation failure.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT name, price, stock_qty FROM products WHERE product_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $p_id);
        mysqli_stmt_execute($stmt);
        $p_res = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if (!$p_res) {
            $err = "Item does not exist.";
        } elseif ($qty > $p_res['stock_qty']) {
            $err = "Insufficient stock! Only {$p_res['stock_qty']} left of '{$p_res['name']}'.";
        } else {
            $tot = $qty * $p_res['price'];
            $now = date('Y-m-d H:i:s');

            $stmt = mysqli_prepare($conn, "INSERT INTO sales (product_id, qty_sold, sale_date, total_price) 
            VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iisd", $p_id, $qty, $now, $tot);

            if (mysqli_stmt_execute($stmt)) {

               $stmt2 = mysqli_prepare($conn, "UPDATE products SET stock_qty = stock_qty - ?
                WHERE product_id = ?");
              mysqli_stmt_bind_param($stmt2, "ii", $qty, $p_id);
             mysqli_stmt_execute($stmt2);

             $_SESSION['success'] = "Sale processed smoothly! Credited TSh " . number_format($tot, 2);


           header("Location: record_sale.php");
           exit();

            } else { $err = "Failed transaction record execution."; }
        }
    }
}
$active_items = mysqli_query($conn, "SELECT product_id, name, stock_qty FROM products WHERE stock_qty > 0");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Record Sale</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="app-container">
    
   
<?php include('nav.php'); ?>
    <main>
        <h2>Record New Sale Transaction</h2><br>
        <?php if ($err): ?><div class="alert-card"><?php echo $err; ?></div><?php endif; ?>
        <?php if ($succ): ?><div class="alert-card alert-success"> <?php echo $succ; ?></div><?php endif; ?>
        <form action="record_sale.php" method="POST" class="form-grid">
            <div class="form-group"><label>Select Product</label><select name="product_id" required><option value="">-- Select Item --</option>
            <?php while($row=mysqli_fetch_assoc($active_items)) echo "<option value='{$row['product_id']}'>{$row['name']} ({$row['stock_qty']} Left)</option>"; ?></select></div>
            <div class="form-group"><label>Quantity Sold</label><input type="number" name="qty_sold" min="1" required></div>
            <div style="grid-column:1/-1;"><button type="submit" class="btn-submit">Process Sale Invoice</button></div>

        </form>
         <?php
if (isset($_SESSION['success'])) {
    echo "<div class='success'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
}
?>
    </main>
</div>
</body>
</html>