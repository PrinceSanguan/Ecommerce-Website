<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$new_quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 0;

// Update stock quantity
$sql = "UPDATE product_list SET quantity = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $new_quantity, $id);

if ($stmt->execute()) {
    echo "Stock quantity updated successfully.";
    // Redirect back to the product details page or list
    header("Location: product_stocks.php");
} else {
    echo "Error updating stock quantity: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
