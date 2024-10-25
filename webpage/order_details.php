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

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("location: /wbsif/webpage/Home.php");
    exit;
}

// Get and validate order_id from query string
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : null;

if ($order_id) {
    // Fetch order details
    $order_stmt = $connection->prepare("SELECT * FROM orders WHERE id = ?");
    $order_stmt->bind_param("i", $order_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();
    
    if ($order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();
        
        // Fetching order details
        $total_amount = $order['total_amount'];
        $payment_method = $order['payment_method'];
        $order_date = $order['order_date'];
        $order_status = $order['status'];
        $order_number = $order['order_number'];

        // Fetch ordered items for this order
        // Modified query to use only order_items table since we don't need to join
        $items_stmt = $connection->prepare("
            SELECT * 
            FROM order_items 
            WHERE order_id = ?
        ");
        
        $items_stmt->bind_param("i", $order_id);
        $items_stmt->execute();
        $items_result = $items_stmt->get_result();
        
        $items = [];
        while ($item = $items_result->fetch_assoc()) {
            $items[] = $item;
        }
        
        $items_stmt->close(); // Close the statement for items
    } else {
        echo "No order found.";
        exit;
    }
    
    $order_stmt->close(); // Close the order statement
} else {
    echo "Invalid order ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-1/2">
            <h1 class="text-2xl font-bold text-center">Order Details</h1>

            <div class="mt-6">
                <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order_number); ?></p>
                <p><strong>Total Amount:</strong> ₱<?php echo number_format($total_amount, 2); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment_method); ?></p>
                <p><strong>Date Ordered:</strong> <?php echo htmlspecialchars($order_date); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order_status); ?></p>
            </div>

            <div class="mt-6">
    <h2 class="font-semibold text-lg">Items Ordered</h2>
    <ul class="list-disc list-inside">
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
                <li class="flex justify-between items-center mt-2">
                    <div class="flex items-center">
                        <img src="../../uploads/<?php echo htmlspecialchars($item['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($item['item_name']); ?>" 
                             class="w-16 h-16 object-cover rounded mr-4">
                        <span><?php echo htmlspecialchars($item['item_name']); ?> - <?php echo (int)$item['quantity']; ?> pcs</span>
                    </div>
                    <span>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="text-gray-500">No items found for this order.</li>
        <?php endif; ?>
    </ul>
</div>






            <div class="mt-6 text-center">
                <a href="orders.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Back to Orders</a>
            </div>
        </div>
    </div>
</body>
</html>
