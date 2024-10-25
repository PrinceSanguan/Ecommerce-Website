<?php
// Start output buffering at the beginning to prevent any accidental output
ob_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Insert into stock_history
    $insert_sql = "INSERT INTO stock_history (product_id, stock_quantity) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param('ii', $product_id, $quantity);

    if ($stmt->execute()) {
        // Update the total quantity in the product_list
        $update_sql = "UPDATE product_list SET quantity = quantity + ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('ii', $quantity, $product_id);
        $update_stmt->execute();

        // Debugging: Uncomment this line if you need to check if the code reaches here
        // echo "Redirecting...";
        
        // Redirect to the view_product.php page
        header("Location: /wbsif/admin/inventory/view_product.php?id=$product_id");
        exit(); // Make sure no further code is executed
    } else {
        echo "Error adding stock: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

// End output buffering and flush the output
ob_end_flush();
?>
