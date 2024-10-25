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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Query the database for product details
$sql = "SELECT id, name, brand, category, description, image, quantity, date_created FROM product_list WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    
    exit;
}

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; // Fallback if no name is provided




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product</title>
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


            <div class="p-4 w-full h-full mt-3">
    <div class="flex flex-col p-2 border-t-4 border-sky-900 rounded-md shadow-2xl bg-white">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-black text-xl">Products Stocks List</h1>
            <div class="flex space-x-2">
            <a href="#" onclick="openModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">Add New Stock</a>
            <a href="product_stocks.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-sm">Back to List</a>
            </div>
        </div>

        <div class="relative w-full mb-7">
            <div class="absolute left-0 right-0 bottom-0 border-t border-zinc-400"></div>
        </div>

       
        <div class="overflow-x-auto">
    <div class="container mx-auto mt-5 p-3 bg-white shadow-md rounded-md flex flex-col md:flex-row gap-4"> <!-- Flex layout -->

        <!-- Product Image -->
        <div class="flex-shrink-0 mb-4 md:mb-0">
            <?php if (!empty($product['image'])): ?>
                <img src="../../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" class="w-48 h-48 object-cover rounded-md mx-auto">
            <?php else: ?>
                <p class="text-gray-500 mb-4 text-sm text-center">No image available for this product.</p>
            <?php endif; ?>
        </div>

        <!-- Product Details -->
        <div class="flex-grow">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm mb-2 p-2">
        <div>
            <strong>Product Name:</strong>
            <p class="text-sm">
                <?php echo isset($product['name']) ? htmlspecialchars($product['name']) : 'N/A'; ?>
            </p>
        </div>
        <div>
            <strong>Brand:</strong>
            <p class="text-sm">
                <?php echo isset($product['brand']) ? htmlspecialchars($product['brand']) : 'N/A'; ?>
            </p>
        </div>
        <div>
            <strong>Category:</strong>
            <p class="text-sm">
                <?php echo isset($product['category']) ? htmlspecialchars($product['category']) : 'N/A'; ?>
            </p>
        </div>
        <div class="md:col-span-2">
            <strong>Description:</strong>
            <p class="text-sm">
                <?php echo isset($product['description']) ? nl2br(htmlspecialchars($product['description'])) : 'N/A'; ?>
            </p>
        </div>
        <div>
            <strong>Stock Quantity:</strong>
            <p class="text-sm">
                <?php echo isset($product['quantity']) ? htmlspecialchars($product['quantity']) : 'N/A'; ?>
            </p>
        </div>
    </div>
</div>



        <!-- Stock Quantity Table -->
        <div class="flex-shrink-0 w-1/2"> <!-- Set the width to 50% and keep it fixed -->
        <table class="min-w-full bg-white border border-gray-300 rounded-md shadow-sm">
    <thead>
        <tr class="bg-gray-200 text-gray-700 text-xs uppercase">
            <th class="py-2 px-3 text-left">Date Created</th>
            <th class="py-2 px-3 text-left">Stock Quantity History</th>
            <th class="py-2 px-3 text-left">Action</th>
        </tr>
    </thead>
    <tbody class='text-gray-600 text-xs'>
    <!-- Stock History Rows -->
    <?php
    // Fetch stock history for the current product
    $stock_sql = "SELECT id, stock_quantity, date_added FROM stock_history WHERE product_id = ? ORDER BY date_added DESC";
    $stock_stmt = $conn->prepare($stock_sql);
    $stock_stmt->bind_param('i', $product['id']);
    $stock_stmt->execute();
    $stock_result = $stock_stmt->get_result();

    // Display stock history if available
    while ($row = $stock_result->fetch_assoc()) {
        echo "<tr class='border-b border-gray-200 hover:bg-gray-100'>";
        echo "<td class='py-2 px-3'>" . htmlspecialchars($row['date_added']) . "</td>";
        echo "<td class='py-2 px-3'>" . htmlspecialchars($row['stock_quantity']) . "</td>";
        
        // Buttons Container
        echo "<td class='py-2 px-3'>";
        echo "<div class='flex space-x-2'>"; // Using Flexbox for alignment
    
        // Edit Button
        echo "<button onclick='openEditQuantityModal(" . $row['id'] . ", " . htmlspecialchars($row['stock_quantity']) . ", " . $product['id'] . ")' class='bg-blue-500 text-white px-2 py-1 rounded'>Edit</button>";
    
        // Delete Button and Form
        echo "<form action='delete_stock_history.php' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this stock entry?\");'>";
        echo "<input type='hidden' name='stock_id' value='" . $row['id'] . "'>";
        echo "<input type='hidden' name='product_id' value='" . $product['id'] . "'>";
        echo "<input type='hidden' name='stock_quantity' value='" . $row['stock_quantity'] . "'>";
        echo "<button type='submit' class='bg-red-500 text-white px-2 py-1 rounded'>Delete</button>";
        echo "</form>";
    
        echo "</div>"; // Closing the flex container
        echo "</td>";
        
        echo "</tr>";
    }
    
    ?>
