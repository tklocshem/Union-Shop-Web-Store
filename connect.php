<?php
$host = "localhost"; 
$dbname = "tklochko-shemiakin";
$username = "tklochko-shemiakin";
$password = "usUrfdMQhZ";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}
?>
