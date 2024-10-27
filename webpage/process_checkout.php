<?php
session_start();

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

        // Set the directory where you want to save the uploaded file
        $targetDir = "uploads/proof_of_payment/";
        // Make sure the uploads directory exists, if not, create it
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true); // Create directory if it doesn't exist
        }
        
        // Set the full path for the uploaded file
        $targetFile = $targetDir . basename($_FILES['proof_image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate the file type (only allow images)
        $validFileTypes = ['jpg', 'jpeg', 'png'];
        if (in_array($imageFileType, $validFileTypes)) {
            if (move_uploaded_file($_FILES['proof_image']['tmp_name'], $targetFile)) {
                // Store the file path in session
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

// Calculate total price (if not already calculated in checkout.php)
$total_price = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['price']) && isset($item['quantity'])) {
            $total_price += $item['price'] * $item['quantity'];
        }
    }
}

// Store total price in session
$_SESSION['total_price'] = $total_price;

// Redirect to order confirmation page
header("Location: order_confirmation.php");
exit();
?>
