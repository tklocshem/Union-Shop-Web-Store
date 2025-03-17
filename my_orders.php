<!--Author: Tymofii Klochko-Shemiakin (G21253710)-->
<!--Email: tklochko-shemiakin@uclan.ac.uk-->

<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Orders</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
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

<main class="content">
    <h2>My Orders</h2>

    <?php
        if (!isset($_SESSION['user_id'])) {
            // Display message if user is not logged in
            echo "<h2>Please log in to view your orders: <a href='login.php'>LOGIN</a></h2>";
        }
        else {
            // Connect to database
            include "connect.php";

            // Get user ID and retrieve orders from database
            $user_id = $_SESSION['user_id'];
            $query = "SELECT * FROM tbl_orders WHERE user_id='$user_id' ORDER BY order_date DESC";
            $result = mysqli_query($connection, $query);

            // Display orders if there are any
            if (mysqli_num_rows($result) > 0) {
                echo "<table class='orders_table'>";
                echo "<tr><th>Order Date</th><th>Products</th></tr>";

                // Loop through each order and retrieve product information
                while ($row = mysqli_fetch_assoc($result)) {
                    $product_ids = explode(",", $row['product_ids']);
                    $product_info = [];

                    foreach ($product_ids as $product_id) {
                        $product_query = "SELECT product_title, product_image FROM tbl_products WHERE product_id='$product_id'";
                        $product_result = mysqli_query($connection, $product_query);
                        $product_row = mysqli_fetch_assoc($product_result);
                        $product_info[] = [
                            'title' => $product_row['product_title'],
                            'image' => $product_row['product_image']
                        ];
                    }

                    // Display order information in a table row
                    echo "<tr>";
                    echo "<td>" . $row['order_date'] . "</td>";
                    echo "<td>";
                    foreach ($product_info as $info) {
                        echo "<div class='order_product_info'>"; // Add the 'product-info' class here
                        echo "<img src='" . $info['image'] . "' alt='" . $info['title'] . "'>";
                        echo "<span>" . $info['title'] . "</span>";
                        echo "</div>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            }
            // Display message if user has not placed any orders yet
            else {
                echo "<p>You have not placed any orders yet.</p>";
            }

            // Close database connection
            mysqli_close($connection);
        }
    ?>

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

<script src="js/functions.js"></script>

</body>
</html>