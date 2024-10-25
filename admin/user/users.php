<?php
session_start(); // Start the session to access session variables

// Database connection details
$servername = "localhost"; // Server name (usually localhost)
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "wbsif_db"; // Your database name

// Assuming you have stored the logged-in user's ID or email in the session when they logged in
$loggedInUserEmail = $_SESSION['user_email']; // Modify this according to your login system

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // Fallback if no name is provided

// Fetch data from the admin_list table, excluding the logged-in user's account
$sql = "SELECT * FROM admin_list WHERE email != '$loggedInUserEmail'";
$result = $conn->query($sql);

$adminAccounts = []; // Initialize an array to store admin accounts
if ($result && $result->num_rows > 0) {
    // Fetch all admin accounts, excluding the logged-in user
    while($row = $result->fetch_assoc()) {
        $adminAccounts[] = $row; // Add each row to the array
    }
}
$conn->close(); // Close the database connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
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
                    <a href="../analytic/analytic.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500">
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
                            <h1 class="text-black text-xl">List of Admin/Users</h1>
                            <a href="create_users.php">
                                <button class="bg-blue-500 text-white py-2 px-4 rounded flex items-center hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"></path>
                                    </svg>
                                    Add User
                                </button>
                            </a>

                        </div>
                        
                        <div class="relative w-full mb-7">
                            <div class="absolute left-0 right-0 bottom-0 border-t border-zinc-400"></div>
                        </div>
                        
                        <div class="flex items-center mb-4">
                            <div class="ml-auto flex items-center">
                                <span class="text-gray-500 mr-2">Search:</span>
                                <input type="text" class="block w-64 px-2 py-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none">
                            </div>
                        </div>
                        
                        <!-- Dynamic Table -->
                        <div class="overflow-x-auto">
                        <table class='min-w-full bg-white border border-gray-300 rounded-md shadow-sm'>
    <thead>
        <tr class='bg-gray-200 text-gray-700 text-xs uppercase'>
        <th class='py-2 px-3 text-left'>#</th>
            <th class='py-2 px-3 text-left'>Date Created</th>
            <th class='py-2 px-3 text-left'>Name</th>
            <th class='py-2 px-3 text-left'>Email</th>
            <th class='py-2 px-3 text-left'>Status</th>
            <th class='py-2 px-3 text-center'>Action</th>
        </tr>
    </thead>
    <tbody class='text-gray-600 text-xs'>
    <?php if (empty($adminAccounts)): ?>
        <tr class="border-b border-gray-300">
            <td colspan="5" class="py-3 px-6 text-left">No admin accounts found.</td>
        </tr>
    <?php else: ?>
        <?php 
        $counter = 1; // Initialize a counter
        foreach ($adminAccounts as $admin): ?>
            <tr class='border-b border-gray-200 hover:bg-gray-100'>
                <td class='py-2 px-3'><?php echo $counter++; ?></td> <!-- Display the counter and then increment it -->

                <td class='py-2 px-3'><?php echo htmlspecialchars($admin['created_at']); ?></td>
                <td class='py-2 px-3'>
                    <?php echo htmlspecialchars($admin['firstname'] . ' ' . $admin['lastname']); ?>
                </td>
                <td class='py-2 px-3'><?php echo htmlspecialchars($admin['email']); ?></td>
                <td class='py-2 px-3 font-bold'>
                    <?php 
                    $status = isset($admin['status']) ? htmlspecialchars($admin['status']) : 'No status'; 
                    $statusClass = $admin['status'] === 'active' ? 'text-green-500' : 'text-red-500'; // Set class based on status
                    ?>
                    <span class="<?php echo $statusClass; ?>"><?php echo $status; ?></span>
                </td>

                <td class='py-2 px-3 text-center space-x-1'>
                    <a href='/wbsif/admin/user/edit_user.php?id=<?php echo $admin['id']; ?>' class='bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-xs'>Edit</a> 
                    <a href='/wbsif/admin/user/delete_user.php?id=<?php echo $admin['id']; ?>' onclick='return confirm("Are you sure?");' class='bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs'>Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>


</table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
