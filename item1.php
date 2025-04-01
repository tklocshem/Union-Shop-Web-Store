<!--item.php-->
<!--Author: Tymofii Klochko-Shemiakin (G21253710)-->
<!--Email: tklochko-shemiakin@uclan.ac.uk-->

<?php
session_start();
include 'connect.php';

$message = ""; // Initialize message variable

// Checks if a product id is provided, if not redirect to products.php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = intval($_GET['id']);

// Process "Add to Basket" action if requested
if (isset($_GET['action']) && $_GET['action'] === 'add') {
    // Check if user is logged in, if not redirect to login.php
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    }
    
    $stmtProduct = $conn->prepare("SELECT product_title FROM tbl_products WHERE product_id = :id");
    $stmtProduct->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmtProduct->execute();
    $prod = $stmtProduct->fetch(PDO::FETCH_ASSOC);
    
    if ($prod) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Increment the product quantity in the session cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
        
        // Added to cart message 
        $_SESSION['cart_message'] = htmlspecialchars($prod['product_title']) . " has been added to your basket.";
        
        // Redirect to avoid duplicate additions on refresh
        header("Location: item.php?id=" . $product_id);
        exit();
    } else {
        $_SESSION['cart_message'] = "Product not found.";
        header("Location: item.php?id=" . $product_id);
        exit();
    }
}

// Clear the added to cart message
$cartMessage = "";
if (isset($_SESSION['cart_message'])) {
    $cartMessage = $_SESSION['cart_message'];
    unset($_SESSION['cart_message']);
}

// Get product details
$stmt = $conn->prepare("SELECT * FROM tbl_products WHERE product_id = :id");
$stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// If product not found, display an error and exit
if (!$product) {
    echo "Product not found.";
    exit();
}

// Add this code to display the reviews
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user_id"])) {
    $title = trim($_POST['review_title']);
    $description = trim($_POST['review_description']);
    $rating = (int)$_POST['rating'];
    $user_id = $_SESSION["user_id"];
    
    if (!empty($title) && !empty($description) && $rating > 0 && $rating <= 5) {
        $stmt = $conn->prepare("INSERT INTO tbl_reviews (product_id, user_id, review_title, review_desc, review_rating) VALUES (:product_id, :user_id, :title, :description, :rating)");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':rating', $rating);
        
        if ($stmt->execute()) {
            $message = "Review added successfully!";
        } else {
            $message = "Error adding review. Please try again.";
        }
    } else {
        $message = "Please fill in all fields correctly.";
    }
}

// Get all reviews for the product
$stmt = $conn->prepare("
    SELECT r.*, u.user_full_name 
    FROM tbl_reviews r 
    JOIN tbl_users u ON r.user_id = u.user_id 
    WHERE r.product_id = :product_id 
    ORDER BY r.review_id DESC
");
$stmt->bindParam(':product_id', $product_id);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate average rating
$avgRating = 0;
if (count($reviews) > 0) {
    $totalRating = array_sum(array_column($reviews, 'review_rating'));
    $avgRating = round($totalRating / count($reviews), 1);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Union Shop - <?php echo htmlspecialchars($product['product_title']); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Header Section -->
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
    <div class="item-container" style="max-width: 800px; margin: 0 auto; text-align: center;">
        <?php if (!empty($cartMessage)): ?>
            <p style="color: green;"><?php echo $cartMessage; ?></p>
        <?php endif; ?>
        
        <h2><?php echo htmlspecialchars($product['product_title']); ?></h2>
        <img src="resources/<?php echo htmlspecialchars($product['product_image']); ?>" 
             alt="<?php echo htmlspecialchars($product['product_title']); ?>" style="width:100%; max-width:400px;">
        <p><?php echo htmlspecialchars($product['product_desc']); ?></p>
        <p>Price: £<?php echo htmlspecialchars($product['product_price']); ?></p>
        <p>Type: <?php echo htmlspecialchars($product['product_type']); ?></p>

        <div class="product-buttons" style="margin-top: 1rem;">
            <a href="products.php" class="btn">Back to Products</a>
            <a href="item.php?id=<?php echo urlencode($product['product_id']); ?>&action=add" class="btn">Add to Basket</a>
            <a href="cart.php" class="btn">View Cart</a>
        </div>

        <div class="reviews-section">
            <h2>Reviews</h2>
            
            <?php if (isset($_SESSION["user_id"])): ?>
                <div class="add-review">
                    <h3>Add a Review</h3>
                    <?php if ($message): ?>
                        <p class="message"><?php echo $message; ?></p>
                    <?php endif; ?>
                    <form action="item.php?id=<?php echo $product_id; ?>" method="POST">
                        <div class="form-group">
                            <label for="review_title">Title:</label>
                            <input type="text" id="review_title" name="review_title" required>
                        </div>
                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <select id="rating" name="rating" required>
                                <option value="">Select rating</option>
                                <option value="5">5 stars</option>
                                <option value="4">4 stars</option>
                                <option value="3">3 stars</option>
                                <option value="2">2 stars</option>
                                <option value="1">1 star</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="review_description">Description:</label>
                            <textarea id="review_description" name="review_description" required></textarea>
                        </div>
                        <button type="submit">Submit Review</button>
                    </form>
                </div>
            <?php else: ?>
                <p>Please <a href="login.php">login</a> to leave a review.</p>
            <?php endif; ?>

            <div class="reviews-list">
                <?php if (count($reviews) > 0): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review">
                            <h3><?php echo htmlspecialchars($review['review_title']); ?></h3>
                            <div class="review-meta">
                                <span class="reviewer">By <?php echo htmlspecialchars($review['user_full_name']); ?></span>
                                <span class="rating"><?php echo $review['review_rating']; ?>/5</span>
                                <span class="date"><?php echo date('F j, Y', strtotime($review['review_timestamp'])); ?></span>
                            </div>
                            <p class="review-description"><?php echo htmlspecialchars($review['review_desc']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reviews yet. Be the first to review this product!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Footer Section -->
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
