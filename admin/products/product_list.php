<?php

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // Fallback if no name is provided

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';


$sql = "SELECT * FROM product_list WHERE name LIKE '%$searchTerm%' OR brand LIKE '%$searchTerm%' OR category LIKE '%$searchTerm%'";
$result = $conn->query($sql);
?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product List</title>
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
                                <h1 class="text-black text-xl">List of Products</h1>
                                <button onclick="window.location.href='create_product.php'" class="bg-blue-500 text-white py-2 px-4 rounded flex items-center hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"></path>
                                    </svg>
                                    Create New
                                </button>
                            </div>
                            
                            <div class="relative w-full mb-7">
                                <div class="absolute left-0 right-0 bottom-0 border-t border-zinc-400"></div>
                            </div>
                            
                            <form method="GET" action="product_list.php" class="flex items-center mb-4">
                            <div class="ml-auto flex items-center">
                                <span class="text-gray-500 mr-2">Search:</span>
                                <input type="text" name="search" class="block w-64 px-2 py-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none" value="<?php echo htmlspecialchars($searchTerm); ?>">
                                <button type="submit" class="ml-2 bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600">Search</button>
                            </div>
                        </form>
                            
                            
                       
                            <div class="overflow-x-auto">
                        <table class='min-w-full bg-white border border-gray-300 rounded-md shadow-sm'>
                            <thead>
                                <tr class='bg-gray-200 text-gray-700 text-xs uppercase'>
                                    <th class='py-2 px-3 text-left'>#</th>
                                    <th class='py-2 px-3 text-left'>Date Created</th>
                                    <th class='py-2 px-3 text-left'>Brand</th>
                                    <th class='py-2 px-3 text-left'>Category</th>
                                    <th class='py-2 px-3 text-left'>Name</th>
                                    <th class='py-2 px-3 text-left'>Price</th>
                                    
                                    <th class='py-2 px-3 text-left'>Status</th>
                                    <th class='py-2 px-3 text-center'>Action</th>
                                </tr>
                            </thead>
                            
                            <tbody class='text-gray-600 text-xs'>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php 
                                    // Initialize a counter for the product numbers
                                    $counter = 1; 
                                    while($row = $result->fetch_assoc()): 
                                    ?>
                                        <tr class='border-b border-gray-200 hover:bg-gray-100'>
                                            <td class='py-2 px-3'><?php echo $counter; ?></td>
                                            <td class='py-2 px-3'><?php echo htmlspecialchars($row['date_created']); ?></td>
                                            <td class='py-2 px-3'><?php echo htmlspecialchars($row['brand']); ?></td>
                                            <td class='py-2 px-3'><?php echo htmlspecialchars($row['category']); ?></td>
                                            <td class='py-2 px-3'><?php echo htmlspecialchars($row['name']); ?></td>
                                            <td class='py-2 px-3'>₱<?php echo number_format($row['price'], 2); ?></td>
                                            
                                            <td class='py-2 px-3'>
                                                        <?php
                                                        // Determine text color based on status
                                                        $status_class = (strtolower($row['status']) == 'active') ? 'text-green-500' : 'text-red-500';
                                                        ?>
                                                        <strong class="<?php echo $status_class; ?>">
                                                            <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                                                        </strong>
                                                    </td>

                                                    <td class='py-2 px-3 text-center space-x-1'>
                                                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-xs">Edit</a>
                                                        <form action="delete_product.php" method="POST" class="inline">
                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                            <button type="submit" onclick='return confirm("Are you sure?")' class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">Delete</button>
                                                        </form>
                                                    </td>
                                        </tr>
                                    <?php 
                                        $counter++; // Increment the counter
                                    endwhile; 
                                    ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4">No products found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $conn->close(); ?>
</body>
</html>
