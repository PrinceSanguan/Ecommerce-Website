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

$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

// Fetch client details including phone number
$client_query = "SELECT firstname, lastname, phone, address FROM client_list WHERE email = ?";
$stmt = $connection->prepare($client_query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

// Redirect to cart if the cart is empty
if (empty($_SESSION['cart'])) {
    // Redirect to the cart page with a message
    $_SESSION['error'] = "Please add to cart first before you checkout!";
    header("Location: products.php");
    exit();
}
// Calculate total price
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    if (isset($item['price']) && isset($item['quantity'])) {
        $total_price += $item['price'] * $item['quantity'];
    }
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
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="bg-[url('../admin/images/bg1.png')] bg-center bg-cover bg-fixed min-h-screen w-full pt-6 flex flex-col">
    <!-- Fixed Header -->
    <div class="header-fixed w-full flex items-center">
        <button id="menu-toggle" class="lg:hidden text-white text-xl px-8 py-4 rounded-md absolute">
            ☰
        </button>

        <div class="px-20 p-2 flex flex-col lg:flex-row items-center mb-2 border-b-stone-100 flex-1">
            <div class="flex items-center mb-4 lg:mb-0">
                <img src="../login/images/logo.jpg" class="w-12 h-12 rounded-full cursor-pointer">
                <h1 class="ml-4 text-white font-extrabold text-xl cursor-pointer">R K E</h1>
            </div>
            <ul id="nav-links" class="lg:flex flex-1 justify-center text-center">
                <li class="list-none px-4 py-2 lg:px-7"><a href="Home.php" class="no-underline text-white">Home</a></li>
                <li class="list-none px-4 py-2 lg:px-7"><a href="products.php" class="no-underline text-white">Products</a></li>
                <li class="list-none px-4 py-2 lg:px-7"><a href="about_us.php" class="no-underline text-white">About Us</a></li>
                <li class="list-none px-4 py-2 lg:px-7"><a href="orders.php" class="no-underline text-white">Orders</a></li>
                <li class="list-none px-4 py-2 lg:px-7">
                    <a href="cart.php" class="no-underline text-white">
                        Cart 
                        <?php if ($item_count > 0): ?>
                            <span class="bg-red-500 text-white rounded-full px-2 text-xs ml-2"><?php echo $item_count; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
            <ul class="flex items-center">
                <li class="list-none inline-block dropdown">
                    <button class="dropbtn text-white px-2 flex items-center">
                        <?php echo htmlspecialchars($user_email); ?>
                        <img src="images/icons8-dropdown-24.png" class="ml-2 filter-invert w-6 h-6">
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

    <div class="px-20 py-3 flex flex-wrap mt-20">
        <div class="w-1/3">
            <h1 class="text-white text-2xl">Order Summary</h1>
        </div>

        <!-- Line Separator -->
        <div class="w-full max-w-full mx-auto mt-5">
            <div class="border-t border-zinc-400"></div>
        </div>

        <!-- Cart Items Container -->
        <div class="w-full max-w-full mx-auto mt-5 bg-white rounded-lg p-6" style="border-top: 4px solid #333;">
            <h2 class="text-xl flex items-center">
                <img src="images/icons8-location-50.png" alt="Location" class="mr-2" style="width: 24px; height: 24px;">
                <span class="font-semibold"><?php echo htmlspecialchars($client['firstname'] . ' ' . $client['lastname']); ?></span>
                <span class="ml-4 text-sm"><?php echo htmlspecialchars($client['phone']); ?></span>
            </h2>
            <p class="mt-1 text-sm text-gray-700"><?php echo htmlspecialchars($client['address']); ?></p>
            <div class="w-full max-w-full mx-auto mt-5">
                <div class="border-t border-zinc-400"></div>
            </div>
           
            <ul class="my-4">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <?php 
                                $image_url = isset($item['image_url']) && !empty($item['image_url']) ? htmlspecialchars($item['image_url']) : 'default-image.jpg'; 
                            ?>
                            <img src="../uploads/<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-20 h-auto rounded">
                            <span class="ml-4"><?php echo htmlspecialchars($item['name']); ?> (<?php echo $item['quantity']; ?>)</span>
                        </div>
                        <span>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <hr class="border-gray-300">
            <div class="flex justify-between font-bold mt-4">
                <span>Total:</span>
                <span>₱<?php echo number_format($total_price, 2); ?></span>
            </div>

            
            
                
            <div class="flex justify-center"> <!-- Centering the form within the container -->
    <form method="POST" action="process_checkout.php" enctype="multipart/form-data" class="p-4 bg-white rounded-lg shadow-lg w-full max-w-[800px]"> <!-- Increased max-width -->
        <!-- Payment Method Selection -->
        <h3 class="font-semibold text-lg mb-4">Select Payment Method</h3>
        
        <div class="flex space-x-4"> <!-- Added space between containers -->
            <!-- Gcash Container -->
            <div class="flex-1 min-w-[150px]"> <!-- Set a minimum width -->
                <label class="flex flex-col items-center p-6 min-h-[220px] border border-gray-300 rounded-lg hover:shadow-md transition duration-200 cursor-pointer payment-option" data-payment-method="gcash">
                    <input type="radio" id="gcash" name="payment_method" value="gcash" required class="form-radio h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500 hidden" onchange="showGcashMessage(); hidePickupAddress();">
                    <img src="../webpage/images/gcash-image (2).png" alt="Gcash" class="w-24 h-24 mb-2"> <!-- Increased Gcash Image Size -->
                    <span class="text-gray-700">Gcash</span>
                </label>
            </div>

            <!-- Cash on Delivery (COD) Container -->
            <div class="flex-1 min-w-[150px]"> <!-- Set a minimum width -->
                <label class="flex flex-col items-center p-6 min-h-[220px] border border-gray-300 rounded-lg hover:shadow-md transition duration-200 cursor-pointer payment-option" data-payment-method="cod">
                    <input type="radio" id="cod" name="payment_method" value="cod" required class="form-radio h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500 hidden" onchange="hideAllMessages(); enablePlaceOrderButton();">
                    <img src="../webpage/images/cod-image (1).png" alt="Cash on Delivery" class="w-24 h-24 mb-2"> <!-- Increased COD Image Size -->
                    <span class="text-gray-700">Cash on Delivery (COD)</span>
                </label>
            </div>

            <!-- Pickup Item Container -->
            <div class="flex-1 min-w-[150px]"> <!-- Set a minimum width -->
                <label class="flex flex-col items-center p-6 min-h-[220px] border border-gray-300 rounded-lg hover:shadow-md transition duration-200 cursor-pointer payment-option" data-payment-method="pickup">
                    <input type="radio" id="pickup" name="payment_method" value="pickup" required class="form-radio h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500 hidden" onchange="showPickupAddress(); hideGcashMessage(); enablePlaceOrderButton();">
                    <img src="../webpage/images/icons8-item-48.png" alt="Pickup" class="w-24 h-24 mb-2"> <!-- Increased Pickup Image Size -->
                    <span class="text-gray-700">Pickup Item</span>
                </label>
            </div>
        </div>

        <!-- Gcash Message -->
        <div id="gcash-message" class="hidden mt-4 flex flex-col items-center"> <!-- Centered message -->
            <span class="text-gray-700">You need to send a proof of payment</span>
            <label class="block mt-2">
                <input type="file" name="proof_image" accept="image/*" class="hidden" id="file-input" onchange="previewImage(event); checkFileUpload();">
                <div class="flex items-center justify-center w-full h-12 border border-gray-300 rounded-lg cursor-pointer bg-gray-100 hover:bg-gray-200 transition duration-200">
                    <span class="text-gray-700">Choose File</span>
                </div>
            </label>
            <img id="image-preview" src="" alt="Proof of Payment Preview" class="mt-2 w-32 h-32 object-cover hidden border border-gray-300 rounded-lg"> <!-- Image preview -->
        </div>

        <!-- Address for Pickup -->
        <div id="pickup-address" class="hidden mt-4">
            <label class="block text-gray-700">Pickup Address:</label>
            <input type="text" id="address" name="pickup_address" class="mt-1 p-2 border border-gray-300 rounded w-full" value="123 Pickup Street, City, State" readonly> <!-- Predefined address -->
        </div>

        <!-- Place Order Button aligned to the right -->
        <div class="flex justify-end mt-4">
            <button type="submit" id="place-order-button" class="bg-green-900 text-white px-4 py-2 rounded hover:bg-green-700 transition" disabled>Place Order</button>
        </div>
    </form>
</div>

<script>
    function hideAllMessages() {
        document.getElementById('gcash-message').classList.add('hidden');
        document.getElementById('pickup-address').classList.add('hidden');
    }

    function showGcashMessage() {
        document.getElementById('gcash-message').classList.remove('hidden');
    }

    function hideGcashMessage() {
        document.getElementById('gcash-message').classList.add('hidden');
    }

    function showPickupAddress() {
        document.getElementById('pickup-address').classList.remove('hidden');
    }

    function hidePickupAddress() {
        document.getElementById('pickup-address').classList.add('hidden');
    }

    function enablePlaceOrderButton() {
        document.getElementById('place-order-button').disabled = false;
    }
</script>


<script>
    function showGcashMessage() {
        document.getElementById('gcash-message').classList.remove('hidden');
        document.getElementById('pickup-address').classList.add('hidden'); // Hide pickup address when Gcash is selected
        document.getElementById('place-order-button').disabled = true; // Disable Place Order button
    }

    function hideGcashMessage() {
        document.getElementById('gcash-message').classList.add('hidden');
        document.getElementById('place-order-button').disabled = false; // Enable Place Order button for other payment methods
    }

    function showPickupAddress() {
        document.getElementById('pickup-address').classList.remove('hidden'); // Show address field when Pickup Item is selected
        document.getElementById('gcash-message').classList.add('hidden'); // Hide Gcash message when Pickup Item is selected
        document.getElementById('place-order-button').disabled = false; // Enable Place Order button
    }

    function previewImage(event) {
        const preview = document.getElementById('image-preview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result; // Set the preview image source to the uploaded file
                preview.classList.remove('hidden'); // Show the preview image
            };
            reader.readAsDataURL(file); // Read the file as a data URL
        }
    }

    function checkFileUpload() {
        const gcashMessage = document.getElementById('gcash-message');
        const fileInput = document.getElementById('file-input').files.length > 0;
        const placeOrderButton = document.getElementById('place-order-button');

        // Enable Place Order button only if a file is uploaded
        if (gcashMessage.classList.contains('hidden') === false && fileInput) {
            placeOrderButton.disabled = false;
        } else {
            placeOrderButton.disabled = true;
        }
    }

    // Add click event to trigger file input
    document.querySelector('[for="file-input"]').addEventListener('click', function() {
        document.getElementById('file-input').click();
    });
</script>





<script>
    // Select all payment option labels
    const paymentOptions = document.querySelectorAll('.payment-option');

    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            paymentOptions.forEach(opt => {
                opt.classList.remove('bg-green-200');
            });
            // Add active class to the selected option
            this.classList.add('bg-green-200');
        });
    });
</script>









            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php
$stmt->close();
$connection->close();
?>
