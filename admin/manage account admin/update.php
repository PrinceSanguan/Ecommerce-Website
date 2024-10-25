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

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("location: /wbsif/admin/login.php");
    exit;
}

$adminId = $_SESSION['admin_id'] ?? '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newEmail = trim($_POST['email']);
    $newPassword = $_POST['password'];

    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        $emailCheckStmt = $connection->prepare("SELECT COUNT(*) FROM admin_list WHERE email = ? AND id != ?");
        $emailCheckStmt->bind_param("si", $newEmail, $adminId);
        $emailCheckStmt->execute();
        $emailCheckStmt->bind_result($emailExists);
        $emailCheckStmt->fetch();
        $emailCheckStmt->close();

        if ($emailExists > 0) {
            echo "Email already in use. Please choose another.";
        } else {
            if (!empty($newPassword)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $connection->prepare("UPDATE admin_list SET email = ?, password = ? WHERE id = ?");
                $stmt->bind_param("ssi", $newEmail, $hashedPassword, $adminId);
            } else {
                $stmt = $connection->prepare("UPDATE admin_list SET email = ? WHERE id = ?");
                $stmt->bind_param("si", $newEmail, $adminId);
            }

            if ($stmt && $stmt->execute()) {
                $_SESSION['email'] = $newEmail; 
                header("Location: /wbsif/admin/manage_account.php?success=1");
                exit;
            } else {
                echo "Error updating account: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>
