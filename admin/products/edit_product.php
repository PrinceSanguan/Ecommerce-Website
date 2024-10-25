<?php
session_start(); // Start session if you haven't already

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



// Fetch the product details if an ID is provided
$product = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM product_list WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

// Update the product if the form is submitted
// Update the product if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $quantity = $_POST['quantity']; // This can be empty

    // Handle image upload (as before)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = basename($_FILES['image']['name']);
        $target = "../../uploads/" . $image;

        // Validate file type
        $imageFileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                echo "Error uploading image.";
                $image = $_POST['current_image']; // Keep the old image if upload fails
            }
        } else {
            echo "Invalid file type.";
            $image = $_POST['current_image']; // Keep the old image if the file is invalid
        }
    } else {
        $image = $_POST['current_image']; // Use existing image if no new upload
    }

    // Update the product including the description and image
    $update_stmt = $conn->prepare("UPDATE product_list SET name = ?, brand = ?, category = ?, price = ?, description = ?, image = ?, status = ? WHERE id = ?");
    $update_stmt->bind_param("sssdsssi", $name, $brand, $category, $price, $description, $image, $status, $id);

    if ($update_stmt->execute()) {
        // Check if quantity is provided, and update stock accordingly
        if (!empty($quantity)) {
            // Only update quantity if it's provided
            $update_quantity_stmt = $conn->prepare("UPDATE product_list SET quantity = ? WHERE id = ?");
            $update_quantity_stmt->bind_param("ii", $quantity, $id);
            $update_quantity_stmt->execute();
            $update_quantity_stmt->close();
        }

        header("Location: product_list.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $update_stmt->close();
}


?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Product</title>
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
                                <h1 class="text-black text-xl">Edit Products</h1>
                            </div>
                            
                            <div class="relative w-full mb-7">
                                <div class="absolute left-0 right-0 bottom-0 border-t border-zinc-400"></div>
                            </div>
                            
                            <div class="container mx-auto p-4">
    <?php if ($product): ?>
        <form action="edit_product.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
            <div class="mb-4">
                <label class="block text-sm">Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" class="border rounded p-2 w-full" required>
            </div>
            <div class="mb-4">
    <label class="block text-sm">Brand:</label>
    <select name="brand" class="border rounded p-2 w-full" required>
        <?php foreach ($brands as $brand): ?>
            <option value="<?php echo htmlspecialchars($brand['brand_name']); ?>" 
                <?php echo ($brand['brand_name'] == $product['brand']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($brand['brand_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-4">
    <label class="block text-sm">Category:</label>
    <select name="category" class="border rounded p-2 w-full" required>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo htmlspecialchars($category['category_name']); ?>" 
                <?php echo ($category['category_name'] == $product['category']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($category['category_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

            <div class="mb-4">
                <label class="block text-sm">Price:</label>
                <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" class="border rounded p-2 w-full" required step="0.01">
            </div>
            <div class="mb-4">
                <label class="block text-sm">Description:</label>
                <textarea name="description" class="border rounded p-2 w-full" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

           
            <div class="mb-4">
                <label class="block text-sm">Status:</label>
                <select name="status" class="border rounded p-2 w-full">
                    <option value="active" <?php echo ($product['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($product['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <div class="mb-4">
    <label class="block text-sm">Image:</label>
    <input type="file" name="image" class="border rounded p-2 w-full">
    <?php if ($product['image']): ?>
        <img src="../../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Current Image" class="mt-2 w-24 h-24 object-cover">
        <!-- Add this hidden input field -->
        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($product['image']); ?>">
    <?php endif; ?>
</div>

            
            <div class="mt-4 flex justify-end">
                <button type="submit" class="bg-sky-900 text-white py-1 px-3 rounded hover:bg-sky-700">Update </button>
                <a href="product_list.php" class="ml-2 bg-gray-500 text-white py-1 px-3 rounded hover:bg-gray-700">Cancel</a>
             </div>
        </form>
    <?php else: ?>
        <p class="text-red-500">Product not found.</p>
    <?php endif; ?>
</div>


                            
                        </div>
                    </div>
                    
                    
                    
                    
                </div>
            </div>
        </div>
        <?php $conn->close(); ?>
    </body>
    </html>
