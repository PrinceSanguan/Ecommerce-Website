<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

// Create a connection to the database
$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$email = "";
$password = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if any field is empty
    if (empty($email) || empty($password)) {
        $errorMessage = "All fields are required.";
    } else {
        // Query for admin users
        $sqlAdmin = "SELECT * FROM admin_list WHERE email = ?";
        $stmtAdmin = $connection->prepare($sqlAdmin);
        $stmtAdmin->bind_param('s', $email);
        $stmtAdmin->execute();
        $resultAdmin = $stmtAdmin->get_result();
        $admin = $resultAdmin->fetch_assoc();

        // If the email exists in the admin_list
        if ($admin) {
            // Verify admin password
            if (password_verify($password, $admin['password'])) {
                // Check if the account is active
                if ($admin['status'] === 'active') {
                    // Set session variables for admin
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $admin['id'];
                    $_SESSION['user_email'] = $admin['email'];
                    $_SESSION['user_name'] = $admin['firstname'] . ' ' . $admin['lastname'];
                    $_SESSION['user_role'] = 'admin'; // Set user role as 'admin'

                    // Redirect to admin dashboard
                    header("location: /wbsif/admin/dashboard/dashboard.php");
                    exit;
                } else {
                    // Account is inactive
                    $errorMessage = "<div style='color: red; text-align: center; margin-top: 20px; font-size: 14px;'>Your account is inactive. Please contact the administrator.</div>";
                }
            } else {
                $errorMessage = "<div style='color: red; text-align: center; margin-top: 20px; font-size: 14px;'>Invalid email or password.</div>";
            }
        } else {
            // If not admin, check the client_list table for client users
            $sqlClient = "SELECT * FROM client_list WHERE email = ? AND status = 'active'";
            $stmtClient = $connection->prepare($sqlClient);
            $stmtClient->bind_param('s', $email);
            $stmtClient->execute();
            $resultClient = $stmtClient->get_result();
            $client = $resultClient->fetch_assoc();

            // If the email exists in the client_list
            if ($client) {
                // Verify client password
                if (password_verify($password, $client['password'])) {
                    // Set session variables for client
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $client['id'];
                    $_SESSION['user_email'] = $client['email'];
                    $_SESSION['user_name'] = $client['firstname'] . ' ' . $client['lastname'];
                    $_SESSION['user_role'] = 'client'; // Set user role as 'client'

                    // Redirect to client home page
                    header("location: /webpage/Home.php");
                    exit;
                } else {
                    $errorMessage = "<div style='color: red; text-align: center; margin-top: 20px; font-size: 14px;'>Invalid email or password.</div>";
                }
            } else {
                $errorMessage = "<div style='color: red; text-align: center; margin-top: 20px; font-size: 14px;'>Your account is either inactive or doesn't exist.</div>";
            }
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
    <title>Login</title>
</head>
<body class="bg-[url('/wbsif/login/images/bg1.png')] bg-center bg-cover bg-fixed min-h-screen flex items-center justify-center p-4">

    <div class="bg-neutral-100 rounded-lg shadow-lg w-96 max-w-md shadow-black-500/50 p-6">
        <div class="flex items-center p-2 relative">
            <img src="/wbsif/login/images/logo.jpg" class="w-10 h-10 rounded-full cursor-pointer absolute left-2">
            <h1 class="text-xl font-semibold text-black mx-auto">Login</h1>
        </div>

        <?php
        if (!empty($errorMessage)) {
            echo "
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong>$errorMessage</strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
            ";
        }
        ?>
       <form action="#" method="post" class="space-y-2 mt-2">
    <div class="relative">
        <input type="email" name="email" required placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" class="w-full p-2 pr-10 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-sky-900">
        <img src="/wbsif/login/images/icons8-user-24 (1).png" alt="User Icon" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-5 h-5">
    </div>

    <div class="relative">
        <input type="password" name="password" placeholder="Password" required class="w-full p-2 pr-10 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-sky-900">
        <img src="/wbsif/login/images/icons8-lock-30.png" alt="User Icon" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-5 h-5">
    </div>

    <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
        <input type="submit" value="Login" class="w-full py-2 text-sm border border-gray-300 rounded bg-sky-900 text-white hover:bg-sky-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-sky-900 text-center">
    </div>
    
    <div class="flex justify-between mt-4">
        <a href="registration.php" class="text-sm text-sky-900 hover:underline">Create an Account</a>
        <a href="forgot_password.php?email=<?php echo urlencode($email); ?>" class="text-sm text-sky-900 hover:underline">Forgot Password?</a>
    </div>
</form>



    </div>
</body>
</html>
