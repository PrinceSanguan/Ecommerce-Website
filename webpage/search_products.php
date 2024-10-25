<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_query = "%{$search}%";

$product_query = "SELECT name FROM product_list WHERE name LIKE ? AND status = 'active'";
$stmt = $connection->prepare($product_query);
$stmt->bind_param("s", $search_query);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);

$stmt->close();
$connection->close();
?>
