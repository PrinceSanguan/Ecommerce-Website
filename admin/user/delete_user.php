<?php
// Database connection details
$servername = "localhost"; // Server name (usually localhost)
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "wbsif_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set
if (isset($_GET['id'])) {
    $adminId = $_GET['id'];

    // Prepare the SQL statement to delete the admin user
    $sql = "DELETE FROM admin_list WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $adminId);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect back to the users page after successful deletion
        header("Location: /wbsif/admin/user/users.php?msg=User deleted successfully");
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "No user ID provided.";
}

$conn->close(); // Close the database connection
?>
