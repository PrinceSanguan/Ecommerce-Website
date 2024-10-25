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



// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // Fallback if no name is provided


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Report</title>
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
                            <h1 class="text-black text-xl">Orders Report</h1>
                            
                        </div>
                        
                        <div class="relative w-full mb-7">
                            <div class="absolute left-0 right-0 bottom-0 border-t border-zinc-400"></div>
                        </div>
                        
                        <div class="mb-5"> 
                            <form id="filter-form" class="space-y-4">
                                <div class="flex flex-wrap items-end space-x-4">
                                    <div class="w-full md:w-1/6">
                                        <label for="date_start" class="block text-sm font-medium text-gray-700 mb-1">Date Start</label>
                                        <input type="date" name="date_start" value="<?php //echo date('Y-m-d', strtotime($date_start)); ?>" class="block w-full border border-gray-400 rounded-md  sm:text-sm p-1">
                                    </div>

                                    <div class="w-full md:w-1/6">
                                        <label for="date_end" class="block text-sm font-medium text-gray-700 mb-1">Date End</label>
                                        <input type="date" name="date_end" value="<?php //echo date('Y-m-d', strtotime($date_end)); ?>" class="block w-full border border-gray-400 rounded-md  sm:text-sm p-1">
                                    </div>


                                    <div class="w-full md:w-auto">
                                        <button type="button" id="printBTN" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700 focus:outline-none ">
                                        <img src="../images/icons8-print-50.png" alt="Print" class="w-4 h-4 mr-1.5"> Print
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="relative w-full mb-7">
                            <div class="absolute left-0 right-0 bottom-0 border-t border-zinc-400"></div>
                        </div>

                        <div class="text-center mb-4">
                            <div>
                                <h3 class="text-xl font-semibold">Motorcycle Parts Shop Management System</h3>
                            </div>
                            <div class="mt-2">
                                <h1 class="text-3xl font-bold">Orders Reports</h1>
                            </div>
                        </div>

                        <div class="relative w-full mb-7">
                            <div class="absolute left-0 right-0 bottom-0 border-t border-zinc-400"></div>
                        </div>

                        
                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                        <th class="py-3 px-6 text-left">#</th>
                                        <th class="py-3 px-6 text-left">Date Time</th>
                                        <th class="py-3 px-6 text-left">Client Name</th>
                                        <th class="py-3 px-6 text-left">Total Amount</th>
                                        <th class="py-3 px-6 text-left">Status</th>
                                       
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                    <tr class="border-b border-gray-300">
                                        <td class="py-3 px-6 text-left">1</td>
                                        <td class="py-3 px-6 text-left">2024-08-25 22:16</td>
                                        <td class="py-3 px-6 text-left">Alonzo, Adrian B.</td>
                                        <td class="py-3 px-6 text-left">$500</td>
                                        <td class="py-3 px-6 text-left font-bold text-green-500">Pending</td>
                                        
                                    </tr>
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
