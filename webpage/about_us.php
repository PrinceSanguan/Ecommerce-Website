
<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "wbsif_db";


$connection = new mysqli($servername, $username, $password, $database);

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("location: /wbsif/webpage/Home.php");
    exit;
}

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '' ;  // Fallback if no name is provided
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="script.js"></script>
    <style>



    </style>
</head>
<body>
    <div class="bg-[url('images/bg1.png')] bg-center bg-cover bg-fixed min-h-screen w-full pt-6 flex flex-col"
    >
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
                                <?php echo htmlspecialchars($user_email); ?></p>
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


            <div class="px-20 py-3 flex flex-wrap mt-20">
                <div class="w-full max-w-full mx-auto mt-5 bg-white rounded-lg p-2" style="border-top: 4px solid #333;">
                    <div class="flex justify-center mb-5">
                        <h1 class="text-4xl">About Us</h1> 
                    </div>
                    <div class="w-full max-w-full mx-auto mt-5">
                        <div class="border-t border-zinc-400"></div>
                    </div>
                    <div>
                        <!-- caption -->
                    </div>
                </div>
            </div>
            
            

            <footer class="w-full text-center py-4 text-white bg-gray-800">
                <p>&copy; Web-Based Sales & Inventory with Forecasting.</p>
            </footer>
    
       
</body>
</html>
