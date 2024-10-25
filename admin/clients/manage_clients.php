
<?php

session_start();


$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

// Create a connection to the database
$connection = new mysqli($servername, $username, $password, $database);

$id = "";
$firstname = "";
$middlename = "";
$lastname = "";
$gender = "";
$phone = "";
$address = "";
$email = "";
$newPassword = "";
$status = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["id"])) {
        header("location: /login/list_clients.php");
        exit;
    }

    $id = $_GET["id"];

    $sql = "SELECT * FROM client_list WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /login/list_clients.php");
        exit;
    }

    $firstname = $row["firstname"];
    $middlename = $row["middlename"];
    $lastname = $row["lastname"];
    $gender = $row["gender"];
    $phone = $row["phone"];
    $address = $row["address"];
    $email = $row["email"];
    $status = $row["status"]; // Fetch the status

} else {
    $id = $_POST["id"];
    $firstname = $_POST["firstname"];
    $middlename = $_POST["middlename"];
    $lastname = $_POST["lastname"];
    $gender = $_POST["gender"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $newPassword = $_POST["new_password"];
    $status = $_POST["status"]; // Get the status from the form

    do {
        if (empty($id) || empty($firstname) || empty($lastname) || empty($gender) || empty($phone) || empty($address) || empty($email) || empty($status)) {
            $errorMessage = "All fields are required";
            break;
        }

        // Retrieve the current email of the client
        $sql = "SELECT email, password FROM client_list WHERE id = $id";
        $result = $connection->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $currentEmail = $row['email'];
            $currentHashedPassword = $row['password'];
        } else {
            $errorMessage = "Failed to fetch current email: " . $connection->error;
            break;
        }

        // Check if the new email already exists in the database (excluding the current record)
        $sql = "SELECT id FROM client_list WHERE email = '$email' AND id != $id";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            $errorMessage = "The email address is already in use.";
            break;
        }

        // Check if the password is being updated
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE client_list SET firstname = '$firstname', middlename = '$middlename', lastname = '$lastname', gender = '$gender', phone = '$phone', address = '$address', email = '$email', password = '$hashedPassword', status = '$status' WHERE id = $id";
        } else {
            $sql = "UPDATE client_list SET firstname = '$firstname', middlename = '$middlename', lastname = '$lastname', gender = '$gender', phone = '$phone', address = '$address', email = '$email', status = '$status' WHERE id = $id";
        }

        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
            break;
        }

        $successMessage = "Client updated correctly";
        header("location: /wbsif/admin/clients/list_clients.php");
        exit;

    } while (true);
}

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // Fallback if no name is provided
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Clients</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js"></script>

    <style>
        
    </style>
    
</head>
<body class="relative">
    <div class="flex h-screen">
        <!-- Button to toggle the side navigation on small screens -->
        <button class="lg:hidden text-gray-950 p-1 absolute top-1 left-3 z-50" onclick="toggleNavSmall()">
            ☰
        </button>
    
        <!-- Side Navigation -->
        <nav id="nav-left" class="nav-left bg-gray-800 z-50 lg:relative flex flex-col transition-all duration-300">
            <ul class="flex flex-col text-xs h-full">
                <div class="flex items-center justify-center text-center p-2 bg-sky-900">
                    <img src="../images/logo.jpg" class="w-8 h-8 rounded-full cursor-pointer">
                    <h1 class="ml-4 text-sm text-white font-bold nav-title">R K E</h1>
                </div>

                <li class="mt-4">
                    <a href="../dashboard/dashboard.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500 mt-7" data-href="dashboard.html">
                        <img src="../images/icons8-dashboard-50.png" class="w-5 h-5 mr-2 ml-1 filter brightness-0 invert">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="../products/product_list.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500">
                        <img src="../images/icons8-bulleted-list-48.png" class="w-5 h-5 mr-2 ml-1 filter brightness-0 invert">
                        <span>Product List</span>
                    </a>
                </li>
                <li>
                    <a href="../inventory/product_stocks.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500">
                        <img src="../images/icons8-inventory-50.png" class="w-5 h-5 mr-2 ml-1 filter brightness-0 invert">
                        <span>Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="../maintenance/list_of_category.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500">
                        <img src="../images/icons8-category-50 (1).png" class="w-5 h-5 mr-2 ml-1 filter brightness-0 invert">
                        <span>Category List</span>
                    </a>
                </li>
                <li>
                    <a href="../maintenance/list_of_brand.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500">
                        <img src="../images/icons8-list-50.png" class="w-5 h-5 mr-2 ml-1 filter brightness-0 invert">
                        <span>Brand List</span>
                    </a>
                </li>
                <li>
                    <a href="../orders/order_list.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500">
                        <img src="../images/icons8-order-50.png" class="w-5 h-5 mr-2 ml-1 filter brightness-0 invert">
                        <span>Orders</span>
                    </a>
                </li>
                <li>
                    <a href="../orders report/orders_report.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500">
                        <img src="../images/icons8-order-50.png" class="w-5 h-5 mr-2 ml-1 filter brightness-0 invert">
                        <span>Orders Report</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link flex items-center p-2 text-white hover:bg-gray-500">
                        <img src="../images/icons8-analytics-30.png" class="w-5 h-5 mr-2 ml-1 filter brightness-0 invert">
                        <span>Analytics</span>
                    </a>
                </li>
                <li>
                    <a href="/wbsif/admin/clients/list_clients.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500">
                        <img src="../images/icons8-group-30.png" class="w-5 h-5 mr-2 ml-1 filter brightness-0 invert">
                        <span>Registered Clients</span>
                    </a>
                </li>
                <li>
                    <a href="../user/users.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500">
                        <img src="../images/icons8-users-settings-32.png" class="w-5 h-5 mr-2 ml-1 filter brightness-0 invert">
                        <span>User List</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="flex flex-col flex-grow bg-slate-100">
            <div class="flex flex-col lg:flex-row shadow-xl p-2 bg-white">
                <div class="center-container flex items-center justify-start w-full lg:w-3/5">
                    <button class="hidden lg:block text-gray-900 p-1 ml-3" onclick="toggleNavLarge()">
                        ☰
                    </button>
                    <h1 class="text-black lg:text-sm hidden lg:block ml-2">Motorcycle Parts & Accessories Management System - Admin</h1>
                </div>
    
             
                <div class="dropdown-container flex items-center lg:w-1/5 ml-auto">
    <ul class="flex items-center w-full justify-end">
        <li class="list-none inline-block dropdown">
            <button class="dropbtn px-2 text-black flex items-center">
                <?php if (!empty($user_avatar)): ?>
                    <img src="<?php echo htmlspecialchars($user_avatar); ?>" alt="User Avatar" class="w-8 h-8 rounded-full mr-2">
                <?php else: ?>
                    <img src="../images/icons8-user-32.png" alt="Default Avatar" class="w-8 h-8 rounded-full mr-2">
                <?php endif; ?>
                <span class="text-black">
                    <p><?php echo htmlspecialchars($user_email); ?></p>
                </span>
                <img src="../images/icons8-dropdown-24.png" class="ml-2" style="width: 1.5em; height: 1.5em; vertical-align: middle;">
            </button>
            <div class="dropdown-content px-0.5 py-0.5 border space-y-2">
                <div>
                    <a href="../manage account admin/manage_account.php" class="flex items-center p-2 hover:bg-gray-500">
                        <img src="../images/icons8-user-24.png" class="w-5 h-5 mr-2 ml-1">
                        <span>My Account</span>
                    </a>
                </div>
                <div>
                    <a href="/wbsif/login/client/logout.php" class="flex items-center p-2 hover:bg-gray-500">
                        <img src="../images/icons8-logout-50.png" class="w-5 h-5 mr-2 ml-1">
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </li>
    </ul>
