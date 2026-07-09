<?php
session_start();
error_reporting(0);
include('db_connection.php');
$msg = ""; $type = "error";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $cat = intval($_POST['category_id']);
    $sup = intval($_POST['supplier_id']);
    $price = floatval($_POST['price']);
    $qty = intval($_POST['stock_qty']);

    if (empty($name) || $cat <= 0 || $sup <= 0 || $price <= 0 || $qty < 0) {
        $msg = "Validation Refused: Invalid input parameters.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO products (name, category_id, supplier_id, price, stock_qty) 
        VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "siidi", $name, $cat, $sup, $price, $qty);

        if (mysqli_stmt_execute($stmt)) {
           $_SESSION['msg'] = "Product successfully added!";
           $_SESSION['type'] = "success";

          header("Location: add_product.php");
          exit();
        }else { $msg = "Server Error. Could not save product."; }
    }
}
$categories = mysqli_query($conn, "SELECT * FROM categories");
$suppliers = mysqli_query($conn, "SELECT * FROM suppliers");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Add Product</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="app-container">
    <?php include('nav.php'); ?>
    <main>
        <h2>Add New Product</h2><br>
        <?php if ($msg): ?><div class="alert-card <?php echo $type=='success'?'alert-success':''; ?>"><?php echo $msg; ?></div><?php endif; ?>
        <form action="add_product.php" method="POST" class="form-grid">
            <div class="form-group"><label>Product Name</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Category</label><select name="category_id" required><option value="">-- Choose --</option>
            <?php while($c=mysqli_fetch_assoc($categories)) echo "<option value='{$c['category_id']}'>{$c['category_name']}</option>"; ?></select></div>
            <div class="form-group"><label>Supplier</label><select name="supplier_id" required><option value="">-- Choose --</option>
            <?php while($s=mysqli_fetch_assoc($suppliers)) echo "<option value='{$s['supplier_id']}'>{$s['supplier_name']}</option>"; ?></select></div>
            <div class="form-group"><label>Price (TSh)</label><input type="number" step="0.01" name="price" required></div>
            <div class="form-group"><label>Stock Quantity</label><input type="number" name="stock_qty" required></div>
            <div style="grid-column:1/-1;"><button type="submit" class="btn-submit">Save Product</button></div>
        </form>
        <?php
          if (isset($_SESSION['msg'])) {
           echo "<div class='" . $_SESSION['type'] . "'>" . $_SESSION['msg'] . "</div>";

           unset($_SESSION['msg']);
           unset($_SESSION['type']);
          }
           ?>
    </main>
</div>
</body>
</html>