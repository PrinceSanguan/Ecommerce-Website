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
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($category_id) {
    // Delete the category without handling the logo
    $query = "DELETE FROM category_list WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        header('Location: list_of_category.php?status=success');
    } else {
        header('Location: list_of_category.php?status=error');
    }
    exit();

    // Close the statement
    $stmt->close();
}

$connection->close();
?>
