<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("location: /wbsif/webpage/Home.php");
    exit;
}

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

// Handle search input for products
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Build the product query based on search input
$product_query = "SELECT * FROM product_list WHERE status = 'active'";

if ($search !== '') {
    $product_query .= " AND name LIKE ?";
}

// Prepare the product statement
$product_stmt = $connection->prepare($product_query);
$params = [];

// Bind search parameter if exists
if ($search !== '') {
    $search_query = "%{$search}%";
    $params[] = $search_query;
}

// Initialize empty arrays for selected brands and categories
$selected_brands = isset($_GET['brands']) ? $_GET['brands'] : [];
$selected_categories = isset($_GET['categories']) ? $_GET['categories'] : [];

// Check if "All" is selected for brands or categories
$filter_brands = !in_array("all", $selected_brands) ? $selected_brands : [];
$filter_categories = !in_array("all", $selected_categories) ? $selected_categories : [];

// Apply brand filter if not empty
if (!empty($filter_brands)) {
    $brand_placeholders = implode(',', array_fill(0, count($filter_brands), '?'));
    $product_query .= " AND brand IN ($brand_placeholders)";
    $params = array_merge($params, $filter_brands);
}

// Apply category filter if not empty
if (!empty($filter_categories)) {
    $category_placeholders = implode(',', array_fill(0, count($filter_categories), '?'));
    $product_query .= " AND category IN ($category_placeholders)";
    $params = array_merge($params, $filter_categories);
}

// Prepare the product statement again with updated query
$product_stmt = $connection->prepare($product_query);

// Dynamically bind the parameters
if ($params) {
    $types = str_repeat("s", count($params)); // Assuming all params are strings
    $product_stmt->bind_param($types, ...$params);
}

// Execute the statement and get the result
$product_stmt->execute();
$product_result = $product_stmt->get_result();

// Fetch active categories
$category_query = "SELECT id, category_name, date_added, status FROM category_list WHERE status = 'active' ORDER BY date_added DESC";
$category_stmt = $connection->prepare($category_query);
$category_stmt->execute();
$category_result = $category_stmt->get_result();

// Fetch active brands
$brands = [];
$brand_query = "SELECT brand_name FROM brand_list WHERE status = 'active'";
$brand_result = $connection->query($brand_query);

if (!$brand_result) {
    // Check if the query failed and show the error
    die("Query failed: " . $connection->error);
}

if ($brand_result->num_rows > 0) {
    while ($row = $brand_result->fetch_assoc()) {
        $brands[] = $row['brand_name'];
    }
} else {
    // Handle the case where no rows are found
    echo "<p>No brands found.</p>";
}

if (isset($_POST['cart'])) {
    $product_id = $_POST['product_id'];
    
    // Fetch the product details, including stock, from the database
    $query = "SELECT p.*, IFNULL(SUM(sh.stock_quantity), 0) AS stock_quantity 
              FROM product_list AS p
              LEFT JOIN stock_history AS sh ON p.id = sh.product_id AND sh.deleted = 0 
              WHERE p.id = ?";
    
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $product_id);  // Bind the product_id as integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Check if the product stock is greater than 0
        if (isset($product['stock_quantity']) && $product['stock_quantity'] > 0) {
            // Prepare the cart item as usual
            $cart_item = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1, // Default quantity
                'image_url' => $product['image'], 
                'brand' => $product['brand'], 
                'category' => $product['category']
            ];
        
            // Add to session cart (similar to your current logic)
            if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                $_SESSION['cart'] = []; // Initialize as an empty array if not already set
            }
        
            // Check if the product is already in the cart
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if (is_array($item) && $item['id'] == $product_id) {  // Ensure $item is an array
                    $item['quantity']++; // Increase quantity if already in cart
                    $found = true;
                    break;
                }
            }
        
            // If not found, add as a new item
            if (!$found) {
                $_SESSION['cart'][] = $cart_item;
        
                // Insert into the cart table in the database
                $insert_query = "INSERT INTO cart (user_email, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)";
                $insert_stmt = $connection->prepare($insert_query);
                $price = $product['price'];
                $insert_stmt->bind_param("sssid", $user_email, $product['id'], $product['name'], $cart_item['quantity'], $price);
                $insert_stmt->execute();
                $insert_stmt->close();
            }
        
            // Redirect to products.php
            header("Location: products.php");
            exit;
        } else {
            // Redirect or display a message for out of stock
            echo "<script>alert('This product is out of stock and cannot be added to the cart.');</script>";
        }
    }
}
    

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Count items in the cart
$item_count = 0;
foreach ($_SESSION['cart'] as $item) {
    if (isset($item['quantity'])) {
        $item_count += $item['quantity'];
    }
}


$sql = "SELECT p.id, p.name, p.price, 
        IFNULL(SUM(sh.stock_quantity), 0) AS stock_quantity 
        FROM product_list AS p
        LEFT JOIN stock_history AS sh ON p.id = sh.product_id AND sh.deleted = 0
        GROUP BY p.id
        ORDER BY sh.date_added DESC";






