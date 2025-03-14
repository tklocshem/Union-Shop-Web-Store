<!--cart.php-->
<!--Author: Tymofii Klochko-Shemiakin (G21253710)-->
<!--Email: tklochko-shemiakin@uclan.ac.uk-->

<?php session_start(); ?>
<!DOCTYPE html> <!-- It is an "information" to the browser about what document type to expect-->
<html lang="en"> <!--Declare the language of the Web page-->
<head> <!--Element <head> is a container for metadata (data about data)-->
    <title>Cart</title> <!--Title of the page-->
    <meta charset="UTF-8"> <!--Specifies the character encoding for the HTML document.
    The HTML5 specification encourages web developers to use the UTF-8 character set!-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Responsive web design-->
    <link rel="stylesheet" href="style.css" /> <!--Link CSS to HTML – Stylesheet File Linking-->
</head>

<body>

<header class="header"> <!--Element represents a container for introductory content-->
    <img id="image1" src="images/uclan.png" alt="UCLan Logo"> <!--Element is used to embed an image in an HTML page-->
    <h2>Student Shop</h2> <!--Element is used to define HTML heading-->
    <div id="header-right"> <!--Element is used as a container for HTML elements - which is then styled with CSS or
        manipulated with JavaScript-->
        <!--Elements <a> defines a hyperlink, which is used to link from one page to another-->
        <a class="tags" href="index.php">Home</a>
        <a class="tags" href="products.php">Products</a>
        <a class="tags" href="cart.php">Cart</a>
        <?php
        // Check if the user is logged in, if yes, display the "My Orders" and "Logout" links
        if (isset($_SESSION['user_id'])) {
            echo '<a class="tags" href="my_orders.php">My Orders</a>';
            echo '<a class="tags" href="logout.php">Logout</a>';
        }
        // If the user is not logged in, display the "Login" and "Sign Up" links
        else {
            echo '<a class="tags" href="login.php">Login</a>';
            echo '<a class="tags" href="signup.php">Sign Up</a>';
        }
        ?>
        <!-- The above PHP code checks if the user is logged in or not and displays the appropriate links accordingly. -->
        <!-- If the user is logged in, it shows the "My Orders" and "Logout" links. -->
        <!-- If the user is not logged in, it shows the "Login" and "Sign Up" links. -->
    </div>

    <!--Elements <a> defines a hyperlink hamburger menu-->
    <a class="click_menu" onclick='click_button_action()'>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </a>
</header>

<main class="content4"> <!--Elements specifies the main content of a document-->
    <h2>Shopping Cart</h2>
    <div id="hide">
        <!--Execute a JavaScript when a button is clicked-->
        <button id="empty_cart_button" onclick='clearBasket()'>Remove Cart</button>

        <!--Elements <a> defines a hyperlink, which is used to link from one page to another (return to products page)-->
        <a href="products.php" class="remove_product_cart_button">Return To Shop</a>

        <p>
            <?php if (isset($_SESSION['user_id'])): ?>
                Welcome, <?= $_SESSION['user_name'] ?>! Here are the items you've added to your shopping cart:
            <?php else: ?>
                The items you've added to your shopping cart are:
            <?php endif; ?>
        </p>

        <div class="flex-container"> <!--Element is used as a container for HTML elements - which is then styled with CSS-->
            <div class="item"><b>Item</b></div>
            <div class="item"></div>
            <div class="item" id="p_text"><b>Product</b></div>
            <div class="item"></div>
            <div class="item"></div>

        </div>

        <div id="cart_container"></div>

        <hr><br>

        <form id="checkout-form" method="POST" action="checkout.php" target="hidden-iframe">
            <input type="hidden" name="cartData" id="cart-data-input">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <h2 class="center_text">In order to check out you must log in: <a href="login.php">LOGIN</a></h2>
            <?php else: ?>
                <button type="submit" class="submit_form" onclick="submitCheckoutForm()">Checkout</button>
            <?php endif; ?>
        </form>
    </div>

    <div id="empty_container"></div>

    <div class="clearfix"></div> <!--Element clears floated content within a container by adding a clearfix utility-->
</main>

<footer class="footer"> <!--Element defines a footer for a document-->
    <div class="row"> <!--Element is used as a container for HTML elements - which is then styled with CSS-->
        <div class="column left"> <!--Element is used as a container for HTML elements - which is then styled with CSS-->
            <h3>Links</h3>
            <p><a href="https://www.uclansu.co.uk/">Students' Union</a></p>
        </div>
        <div class="column middle"> <!--Element is used as a container for HTML elements - which is then styled with CSS-->
            <h3>Contact</h3>
            <p>Email: suinformation@uclan.ac.uk</p>
            <p>Phone: 01772 89 3000</p>
        </div>
        <div class="column right"> <!--Element is used as a container for HTML elements - which is then styled with CSS-->
            <h3>Location</h3>
            <p>University of Central Lancashire Students' Union.<br>
                Fylde Road, Preston. PR1 7BY<br>
                Registered in England<br>
                Company Number: 7623917<br>
                Registered Charity Number: 1142616</p>
        </div>
    </div>
</footer>

<script src="js/cart.js"></script> <!-- Attribute <script> specifies the URL of an external script file-->
<script src="js/functions.js"></script> <!--Attribute <script> specifies the URL of an external script file-->

</body>
</html>
