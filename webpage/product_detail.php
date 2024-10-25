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

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the product details
$product_query = $connection->query("SELECT * FROM product_list WHERE id = $product_id"); 
$product = $product_query->fetch_assoc();

if (!$product) {
    echo "<p>Product not found.</p>";
    exit;
}

// Add to cart functionality
if (isset($_POST['add_to_cart'])) {
    $product_id = $product['id']; // Use fetched product ID
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Get quantity from form

    // Prepare the cart item
    $cart_item = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity, // Use quantity from form
        'image_url' => $product['image'], 
        'brand' => $product['brand'], 
        'category' => $product['category']
    ];

    // Debugging line to check the state of the cart
    var_dump($_SESSION['cart']); // Check what is stored in the cart

    // Initialize cart as an empty array if not already set
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Filter out any non-array entries from the cart
    $_SESSION['cart'] = array_filter($_SESSION['cart'], 'is_array');

    // Check if the product is already in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] === $product_id) {
            $item['quantity'] += $quantity; // Update quantity if already in cart
            $found = true;
            break;
        }
    }

    // If not found, add new item
    if (!$found) {
        $_SESSION['cart'][] = $cart_item;
    }
    
    // Optional: Redirect to the same page or cart page after adding to cart
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="script.js"></script>
</head>
<body>
<div class="bg-[url('images/bg1.png')] bg-center bg-cover bg-fixed min-h-screen w-full pt-6 flex flex-col">
    <!-- Fixed Header -->
    <div class="header-fixed w-full flex items-center">
        <button id="menu-toggle" class="lg:hidden text-white text-xl px-8 py-4 rounded-md absolute">☰</button>
        
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
                    <a href="cart.php" class="no-underline text-white">Cart 
                        <?php if (isset($_SESSION['cart'])): ?>
                            <span style="background-color: red; color: white; border-radius: 9999px; padding: 0.25rem 0.5rem; font-size: 0.75rem; margin-left: 0.5rem;">
                                <?php echo array_sum(array_column($_SESSION['cart'], 'quantity')); ?>
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
                        <a href="manage_account.html">Manage Account</a>
                        <a href="/wbsif/login/client/logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Product Detail Section -->
    <div class="p-10">
        <div class="mt-4">
            <div class="w-full max-w-4xl mx-auto"> <!-- Increased max width to max-w-4xl -->
                <div class="bg-white bg-opacity-30 backdrop-blur-lg border border-gray-300 rounded-lg shadow-lg overflow-hidden transition-transform duration-200 mt-20 p-8"> <!-- Increased padding to p-8 -->
                    <div class="flex space-x-2 mt-2">
                        <div class="bg-gray-200 p-2 rounded-md inline-block">
                            <p class="text-sm"><?php echo htmlspecialchars($product['brand']); ?></p> <!-- Brand -->
                        </div>
                        <div class="bg-gray-200 p-2 rounded-md inline-block">
                            <p class="text-sm"><?php echo htmlspecialchars($product['category']); ?></p> <!-- Category -->
                        </div>
                    </div>

                    <img class="w-full h-64 object-contain mt-4 rounded-lg" src="../../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.onerror=null; this.src='/path/to/default-image.jpg';">

                    <p class="mt-4"><?php echo htmlspecialchars($product['description']); ?></p>
                    <span class="text-xl font-bold mt-2">₱<?php echo number_format($product['price'], 2); ?></span>

                    <!-- Add to Cart Form -->
                    <form method="POST" action="">
    <label for="quantity" class="block mb-2 text-sm font-medium">Quantity:</label>
    <div class="flex items-center space-x-1 mb-4">
        <button type="button" id="decrement" class="bg-gray-200 text-black px-2 py-1 rounded hover:bg-gray-300 focus:outline-none">-</button>
        <input type="number" id="quantity" name="quantity" min="1" value="1" class="w-8 text-center bg-white rounded focus:outline-none" readonly>
        <button type="button" id="increment" class="bg-gray-200 text-black px-2 py-1 rounded hover:bg-gray-300 focus:outline-none">+</button>
    </div>
    <button type="submit" name="add_to_cart" class="bg-red-900 text-white px-4 py-2 rounded hover:bg-red-700 w-full">Add to Cart</button>
</form>

<script>
    const quantityInput = document.getElementById('quantity');
    const incrementButton = document.getElementById('increment');
    const decrementButton = document.getElementById('decrement');

    incrementButton.addEventListener('click', () => {
        quantityInput.value = parseInt(quantityInput.value) + 1; // Increment quantity
    });

    decrementButton.addEventListener('click', () => {
        if (parseInt(quantityInput.value) > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1; // Decrement quantity
        }
    });
</script>


                </div>
            </div>
        </div>
    </div>
</body>
</html>
