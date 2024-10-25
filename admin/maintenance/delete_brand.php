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

// Handle deletion
$brand_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($brand_id) {
    // Get the logo filename to delete
    $query = "SELECT logo FROM brand_list WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $brand_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $brand = $result->fetch_assoc();
    $stmt->close();

    if ($brand['logo']) {
        $logo_path = "uploads/" . $brand['logo'];
        if (file_exists($logo_path)) {
            unlink($logo_path);
        }
    }

    // Delete the brand
    $query = "DELETE FROM brand_list WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $brand_id);

   
    if ($stmt->execute()) {
        header('Location: list_of_brand.php?status=success');
    } else {
        header('Location: list_of_brand.php?status=error');
    }
    exit();



    $stmt->close();
}

$connection->close();
?>
