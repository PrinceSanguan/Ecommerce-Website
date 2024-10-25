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
    $brand_name = $_POST['brand_name'];
    
    // Insert into the database
    $sql = "INSERT INTO brand_list (brand_name) VALUES ('$brand_name')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: list_of_brand.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