</div>
            </div>
            <!-- Welcome Container -->
            <div>
                <div class="p-4 w-full h-full mt-3">
                    <div class="flex flex-col p-2 border-t-4 border-sky-900 rounded-md shadow-2xl bg-white">
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-black text-xl">Edit Client</h1>
                        </div>
                        
                        <div class="relative w-full mb-7">
                            <div class="absolute left-0 right-0 bottom-0 border-t border-zinc-400"></div>
                        </div>
                        
                        <div class="container  p-4">
                                        <?php
                                            // Display error message if set
                                            if (!empty($errorMessage)) {
                                                echo "
                                                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                                        <strong>$errorMessage</strong>
                                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                                    </div>
                                                ";
                                            }
                                        ?>

                                        <?php
                                            // Display success message if set
                                            if (!empty($successMessage)) {
                                                echo "
                                                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                                        <strong>$successMessage</strong>
                                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                                    </div>
                                                ";
                                            }
                                        ?>

                                    <form method="post" action="">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                                        <div class="mb-4">
                                            <label class="form-label text-sm text-gray-700">First Name</label>
                                            <input type="text" name="firstname" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" value="<?php echo htmlspecialchars($firstname); ?>" required>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-sm text-gray-700">Middle Name</label>
                                            <input type="text" name="middlename" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" value="<?php echo htmlspecialchars($middlename); ?>">
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-sm text-gray-700">Last Name</label>
                                            <input type="text" name="lastname" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" value="<?php echo htmlspecialchars($lastname); ?>" required>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-sm text-gray-700">Gender</label>
                                            <select name="gender" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" required>
                                                <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                                                <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                                            </select>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-sm text-gray-700">Phone</label>
                                            <input type="text" name="phone" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" value="<?php echo htmlspecialchars($phone); ?>" required>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-sm text-gray-700">Address</label>
                                            <input type="text" name="address" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" value="<?php echo htmlspecialchars($address); ?>" required>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-sm text-gray-700">Email</label>
                                            <input type="email" name="email" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" value="<?php echo htmlspecialchars($email); ?>" required>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-sm text-gray-700">New Password</label>
                                            <input type="password" name="new_password" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" placeholder="Leave blank to keep current password">
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-sm text-gray-700">Status</label>
                                            <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" required>
                                                <option value="Active" <?php if ($status == 'Active') echo 'selected'; ?>>Active</option>
                                                <option value="Inactive" <?php if ($status == 'Inactive') echo 'selected'; ?>>Inactive</option>
                                            </select>
                                        </div>

                                        
                                    

                                        <div class="mt-4 flex justify-end">
                                            <button type="submit" class="bg-sky-900 text-white py-1 px-3 rounded hover:bg-sky-700">Update</button>
                                            <a href="/wbsif/admin/clients/list_clients.php" class="ml-2 bg-gray-500 text-white py-1 px-3 rounded hover:bg-gray-700">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>