// Clean up
$product_stmt->close();
$category_stmt->close();
$connection->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="script.js"></script>
  
   <style>
    .hidden {
    display: none;
}

   </style>
</head>
<body>
    <div class="bg-[url('images/bg1.png')] bg-center bg-cover bg-fixed min-h-screen w-full pt-6 flex flex-col">
        <!-- Fixed Header -->
        <div class="header-fixed w-full flex items-center">
            <button id="menu-toggle" class="lg:hidden text-white text-xl px-8 py-4 rounded-md absolute">
                ☰
            </button>
        
            <div class="px-20 p-2 flex flex-col lg:flex-row items-center mb-2 border-b-stone-100 flex-1">
                <div class="flex items-center mb-4 lg:mb-0">
                    <img src="images/logo.jpg" class="w-12 h-12 rounded-full cursor-pointer">
                    <h1 class="ml-4 text-white font-extrabold text-xl cursor-pointer">R K E</h1>
                </div>
                <ul id="nav-links" class="lg:flex flex-1 justify-center lg:justify-center text-center">
                    <li class="list-none px-4 py-2 lg:px-7"><a href="Home.php" class="no-underline text-white">Home</a></li>
                    <li class="list-none px-4 py-2 lg:px-7"><a href="products.php" class="no-underline text-white">Products</a></li>
                    <li class="list-none px-4 py-2 lg:px-7"><a href="about_us.php" class="no-underline text-white">About Us</a></li>
                    <li class="list-none px-4 py-2 lg:px-7"><a href="orders.php" class="no-underline text-white">Orders</a></li>
                    <li class="list-none px-4 py-2 lg:px-7">
    <a href="cart.php" class="no-underline text-white">
        Cart 
        <?php if ($item_count > 0): ?>
            <span style="background-color: red; color: white; border-radius: 9999px; padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                <?php echo $item_count; ?>
            </span>
        <?php endif; ?>
    </a>
