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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['stock_id'])) {
    $stock_id = $_POST['stock_id'];
    $new_quantity = $_POST['stock_quantity'];

    // Prepare the update statement
    $update_sql = "UPDATE stock_history SET stock_quantity = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ii', $new_quantity, $stock_id);

    if ($update_stmt->execute()) {
        // Successfully updated
        header("Location: /wbsif/admin/inventory/view_product.php?message=Stock updated successfully");
    } else {
        // Error occurred
        header("Location: /wbsif/admin/inventory/view_product.php?message=Error updating stock");
    }

    $update_stmt->close();
}

$conn->close();
?>
