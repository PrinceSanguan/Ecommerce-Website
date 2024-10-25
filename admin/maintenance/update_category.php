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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    $status = $_POST['status'];

    // Update the category in the database without handling the logo
    $query = "UPDATE category_list SET category_name = ?, status = ? WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ssi", $category_name, $status, $category_id);

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
