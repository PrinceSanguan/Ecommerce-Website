<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

$connection = new mysqli($servername, $username, $password, $database);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$email = isset($_GET['email']) ? $_GET['email'] : "";
$message = "";

// Handle new password generation and email sending
if (isset($_POST['send_email'])) {
    $email = $_POST['email'];
    $sqlCheckEmail = "SELECT * FROM client_list WHERE email = ?"; // Check only the email
    $stmtCheckEmail = $connection->prepare($sqlCheckEmail);
    $stmtCheckEmail->bind_param('s', $email);
    $stmtCheckEmail->execute();
    $result = $stmtCheckEmail->get_result();

    if ($result->num_rows > 0) {
        $newPassword = bin2hex(random_bytes(4)); // Generate a random 8-character password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update the password in the database
        $sqlUpdatePassword = "UPDATE client_list SET password = ? WHERE email = ?";
        $stmtUpdatePassword = $connection->prepare($sqlUpdatePassword);
        $stmtUpdatePassword->bind_param('ss', $hashedPassword, $email);
        
        if ($stmtUpdatePassword->execute()) {
            // Send the new password to the user's email
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'rke49127@gmail.com'; // Your Gmail address
                $mail->Password   = 'gyxa dyuc zhks gkon'; // Your App Password here
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                
                // Recipients
                $mail->setFrom('rke49127@gmail.com', 'Your Name');
                $mail->addAddress($email); // Add a recipient

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your New Password';
                $mail->Body    = 'Your new password is: <strong>' . $newPassword . '</strong>';
                $mail->AltBody = 'Your new password is: ' . $newPassword;

                $mail->send();
                
                $message = "<div style='color: green;'>New password has been sent to your email!</div>";
            } catch (Exception $e) {
                $message = "<div style='color: red;'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
            }
        } else {
            $message = "<div style='color: red;'>Error occurred while updating password.</div>";
        }
    } else {
        $message = "<div style='color: red;'>Email does not exist.</div>";
    }
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($email)) {
        $message = "<div style='color: red;'>Email is required.</div>";
    } elseif (strlen($newPassword) < 8) {
        $message = "<div style='color: red;'>Password must be at least 8 characters long.</div>";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "<div style='color: red;'>Passwords do not match.</div>";
    } else {
        // Check if the email exists
        $sqlCheckEmail = "SELECT * FROM client_list WHERE email = ?";
        $stmtCheckEmail = $connection->prepare($sqlCheckEmail);
        $stmtCheckEmail->bind_param('s', $email);
        $stmtCheckEmail->execute();
        $result = $stmtCheckEmail->get_result();
        
        if ($result->num_rows > 0) {
            // Get the current password hash
            $user = $result->fetch_assoc();
            $currentHashedPassword = $user['password'];

            // Ensure the new password is not the same as the current password
            if (password_verify($newPassword, $currentHashedPassword)) {
                $message = "<div style='color: red;'>New password cannot be the same as the old password.</div>";
            } else {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                // Update the password in the database
                $sqlUpdatePassword = "UPDATE client_list SET password = ? WHERE email = ?";
                $stmtUpdatePassword = $connection->prepare($sqlUpdatePassword);
                $stmtUpdatePassword->bind_param('ss', $hashedPassword, $email);
                
                if ($stmtUpdatePassword->execute()) {
                    // Send notification email about password change
                    $mail = new PHPMailer(true);
                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'rke49127@gmail.com'; // Your Gmail address
                        $mail->Password   = 'gyxa dyuc zhks gkon'; // Your App Password here
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;
                        
                        // Recipients
                        $mail->setFrom('rke49127@gmail.com', 'RKE');
                        $mail->addAddress($email); // Add a recipient

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Password Changed Successfully';
                        $mail->Body    = 'Your password has been changed successfully. If you did not make this change, please contact support immediately.';
                        $mail->AltBody = 'Your password has been changed successfully. If you did not make this change, please contact support immediately.';

                        $mail->send();

                        $message = "<div style='color: green;'>Password updated successfully! A notification has been sent to your email.</div>";
                    } catch (Exception $e) {
                        $message = "<div style='color: red;'>Password updated, but notification email could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
                    }
                } else {
                    $message = "<div style='color: red;'>Error occurred while updating password.</div>";
                }
            }
        } else {
            $message = "<div style='color: red;'>Email does not exist.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Forgot Password</title>
    <style>
        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 0; 
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 400px; 
            border-radius: 8px; 
            position: absolute; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%); 
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-[url('/wbsif/login/images/bg1.png')] bg-center bg-cover bg-fixed min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-lg shadow-lg w-96 max-w-md p-6">
        <h1 class="text-xl font-semibold text-black text-center">Update Your Password</h1>

     

        <div class="text-center mb-4">
        <?php if (!empty($message)) echo $message; ?>

        </div>

        <form action="" method="POST">
            <input type="email" name="email" required placeholder="Email" class="w-full border border-gray-300 rounded-md p-2 mb-4">
            <input type="password" name="new_password" required placeholder="New Password" class="w-full border border-gray-300 rounded-md p-2 mb-4">
            <input type="password" name="confirm_password" required placeholder="Confirm Password" class="w-full border border-gray-300 rounded-md p-2 mb-4">
            
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <button type="submit" name="update_password" class="w-full py-2 text-sm border border-gray-300 rounded bg-sky-900 text-white hover:bg-sky-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-sky-900 text-center">Update Password</button>
            </div>
        </form>

        <div class="flex justify-between mt-4">
        <a href="login.php" class="text-sm text-sky-900 hover:underline">Back to Login</a>
        <button id="forgotPasswordBtn" class="text-sm text-sky-900 hover:underline">Try Another Way</button>
        </div>

    </div>

    <!-- Modal for email input -->
    <div id="emailModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h2 class="text-lg font-semibold mb-4">Enter your email address:</h2>
            <form action="" method="POST">
                <input type="email" name="email" required placeholder="Email" class="w-full border border-gray-300 rounded-md p-2 mb-4">
                
                
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <button type="submit" name="send_email" class="w-full py-2 text-sm border border-gray-300 rounded bg-sky-900 text-white hover:bg-sky-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-sky-900 text-center">Send Email</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("emailModal");
        var btn = document.getElementById("forgotPasswordBtn");
        var span = document.getElementById("closeModal");

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
