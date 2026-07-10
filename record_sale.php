<?php
session_start();
require_once("error_handler.php");
require_once('db_connection.php');

$err = "";

// Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id']);
    $qty_sold = intval($_POST['qty_sold']);

    // Check if the input is valid
    if ($product_id <= 0 || $qty_sold <= 0) {
        $err = "Please enter valid sale information.";
    } else {
        // Find the product in the database securely
        $stmt = mysqli_prepare($conn, "SELECT name, price, stock_qty FROM products WHERE product_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $product = mysqli_fetch_assoc($result);

        // Validate stock levels
        if (!$product) {
            $err = "Item does not exist.";
        } elseif ($product['stock_qty'] <= 0) {
            $err = "This product is out of stock.";
        } elseif ($qty_sold > $product['stock_qty']) {
            $err = "Cannot sell more than the available stock.";
        } else {
            // Do the math and get the current time
            $total_price = $qty_sold * $product['price'];
            $sale_date = date('Y-m-d H:i:s');

            try {
                // 1. Save the sale to the sales table
                $insert_stmt = mysqli_prepare($conn, "INSERT INTO sales (product_id, qty_sold, sale_date, total_price) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($insert_stmt, "iisd", $product_id, $qty_sold, $sale_date, $total_price);
                mysqli_stmt_execute($insert_stmt);

                // 2. Subtract the sold quantity from the products table
                $update_stmt = mysqli_prepare($conn, "UPDATE products SET stock_qty = stock_qty - ? WHERE product_id = ?");
                mysqli_stmt_bind_param($update_stmt, "ii", $qty_sold, $product_id);
                mysqli_stmt_execute($update_stmt);

                // 3. Save success message and refresh the page
                $_SESSION['success'] = "Sale processed smoothly! Credited TSh " . number_format($total_price, 2);
                header("Location: record_sale.php");
                exit();

            } catch (Exception $e) {
                $err = "Unable to process the sale. Please try again.";
            }
        }
    }
}

// Fetch active items for the dropdown menu
$active_items = mysqli_query($conn, "SELECT product_id, name, stock_qty FROM products WHERE stock_qty > 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Record Sale - Duka Bora</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app-container">
    
    <?php include('nav.php'); ?>
    
    <main>
        <h2>Record New Sale Transaction</h2>
        <br>

        <?php if ($err): ?>
            <div class="alert-card">
                <?php echo $err; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-card alert-success"> 
                <?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']); // Clear message after showing it
                ?>
            </div>
        <?php endif; ?>

        <form action="record_sale.php" method="POST" class="form-grid">
            
            <div class="form-group">
                <label>Select Product</label>
                <select name="product_id" required>
                    <option value="">-- Select Item --</option>
                    <?php while($row = mysqli_fetch_assoc($active_items)): ?>
                        <option value="<?php echo $row['product_id']; ?>">
                            <?php echo $row['name']; ?> (<?php echo $row['stock_qty']; ?> Left)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Quantity Sold</label>
                <input type="number" name="qty_sold" min="1" required>
            </div>
            
            <div style="grid-column: 1 / -1;">
                <button type="submit" class="btn-submit">Process Sale Invoice</button>
            </div>

        </form>
    </main>
</div>

</body>
</html>