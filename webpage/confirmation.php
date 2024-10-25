<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="confirmation">
    <h1>Thank You for Your Order!</h1>
    <p>Your order has been placed successfully.</p>
    <a href="products.php">Continue Shopping</a>
</div>
</body>
</html>
