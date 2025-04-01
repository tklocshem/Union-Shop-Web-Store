<?php
session_start();
include 'connect.php';

// Ensure cart exists in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle remove item action
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        header("Location: cart.php");
        exit();
    }
}

// Handle empty cart action
if (isset($_GET['action']) && $_GET['action'] === 'empty') {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Union Shop - Cart</title>
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
    <h2 style="text-align: center;">Your Cart</h2>
    
    <?php
    // If the cart is empty, display a message
    if (empty($_SESSION['cart'])) {
        echo "<p style='text-align: center;'>Your cart is empty.</p>";
    } else {
        echo "<div class='cart-container'>";
        $grandTotal = 0;
        // Loop over each product in the cart
        foreach ($_SESSION['cart'] as $id => $quantity) {
            $stmt = $conn->prepare("SELECT * FROM tbl_products WHERE product_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                $price = $product['product_price'];
                $lineTotal = $price * $quantity;
                $grandTotal += $lineTotal;
                
                echo "<div class='cart-item'>";
                echo "<img src='resources/" . htmlspecialchars($product['product_image']) . "' alt='" . htmlspecialchars($product['product_title']) . "'>";
                echo "<h3>" . htmlspecialchars($product['product_title']) . "</h3>";
                echo "<p>" . htmlspecialchars($product['product_desc']) . "</p>";
                echo "<p>Type: " . htmlspecialchars($product['product_type']) . "</p>";
                echo "<p>Price: £" . htmlspecialchars($price) . "</p>";
                echo "<p>Quantity: " . $quantity . "</p>";
                echo "<p>Total: £" . number_format($lineTotal, 2) . "</p>";
                echo "<a href='cart.php?action=remove&id=" . $id . "' class='btn'>Remove</a>";
                echo "</div>";
            }
        }
        echo "</div>"; // end cart-container
        
        echo "<div class='cart-summary'>";
        echo "<p class='grand-total'>Grand Total: £" . number_format($grandTotal, 2) . "</p>";
        
        if (isset($_SESSION["user_id"])) {
            echo "<form action='checkout.php' method='POST'>";
            echo "<button type='submit' class='btn'>Proceed to Checkout</button>";
            echo "</form>";
        } else {
            echo "<p>Please <a href='login.php'>login</a> to proceed with checkout.</p>";
        }
        
        echo "<div class='cart-actions'>";
        echo "<a href='products.php' class='btn'>Continue Shopping</a>";
        echo "<a href='cart.php?action=empty' class='btn'>Empty Cart</a>";
        echo "</div>";
        echo "</div>";
    }
    ?>
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
            <p>University of Central Lancashire Students' Union.<br>
               Fylde Road, Preston. PR1 7BY<br>
               Registered in England<br>
               Company Number: 7623917<br>
               Registered Charity Number: 11426616</p>
        </div>
    </div>
    <span>&copy; 2024; Student Union Shop. All rights reserved.</span>
</footer>

<script src="js/script.js"></script>
</body>
</html>
