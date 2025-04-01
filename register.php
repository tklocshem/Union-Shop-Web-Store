<?php
session_start();
include 'connect.php';

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE user_email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $message = "Email already exists!";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Hash password using bcrypt
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        // Insert new user into database
        $stmt = $conn->prepare("INSERT INTO tbl_users (user_full_name, user_email, user_address, user_pass) VALUES (:full_name, :email, :address, :password)");
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':password', $hashed_password);
        
        if ($stmt->execute()) {
            $_SESSION["user_id"] = $conn->lastInsertId();
            $_SESSION["user_name"] = $full_name;
            header("Location: index.php");
            exit();
        } else {
            $message = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Union Shop - Register</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
<div class="auth-container">
    <h2>Register a New Account</h2>

    <!-- SERVER-SIDE MESSAGES -->
    <?php if ($message): ?>
        <p class="error"><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Registration Form -->
    <form action="register.php" method="POST" id="regForm">
        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" placeholder="Enter your full name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label for="address">Address:</label>
        <input type="text" name="address" placeholder="Enter your address" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>

        <!--password requirements list -->
        <div class="requirements hidden" id="password-requirements">
            <ul>
                <li id="req-length">Minimum length is 8 characters</li>
                <li id="req-upper">At least 1 uppercase letter</li>
                <li id="req-lower">At least 1 lowercase letter</li>
                <li id="req-digit">At least 1 digit</li>
                <li id="req-special">At least 1 special symbol</li>
            </ul>
        </div>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
        <p id="confirmMsg" class="hidden"></p>

        <button type="submit" class="auth-btn">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</div>
<script src="js/auth.js"></script>
</body>
</html>




