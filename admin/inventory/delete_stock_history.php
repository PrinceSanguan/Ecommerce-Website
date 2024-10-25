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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $stock_id = $_POST['stock_id'];
    $product_id = $_POST['product_id'];
    $stock_quantity = $_POST['stock_quantity'];

    // Delete the stock entry from the stock_history table
    $delete_stock_sql = "DELETE FROM stock_history WHERE id = ?";
    $stmt = $conn->prepare($delete_stock_sql);
    $stmt->bind_param("i", $stock_id);

    if ($stmt->execute()) {
        // If deletion is successful, update the product quantity in product_list
        $update_product_sql = "UPDATE product_list SET quantity = quantity - ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_product_sql);
        $update_stmt->bind_param("ii", $stock_quantity, $product_id);

        if ($update_stmt->execute()) {
            // Redirect back to view_product.php with a success message
            header("Location: view_product.php?id=$product_id&delete_success=1");
        } else {
            echo "Error updating product quantity: " . $conn->error;
        }
    } else {
        echo "Error deleting stock: " . $conn->error;
    }

    // Close the prepared statements
    $stmt->close();
    $update_stmt->close();
}

// Close the database connection
$conn->close();
?>
