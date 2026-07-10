<?php
require_once("error_handler.php");
require_once('db_connection.php');

$id = isset($_POST['id']) ? intval($_POST['id']) : intval($_GET['id'] ?? 0);

// Only perform the actual delete on a POST request (i.e. after the user
// confirms on this page). A plain GET link/click just shows the confirmation
// below, so nobody can delete a product by accident or via a stray link.
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if ($id > 0) {
        try {
    $del_sales = mysqli_prepare($conn, "DELETE FROM sales WHERE product_id = ?");
    mysqli_stmt_bind_param($del_sales, "i", $id);
    mysqli_stmt_execute($del_sales);

    $del_product = mysqli_prepare($conn, "DELETE FROM products WHERE product_id = ?");
    mysqli_stmt_bind_param($del_product, "i", $id);
    mysqli_stmt_execute($del_product);
    header("Location: product.php");
    exit();
            }
    catch (Exception $e) 
    {$msg = "Unable to delete the product. Please try again.";}
}
}

$stmt = mysqli_prepare($conn, "SELECT name FROM products WHERE product_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$p_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$p_data) {
    header("Location: product.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Delete Product</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="app-container">
    <?php include('nav.php'); ?>
    <main>
        <h2>Delete Product</h2><br>
        <div class="alert-card">
            Are you sure you want to delete <strong><?php echo htmlspecialchars($p_data['name']); ?></strong>?
            This will also remove its sales history and cannot be undone.
        </div>
        <form action="delete_product.php" method="POST" class="confirm-actions">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" class="btn-submit btn-danger">Yes, Delete It</button>
            <a href="product.php" class="btn-cancel">Cancel</a>
        </form>
    </main>
</div>
</body>
</html>