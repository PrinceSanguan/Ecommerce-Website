<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Store selected payment method in session
if (isset($_POST['payment_method'])) {
    $_SESSION['payment_method'] = $_POST['payment_method'];
    
    // Handle proof of payment image upload for Gcash
    if ($_POST['payment_method'] == 'gcash' && isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] == 0) {
        
        $targetDir = "uploads/proof_of_payment/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $targetFile = $targetDir . basename($_FILES['proof_image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        $validFileTypes = ['jpg', 'jpeg', 'png'];
        if (in_array($imageFileType, $validFileTypes)) {
            if (move_uploaded_file($_FILES['proof_image']['tmp_name'], $targetFile)) {
                $_SESSION['proof_of_payment'] = $targetFile;
            } else {
                echo "Error uploading file.";
                exit();
            }
        } else {
            echo "Only JPG, JPEG, and PNG files are allowed.";
            exit();
        }
    }
}

// Start transaction
$connection->begin_transaction();

try {
    // Calculate total price and update stock
    $total_price = 0;
    
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            if (isset($item['price']) && isset($item['quantity'])) {
                $total_price += $item['price'] * $item['quantity'];
                
                // Verify current stock in stock_history before proceeding
                $check_stock_query = "SELECT stock_quantity FROM stock_history WHERE product_id = ? AND deleted = 0 LIMIT 1";
                $check_stmt = $connection->prepare($check_stock_query);
                $check_stmt->bind_param("i", $item['id']);
                $check_stmt->execute();
                $stock_result = $check_stmt->get_result();
                
                if ($stock_result->num_rows > 0) {
                    $current_stock = $stock_result->fetch_assoc()['stock_quantity'];
                    
                    if ($current_stock < $item['quantity']) {
                        throw new Exception("Not enough stock for product ID: " . $item['id']);
                    }
                    
                    // Update stock in stock_history table
                    $update_stock_query = "UPDATE stock_history SET stock_quantity = stock_quantity - ? WHERE product_id = ? AND deleted = 0";
                    $update_stmt = $connection->prepare($update_stock_query);
                    $update_stmt->bind_param("ii", $item['quantity'], $item['id']);
                    $update_stmt->execute();

                    // Update quantity in product_list table
                    $update_product_query = "UPDATE product_list SET quantity = quantity - ? WHERE id = ?";
                    $update_product_stmt = $connection->prepare($update_product_query);
                    $update_product_stmt->bind_param("ii", $item['quantity'], $item['id']);
                    $update_product_stmt->execute();
                    
                } else {
                    throw new Exception("Product ID: " . $item['id'] . " not found.");
                }
            }
        }
    }
    
    // Store total price in session
    $_SESSION['total_price'] = $total_price;
    
    // Commit transaction
    $connection->commit();
    
    // Clear the cart from session and database after successful checkout
    if (isset($_SESSION['user_email'])) {
        $delete_cart_query = "DELETE FROM cart WHERE user_email = ?";
        $delete_stmt = $connection->prepare($delete_cart_query);
        $delete_stmt->bind_param("s", $_SESSION['user_email']);
        $delete_stmt->execute();
    }
    
    // Clear session cart
    $_SESSION['cart'] = array();
    
    // Redirect to order confirmation page
    header("Location: order_confirmation.php");
    exit();
    
} catch (Exception $e) {
    // Rollback transaction if an error occurs
    $connection->rollback();
    
    // Store error message in session
    $_SESSION['checkout_error'] = "Error processing checkout: " . $e->getMessage();
    
    // Redirect back to checkout page
    header("Location: checkout.php");
    exit();
}

// Close the database connection
$connection->close();
?>
