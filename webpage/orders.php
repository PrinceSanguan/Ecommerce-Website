<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

$connection = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("location: /wbsif/webpage/Home.php");
    exit;
}

// Retrieve user data from session
$user_email = htmlspecialchars($_SESSION['user_email']);
$user_name = htmlspecialchars($_SESSION['user_name'] ?? ''); // Fallback if no name is provided
$user_id = (int)$_SESSION['user_id']; // Cast to int for security

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize input data
    $total_amount = htmlspecialchars(trim($_POST['total_amount']));
    $payment_method = htmlspecialchars(trim($_POST['payment_method']));
    $order_date = htmlspecialchars(trim($_POST['order_date']));
    $items = isset($_POST['items']) ? $_POST['items'] : []; // Ensure items are retrieved from POST data

    // Check for duplicate orders before inserting
    $stmt = $connection->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND total_amount = ? AND payment_method = ? AND order_date = ?");
    $stmt->bind_param("isss", $user_id, $total_amount, $payment_method, $order_date);
    $stmt->execute();
    $stmt->bind_result($order_count);
    $stmt->fetch();
    $stmt->close();

    if ($order_count > 0) {
        
    } else {
        // Store the order in the database
        $stmt = $connection->prepare("INSERT INTO orders (user_id, total_amount, payment_method, order_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $total_amount, $payment_method, $order_date);
        
        if ($stmt->execute()) {
            // Get the last inserted order ID
            $order_id = $connection->insert_id;

            // Prepare to insert unique items
            $unique_items = [];

            // Accumulate unique items
            foreach ($items as $item) {
                $item_name = htmlspecialchars(trim($item['name']));
                $item_quantity = (int)$item['quantity'];
                $item_price = (float)$item['price'];

                // Check if the item already exists in the unique_items array
                if (!isset($unique_items[$item_name])) {
                    // If not, add it to the array
                    $unique_items[$item_name] = [
                        'quantity' => $item_quantity,
                        'price' => $item_price
                    ];
                } else {
                    // If it exists, accumulate the quantity
                    $unique_items[$item_name]['quantity'] += $item_quantity;
                }
            }

            // Insert unique items into order_items table
            $stmt = $connection->prepare("INSERT INTO order_items (order_id, item_name, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($unique_items as $item_name => $item_data) {
                $stmt->bind_param("isid", $order_id, $item_name, $item_data['quantity'], $item_data['price']);
                $stmt->execute();
            }

            // Redirect to orders.php after successfully placing the order
            header("Location: orders.php");
            exit; // Ensure no further code is executed
        } else {
            // Handle error during order insertion
            echo "Error: Could not place the order.";
        }
    }
}

// Fetch user orders from the database only once
$stmt = $connection->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch order details if order_id is set
$order_details = null;
$ordered_items = [];
if (isset($_GET['order_id'])) {
    $order_id = (int)$_GET['order_id'];
    $stmt = $connection->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_details = $stmt->get_result()->fetch_assoc();

    // Fetch items associated with this order
    if ($order_details) {
        $stmt = $connection->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $ordered_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
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
                    <li class="list-none px-4 py-2 lg:px-7"><a href="cart.php" class="no-underline text-white">Cart</a></li>
                </ul>

                <ul class="flex items-center">
                    <li class="list-none inline-block dropdown">
                        <button class="dropbtn text-white px-2 flex items-center">
                            <?php echo $user_email; ?>
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

        <div class="px-20 py-3 flex flex-wrap mt-20">
            <div class="w-1/3">
                <h1 class="text-white text-2xl">My Orders</h1>
            </div>
            <div class="w-full max-w-full mx-auto mt-5">
                <div class="border-t border-zinc-400"></div>
            </div>

            <div class="w-full max-w-full mx-auto mt-5 bg-white rounded-lg p-2" style="border-top: 4px solid #333;">
                <div class="flex items-center justify-between p-2">
                    <div class="text-white"></div>
                    <div class="w-full max-w-sm">
                        <div class="flex items-center">
                            <span class="text-gray-500 mr-2">Search:</span>
                            <input type="text" class="block w-2/3 px-2 py-1 border border-gray-300 rounded-lg shadow-sm focus:outline-none">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">#</th>
                            <th class="py-3 px-6 text-left">Order Date</th>
                            <th class="py-3 px-6 text-left">Total Amount</th>
                            
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($order = $result->fetch_assoc()): ?>
                                <tr class="border-b border-gray-300 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left"><?php echo $order['id']; ?></td>
                                    <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($order['order_date']); ?></td>
                                    <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($order['total_amount']); ?></td>
                                                                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($order['status']); ?></td> <!-- New Status Column -->
                                    <td class="py-3 px-6 text-left">
                                        <a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="text-blue-600 hover:text-blue-800">View</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-3">No orders found</td> <!-- Adjusted colspan for new column -->
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$connection->close();
?>
