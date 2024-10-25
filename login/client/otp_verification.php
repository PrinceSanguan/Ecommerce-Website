<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$database = "wbsif_db";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$email = $_POST['email'] ?? '';
$otp = '';
$errorMessage = $successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    // Check if OTP matches
    $sql = "SELECT * FROM client_list WHERE email = '$email' AND otp = '$otp'";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        // Update user to mark as verified
        $updateSql = "UPDATE client_list SET verified = 1, otp = NULL WHERE email = '$email'";
        $connection->query($updateSql);

        $successMessage = "Your account has been verified successfully!";
        // Redirect or further processing can be done here
    } else {
        $errorMessage = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div>
        <?php if (!empty($errorMessage)) echo "<div>$errorMessage</div>"; ?>
        <?php if (!empty($successMessage)) echo "<div>$successMessage</div>"; ?>
    </div>
</body>
</html>
