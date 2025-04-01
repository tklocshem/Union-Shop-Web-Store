<?php
session_start();
include 'connect.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$message = "";
$success = false;

// Process checkout
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    try {
        // Start transaction
        $conn->beginTransaction();
        
        // Get product details for the order
        $product_ids = array_keys($_SESSION['cart']);
        $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
        $stmt = $conn->prepare("SELECT product_id, product_title, product_price FROM tbl_products WHERE product_id IN ($placeholders)");
        $stmt->execute($product_ids);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($products)) {
            throw new Exception("No products found in the cart.");
        }
        
        // Calculate total price
        $total = 0;
        foreach ($products as $product) {
            $quantity = $_SESSION['cart'][$product['product_id']];
            $total += $product['product_price'] * $quantity;
        }
        
        // Create order record
        $stmt = $conn->prepare("INSERT INTO tbl_orders (user_id, product_ids, order_date) VALUES (:user_id, :product_ids, NOW())");
        $product_ids_json = json_encode($_SESSION['cart']);
        
        // Validate data before binding
        if (!is_numeric($_SESSION['user_id'])) {
            throw new Exception("Invalid user ID.");
        }
        
        if (empty($product_ids_json)) {
            throw new Exception("Invalid product data.");
        }
        
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':product_ids', $product_ids_json, PDO::PARAM_STR);
        
        if (!$stmt->execute()) {
            $error = $stmt->errorInfo();
            throw new Exception("Database error: " . $error[2]);
        }
        
        // Clear the cart
        $_SESSION['cart'] = array(); // gives back an item to the phpmyadmin table
        $success = true;
        $message = "Thank you for your order! Your order has been successfully placed.";
        
        // Commit transaction
        $conn->commit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollBack();
        $message = "An error occurred while processing your order: " . $e->getMessage();
        error_log("Checkout error: " . $e->getMessage());
    }
} else {
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Union Shop - Checkout</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-title">
                <img src="resources/images/logos/UCLAN.png" alt="Student Union Logo">
                <h1>Student Union Shop</h1>
            </div>
            <nav class="nav-links" id="nav-links">
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="cart.php">Cart</a>
                <?php if (isset($_SESSION["user_name"])): ?>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="register.php">Sign Up</a>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </nav>
            <div class="burger-menu" id="burger-menu" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>

    <main>
        <div class="checkout-container">
            <?php if ($success): ?>
                <div class="success-message">
                    <h2>Order Successful!</h2>
                    <p><?php echo $message; ?></p>
                    <div class="checkout-buttons">
                        <a href="products.php" class="btn">Continue Shopping</a>
                        <a href="index.php" class="btn">Return to Home</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <h2>Order Failed</h2>
                    <p><?php echo $message; ?></p>
                    <div class="checkout-buttons">
                        <a href="cart.php" class="btn">Return to Cart</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="footer">
            <div class="info">
                <h3>Links</h3>
                <p><a href="">Students' Union</a></p>
            </div>
            <div class="info">
                <h3>Contact</h3>
                <p>Email: <a href="mailto:suinformation@uclan.ac.uk">suinformation@uclan.ac.uk</a></p>
                <p>Phone: 01772 89 3000</p>
            </div>
            <div class="info">
                <h3>Location</h3>
                <p>
                    University of Central Lancashire Students' Union.<br>
                    Fylde Road, Preston. PR1 7BY<br>
                    Registered in England<br>
                    Company Number: 7623917<br>
                    Registered Charity Number: 11426616
                </p>
            </div>
        </div>
        <span>&copy; 2024; Student Union Shop. All rights reserved.</span>
    </footer>

    <script src="js/script.js"></script>
</body>
</html> 