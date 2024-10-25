<?php

session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // Fallback if no name is provided

// Default search parameter
$search = ''; 
$search_query = "%{$search}%";

// Check if the status update form is submitted
if (isset($_POST['update_status'])) {
    $client_id = intval($_POST['client_id']);
    $new_status = $connection->real_escape_string($_POST['status']); // Escape status to prevent SQL injection
    
    // Update the client's status directly in SQL
    $updateSql = "UPDATE client_list SET status='$new_status' WHERE id=$client_id";
    if ($connection->query($updateSql) === TRUE) {
        echo "Status updated successfully!";
    } else {
        echo "Error updating status: " . $connection->error;
    }
}

// Handle search via AJAX
if (isset($_POST['search'])) {
    $search = $connection->real_escape_string($_POST['search']); // Escape search input to prevent SQL injection
    $searchQuery = "%$search%";

    // SQL query to fetch data based on search, adjusted to match your table structure
    $sql = "SELECT * FROM client_list WHERE 
    firstname LIKE '%$search%' OR 
    middlename LIKE '%$search%' OR 
    lastname LIKE '%$search%' OR 
    email LIKE '%$search%' OR 
    status LIKE '%$search%'";

    $result = $connection->query($sql);

    // Output the rows as HTML
    if ($result->num_rows > 0) {
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            $status_class = ($row['status'] == 'active') ? 'text-green-500' : 'text-red-500';
            $created_at = $row['date_created'];  // Correct column name based on your table
            $status = ucfirst($row['status']);  // Capitalize the status
            echo "
                <tr class='border-b border-gray-200 hover:bg-gray-100'>
                    <td class='py-2 px-3'>{$counter}</td>
                    <td class='py-2 px-3'>{$created_at}</td>
                    <td class='py-2 px-3'>{$row['firstname']} {$row['middlename']} {$row['lastname']}</td>
                    <td class='py-2 px-3'>{$row['email']}</td>
                    <td class='py-2 px-3 {$status_class} font-bold'>{$status}</td>
                    <td class='py-2 px-3 text-center space-x-1'>
                        <a href='manage_clients.php?id={$row['id']}' class='bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-xs'>Edit</a>
                        <a href='delete_client.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")' class='bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs'>Delete</a>
                    </td>
                </tr>
            ";
            $counter++;
        }
    } else {
        echo "
            <tr>
                <td colspan='6' class='text-center text-gray-600'>No clients found</td>
            </tr>
        ";
    }

    exit();  // Stop further script execution for AJAX response
}

// SQL query to load all records if no search is active
$sql = "SELECT * FROM client_list";
$result = $connection->query($sql);

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Clients</title>
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
                    <h1 class="text-black text-xl">List of Clients</h1>
                </div>

                <div class="relative w-full mb-7">
                    <div class="absolute left-0 right-0 bottom-0 border-t border-zinc-400"></div>
                </div>

                <!-- Search Form -->
                <div class="flex items-center mb-4">
                        <div class="ml-auto flex items-center">
                            <span class="text-gray-500 mr-2">Search:</span>
                            <input 
                                type="text" 
                                name="search" 
                                id="search-input" 
                                value="<?php echo htmlspecialchars($search); ?>" 
                                class="block w-64 px-2 py-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none" 
                                onkeyup="searchCategories()" 
                                placeholder="Type to search..."
                            />
                            <div id="search-results" class="absolute bg-white shadow-lg border mt-1 rounded-lg max-h-48 overflow-y-auto w-64 hidden"></div>
                        </div>
                    </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <?php
                    if ($result->num_rows > 0) {
                        echo "
                        <table class='min-w-full bg-white border border-gray-300 rounded-md shadow-sm'>
                            <thead>
                                <tr class='bg-gray-200 text-gray-700 text-xs uppercase'>
                                    <th class='py-2 px-3 text-left'>#</th>
                                    <th class='py-2 px-3 text-left'>Date Created</th>
                                    <th class='py-2 px-3 text-left'>Name</th>
                                    <th class='py-2 px-3 text-left'>Email</th>
                                    <th class='py-2 px-3 text-left'>Status</th>
                                    <th class='py-2 px-3 text-center'>Actions</th>
                                </tr>
                            </thead>
                            <tbody id='client-table-body' class='text-gray-600 text-xs'>
                        ";

                        // Initialize a counter for the ID
                        $counter = 1;

                        while ($row = $result->fetch_assoc()) {
                            $created_at = date('Y-m-d H:i:s', strtotime($row['date_created']));
                            $status = ($row['status'] == 'active') ? 'Active' : 'Inactive'; 
                            $statusColor = ($row['status'] == 'active') ? 'text-green-500' : 'text-red-500';

                            echo "
                                <tr class='border-b border-gray-200 hover:bg-gray-100'>
                                    <td class='py-2 px-3'>{$counter}</td> <!-- Use the counter here -->
                                    <td class='py-2 px-3'>{$created_at}</td>
                                    <td class='py-2 px-3'>{$row['firstname']} {$row['middlename']} {$row['lastname']}</td>
                                    <td class='py-2 px-3'>{$row['email']}</td>
                                    <td class='py-2 px-3 {$statusColor} font-bold'>{$status}</td>
                                    <td class='py-2 px-3 text-center space-x-1'>
                                        <a href='manage_clients.php?id={$row['id']}' class='bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-xs'>Edit</a>
                                        <a href='delete_client.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")' class='bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs'>Delete</a>
                                    </td>
                                </tr>
                            ";

                            // Increment the counter for the next ID
                            $counter++;
                        }

                        echo "
                            </tbody>
                        </table>
                        ";
                    } else {
                        echo "<p class='text-center text-gray-500'>No clients found matching your search.</p>";
                    }

                    $connection->close();
                    ?>
                </div>
                <script>
                        function searchCategories() {
                            let query = document.getElementById('search-input').value;

                            // Make an AJAX request to get the filtered results
                            let xhr = new XMLHttpRequest();
                            xhr.open('POST', 'list_clients.php', true);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    const response = xhr.responseText.trim();
                                    const tableBody = document.getElementById('client-table-body');
                                    const noResults = document.getElementById('no-results');

                                    if (response) {
                                        tableBody.innerHTML = response;
                                        noResults.classList.add('hidden');
                                    } else {
                                        tableBody.innerHTML = '';
                                        noResults.classList.remove('hidden');
                                    }
                                }
                            };
                            xhr.send('search=' + query);
                        }


                    </script>

            </div>
        </div>
    </div>
</body>
</html>