</li>
                   
                </ul>
                <ul class="flex items-center">
                    <li class="list-none inline-block dropdown">
                        <button class="dropbtn text-white px-2 flex items-center">
                            <?php echo htmlspecialchars($user_email); ?>
                            <img src="images/icons8-dropdown-24.png" class="ml-2" style="filter: invert(100%); width: 1.5em; height: 1.5em; vertical-align: middle;">
                        </button>
                        <div class="dropdown-content px-0.5 py-0.5">
                            <a href="#">Chat Bot</a>
                            <a href="manage_account.php">Manage Account</a>
                            <a href="/wbsif/login/client/logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="px-20 py-3 flex flex-wrap gap-6 mt-20 ">
    <div class="w-1/4"> <!-- Filters section -->
        <div class="filter-title">
            <h1 class="text-2xl text-white">Filter</h1>
            <button id="toggle-filters" class="text-sm text-blue-500 hidden">Show Filters</button>
            <div class="w-full max-w-full mx-auto mt-2 mb-5">
                <div class="border-t border-zinc-400"></div>
            </div>
        </div>

        <!-- Filter Form (Brand and Category) -->
        <div class="container mx-auto px-4">
            <form method="GET" action="products.php">
                <!-- Brand Section -->
                <div class="filter-section brand-section mb-4 border border-gray-300 bg-gray-50 shadow-sm rounded-lg">
                    <div class="flex justify-center items-center p-3 bg-gray-200">
                        <h1 class="text-gray-900 font-bold text-sm">Brand</h1>
                    </div>
                    <div class="px-5 py-4 bg-white">
                        <!-- Brand Checkbox List -->
                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="brands[]" value="all" class="mr-2"
                                <?php if (in_array("all", $selected_brands)) echo 'checked'; ?> 
                                onchange="handleAllCheckbox(this, 'brands');">
                            <span class="text-gray-700 text-sm">All</span>
                        </div>
                        <?php foreach ($brands as $brand): ?>
                        <div class="px-4 py-2 border border-gray-300 mt-3 bg-gray-100 flex items-center text-sm">
                            <input type="checkbox" name="brands[]" value="<?php echo htmlspecialchars($brand); ?>" class="mr-2"
                                <?php if (in_array($brand, $selected_brands)) echo 'checked'; ?> 
                                onchange="handleBrandCheckbox(this); this.form.submit();">
                            <span class="text-gray-700"><?php echo htmlspecialchars($brand); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Categories Section -->
                <div class="filter-section category-section mb-4 border border-gray-300 bg-gray-50 shadow-sm rounded-lg">
                    <div class="flex justify-center items-center p-3 bg-gray-200">
                        <h1 class="text-gray-900 font-bold text-sm">Categories</h1>
                    </div>
                    <div class="px-5 py-4 bg-white">
                        <!-- Category Checkbox List -->
                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="categories[]" value="all" class="mr-2"
                                <?php if (in_array("all", $selected_categories)) echo 'checked'; ?> 
                                onchange="handleAllCheckbox(this, 'categories');">
                            <span class="text-gray-700 text-sm">All</span>
                        </div>
                        <?php while ($row = $category_result->fetch_assoc()): ?>
                        <div class="px-4 py-2 border border-gray-300 mt-3 bg-gray-100 flex items-center text-sm">
                            <input type="checkbox" name="categories[]" value="<?php echo htmlspecialchars($row['category_name']); ?>" class="mr-2"
                                <?php if (in_array($row['category_name'], $selected_categories)) echo 'checked'; ?> 
                                onchange="handleCategoryCheckbox(this); this.form.submit();">
                            <span class="text-gray-700"><?php echo htmlspecialchars($row['category_name']); ?></span>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </form>
        </div>






                <script>
                function handleAllCheckbox(allCheckbox, groupName) {
                    const checkboxes = document.querySelectorAll(`input[name="${groupName}[]"]`);
                    
                    // If "All" is checked, uncheck other checkboxes
                    if (allCheckbox.checked) {
                        checkboxes.forEach((checkbox) => {
                            if (checkbox !== allCheckbox) {
                                checkbox.checked = false; // Uncheck other checkboxes
                            }
                        });
                    } else {
                        // If "All" is unchecked, check if any other checkbox is checked
                        const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                        if (!anyChecked) {
                            allCheckbox.checked = true; // Re-check "All" if no other checkboxes are checked
                        }
                    }

                    // Submit the form to apply changes
                    allCheckbox.form.submit();
                }

                function handleBrandCheckbox(brandCheckbox) {
                    const allCheckbox = document.querySelector('input[name="brands[]"][value="all"]');
                    // Uncheck "All" checkbox if any brand is checked
                    if (brandCheckbox.checked) {
                        allCheckbox.checked = false;
                    }
                }

                function handleCategoryCheckbox(categoryCheckbox) {
                    const allCheckbox = document.querySelector('input[name="categories[]"][value="all"]');
                    // Uncheck "All" checkbox if any category is checked
                    if (categoryCheckbox.checked) {
                        allCheckbox.checked = false;
                    }
                }
                </script>



            </div>

                

            <div class="flex-1 relative">
                <div class="flex items-center bg-white rounded-md shadow-md p-1 border border-gray-300 max-w-xs"> <!-- Pinalitan ang max-w-md ng max-w-xs -->
                    <form method="POST" action="products.php" class="flex w-full">
                        <input type="text" id="search-input" name="search" placeholder="Search" class="flex-1 px-1 py-0.5 text-sm text-gray-700 rounded-l-md focus:outline-none">
                        <button type="submit" id="search-button" class="text-sky-900 px-1 py-0.5 rounded-r-md transition">
                            <span class="material-symbols-outlined text-sm">search</span>
                        </button>
                    </form>
                </div>




                <div class="mt-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php if ($product_result->num_rows > 0): ?>
            <?php while ($row = $product_result->fetch_assoc()): ?>
                <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="relative block bg-white bg-opacity-30 backdrop-blur-lg border border-gray-300 rounded-lg shadow-lg overflow-hidden transition-transform duration-200 p-4 min-h-48 min-w-[200px]">
                    <?php
                    $imagePath = "../uploads/" . htmlspecialchars($row['image']);
                    ?>
                    <img class="w-full h-28 object-contain rounded-lg bg-transparent" src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" onerror="this.onerror=null; this.src='path/to/default-image.jpg';">
                    
                    <div class="relative p-2">
                        <h2 class="text-gray-900 text-sm font-semibold"><?php echo htmlspecialchars($row['name']); ?></h2>
                        <p class="mt-1 text-gray-700 text-xs overflow-hidden text-ellipsis max-h-10 cursor-pointer"><?php echo htmlspecialchars($row['description']); ?></p>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-900">₱<?php echo number_format($row['price'], 2); ?></span>
                        </div>
                        <div class="mt-2 flex space-x-2">
                            <form method="POST" action="products.php" class="flex-1">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="cart" class="bg-red-800 text-white w-full px-4 py-2 rounded-l hover:bg-red-700 transition text-xs flex items-center justify-center">
                                    <span>Add to cart</span>
                                </button>
                            </form>
                            <form action="checkout.php" method="POST" class="checkout-form">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Checkout</button>
                            </form>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-gray-700 text-sm">No products found.</p>
        <?php endif; ?>
    </div>
</div>


                
            </div>
            
                            </div>
                            <footer class="w-full text-center py-4 text-white bg-gray-800">
            <p>&copy; Web-Based Sales & Inventory with Forecasting.</p>
        </footer>
                            </div>
                <script>
                    // Dropdown script for user menu
                    document.addEventListener("DOMContentLoaded", function() {
                        const dropdown = document.querySelector(".dropbtn");
                        const dropdownContent = document.querySelector(".dropdown-content");

                        dropdown.addEventListener("click", function() {
                            dropdownContent.classList.toggle("show");
                        });

                        window.addEventListener("click", function(event) {
                            if (!event.target.matches('.dropbtn')) {
                                dropdownContent.classList.remove("show");
                            }
                        });
                    });

                    // Toggle navigation for small screens
                    const menuToggle = document.getElementById("menu-toggle");
                    const navLinks = document.getElementById("nav-links");

                    menuToggle.addEventListener("click", () => {
                        navLinks.classList.toggle("hidden");
                    });
                </script>
</body>
</html>





