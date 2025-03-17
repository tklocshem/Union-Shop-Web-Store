<!--logout.php-->
<!--Author: Tymofii Klochko-Shemiakin (G21253710)-->
<!--Email: tklochko-shemiakin@uclan.ac.uk-->

<?php
    session_start();

    // Destroying the session data
    session_destroy();

    // Redirect the user to the index page
    header("Location: index.php");

    exit();
?>