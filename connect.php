<!--connect.php-->
<!--Author: Tymofii Klochko-Shemiakin (G21253710)-->
<!--Email: tklochko-shemiakin@uclan.ac.uk-->

<?php
$host = "localhost"; 
$dbname = "tklochko-shemiakin";
$username = "tklochko-shemiakin";
$password = "usUrfdMQhZ"; <!-- All these variables to connect to my Database, using dbname and credentials

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}
?>
