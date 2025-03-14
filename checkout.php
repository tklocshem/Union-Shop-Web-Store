<!--checkout.php-->
<!--Author: Tymofii Klochko-Shemiakin (G21253710)-->
<!--Email: tklochko-shemiakin@uclan.ac.uk-->

<?php
    session_start();

    include "connect.php";

    // Get the cart data from local storage
    $cartData = json_decode($_POST['cartData'], true);

    // Prepare the order data
    $order_date = date("Y-m-d H:i:s"); // Get the current date and time
    $user_id = $_SESSION['user_id']; // Get the user ID from the session
    $product_ids = implode(",", array_column($cartData, 'product_id')); // Get a comma-separated list of product IDs from the cart data

    // Insert the order into the tbl_orders table
    $query = "INSERT INTO tbl_orders (order_date, user_id, product_ids) VALUES ('$order_date', '$user_id', '$product_ids')";

    if (mysqli_query($connection, $query)) { // If the query was successful
        echo "<script>
        alert('Your order has been successfully placed!'); // Show a success message
        window.location.href = 'cart.php'; // Redirect the user to the cart page
        </script>";
    } else {
        echo "ERROR: could not insert order: " . mysqli_error($connection);
    }

    mysqli_close($connection);
?>