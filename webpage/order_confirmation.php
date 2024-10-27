<?php
session_start();

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

$connection = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Generate a unique order number
$order_number = rand(100000, 999999);
$total_price = isset($_SESSION['total_price']) ? $_SESSION['total_price'] : 0;
$payment_method = isset($_SESSION['payment_method']) ? $_SESSION['payment_method'] : '';
$order_date = date("Y-m-d H:i:s");

// Insert order details into the database only if it does not already exist
$user_id = $_SESSION['user_id'];

// Check if the order already exists for this user
$check_order_query = "SELECT * FROM orders WHERE user_id = ? AND order_number = ?";
$check_stmt = $connection->prepare($check_order_query);
$check_stmt->bind_param("is", $user_id, $order_number);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows == 0) {
    // Proceed to insert the order
    $query = "INSERT INTO orders (order_number, user_id, total_amount, payment_method, order_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("sssss", $order_number, $user_id, $total_price, $payment_method, $order_date);
    $stmt->execute();
    $stmt->close();

    // Get the last inserted order ID
    $order_id = $connection->insert_id;

    // Save each cart item to the order_items table
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            // Check stock availability
            $check_stock_query = "SELECT stock_quantity FROM stock_history WHERE product_id = (SELECT id FROM product_list WHERE name = ?) AND deleted = 0 LIMIT 1";
            $stock_stmt = $connection->prepare($check_stock_query);
            $stock_stmt->bind_param("s", $item['name']);
            $stock_stmt->execute();
            $stock_result = $stock_stmt->get_result();

            if ($stock_result->num_rows > 0) {
                $current_stock = $stock_result->fetch_assoc()['stock_quantity'];

                // Check if there's enough stock
                if ($current_stock >= $item['quantity']) {
                    // Update stock in stock_history
                    $update_stock_query = "UPDATE stock_history SET stock_quantity = stock_quantity - ? WHERE product_id = (SELECT id FROM product_list WHERE name = ?) AND deleted = 0";
                    $update_stmt = $connection->prepare($update_stock_query);
                    $update_stmt->bind_param("is", $item['quantity'], $item['name']);
                    $update_stmt->execute();

                    // Update quantity in product_list
                    $update_product_query = "UPDATE product_list SET quantity = quantity - ? WHERE name = ?";
                    $update_product_stmt = $connection->prepare($update_product_query);
                    $update_product_stmt->bind_param("is", $item['quantity'], $item['name']);
                    $update_product_stmt->execute();

                    // Insert into order_items
                    $item_query = "INSERT INTO order_items (order_id, item_name, price, quantity, image_url) VALUES (?, ?, ?, ?, ?)";
                    $item_stmt = $connection->prepare($item_query);
                    $item_stmt->bind_param("isdis", $order_id, $item['name'], $item['price'], $item['quantity'], $item['image_url']);
                    $item_stmt->execute();
                    $item_stmt->close(); // Close the item statement
                } else {
                    // Handle insufficient stock
                    $_SESSION['order_error'] = "Insufficient stock for item: " . htmlspecialchars($item['name']);
                }
            } else {
                // Handle case where product is not found
                $_SESSION['order_error'] = "Product not found in stock history: " . htmlspecialchars($item['name']);
            }

            $stock_stmt->close(); // Close stock statement
        }
    }
} else {
    // Handle the case where the order already exists
    $order_id = $result->fetch_assoc()['id']; // Get the existing order ID
    $_SESSION['order_error'] = "Order has already been placed with this order number.";
}

// Set the payment method display text
$payment_method_display = match ($payment_method) {
    'gcash' => 'Gcash',
    'cod' => 'Cash on Delivery (COD)',
    'pickup' => 'Pickup Item',
    default => 'N/A',
};

// Close the database connection
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-1/2">
            <h1 class="text-2xl font-bold text-center">Order Confirmation</h1>
            <p class="text-gray-700 mt-4 text-center">
                Thank you for your order, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
            </p>

            <div class="mt-6">
                <h2 class="font-semibold text-lg">Order Details</h2>
                <p><strong>Order Number:</strong> <?php echo $order_number; ?></p>
                <p><strong>Total Amount:</strong> ₱<?php echo number_format($total_price, 2); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment_method_display); ?></p>
                <p><strong>Date Ordered:</strong> <?php echo htmlspecialchars($order_date); ?></p>
            </div>

            <?php if ($payment_method == 'gcash' && isset($_SESSION['proof_of_payment'])): ?>
                <div class="mt-6">
                    <h2 class="font-semibold text-lg">Proof of Payment</h2>
                    <img src="<?php echo htmlspecialchars($_SESSION['proof_of_payment']); ?>" alt="Proof of Payment" class="w-64 h-auto object-cover border border-gray-300 rounded-lg">
                </div>
            <?php endif; ?>

            <div class="w-full max-w-full mx-auto mt-5">
                <div class="border-t border-zinc-400"></div>
            </div>

            <div class="mt-6">
                <h2 class="font-semibold text-lg">Items Ordered</h2>
                <ul class="list-disc list-inside">
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <li class="flex justify-between">
                                <span><?php echo htmlspecialchars($item['name']); ?> - <?php echo (int)$item['quantity']; ?> pcs</span>
                                <span>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="text-gray-500">No items in cart.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <form action="orders.php" method="POST">
                <input type="hidden" name="order_number" value="<?php echo $order_number; ?>">
                <input type="hidden" name="total_amount" value="<?php echo $total_price; ?>">
                <input type="hidden" name="payment_method" value="<?php echo $payment_method; ?>">
                <input type="hidden" name="order_date" value="<?php echo $order_date; ?>">
                <input type="hidden" name="user_email" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>">
                <input type="hidden" name="user_name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>">

                <div class="mt-6 text-center">
                    <button type="submit" name="continue_shopping" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Continue Shopping</button>
                    <button type="submit" name="view_orders" class="ml-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">View My Orders</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    // Clear the cart after displaying order confirmation
    unset($_SESSION['cart']);
    ?>
</body>
</html>
