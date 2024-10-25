<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify the token
    $sql = "SELECT * FROM client_list WHERE verify_token = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, update the user to mark their email as verified
        $sqlUpdate = "UPDATE client_list SET verified = 1 WHERE verify_token = ?";
        $stmtUpdate = $connection->prepare($sqlUpdate);
        $stmtUpdate->bind_param('s', $token);
        if ($stmtUpdate->execute()) {
            // Display success message and login button
            echo "
            <div style='text-align: center; padding: 50px;'>
                <h1>Email Verified Successfully!</h1>
                <p>Your email has been verified. You can now log in to your account.</p>
                <a href='login.php' style='display:inline-block; padding:10px 15px; font-size:16px; color:white; background-color:#007bff; text-decoration:none; border-radius:5px;'>
                    Go to Login
                </a>
            </div>
            ";
        } else {
            echo "Error verifying email.";
        }
    } else {
        echo "Invalid verification token.";
    }
} else {
    echo "No token provided.";
}

$connection->close();
?>
