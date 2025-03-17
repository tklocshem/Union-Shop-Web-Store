<!--Author: Tymofii Klochko-Shemiakin (G21253710)-->
<!--Email: tklochko-shemiakin@uclan.ac.uk-->

<?php session_start(); ?>
<!DOCTYPE html> <!-- It is an "information" to the browser about what document type to expect-->
<html lang="en"> <!--Declare the language of the Web page-->
<head> <!--Element <head> is a container for metadata (data about data)-->
    <title>Item</title> <!--Title of the page-->
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

<main class="content3"> <!--Elements specifies the main content of a page-->
    <div id="card">
        <?php
        include 'connect.php';

        // Check if product ID is set
        if (!isset($_GET['product_id'])) {
            echo "ERROR: Product ID is not set.";
            exit;
        }

        $product_id = mysqli_real_escape_string($connection, $_GET['product_id']);
        $query = "SELECT * FROM tbl_products WHERE product_id = '$product_id'";
        $result = mysqli_query($connection, $query);

        // Check if product is found
        if (mysqli_num_rows($result) == 0) {
            echo "ERROR: Product not found.";
            exit;
        }

        $row = mysqli_fetch_assoc($result);
        echo "<div class='card_info'>";
        echo "<img class='image' alt='" . $row['product_title'] . "' src='" . $row['product_image'] . "'>";
        echo "<div class='products_info'>";
        echo "<h2 class='heading'>" . $row['product_title'] . "</h2>";
        echo "<p class='description'>" . $row['product_desc'] . "</p>";
        echo "<p class='price'>" . $row['product_price'] . "</p>";
        // Add product to cart on button click
        echo "<p class='button'><button onclick='addToCart(" . json_encode($row) .")'>Buy</button></p>";
        echo "</div></div>";

        // Add this code at the beginning of the file after session_start()
        if (isset($_POST['submit_review'])) {
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $product_id = mysqli_real_escape_string($connection, $_POST['product_id']);
                $review_title = mysqli_real_escape_string($connection, $_POST['review_title']);
                $review_desc = mysqli_real_escape_string($connection, $_POST['review_desc']);
                $review_rating = mysqli_real_escape_string($connection, $_POST['review_rating']);

                $query = "INSERT INTO tbl_reviews (user_id, product_id, review_title, review_desc, review_rating, review_timestamp) VALUES ('$user_id', '$product_id', '$review_title', '$review_desc', '$review_rating', NOW())";
                mysqli_query($connection, $query);

                // Redirect to the same page after form submission
                header("Location: item.php?product_id=" . $product_id);
                exit;
            }
            else {
                echo "You must be logged in to submit a review.";
            }
        }

        // Create an associative array to map numbers to words
        $rating_words = [
            1 => 'Poor',
            2 => 'Fair',
            3 => 'Good',
            4 => 'Very Good',
            5 => 'Excellent'
        ];

        // Add this code to display the reviews and average rating
        $query = "SELECT * FROM tbl_reviews WHERE product_id = '$product_id'";
        $result = mysqli_query($connection, $query);
        $total_rating = 0;
        $review_count = 0;

        // Store the reviews in an array
        $reviews = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = [
                'title' => $row['review_title'],
                'desc' => $row['review_desc'],
                'rating' => $row['review_rating']
            ];
            $total_rating += $row['review_rating'];
            $review_count++;
        }

        if ($review_count > 0) {
            $average_rating = $total_rating / $review_count;
            echo '<h1 class="center_text">Average Rating: ' . round($average_rating, 1) . '</h1>';

            echo '<div class="reviews">';
            echo '<h3>Reviews</h3>';
            // Display the reviews
            foreach ($reviews as $review) {
                echo '<div class="review">';
                echo '<h4>' . $review['title'] . '</h4>';
                echo '<p>' . $review['desc'] . '</p>';
                echo '<p>Rating: ' . $rating_words[$review['rating']] . '</p>';
                echo '</div>';
            }
            echo '</div>';

        }
        else {
            echo '<h2 class="center_text">No reviews yet</h2>';
        }

        // Add this code inside the main content div, after displaying the product details
        if (isset($_SESSION['user_id'])) {
            echo '<div class="review_form">';
            echo '<h3 class="reviews-title">Submit a Review</h3>';
            echo '<form method="post" action="item.php?product_id=' . $product_id . '">';
            echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
            echo '<label for="review_title">Title:</label>';
            echo '<input type="text" name="review_title" required>';
            echo '<label for="review_desc">Comment:</label>';
            echo '<textarea name="review_desc" required></textarea>';
            echo '<label for="review_rating">Rating:</label>';
            echo '<select name="review_rating" required>';
            echo '<option value="" selected disabled>Select rating</option>';
            foreach ($rating_words as $key => $value) {
                echo '<option value="' . $key . '">' . $value . '</option>';
            }
            echo '</select><br><br>';
            echo '<input class="submit_form" type="submit" name="submit_review" value="Submit Review">';
            echo '</form>';
            echo '</div>';
        }

        if(!isset($_SESSION['user_id'])) {
            echo '<h2 class="center_text">You must be logged in to submit a review: <a href="login.php">LOGIN</a></h2>';
        }
        ?>

    </div>

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

<script src="js/item.js"></script> <!-- Attribute <script> specifies the URL of an external script file -->
<script src="js/functions.js"></script> <!-- Attribute <script> specifies the URL of an external script file -->

</body>
</html>
