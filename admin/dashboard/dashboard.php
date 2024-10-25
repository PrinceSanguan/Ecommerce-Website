<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header("location: /wbsif/admin/dashboard/dashboard.php");
    exit;
}

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // Fallback if no name is provided

// Get total brands
$brandCountQuery = "SELECT COUNT(*) as total_brands FROM brand_list"; // Adjust table name if needed
$brandCountResult = $conn->query($brandCountQuery);
$brandCount = $brandCountResult->fetch_assoc()['total_brands'];

// Get total categories
$categoryCountQuery = "SELECT COUNT(*) as total_categories FROM category_list"; // Adjust table name if needed
$categoryCountResult = $conn->query($categoryCountQuery);
$categoryCount = $categoryCountResult->fetch_assoc()['total_categories'];

// Get total client
$clientCountQuery = "SELECT COUNT(*) as total_clients FROM client_list"; // Adjust table name if needed
$clientCountResult = $conn->query($clientCountQuery);
$clientCount = $clientCountResult->fetch_assoc()['total_clients'];

//admin
$adminCountQuery = "SELECT COUNT(*) as total_admin FROM admin_list"; // Adjust table name if needed
$adminCountResult = $conn->query($adminCountQuery);
$adminCount = $adminCountResult->fetch_assoc()['total_admin'];
// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/wbsif/admin/assets/style.css">
    <script src="/wbsif/admin/assets/script.js"></script>
    
</head>


<body class="relative">

    <!--loading to
    <div id="loading-overlay" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center ">
        <div class="loader"></div>
    </div>-->

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
                    <a href="/wbsif/admin/dashboard/dashboard.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500 mt-7" data-href="dashboard.html">
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
            
            <div class="p-2 w-full h-full">
                <div class="flex items-center justify-center p-2 text-center">
                    <h1 class="text-black text-2xl lg:text-2xl">Welcome to Motorcycle Parts & Accessories Management System</h1>
                </div>

                <div class="w-full max-w-full mx-auto p-2">
                    <div class="border-t border-zinc-400"></div>
                </div>
                
                <div class="mt-4 flex flex-wrap gap-2 relative text-sm w-full">
    <div class="p-4 rounded-lg flex items-center gap-2 shadow-2xl bg-white w-full lg:w-60">
        <img src="../images/icons8-virus-total-48.png" class="py-3 px-1 bg-stone-800 rounded-lg">
        <div class="flex flex-col justify-between h-full w-full">
            <span>Total Brands:</span>
            <span class="font-bold text-lg text-right ml-auto"><?php echo $brandCount; ?></span>
        </div>
    </div>
    <div class="p-4 rounded-lg flex items-center gap-2 shadow-2xl bg-white w-full lg:w-60">
        <img src="../images/icons8-category-50.png" class="py-3 px-1 bg-slate-500 rounded-lg filter invert">
        <div class="flex flex-col justify-between h-full w-full">
            <span>Total Categories:</span>
            <span class="font-bold text-lg text-right ml-auto"><?php echo $categoryCount; ?></span>
        </div>
    </div>
    <div class="p-4 rounded-lg flex items-center gap-2 shadow-2xl bg-white w-full lg:w-60">
        <img src="../images/icons8-register-50.png" class="py-3 px-1 bg-sky-400 rounded-lg">
        <div class="flex flex-col justify-between h-full w-full">
            <span>Registered Clients:</span>
            <span class="font-bold text-lg text-right ml-auto"><?php echo $clientCount; ?></span>
        </div>
    </div>
    <div class="p-4 rounded-lg flex items-center gap-2 shadow-2xl bg-white w-full lg:w-60">
        <img src="../images/icons8-register-50.png" class="py-3 px-1 bg-sky-400 rounded-lg">
        <div class="flex flex-col justify-between h-full w-full">
            <span>Users:</span>
            <span class="font-bold text-lg text-right ml-auto"><?php echo $adminCount; ?></span>
        </div>
    </div>
    <div class="p-4 rounded-lg flex items-center gap-2 shadow-2xl bg-white w-full lg:w-60">
        <img src="../images/icons8-data-pending-50.png" class="py-3 px-1 bg-stone-400 rounded-lg">
        <div class="flex flex-col justify-between h-full w-full">
            <span>Pending Orders:</span>
            <span class="font-bold text-lg text-right ml-auto">0</span> <!-- Replace with actual count -->
        </div>
    </div>
    <div class="p-4 rounded-lg flex items-center gap-2 shadow-2xl bg-white w-full lg:w-60">
        <img src="../images/icons8-confirmed-48.png" class="py-3 px-1 bg-sky-400 rounded-lg">
        <div class="flex flex-col justify-between h-full w-full">
            <span>Confirmed Orders:</span>
            <span class="font-bold text-lg text-right ml-auto">0</span> <!-- Replace with actual count -->
        </div>
    </div>
</div>


            </div>
        </div>
    </div>

   
</body>
</html>
