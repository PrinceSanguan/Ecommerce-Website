<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

$connection = new mysqli($servername, $username, $password, $database);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$message = "";
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists
    $sqlCheckToken = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW()";
    $stmtCheckToken = $connection->prepare($sqlCheckToken);
    $stmtCheckToken->bind_param('s', $token);
    $stmtCheckToken->execute();
    $result = $stmtCheckToken->get_result();

    if ($result->num_rows == 0) {
        $message = "<div style='color: red;'>Invalid or expired token.</div>";
    } else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newPassword = $_POST['new_password'];
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the client_list
            $row = $result->fetch_assoc();
            $email = $row['email'];

            $sqlUpdatePassword = "UPDATE client_list SET password = ? WHERE email = ?";
            $stmtUpdatePassword = $connection->prepare($sqlUpdatePassword);
            $stmtUpdatePassword->bind_param('ss', $hashedPassword, $email);

            if ($stmtUpdatePassword->execute()) {
                // Delete the token from the database
                $sqlDeleteToken = "DELETE FROM password_resets WHERE token = ?";
                $stmtDeleteToken = $connection->prepare($sqlDeleteToken);
                $stmtDeleteToken->bind_param('s', $token);
                $stmtDeleteToken->execute();

                $message = "<div style='color: green;'>Password updated successfully!</div>";
            } else {
                $message = "<div style='color: red;'>Error occurred while updating password.</div>";
            }
        }
    }
} else {
    $message = "<div style='color: red;'>No token provided.</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Reset Password</title>
</head>
<body class="bg-[url('/wbsif/login/images/bg1.png')] bg-center bg-cover bg-fixed min-h-screen flex items-center justify-center p-4">

    <div class="bg-neutral-100 rounded-lg shadow-lg w-96 max-w-md shadow-black-500/50 p-6">
        <h1 class="text-xl font-semibold text-black text-center">Reset Password</h1>

        <?php if (!empty($message)) echo $message; ?>

        <form action="" method="post" class="space-y-2 mt-2">
            <div class="relative">
                <input type="password" name="new_password" required placeholder="New Password" class="w-full p-2 pr-10 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-sky-900">
            </div>

            <div class="flex justify-center">
                <input type="submit" value="Update Password" class="w-full py-2 text-sm border border-gray-300 rounded bg-sky-900 text-white hover:bg-sky-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-sky-900">
            </div>
        </form>
    </div>
</body>
</html>
