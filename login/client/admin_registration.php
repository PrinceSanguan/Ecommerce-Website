<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

// Create a connection to the database
$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$firstname = "";
$lastname = "";
$email = "";
$password = "";
$confirmPassword = "";
$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate the input
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirmPassword)) {
        $errorMessage = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $errorMessage = "Passwords do not match.";
    } else {
        // Check if email already exists in admin_list table
        $sql = "SELECT * FROM admin_list WHERE email = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errorMessage = "Email already exists.";
        } else {
            // Hash the password before saving
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin into admin_list table
            $sql = "INSERT INTO admin_list (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashedPassword);

            if ($stmt->execute()) {
                $successMessage = "Admin registration successful!";
                // Clear form fields after successful registration
                $firstname = $lastname = $email = $password = $confirmPassword = "";
            } else {
                $errorMessage = "Error: " . $stmt->error;
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
    <title>Admin Registration</title>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-4">Admin Registration</h1>

        <?php
        if (!empty($errorMessage)) {
            echo "<div class='bg-red-100 text-red-600 p-2 rounded mb-4'>$errorMessage</div>";
        }

        if (!empty($successMessage)) {
            echo "<div class='bg-green-100 text-green-600 p-2 rounded mb-4'>$successMessage</div>";
        }
        ?>

        <form action="#" method="post" class="space-y-4">
            <div>
                <label for="firstname" class="block text-sm font-medium">First Name</label>
                <input type="text" name="firstname" required class="w-full p-2 border border-gray-300 rounded" value="<?php echo htmlspecialchars($firstname); ?>">
            </div>

            <div>
                <label for="lastname" class="block text-sm font-medium">Last Name</label>
                <input type="text" name="lastname" required class="w-full p-2 border border-gray-300 rounded" value="<?php echo htmlspecialchars($lastname); ?>">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" name="email" required class="w-full p-2 border border-gray-300 rounded" value="<?php echo htmlspecialchars($email); ?>">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium">Password</label>
                <input type="password" name="password" required class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium">Confirm Password</label>
                <input type="password" name="confirm_password" required class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="flex justify-center">
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-500">Register Admin</button>
            </div>
        </form>
    </div>
</body>
</html>
