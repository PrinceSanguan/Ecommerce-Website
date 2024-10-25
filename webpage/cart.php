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

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

// Remove item from cart functionality
if (isset($_GET['remove']) && isset($_SESSION['cart'][$_GET['remove']])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header("Location: cart.php"); // Refresh the page after removing the item
    exit();
}


if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Increase quantity
if (isset($_GET['increase'])) {
    $index = $_GET['increase'];

    if (isset($_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index]['quantity']++;
        // Update the database
        $stmt = $connection->prepare("UPDATE cart SET quantity = ? WHERE user_email = ? AND product_id = ?");
        $stmt->bind_param("isi", $_SESSION['cart'][$index]['quantity'], $user_email, $index);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: cart.php");
    exit;
}

// Decrease quantity
if (isset($_GET['decrease'])) {
    $index = $_GET['decrease'];

    if (isset($_SESSION['cart'][$index])) {
        if ($_SESSION['cart'][$index]['quantity'] > 1) {
            $_SESSION['cart'][$index]['quantity']--;
            // Update the database
            $stmt = $connection->prepare("UPDATE cart SET quantity = ? WHERE user_email = ? AND product_id = ?");
            $stmt->bind_param("isi", $_SESSION['cart'][$index]['quantity'], $user_email, $index);
            $stmt->execute();
            $stmt->close();
        } else {
            unset($_SESSION['cart'][$index]);
            // Remove from database as well
            $stmt = $connection->prepare("DELETE FROM cart WHERE user_email = ? AND product_id = ?");
            $stmt->bind_param("si", $user_email, $index);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: cart.php");
    exit;
}


// Remove item from cart
if (isset($_GET['remove'])) {
    $index = $_GET['remove'];

    // Remove the item from the cart
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
    }

    // Redirect back to cart page
    header("Location: cart.php");
    exit;
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


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="script.js"></script>
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
                <span style="background-color: red; color: white; border-radius: 9999px; padding: 0.25rem 0.5rem; font-size: 0.75rem; margin-left: 0.5rem;">
    <?php echo $item_count; ?>
</span>

            <?php endif; ?>
        </a>
    </li>                </ul>
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

    <!-- Cart Section -->
    <div class="px-20 py-3 flex flex-wrap mt-20">
        <div class="w-1/3">
            <h1 class="text-white text-2xl">My Shopping Cart</h1>
        </div>

        <!-- Line Separator -->
        <div class="w-full max-w-full mx-auto mt-5">
            <div class="border-t border-zinc-400"></div>
        </div>

        <!-- Cart Items Container -->
<div class="w-full max-w-full mx-auto mt-5 bg-white rounded-lg p-2" style="border-top: 4px solid #333;">
    <!-- Check if the cart is empty -->
    <?php if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])): ?>
    <div class="flex items-center justify-center p-2">
        <div class="w-full max-w-sm text-center">
            <p class="text-black">No items in your cart.</p>
        </div>
    </div>
<?php else: ?>
    <!-- Your else logic goes here -->


        <!-- Cart Items -->
        <?php
        $total_price = 0;
        foreach ($_SESSION['cart'] as $index => $item):
            if (is_array($item)) { // Ensure each item is an array
                $total_price += $item['price'] * $item['quantity'];
        ?>
            <div class="flex items-center justify-between p-2 border-b">
                <!-- Product Details -->
                <div class="flex items-center space-x-4">
                    <img src="../../uploads/<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-20 h-auto">
                    <div>
                        <h2 class="text-lg font-bold"><?php echo htmlspecialchars($item['name']); ?></h2>
                        <p class="text-sm text-gray-500">Brand: <?php echo htmlspecialchars($item['brand']); ?></p>
                        <p class="text-sm text-gray-500">Category: <?php echo htmlspecialchars($item['category']); ?></p>
                    </div>
                </div>

                <div class="flex flex-col items-end">
    <p class="text-right font-semibold">₱<?php echo number_format($item['price'], 2); ?></p>
    
    <!-- Quantity Adjustment Section -->
    <div class="flex items-center mt-1">
        <!-- Decrease Quantity Button -->
        <a href="cart.php?decrease=<?php echo $index; ?>" class="px-2 py-1 text-gray-500 hover:text-black border border-gray-300">-</a>

        <!-- Quantity Display -->
        <span class="mx-2 text-sm text-gray-500">Quantity: <?php echo $item['quantity']; ?></span>

        <!-- Increase Quantity Button -->
        <a href="cart.php?increase=<?php echo $index; ?>" class="px-2 py-1 text-gray-500 hover:text-black border border-gray-300">+</a>
    </div>

    <!-- Remove Button -->
    <a href="cart.php?remove=<?php echo $index; ?>" class="text-red-500 text-sm hover:underline mt-1">Remove</a>
</div>

            </div>
        <?php 
            } 
        endforeach; 
        ?>
    <?php endif; ?>

    <!-- Line Separator -->
    <div class="w-full max-w-full mx-auto mt-5">
        <div class="border-t border-zinc-400"></div>
    </div>

    <!-- Total Section -->
    <?php if (!empty($_SESSION['cart'])): ?>
    <div class="flex items-center justify-between p-2">
        <div class="flex-1 flex items-center justify-center">
            <h1 class="text-lg font-bold">Total</h1>
        </div>
        <div class="w-full max-w-sm flex justify-end">
            <div class="flex items-center">
                <h1 class="text-right text-lg font-bold">₱<?php echo number_format($total_price, 2); ?></h1>
            </div>
        </div>
    </div>

    <div class="flex justify-end mt-4">
    <a href="checkout.php" class="bg-red-900 text-white px-4 py-2 rounded hover:bg-red-700">Checkout</a>
</div>

    <?php endif; ?>
</div>
    </div>
</div>
</body>
</html>
