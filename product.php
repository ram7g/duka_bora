<?php
require_once("error_handler.php");
require_once('db_connection.php');
$last_viewed = isset($_COOKIE['last_viewed_product']) ? htmlspecialchars($_COOKIE['last_viewed_product']) : null;
$query = "SELECT p.*, c.category_name FROM products p JOIN categories c ON p.category_id = c.category_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "<div style='max-width:500px; margin:10% auto; text-align:center; font-family:sans-serif;'><h2>System connection issue.</h2>
    <p>Please refresh or try again later.</p></div>";
    exit();
}

if (isset($_GET['view'])) {
    $id = intval($_GET['view']);

    $stmt = mysqli_prepare($conn, "SELECT name FROM products WHERE product_id=?");
    mysqli_stmt_bind_param($stmt,"i",$id);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_assoc($result))
    {
        setcookie("last_viewed_product", $row['name'], time()+86400, "/");
        $_COOKIE['last_viewed_product']=$row['name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Duka Bora - Inventory</title><link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-container">
    <?php include('nav.php'); ?>
    <main>
        <?php if ($last_viewed): ?>
            <div class="cookie-banner"><span>🕒 Quick Access: Last viewed: <strong><?php echo $last_viewed; ?></strong></span>
            <button onclick="this.parentElement.style.display='none'">✕</button></div>
        <?php endif; ?>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
            <h2>Product Inventory</h2>
            <a href="add_product.php" class="btn-submit" style="text-decoration:none;">+ Add New Product</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><a href="product.php?view=<?php echo $row['product_id']; ?>">
                                <?php echo htmlspecialchars($row['name']); ?></a></td>
                            <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                            <td>TSh <?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo $row['stock_qty']; ?></td>
                            <td>
                                <?php if ($row['stock_qty'] >= 10): ?><span class="badge badge-success">🟢 In Stock</span>
                                <?php elseif ($row['stock_qty'] >= 1): ?><span class="badge badge-warning">🟡 Low Stock</span>
                                <?php else: ?><span class="badge badge-danger">🔴 Out of Stock</span><?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['product_id']; ?>" class="action-link action-edit">Edit</a>
                                <a href="delete_product.php?id=<?php echo $row['product_id']; ?>" class="action-link action-delete">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>