<!--login.php-->
<!--Author: Tymofii Klochko-Shemiakin (G21253710)-->
<!--Email: tklochko-shemiakin@uclan.ac.uk-->

<?php
    session_start();
    ob_start(); // turn on output buffering

    include 'connect.php';

    $formSubmitted = false; // initialize form submission flag
    $emailError = null; // initialize email error message variable
    $passwordError = null; // initialize password error message variable

    if (isset($_POST['login'])) { // check if login form is submitted
        $formSubmitted = true; // set form submission flag to true
        $email = mysqli_real_escape_string($connection, $_POST['email_login']); // sanitize email input
        $password = mysqli_real_escape_string($connection, $_POST['password_login']); // sanitize password input

        $query = "SELECT * FROM tbl_users WHERE user_email='$email'"; // create SQL query to select user data from database
        $result = mysqli_query($connection, $query); // execute SQL query

        if (mysqli_num_rows($result) === 0) { // check if email is not found in database
            $emailError = "Error: Email not found <br><br>"; // set email error message
            echo "<script>alert('Email not found');</script>"; // display email not found error message using JavaScript alert
        }
        else { // if email is found in database
            $user = mysqli_fetch_assoc($result); // fetch user data from database

            if (!password_verify($password, $user['user_pass'])) { // check if password is incorrect
                $passwordError = "Error: Incorrect password <br><br>"; // set password error message
                echo "<script>alert('Incorrect password');</script>"; // display incorrect password error message using JavaScript alert
            }
            else { // if password is correct
                echo "<script>alert('User login successful!');</script>"; // display successful login message using JavaScript alert
                $_SESSION['user_id'] = $user['user_id']; // store user ID in session variable
                $_SESSION['user_name'] = $user['user_full_name']; // store user full name in session variable
                header("Location: cart.php"); // redirect user to cart page
                exit(); // exit script
            }
        }
    }
    ob_end_flush(); // flush output buffer and turn off output buffering
?>


<html lang="en"> <!--Declare the language of the Web page-->
<head> <!--Element <head> is a container for metadata (data about data)-->
    <title>Products</title> <!--Title of the page-->
    <meta charset="UTF-8"> <!--Specifies the character encoding for the HTML document.
    The HTML5 specification encourages web developers to use the UTF-8 character set!-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Responsive web design-->
    <link rel="stylesheet" href="style.css" /> <!--Link CSS to HTML – Stylesheet File Linking-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

<main class="content5"> <!--Elements specifies the main content of a page-->

    <h1>Login</h1>
    <p>In order to check out, leave feedback and view the order list you must log in</p>
    <form class="form" method="post" action="login.php">
        <label for="email_login">Email:</label>
        <input class="email_form" type="email" name="email_login" required>
        <span id="emailError" class="error"><?php if ($formSubmitted && isset($emailError)) echo $emailError; ?></span>

        <label for="password_login">Password:</label>
        <input class="password_form" type="password" name="password_login" required>
        <span id="passwordError" class="error"><?php if ($formSubmitted && isset($passwordError)) echo $passwordError; ?></span>

        <input class="submit_form" type="submit" name="login" value="Login">
    </form><hr>

    <p>Need an account? <a href="signup.php">SIGN UP</a></p>

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

<script src="js/item.js"></script> <!-- Attribute <script> specifies the URL of an external script file-->
<script src="js/functions.js"></script> <!--Attribute <script> specifies the URL of an external script file-->

</body>
</html>
