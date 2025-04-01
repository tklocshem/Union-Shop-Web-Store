<?php
session_start();
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Union Shop - Home</title>
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
            <!-- If the user or session does store logged in, show Logout -->
            <a href="logout.php">Logout</a>
            <?php else: ?>
                <!-- If thr user or session does not store logged in, show Login -->
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

<!-- Hero section -->
<div class="container">
    <?php if (isset($_SESSION["user_name"])): ?>
        <h2>Welcome back, <?php echo htmlspecialchars($_SESSION["user_name"]); ?> to the 
            official Student Union Shop!</h2>
    <?php else: ?>
        <h2>Welcome to the official Student Union Shop!</h2>
    <?php endif; ?>

   <h1 class="h1_title">Where opportunity creates success</h1> <!--Element is used to define HTML heading-->
               <p>Every student at The University of Central Lancashire is automatically a member of the Student's Union. We're here to make life better for students - inspiring you to succeed and achieve your goals.</p>
               <p>Everything you need to know about UCLan Student's Union. Your membership starts here.</p>  <!--Element defines a paragraph-->

</div>

<div class="offers-container">
    <h2>Current Offers</h2>
    <ul>
        <?php
        // Fetch offers from the database
        $query = $conn->query("SELECT offer_title, offer_dec FROM tbl_offers");

        while ($row = $query->fetch()) {
            echo "<li><strong>" . htmlspecialchars($row['offer_title']) . "</strong>: " . htmlspecialchars($row['offer_dec']) . "</li>";
        }
        ?>
    </ul>
</div>

<!-- Footer -->
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
            <p>University of Central Lancashire Students' Union.<br>Fylde Road, Preston. PR1 7BY<br>Registered in England<br>Company Number: 7623917<br>Registered Charity Number: 11426616</p>
        </div>
    </div>
    <span> &copy 2024; Student Union Shop. All rights reserved.</span>
</footer>
<script src=js/script.js></script>
</body>
</html>
