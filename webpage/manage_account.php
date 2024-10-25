<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

$connection = new mysqli($servername, $username, $password, $database);

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("location: /wbsif/webpage/Home.php");
    exit;
}

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // Fallback if no name is provided


// Fetch user information from the database
$sql = "SELECT firstname, lastname, email, address, phone FROM client_list WHERE email = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Check if the new email already exists for a different user
    $email_check_query = "SELECT id FROM client_list WHERE email = ? AND email != ?";
    $stmt = $connection->prepare($email_check_query);
    $stmt->bind_param("ss", $email, $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "Error: The email is already in use by another account.";
        $_SESSION['message_type'] = "error";
    } else {
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_query = "UPDATE client_list SET firstname = ?, lastname = ?, email = ?, address = ?, phone = ?, password = ? WHERE email = ?";
            $stmt = $connection->prepare($update_query);
            $stmt->bind_param("sssssss", $firstname, $lastname, $email, $address, $phone, $hashed_password, $user_email);
        } else {
            $update_query = "UPDATE client_list SET firstname = ?, lastname = ?, email = ?, address = ?, phone = ? WHERE email = ?";
            $stmt = $connection->prepare($update_query);
            $stmt->bind_param("ssssss", $firstname, $lastname, $email, $address, $phone, $user_email);
        }

        if ($stmt->execute()) {
            $_SESSION['user_email'] = $email;
            $_SESSION['message'] = "Account updated successfully!";
            $_SESSION['message_type'] = "success";
            header("Location: manage_account.php");
            exit;
        } else {
            $_SESSION['message'] = "Error updating account.";
            $_SESSION['message_type'] = "error";
        }
    }
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Count items in the cart
$item_count = 0;
foreach ($_SESSION['cart'] as $item) {
    if (isset($item['quantity'])) {
        $item_count += $item['quantity'];
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="script.js"></script>
</head>
<body>
    <div class="bg-[url('images/bg1.png')] bg-center bg-cover bg-fixed min-h-screen w-full pt-6 flex flex-col">
        <div class="header-fixed w-full flex items-center">
            <button id="menu-toggle" class="lg:hidden text-white text-xl px-8 py-4 rounded-md absolute">☰</button>
            <div class="px-20 p-2 flex flex-col lg:flex-row items-center mb-2 border-b-stone-100 flex-1">
                <div class="flex items-center mb-4 lg:mb-0">
                    <img src="images/logo.jpg" class="w-12 h-12 rounded-full cursor-pointer">
                    <h1 class="ml-4 text-white font-extrabold text-xl cursor-pointer">R K E</h1>
                </div>
                <ul id="nav-links" class="lg:flex flex-1 justify-center lg:justify-center text-center">
                    <li class="list-none px-4 py-2 lg:px-7"><a href="Home.php" class="no-underline text-white">Home</a></li>
                    <li class="list-none px-4 py-2 lg:px-7"><a href="products.php" class="no-underline text-white">Products</a></li>
                    <li class="list-none px-4 py-2 lg:px-7"><a href="about_us.php" class="no-underline text-white">About Us</a></li>
                    <li class="list-none px-4 py-2 lg:px-7"><a href="orders.php" class="no-underline text-white">Orders</a></li>
                    <li class="list-none px-4 py-2 lg:px-7">
    <a href="cart.php" class="no-underline text-white">
        Cart 
        <?php if ($item_count > 0): ?>
            <span style="background-color: red; color: white; border-radius: 9999px; padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                <?php echo $item_count; ?>
            </span>
        <?php endif; ?>
    </a>
</li>                  
</ul>
                <ul class="flex items-center">
                    <li class="list-none inline-block dropdown">
                        <button class="dropbtn text-white px-2 flex items-center">
                            <p><?php echo htmlspecialchars($user_email); ?></p>
                            <img src="images/icons8-dropdown-24.png" class="ml-2" style="filter: invert(100%); width: 1.5em; height: 1.5em; vertical-align: middle;">
                        </button>
                        <div class="dropdown-content px-0.5 py-0.5">
                            <a href="#">Chat Bot</a>
                            <a href="manage_account.php">Manage Account</a>
                            <a href="/wbsif/login/client/logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>



        <div class="px-20 py-3 flex flex-wrap mt-20">
            <div class="w-1/3">
                <h1 class="text-white text-2xl">My Account</h1>
            </div>
            <div class="w-full max-w-full mx-auto mt-5">
                <div class="border-t border-zinc-400"></div>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
        <div class="mb-4 p-3 rounded-lg 
            <?php echo $_SESSION['message_type'] === 'success' ? 'bg-green-200 text-green-700' : 'bg-red-200 text-red-700'; ?>">
            <?php 
                echo $_SESSION['message'];
                unset($_SESSION['message']); // Clear the message after displaying it
            ?>
        </div>
    <?php endif; ?>

            <div class="w-full max-w-full mx-auto mt-5 bg-white rounded-lg p-4" style="border-top: 4px solid #333;">
            <form action="manage_account.php" method="POST">
                    <div class="mb-4">
                        <label for="firstname" class="form-label text-sm text-gray-700">First Name</label>
                        <input type="text" name="firstname" id="firstname" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" value="<?php echo htmlspecialchars($user_data['firstname']); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="lastname" class="form-label text-sm text-gray-700">Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" value="<?php echo htmlspecialchars($user_data['lastname']); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label text-sm text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="form-label text-sm text-gray-700">Address</label>
                        <input type="text" name="address" id="address" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" value="<?php echo htmlspecialchars($user_data['address']); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="form-label text-sm text-gray-700">Phone</label>
                        <input type="text" name="phone" id="phone" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" value="<?php echo htmlspecialchars($user_data['phone']); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="form-label text-sm text-gray-700">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" placeholder="Leave blank to keep current password">
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="bg-sky-900 text-white py-1 px-3 rounded hover:bg-sky-700">Update</button>
                        <button type="button" class="ml-2 bg-gray-500 text-white py-1 px-3 rounded hover:bg-gray-700" onclick="window.history.back();">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>






        