</tbody>
</table>

<script>
function deleteStockHistory(stockId) {
    if (confirm("Are you sure you want to delete this stock record?")) {
        // Send a request to the server to delete the stock entry
        window.location.href = 'delete_stock.php?id=' + stockId; // Adjust this path as necessary
    }
}

function openEditHistoryModal(stockId, currentQuantity) {
    // Code to open a modal for editing stock quantity
    // For example, set the current quantity in an input field in the modal
    document.getElementById('edit-stock-id').value = stockId;
    document.getElementById('edit-stock-quantity').value = currentQuantity;
    document.getElementById('editModal').style.display = 'block'; // Show modal
}

// Function to close the modal
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>





<script>
    function saveStockHistory() {
    const stockId = document.getElementById('modal-stock-id').value;
    const quantity = document.getElementById('stock-quantity').value;

    // Perform an AJAX request to update the stock history in the database
    // Example using Fetch API
    fetch('/update_quantity.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: stockId, quantity: quantity }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh the stock table or reload the page
            location.reload();
        } else {
            alert('Error updating stock history.');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

</script>


        </div>
    </div>
</div>





<!-- Edit Quantity Modal -->

<div id="editQuantityModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
        <h2 class="text-lg font-bold mb-4 text-center">Edit Stock Quantity</h2>
        <form id="editQuantityForm" class="space-y-4">
            <input type="hidden" name="stock_history_id" id="stock_history_id" value="">
            <input type="hidden" name="product_id" id="product_id" value="">
            <div>
                <label for="editQuantityInput" class="block mb-1">Enter quantity:</label>
                <input type="number" id="editQuantityInput" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" placeholder="Enter quantity">
            </div>
            <div class="flex justify-between mt-4">
                <button type="button" onclick="closeEditQuantityModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-sm">Cancel</button>
                <button type="button" onclick="submitEditQuantity()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">Save</button>
            </div>
        </form>
    </div>
</div>






<div id="add-stock-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white rounded-lg p-6 w-96 h-auto shadow-lg">
        <h2 class="text-lg font-bold mb-4">Add Stock Quantity</h2>
        <form method="POST" action="add_stock.php" class="space-y-4">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
            <div>
                <input type="number" name="quantity" id="quantity" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" placeholder="Enter quantity">
            </div>
            <div class="flex justify-between">
            <button type="button" onclick="window.location.href='/wbsif/admin/inventory/view_product.php?id=<?php echo $product['id']; ?>';" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-sm">Cancel</button>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">Add Stock</button>
            </div>
        </form>
    </div>
</div>





                
            </div>
        </div>
    </div>
</div>

<script>
    function openEditQuantityModal(stock_history_id, product_id) {
        document.getElementById('stock_history_id').value = stock_history_id;
        document.getElementById('product_id').value = product_id;
        document.getElementById('editQuantityModal').style.display = 'flex'; // Show the modal
    }

    function closeEditQuantityModal() {
        document.getElementById('editQuantityModal').style.display = 'none'; // Hide the modal
    }

    function submitEditQuantity() {
        // Your submission logic here
        closeEditQuantityModal(); // Close the modal after submission
    }
</script>
<script>
    function openModal() {
    document.getElementById('add-stock-modal').classList.remove('hidden');
}

function closeAddStockModal() {
    document.getElementById('add-stock-modal').classList.add('hidden');
}

</script>

<script>
let currentStockId;
let currentProductId;

function openEditQuantityModal(stockId, currentQuantity, productId) {
    currentStockId = stockId;
    currentProductId = productId;
    document.getElementById('editQuantityInput').value = currentQuantity;

    const modal = document.getElementById('editQuantityModal');
    modal.style.display = 'flex'; // Use flex to align items

    // Center the modal vertically and horizontally
    modal.style.justifyContent = 'center';
    modal.style.alignItems = 'center';
}

function closeEditQuantityModal() {
    document.getElementById('editQuantityModal').style.display = 'none';
}

function submitEditQuantity() {
    const newQuantity = document.getElementById('editQuantityInput').value;

    if (newQuantity) {
        // Send AJAX request to update stock quantity
        fetch('update_stock.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `stock_history_id=${currentStockId}&new_quantity=${newQuantity}&product_id=${currentProductId}`
        })
        .then(response => response.text())
        .then(result => {
            if (result === 'success') {
                location.reload(); // Refresh the page to show the updated data
            } else {
                alert('Failed to update quantity');
            }
        });

        closeEditQuantityModal(); // Close the modal after submission
    } else {
        alert('Please enter a quantity');
    }
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('editQuantityModal');
    if (event.target == modal) {
        closeEditQuantityModal();
    }
}
</script>




                </div>
</body>
</html>