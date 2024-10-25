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



// Fetch only active brands
$brands_query = $conn->query("SELECT * FROM brand_list WHERE status = 'active'");
$brands = $brands_query->fetch_all(MYSQLI_ASSOC);

// Fetch only active categories
$categories_query = $conn->query("SELECT * FROM category_list WHERE status = 'active'");
$categories = $categories_query->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_product'])) {
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $description = $_POST['description'];
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Allowed file types
        $maxFileSize = 2 * 1024 * 1024; // Maximum file size (2MB)

        // Check file type and size
        if (in_array($_FILES['image']['type'], $allowedTypes) && $_FILES['image']['size'] <= $maxFileSize) {
            $uploadDir = '../../uploads/'; // Make sure this directory is writable
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // Create directory if it doesn't exist
            }
            $uploadFile = $uploadDir . basename($_FILES['image']['name']);
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $image = basename($_FILES['image']['name']);
            } else {
                echo "<p class='text-red-500'>Image upload failed.</p>";
            }
        } else {
            echo "<p class='text-red-500'>Invalid file type or file size exceeds limit.</p>";
        }
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO product_list (brand, category, name, price, status, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $brand, $category, $name, $price, $status, $description, $image);

    if ($stmt->execute()) {
        echo "<p class='text-green-500'>Product added successfully!</p>";
        header("Location: product_list.php"); // Redirect to product list after successful insertion
        exit();
    } else {
        echo "<p class='text-red-500'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
ob_end_flush(); // Send output and turn off output buffering
?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Product</title>
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
                        <a href="#" class="nav-link flex items-center p-2 text-white hover:bg-gray-500">
                            <img src="../images/icons8-analytics-30.png" class="w-5 h-5 mr-2 ml-1 filter brightness-0 invert">
                            <span>Analytics</span>
                        </a>
                    </li>
                    <li>
                        <a href="../clients/list_of_clients.php" class="nav-link flex items-center p-2 text-white hover:bg-gray-500">
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
                                <span class="text-black"><p><?php echo htmlspecialchars($user_email); ?></p></span>
                                    <img src="../images/icons8-dropdown-24.png" class="ml-2" style=" width: 1.5em; height: 1.5em; vertical-align: middle;">
                                </button>
                                <div class="dropdown-content px-0.5 py-0.5 border space-y-2">
                                <div>
                                    <a href="../manage account admin/manage_account.php" class=" flex items-center p-2  hover:bg-gray-500">
                                        <img src="../images/icons8-user-24.png" class="w-5 h-5 mr-2 ml-1 ">
                                        <span>My Account</span>
                                    </a>
                                </div>
                                <div>
                                    <a href="/wbsif/login/client/logout.php" class=" flex items-center p-2  hover:bg-gray-500">
                                        <img src="../images/icons8-logout-50.png" class="w-5 h-5 mr-2 ml-1 ">
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
                            <h1 class="text-black text-xl mb-4">Add New Product</h1>
                            

                            <div class="relative w-full mb-7">
                                <div class="absolute left-0 right-0 bottom-0 border-t border-zinc-400"></div>
                            </div>

                            <!-- Form for Creating New Products -->

                            <form action="" method="post" enctype="multipart/form-data">
    <div class="space-y-4 p-4">
        <div class="mb-4">
            <label class="block text-sm">Brand:</label>
            <select name="brand" class="border rounded p-2 w-full" required>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?php echo htmlspecialchars($brand['brand_name']); ?>"><?php echo htmlspecialchars($brand['brand_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm">Category:</label>
            <select name="category" class="border rounded p-2 w-full" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['category_name']); ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="name" class="block text-sm text-gray-700">Name</label>
            <input type="text" id="name" name="name" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" required>
        </div>

        <div>
            <label for="price" class="block text-sm text-gray-700">Price</label>
            <input type="number" step="0.01" id="price" name="price" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" required>
        </div>

        <div>
            <label for="status" class="block text-sm text-gray-700">Status</label>
            <select id="status" name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <div>
            <label for="description" class="block text-sm text-gray-700">Description</label>
            <textarea id="description" name="description" rows="2" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600 sm:text-sm"></textarea>
        </div>

        <div>
            <label for="image" class="block text-sm text-gray-700">Product Image</label>
            <input type="file" id="image" name="image" accept="image/png, image/gif" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-600 sm:text-sm">
            <small class="text-gray-500">Use a PNG file to keep the background transparent.</small>
        </div>
    </div>

    <div class="mt-4 flex justify-end">
        <button type="submit" name="create_product" class="bg-sky-900 text-white py-1 px-3 rounded hover:bg-sky-700">Add Product</button>
        <a href="/wbsif/admin/products/product_list.php" class="ml-2 bg-gray-500 text-white py-1 px-3 rounded hover:bg-gray-700">Cancel</a>
    </div>
</form>


                        </div>
                    </div>
                </div>
            
            </div>
        </div>
    </body>
    </html>
