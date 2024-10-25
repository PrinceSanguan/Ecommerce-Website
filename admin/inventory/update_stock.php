<?php
session_start();
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

function updateStockQuantity($conn, $stock_history_id, $new_quantity, $product_id) {
    // 1. Update the stock_history table
    $update_history_sql = "UPDATE stock_history SET stock_quantity = ? WHERE id = ?";
    $history_stmt = $conn->prepare($update_history_sql);
    $history_stmt->bind_param('ii', $new_quantity, $stock_history_id);
    $history_stmt->execute();

    // 2. Recalculate the total stock quantity from stock_history for the specific product
    $total_quantity_sql = "SELECT SUM(stock_quantity) AS total_quantity FROM stock_history WHERE product_id = ?";
    $total_stmt = $conn->prepare($total_quantity_sql);
    $total_stmt->bind_param('i', $product_id);
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total_quantity = $total_result->fetch_assoc()['total_quantity'];

    // 3. Update the quantity in the product_list table
    $update_product_list_sql = "UPDATE product_list SET quantity = ? WHERE id = ?";
    $product_list_stmt = $conn->prepare($update_product_list_sql);
    $product_list_stmt->bind_param('ii', $total_quantity, $product_id);
    $product_list_stmt->execute();

    // Check if both queries were successful
    return ($history_stmt->affected_rows > 0 && $product_list_stmt->affected_rows > 0);
}

// Handle the AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stock_history_id = intval($_POST['stock_history_id']);
    $new_quantity = intval($_POST['new_quantity']);
    $product_id = intval($_POST['product_id']);

    // Call the update function and return the result
    if (updateStockQuantity($conn, $stock_history_id, $new_quantity, $product_id)) {
        echo 'success';
    } else {
        echo 'failure';
    }
}

$conn->close();
?>
