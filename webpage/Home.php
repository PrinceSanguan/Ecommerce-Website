<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

$connection = new mysqli($servername, $username, $password, $database);

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'client') {
    header("location: webpage/Home.php");
    exit;
}

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // Fallback if no name is provided

// Fetch products from the database
$product_query = "SELECT * FROM product_list WHERE status = 1 LIMIT 5";
$product_result = $connection->query($product_query);

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
    <title>Homepage</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="script.js"></script>
</head>
<body>
    <div class="bg-[url('images/bg1.png')] bg-center bg-cover bg-fixed min-h-screen w-full pt-6 flex flex-col">
        
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
    <a href="cart.php" class="no-underline text-white">
        Cart 
        <?php if ($item_count > 0): ?>
            <span style="background-color: red; color: white; border-radius: 9999px; padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                <?php echo $item_count; ?>
            </span>
        <?php endif; ?>
    </a>
</li>                </ul>
                <ul class="flex items-center">
                    <li class="list-none inline-block dropdown">
                        <button class="dropbtn text-white px-2 flex items-center">
                            <p><?php echo htmlspecialchars($user_email); ?></p>
                            <img src="images/icons8-dropdown-24.png" class="ml-2" style="filter: invert(100%); width: 1.5em; height: 1.5em; vertical-align: middle;">
                        </button>
                        <div class="dropdown-content px-0.5 py-0.5">
                            <a href="#">Chat Bot</a>
                            <a href="manage_account.php">Manage Account</a>
                            <a href="../login/client/logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="hero-content flex flex-col lg:flex-row items-center justify-center lg:justify-center mb-20">
            <div class="text-white max-w-lg lg:max-w-2xl text-center flex flex-col items-center mt-44">
                <h1 class="text-3xl lg:text-5xl font-semibold leading-tight">Motor Parts & Accessories System for RKE</h1>
                <p class="mt-3 text-lg">Find Parts & Accessories for your Motorcycle</p>
                <button class="mt-5 px-6 py-3 bg-gray-800 font-semibold rounded-lg hover:bg-gray-700 duration-300">
                    <a href="products.php">
                        SHOP NOW
                    </a>
                </button>
            </div>
        </div>

        <div class="product-container grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 my-10 px-20">
    <?php if ($product_result && $product_result->num_rows > 0): ?>
        <?php while ($row = $product_result->fetch_assoc()): ?>
            <section class="text-center">
                <div class="cont-1 bg-white bg-opacity-30 backdrop-blur-lg p-4 rounded-lg shadow-md">
                    <?php
                    $imagePath = "../uploads/" . htmlspecialchars($row['image']);
                    ?>
                    <div class="cg text-gray-700"><img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="object-contain h-32"></div>
                    <p class="text- font-bold"><?php echo htmlspecialchars($row['name']); ?></p>
                    <p class="text-gray-700">₱<?php echo number_format($row['price'], 2); ?></p>
                    <div class="relative w-full mt-3 mb-7">
                        <div class="absolute left-0 right-0 bottom-0 border-t border-zinc-400"></div>
                    </div>
                    <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars($row['description']); ?></p> <!-- Added description -->
                    
                </div>
            </section>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-white text-sm">No products found.</p>
    <?php endif; ?>
</div>




        <footer class="w-full text-center py-4 text-white bg-gray-800">
            <p>&copy; Web-Based Sales & Inventory with Forecasting.</p>
        </footer>
    </div>
</body>
</html>
