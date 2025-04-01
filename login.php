<?php
session_start();
include 'connect.php';

$message = "";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Get user from database
    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE user_email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verify password using password_verify
        if (password_verify($password, $user['user_pass'])) {
            $_SESSION["user_id"] = $user['user_id'];
            $_SESSION["user_name"] = $user['user_full_name'];
            header("Location: index.php");
            exit();
        } else {
            $message = "Invalid email or password!";
        }
    } else {
        $message = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Union Shop - Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
<div class="auth-container">
    <h2>Login to Your Account</h2>

    <?php if ($message): ?>
        <p class="error"><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <button type="submit" class="auth-btn">Login</button>
    </form>
    <p>New user? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
