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
    // Retrieve the form data
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Insert the new stock into the product_stocks table
    $insert_stock_sql = "INSERT INTO stock_history (product_id, stock_quantity, date_added) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($insert_stock_sql);
    $stmt->bind_param("ii", $product_id, $quantity);
    
    if ($stmt->execute()) {
        // If the stock is successfully inserted, update the product_list table
        $update_product_sql = "UPDATE product_list SET quantity = quantity + ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_product_sql);
        $update_stmt->bind_param("ii", $quantity, $product_id);
        
        if ($update_stmt->execute()) {
            // Redirect back to product_stocks.php with a success message
            header("Location: product_stocks.php?success=1");
        } else {
            // Error updating product quantity
            echo "Error updating product quantity: " . $conn->error;
        }
    } else {
        // Error inserting into product_stocks table
        echo "Error adding new stock: " . $conn->error;
    }

    // Close the prepared statements
    $stmt->close();
    $update_stmt->close();
}

// Close the database connection
$conn->close();
?>
