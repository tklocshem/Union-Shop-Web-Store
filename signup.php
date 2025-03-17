<!--Author: Tymofii Klochko-Shemiakin (G21253710)-->
<!--Email: tklochko-shemiakin@uclan.ac.uk-->

<?php
    session_start(); // start session
    ob_start(); // turn on output buffering

    include 'connect.php';

    $formSubmitted = false; // initialize formSubmitted variable to false

    if (isset($_POST['register'])) { // check if register button is clicked
        $formSubmitted = true; // set formSubmitted variable to true
        $fullName = mysqli_real_escape_string($connection, $_POST['fullName']); // sanitize full name input
        $email = mysqli_real_escape_string($connection, $_POST['email']); // sanitize email input
        $password = mysqli_real_escape_string($connection, $_POST['password']); // sanitize password input
        $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']); // sanitize confirm password input
        $address = mysqli_real_escape_string($connection, $_POST['address']); // sanitize address input

        $query = "SELECT * FROM tbl_users WHERE user_email='$email'"; // query to check if email is already registered
        $result = mysqli_query($connection, $query); // execute query

        if (mysqli_num_rows($result) > 0) { // check if email is already registered
            $emailError = "Error: Email is already registered.<br><br>"; // set email error message
            echo "<script>alert('Email is already registered!');</script>"; // display alert message
        }

        // Check if the passwords match
        if ($password !== $confirm_password) {
            $confirmPasswordError = "Error: Passwords do not match.<br><br>"; // set confirm password error message
            echo "<script>alert('Passwords do not match!');</script>"; // display alert message
        }

        // Check if the password contains at least one number, one uppercase and lowercase letter, and at least 8 or more characters
        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {
            $passwordError = "Error: Password must contain at least one number, one uppercase and lowercase letter, and at least 8 or more characters.<br><br>"; // set password error message
            echo "<script>alert('Password must contain at least one number, one uppercase and lowercase letter, and at least 8 or more characters!');</script>"; // display alert message
        }

        if (!isset($emailError) && !isset($passwordError) && !isset($confirmPasswordError)) { // check if there are no errors
            // Hash and salt password
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            // Insert user into database
            $query = "INSERT INTO tbl_users (user_id, user_full_name, user_address, user_email, user_pass, user_timestamp) VALUES (NULL, '$fullName', '$address', '$email', '$password_hash', NOW())"; // query to insert user into database
            if (mysqli_query($connection, $query)) { // execute query
                $userAccount = "User account created successfully.<br>"; // set success message
                echo "<script>alert('User account created successfully!');</script>"; // display alert message
            }
            else {
                echo $errorMessage = "Error: Could not register user: " . mysqli_error($connection); // set error message
                echo "<script>alert('" . addslashes($errorMessage) . "');</script>"; // display alert message
            }
        }
    }
    ob_end_flush(); // flush output buffer and turn off output buffering
?>

<!DOCTYPE html> 
<html lang="en">
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

    <div id="mainContent">
        <h1>Sign Up</h1>
        <p>
            In order to purchase from the Student's Union shop, you need to create an account with all fields below required.
            If you have any difficulties with the form please contact the <a href="https://www.uclancyprus.ac.cy/talk-to-us/" target="_blank">webmaster</a>.
        </p>
        <span id="userAccount" class="success" style="<?php if (!$formSubmitted || !isset($userAccount)) echo 'display: none;'; ?>"><?php if ($formSubmitted && isset($userAccount)) echo $userAccount; ?></span>
        <form class="form" method="post" action="signup.php">
            <label for="fullName">Full Name:<br>
                <input class="text_form" type="text" name="fullName" required>
            </label>

            <label for="email">Email address:<br>
                <input class="email_form" type="email" name="email" id="email" required>
                <span id="emailError" class="error"><?php if ($formSubmitted && isset($emailError)) echo $emailError; ?></span>
            </label>

            <label for="password">Password:</label>
            <p>
                Password must contain at least one number and one uppercase and lowercase letter, and at least 8 or more
                characters
            </p>
            <div class="password-container">
                <input class="password_form" type="password" name="password" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" oninput="validatePassword()" onfocus="showValidator()" onblur="hideValidator()" required>
                <i class="fas fa-eye toggle-password" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                <span id="passwordError" class="error"><?php if ($formSubmitted && isset($passwordError)) echo $passwordError; ?></span>
            </div>
            <div class="validator" id="validator"><br>
                <b>Password must contain the following:</b>
                <ul>
                    <li><input type="checkbox" id="lowercase" disabled> A <b>lowercase</b> letter</li><br>
                    <li><input type="checkbox" id="uppercase" disabled> A <b>capital (uppercase)</b> letter</li><br>
                    <li><input type="checkbox" id="number" disabled> A <b>number</b></li><br>
                    <li><input type="checkbox" id="length" disabled> Minimum <b>8 characters</b></li>
                </ul>
            </div>


            <label for="confirm_password">Confirm Password:<br></label>
            <div class="password-container">
                <input class="password_form" type="password" name="confirm_password" id="confirmPassword" required>
                <i class="fas fa-eye toggle-password" id="toggleConfirmPassword" onclick="toggleConfirmPasswordVisibility()"></i>
                <span id="confirmPasswordError" class="error"><?php if ($formSubmitted && isset($confirmPasswordError)) echo $confirmPasswordError; ?></span>
            </div>


            <label for="address">Address:<br>
                <textarea name="address" required></textarea>
            </label>

            <input class="submit_form" type="submit" name="register" value="Submit">
        </form><hr>

        <p>Already a user? <a href="login.php">LOGIN</a></p>
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

<script src="js/item.js"></script> <!-- Attribute <script> specifies the URL of an external script file-->
<script src="js/functions.js"></script> <!--Attribute <script> specifies the URL of an external script file-->

</body>
</html>
