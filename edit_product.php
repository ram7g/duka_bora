<?php
require_once("error_handler.php");
require_once('db_connection.php');
$id = intval($_GET['id']);
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $qty = intval($_POST['stock_qty']);
    
    if (trim($name) == "" ||!is_numeric($_POST['price']) ||!is_numeric($_POST['stock_qty']) ||$price <= 0 ||$qty < 0)
{
    $msg = "Please enter valid values.";
}
else {
    try {
    $stmt = mysqli_prepare($conn, "UPDATE products SET name = ?, price = ?, stock_qty = ? WHERE product_id = ?");
    mysqli_stmt_bind_param($stmt, "sdii", $name, $price, $qty, $id);
    mysqli_stmt_execute($stmt);
    header("Location: product.php");
    exit();
        }
    catch (Exception $e) 
    {
    $msg = "Unable to update the product. Please try again.";
    } 
     }
}

$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE product_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$p_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$p_data) { header("Location: product.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Edit Product</title>
<link rel="stylesheet" href="style.css"></head>
<body>
<div class="app-container">
    <?php include('nav.php'); ?>
    <main>
        <h2>Modify Product Record</h2><br>
        <?php if ($msg): ?><div class="alert-card"><?php echo $msg; ?></div><?php endif; ?>
        <form action="edit_product.php" method="POST" class="form-grid">
            <input type="hidden" name="id" value="<?php echo $p_data['product_id']; ?>">
            <div class="form-group"><label>Product Name</label><input type="text" name="name" value="<?php echo htmlspecialchars($p_data['name']); ?>" required></div>
            <div class="form-group"><label>Price (TSh)</label><input type="number" step="0.01" name="price" value="<?php echo $p_data['price']; ?>" required></div>
            <div class="form-group"><label>Stock Quantity</label><input type="number" name="stock_qty" value="<?php echo $p_data['stock_qty']; ?>" required></div>
            <div style="grid-column:1/-1;"><button type="submit" class="btn-submit">Update Record</button></div>
        </form>
    </main>
</div>
</body>
</html>