<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$database = "wbsif_db";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Registration Logic
$firstname = $middlename = $lastname = $gender = $phone = $address = $email = $password = $confirmPassword = "";
$errorMessage = $successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST["firstname"];
    $middlename = $_POST["middlename"];
    $lastname = $_POST["lastname"];
    $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";  
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $verify_token = md5(rand());

    do {
        if (empty($firstname) || empty($lastname) || empty($gender) || empty($phone) || empty($address) || empty($email) || empty($password) || empty($confirmPassword)) {
            $errorMessage = "<div class='text-red-600 text-center'>All fields are required</div>"; // Red color for error, centered
            break;
        }
        
        if ($password !== $confirmPassword) {
            $errorMessage = "<div class='text-red-600 text-center'>Password Not Match</div>"; // Red color for error, centered
            break;
        }
        
        // Check if email already exists
        $checkEmailQuery = "SELECT * FROM client_list WHERE email = '$email'";
        $emailResult = $connection->query($checkEmailQuery);
        
        if ($emailResult && $emailResult->num_rows > 0) {
            $errorMessage = "<div class='text-red-600 text-center'>This email is already registered</div>"; // Red color for error, centered
            break;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO client_list (firstname, middlename, lastname, gender, phone, address, email, password, verified) 
        VALUES ('$firstname', '$middlename', '$lastname', '$gender', '$phone', '$address', '$email', '$hashedPassword', 0)";

        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
            break;
        }

        // Send confirmation email after successful registration
        // Send confirmation email after successful registration
$mail = new PHPMailer(true);
$sendEmailResponse = sendemail($mail, $firstname, $middlename, $lastname, $email); // Now includes $middlename

// Check if email was sent successfully
if (strpos($sendEmailResponse, 'failed') !== false) {
    $errorMessage = $sendEmailResponse; // Capture the error message from email sending
} else {
    $successMessage = $sendEmailResponse;
    // Redirect to login after successful registration
    header("Location: login.php");
    exit;
}


        // Clear form fields after success
        $firstname = $middlename = $lastname = $gender = $phone = $address = $email = $password = $confirmPassword = "";

    } while (false);
}

function sendemail($mail, $firstname, $middlename, $lastname, $email) {
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
        $fullName = "$firstname $middlename $lastname"; // Combine first and last name
        $mail->setFrom('rke49127@gmail.com', 'RKE'); // Use the RKE name here
        $mail->addAddress($email, $fullName); // Send to the user's email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'RKE Registration Confirmation';

        // Embed RKE logo
        
        $mail->Body = "
        <div style='text-align: center;'>
            
            <h1>Welcome, $fullName!</h1>
            <p>Your registration to RKE was successful.</p>
        </div>
        ";

        $mail->send();
        return "Registration successful! A confirmation email has been sent to $email.";
    } catch (Exception $e) {
        return "Registration successful, but failed to send confirmation email: {$mail->ErrorInfo}";
    }
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js"></script>
    <title>Register</title>
</head>
<body class="bg-[url('/wbsif/login/images/bg1.png')] bg-center bg-cover bg-fixed min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl shadow-black-500/50 p-8">
        <div class="flex items-center p-2 relative mb-3">
            <img src="/wbsif/login/images/logo.jpg" class="w-12 h-12 rounded-full cursor-pointer absolute left-2">
            <h1 class="text-xl font-semibold text-black mx-auto">Create an Account</h1>
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
        if (!empty($successMessage)) {
            echo "
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>$successMessage</strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
            ";
        }
        ?>
        <form action="#" method="post" class="space-y-4">
            <!-- First Name and Middle Name -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required placeholder="First Name" class="w-full p-3 text-sm border border-gray-300 rounded focus:border-sky-900 focus:outline-none">
                    <span class="text-xs text-gray-600 block mt-1">First Name</span>
                </div>
                <div>
                    <input type="text" name="middlename" value="<?php echo htmlspecialchars($middlename); ?>" required placeholder="Middle Name" class="w-full p-3 text-sm border border-gray-300 rounded focus:border-sky-900 focus:outline-none">
                    <span class="text-xs text-gray-600 block mt-1">Middle Name</span>
                </div>
            </div>

            <!-- Last Name -->
            <div>
                <input type="text" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required placeholder="Last Name" class="w-full p-3 text-sm border border-gray-300 rounded focus:border-sky-900 focus:outline-none">
                <span class="text-xs text-gray-600 block mt-1">Last Name</span>
            </div>

            <!-- Gender and Contact -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <select id="gender" name="gender" class="w-full p-3 text-sm border border-gray-300 rounded focus:border-sky-900 focus:outline-none">
                        <option value="" disabled <?php echo empty($gender) ? 'selected' : ''; ?>>Select Gender</option>
                        <option value="Male" <?php echo $gender === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $gender === 'Female' ? 'selected' : ''; ?>>Female</option>
                    </select>
                    <label for="gender" class="text-xs text-gray-600 block mt-1">Gender</label>
                </div>
                <div>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required placeholder="Contact#" class="w-full p-3 text-sm border border-gray-300 rounded focus:border-sky-900 focus:outline-none">
                    <span class="text-xs text-gray-600 block mt-1">Contact#</span>
                </div>
            </div>

            <!-- Address -->
            <div>
                <span class="text-xs text-gray-600 block mb-1">Address</span>
                <textarea name="address" rows="3" required placeholder="Address" class="w-full p-3 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-sky-900"></textarea>
            </div>

            <!-- Email -->
            <div>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="Email" class="w-full p-3 text-sm border border-gray-300 rounded focus:border-sky-900 focus:outline-none">
                <span class="text-xs text-gray-600 block mt-1">Email</span>
            </div>

            <!-- Password and Confirm Password -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <input type="password" name="password" required placeholder="Password" class="w-full p-3 text-sm border border-gray-300 rounded focus:border-sky-900 focus:outline-none">
                    <span class="text-xs text-gray-600 block mt-1">Password</span>
                </div>
                <div>
                    <input type="password" name="confirm_password" required placeholder="Confirm Password" class="w-full p-3 text-sm border border-gray-300 rounded focus:border-sky-900 focus:outline-none">
                    <span class="text-xs text-gray-600 block mt-1">Confirm Password</span>
                </div>
            </div>

            <!-- Register Button -->
            <div>
                <button type="submit" class="w-full p-3 text-sm bg-sky-900 text-white rounded">Register</button>
            </div>
            <div class="text-sm text-center mt-2">
                Already have an account? <a href="login.php" class="text-sky-900 hover:underline">Login</a>
            </div>
        </form>
    </div>
</body>

</html>
