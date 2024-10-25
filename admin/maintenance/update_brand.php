<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

// Create a connection to the database
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['brand_id'])) {
    $brand_id = $_POST['brand_id'];
    $brand_name = $_POST['brand_name'];
    $status = $_POST['status'];
    $current_logo = $_POST['current_logo'];
    $logo = $current_logo; // Default to current logo

    // Handle file upload if a file is provided
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
        $logo = $_FILES['logo']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($logo);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            // File uploaded successfully
        } else {
            echo "<script>alert('Failed to upload logo. Please check file permissions.');</script>";
            $logo = $current_logo; // Revert to current logo on failure
        }
    }

    // Update the brand in the database
    $query = "UPDATE brand_list SET brand_name = ?, status = ?, logo = ? WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("sssi", $brand_name, $status, $logo, $brand_id);

    if ($stmt->execute()) {
        header('Location: list_of_brand.php?status=success');
    } else {
        header('Location: list_of_brand.php?status=error');
    }
    exit();

    // Close the statement
    $stmt->close();
}

$connection->close();
?>
