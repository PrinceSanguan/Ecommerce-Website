<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wbsif_db";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // Fallback if no name is provided


// Get user ID from URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the admin user data
    $sql = "SELECT * FROM admin_list WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // If form is submitted, update the user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $new_password = $_POST['new_password'];

    // Validate form inputs
    if (!empty($firstname) && !empty($lastname) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare update query for user data
        $update_sql = "UPDATE admin_list SET firstname = ?, lastname = ?, email = ?, status = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssi", $firstname, $lastname, $email, $status, $id);
        $update_stmt->execute();

        // Update password if provided
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_password_sql = "UPDATE admin_list SET password = ? WHERE id = ?";
            $update_password_stmt = $conn->prepare($update_password_sql);
            $update_password_stmt->bind_param("si", $hashed_password, $id);
            $update_password_stmt->execute();
        }

        // Update session data
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $firstname . ' ' . $lastname;

        // Redirect to users.php after updating
        header("Location: /wbsif/admin/user/users.php");
        exit(); // Ensure no further code is executed
    }
}

} else {
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js"></script>
</head>
<body class="relative">
    <div class="flex h-screen">
        <button class="lg:hidden text-gray-950 p-1 absolute top-1 left-3 z-50" onclick="toggleNavSmall()">
            ☰
        </button>

        <nav id="nav-left" class="nav-left bg-gray-800 z-50 lg:relative flex flex-col transition-all duration-300">
            <ul class="flex flex-col text-xs h-full">
                <div class="flex items-center justify-center text-center p-2 bg-sky-900">
                    <img src="../images/logo.jpg" class="w-8 h-8 rounded-full cursor-pointer">
                    <h1 class="ml-4 text-sm text-white font-bold nav-title">R K E</h1>
                </div>

                <li class="mt-4">
                    <a href="../dashboard/dashboard.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500 mt-7">
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
            <div>
                <div class="p-4 w-full h-full mt-3">
                    <div class="flex flex-col p-2 border-t-4 border-sky-900 rounded-md shadow-2xl bg-white">
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-black text-xl">Edit Admin/User</h1>
                        </div>
                        
                        <div class="relative w-full mb-7">
                            <div class="absolute left-0 right-0 bottom-0 border-t border-zinc-400"></div>
                        </div>
                        
                        <div class="container  p-4">
                <div >
                    
                    <form action="" method="post" >
                        <div class="mb-4">
                            <label for="firstname"  class="form-label text-sm text-gray-700">First Name</label>
                            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($admin['firstname']); ?>" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" required>
                        </div>
                        <div class="mb-4">
                            <label for="lastname" class="form-label text-sm text-gray-700">Last Name</label>
                            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($admin['lastname']); ?>" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label text-sm text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($admin['email']); ?>" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" required>
                        </div>
                        <div class="mb-4">
                            <label for="status" class="form-label text-sm text-gray-700">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" required>
                                <option value="active" <?php if ($admin['status'] === 'active') echo 'selected'; ?>>Active</option>
                                <option value="inactive" <?php if ($admin['status'] === 'inactive') echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="new_password" class="form-label text-sm text-gray-700">New Password</label>
                            <input type="password" name="new_password" id="new_password" placeholder="Leave blank to keep current password" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600">
                        </div>


                        <div class="mt-4 flex justify-end">
                        <button type="submit" class="bg-sky-900 text-white py-1 px-3 rounded hover:bg-sky-700">Update</button>
                                            <a href="/wbsif/admin/user/users.php" class="ml-2 bg-gray-500 text-white py-1 px-3 rounded hover:bg-gray-700">Cancel</a>
                                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
           