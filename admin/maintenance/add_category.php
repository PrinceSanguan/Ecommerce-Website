<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";


$connection = new mysqli($servername, $username, $password, $database);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add brand to the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];
    
    // Insert into the database
    $sql = "INSERT INTO category_list (category_name) VALUES ('$category_name')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: list_of_category.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
