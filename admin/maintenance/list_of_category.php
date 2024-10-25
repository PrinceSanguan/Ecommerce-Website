<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

// Create a connection to the database
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}


// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // Fallback if no name is provided


// Initialize the search variable
$search = ''; // Default value for search
$search_query = "%{$search}%"; // Default search query

// Handle form submission for adding a new category
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_name']) && !isset($_POST['search'])) {
    $category_name = $_POST['category_name'];
    $status = $_POST['status'];

    // Insert the new category into the database
    $query = "INSERT INTO category_list (category_name, status) VALUES (?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $category_name, $status);

    if ($stmt->execute()) {
        // Redirect to the list of categories on success
        header("Location: list_of_category.php");
        exit;
    } else {
        echo "<script>alert('Failed to add category.');</script>";
    }

    // Close the statement
    $stmt->close();
}

// Handle search via AJAX
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $search_query = "%{$search}%";
    $query = "SELECT id, category_name, date_added, status FROM category_list WHERE category_name LIKE ? ORDER BY date_added DESC";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $search_query);
    $stmt->execute();
    $result = $stmt->get_result();

    // Output the rows as HTML
    if ($result->num_rows > 0) {
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            $status_class = ($row['status'] == 'active') ? 'text-green-500' : 'text-red-500';
            echo "
                <tr class='border-b border-gray-200 hover:bg-gray-100'>
                                        <td class='py-2 px-3'>{$counter}</td> <!-- Use the counter here -->
                                        <td class='py-2 px-3'>{$row['date_added']}</td>
                                        <td class='py-2 px-3'>{$row['category_name']}</td>
                                        <td class='py-2 px-3 {$status_class} font-bold' style='text-transform: capitalize;'>{$row['status']}</td>
                                        <td class='py-2 px-3 text-center space-x-1'>
                                            <a href='#' onclick='openEditForm({$row['id']}, \"{$row['category_name']}\", \"{$row['status']}\")' class='bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-xs'>Edit</a>
                                            <a href='delete_category.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")' class='bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs'>Delete</a>
                                        </td>
                                    </tr>
                                ";
        
            $counter++;
        }
    } else {
        // No results found, display message
        echo "
            <tr>
                <td colspan='5' class='text-center text-gray-600'>No Category found</td>
            </tr>
        ";
    }

    $stmt->close();
    exit();  // Stop further script execution for AJAX response
}

// Regular page load (initial table load)
$query = "SELECT id, category_name, date_added, status FROM category_list WHERE category_name LIKE ? ORDER BY date_added DESC";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $search_query);
$stmt->execute();
$result = $stmt->get_result();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js"></script>
    <style>
        /* Additional styles for modal if needed */
        .modal-hidden {
            display: none;
        }
        .modal-visible {
            display: block;
        }
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
                <!-- Navigation Links -->
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
                <!-- Additional Links Here -->
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex flex-col flex-grow bg-slate-100">
            <div class="flex flex-col lg:flex-row shadow-xl p-2 bg-white">
                <div class="center-container flex items-center justify-start w-full lg:w-3/5">
                    <button class="hidden lg:block text-gray-900 p-1 ml-3" onclick="toggleNavLarge()">☰</button>
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
            <div class="p-4 w-full h-full mt-3">
                <div class="flex flex-col p-2 border-t-4 border-sky-900 rounded-md shadow-2xl bg-white">
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-black text-xl">List of Category</h1>
                        <button class="bg-blue-500 text-white py-2 px-4 rounded flex items-center hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400" onclick="openModal()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"></path>
                            </svg>
                            Create New
                        </button>
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
                                        <th class='py-2 px-3 text-left'>Date Added</th>
                                        <th class='py-2 px-3 text-left'>Category Name</th>
                                        <th class='py-2 px-3 text-left'>Status</th>
                                        <th class='py-2 px-3 text-center'>Actions</th>
                                    </tr>
                                </thead>

                                <tbody id='category-table-body' class='text-gray-600 text-xs'>
                            ";

                            // Initialize a counter for the ID
                            $counter = 1;

                            // Output data for each row
                            while ($row = $result->fetch_assoc()) {
                                $status_class = ($row['status'] == 'active') ? 'text-green-500' : 'text-red-500'; 
                                echo "
                                    <tr class='border-b border-gray-200 hover:bg-gray-100'>
                                        <td class='py-2 px-3'>{$counter}</td> <!-- Use the counter here -->
                                        <td class='py-2 px-3'>{$row['date_added']}</td>
                                        <td class='py-2 px-3'>{$row['category_name']}</td>
                                        <td class='py-2 px-3 {$status_class} font-bold' style='text-transform: capitalize;'>{$row['status']}</td>
                                        <td class='py-2 px-3 text-center space-x-1'>
                                            <a href='#' onclick='openEditForm({$row['id']}, \"{$row['category_name']}\", \"{$row['status']}\")' class='bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-xs'>Edit</a>
                                            <a href='delete_category.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")' class='bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs'>Delete</a>
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
                        
                        }

                        $connection->close();
                        ?>
                    </div>

                    <script>
                        function searchCategories() {
                            let query = document.getElementById('search-input').value;

                            // Make an AJAX request to get the filtered results
                            let xhr = new XMLHttpRequest();
                            xhr.open('POST', 'list_of_category.php', true);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    const response = xhr.responseText.trim();
                                    const tableBody = document.getElementById('category-table-body');
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

            <!-- Add Brand Modal -->
            <div id="addCategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-md w-1/3">
                    <h2 class="text-lg font-bold mb-4">Add New Category</h2>
                    <form id="addCategoryForm" method="POST" action="list_of_category.php" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="category_name" class="block text-sm font-medium text-gray-700">Category Name</label>
                            <input type="text" name="category_name" id="category_name" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        
                        <div class="flex justify-end space-x-2">
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="closeModal()">Cancel</button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
                        </div>
                    </form>
                </div>
            </div>


           
    <!-- Edit Brand Modal -->
<div id="editCategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-md w-1/3">
        <h2 class="text-lg font-bold mb-4">Edit Category</h2>
        <form id="editCategoryForm" method="POST" action="update_category.php" enctype="multipart/form-data">
            <input type="hidden" name="category_id" id="edit_category_id">
            <div class="mb-4">
                <label for="edit_category_name" class="block text-sm font-medium text-gray-700">Category Name</label>
                <input type="text" name="category_name" id="edit_category_name" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label for="edit_status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="edit_status" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <div class="flex justify-end space-x-2">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</div>




<script>
    function openEditForm(id, categoryName, status, logo) {
        document.getElementById('editCategoryModal').classList.remove('hidden');
        document.getElementById('edit_category_id').value = id;
        document.getElementById('edit_category_name').value = categoryName;
        document.getElementById('edit_status').value = status;
        
    }

    function closeEditModal() {
        document.getElementById('editCategoryModal').classList.add('hidden');
    }
</script>




            

            <script>
                // JavaScript to open and close the modal
                function openModal() {
                    document.getElementById('addCategoryModal').classList.remove('hidden');
                }

                function closeModal() {
                    document.getElementById('addCategoryModal').classList.add('hidden');
                }
            </script>


            
        </div>
    </div>
</body>
</html>
