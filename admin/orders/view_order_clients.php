<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";

$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // Fallback if no name is provided

// Get the order ID from the URL
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if form was submitted to update status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['order_id'])) {
        $new_status = $_POST['action']; // Either "Shipped" or "Delivered"
        $order_id = intval($_POST['order_id']);

        // Update the order status in the database
        $update_query = "UPDATE orders SET status = ? WHERE id = ?";
        $update_stmt = $connection->prepare($update_query);
        $update_stmt->bind_param("si", $new_status, $order_id);

        if ($update_stmt->execute()) {
            // Success message
            $_SESSION['status_message'] = "Order status updated to '$new_status'.";
        } else {
            $_SESSION['status_message'] = "Failed to update order status.";
        }

        $update_stmt->close();
    }
}

// Fetch order details from the database
$query = "
    SELECT o.*, c.firstname, c.lastname 
    FROM orders o
    LEFT JOIN client_list c ON o.user_id = c.id
    WHERE o.id = ?";

$stmt = $connection->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

$order = $result->fetch_assoc();

if (!$order) {
    echo "Order not found!";
    exit;
}

// Fetch ordered products
$product_query = "
    SELECT oi.item_name, oi.image_url, oi.quantity, oi.price
    FROM order_items oi
    WHERE oi.order_id = ?";
    
$product_stmt = $connection->prepare($product_query);
$product_stmt->bind_param("i", $order_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

// Store ordered products in an array
$products = $product_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">
    <div class="max-w-xl mx-auto mt-10 p-5 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Order Details</h1>

        <!-- Display status message if available -->
        <?php if (isset($_SESSION['status_message'])): ?>
            <div class="mb-4 text-green-600">
                <?php 
                echo $_SESSION['status_message']; 
                unset($_SESSION['status_message']); // Clear the message
                ?>
            </div>
        <?php endif; ?>
        
        <!-- Display the same order number from the database -->
        <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
        <p><strong>Date Ordered:</strong> <?php echo $order['order_date']; ?></p>
        <p><strong>Client Name:</strong> <?php echo $order['firstname'] . ' ' . $order['lastname']; ?></p>
        <p><strong>Total Amount:</strong> ₱<?php echo number_format($order['total_amount'], 2); ?></p>
        <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
        <p><strong>Status:</strong> <?php echo $order['status']; ?></p>

        <h2 class="text-xl font-semibold mt-6">Ordered Products</h2>

        <table class="min-w-full mt-4 border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2 text-left">Product Name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Product Image</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Quantity</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Price</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($product['item_name']); ?></td>
                    <td class="border border-gray-300 px-4 py-2">
                        <img src="../../uploads/<?php echo htmlspecialchars($product['image_url']); ?>" alt="Current Image" class="mt-2 w-24 h-24 object-cover">
                    </td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($product['quantity']); ?></td>
                    <td class="border border-gray-300 px-4 py-2">₱<?php echo number_format($product['price'], 2); ?></td>
                    <td class="border border-gray-300 px-4 py-2">
                        <!-- Shipped Button -->
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $order_id; ?>" method="POST" class="inline-block">
                            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                            <input type="hidden" name="action" value="Shipped">
                            <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded">Shipped</button>
                        </form>
                        <!-- Delivered Button -->
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $order_id; ?>" method="POST" class="inline-block">
                            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                            <input type="hidden" name="action" value="Delivered">
                            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">Delivered</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="order_list.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Back to Orders</a>
    </div>
</body>
</html>

<?php
// Close connections
$product_stmt->close();
$stmt->close();
$connection->close();
?>
