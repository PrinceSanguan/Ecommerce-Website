
CREATE TABLE `admin_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `admin_list`
--

INSERT INTO `admin_list` (`id`, `firstname`, `lastname`, `email`, `password`, `created_at`, `status`, `avatar`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com', '$2y$10$tuccBVLyWMMMeyN/RJICWOeWa3dlI1HZSWgnPl7I8jLOokBGKc.Oq', '2024-10-16 13:32:00', 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `brand_list`
--

CREATE TABLE `brand_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `brand_list`
--

INSERT INTO `brand_list` (`id`, `brand_name`, `date_added`, `logo`, `status`) VALUES
(1, 'Bosch', '2024-10-08 02:05:57', NULL, 'active'),
(2, 'NGK', '2024-10-08 02:06:08', NULL, 'active'),
(3, 'K&N', '2024-10-08 02:06:20', NULL, 'active'),
(4, 'Brembo', '2024-10-08 02:06:30', NULL, 'active'),
(5, 'Gates', '2024-10-08 02:06:47', NULL, 'active'),
(6, 'Denso', '2024-10-08 02:06:56', NULL, 'active'),
(7, 'Valeo', '2024-10-08 02:07:06', NULL, 'active'),
(8, 'ACDelco', '2024-10-08 02:07:16', NULL, 'active'),
(9, 'Garret', '2024-10-08 02:07:26', NULL, 'active'),
(10, 'Exedy', '2024-10-08 02:07:34', NULL, 'active'),
(11, 'Mahle', '2024-10-08 02:07:43', NULL, 'active'),
(12, 'Optima', '2024-10-08 02:07:54', NULL, 'active'),
(13, 'Delphi ', '2024-10-08 02:08:05', NULL, 'active'),
(14, 'Cardone', '2024-10-08 02:08:37', NULL, 'active'),
(15, 'Spectra', '2024-10-08 02:08:51', NULL, 'active'),
(16, 'Bilstein', '2024-10-08 02:09:00', NULL, 'active'),
(17, 'Fel-Pro', '2024-10-08 02:09:11', NULL, 'active'),
(18, 'Walker', '2024-10-08 02:09:23', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `cart`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `category_name`, `date_added`, `status`) VALUES
(1, 'Engine', '2024-10-08 02:10:02', 'active'),
(2, 'Ignition', '2024-10-08 02:10:16', 'active'),
(3, 'Intake', '2024-10-08 02:10:34', 'active'),
(4, 'Braking', '2024-10-08 02:10:45', 'active'),
(5, 'Gates', '2024-10-08 02:10:55', 'active'),
(6, 'Fuel System', '2024-10-08 07:22:51', 'active'),
(7, 'Electrical', '2024-10-08 07:23:04', 'active'),
(8, 'Cooling System', '2024-10-08 07:23:17', 'active'),
(9, 'Forced Induction', '2024-10-08 07:23:29', 'active'),
(10, 'Transmission', '2024-10-08 07:23:41', 'active'),
(11, 'Engine', '2024-10-08 07:23:52', 'active'),
(12, 'Ignition', '2024-10-08 07:24:08', 'active'),
(13, 'Emission Control', '2024-10-08 07:24:18', 'active'),
(14, 'Suspension', '2024-10-08 07:24:29', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `client_list`
--

CREATE TABLE `client_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `is_active` tinyint(1) DEFAULT 1,
  `verified` tinyint(1) DEFAULT 0,
  `verify_token` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `otp` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `client_list`
--

INSERT INTO `client_list` (`id`, `firstname`, `middlename`, `lastname`, `gender`, `phone`, `address`, `email`, `password`, `date_created`, `reset_token`, `status`, `is_active`, `verified`, `verify_token`, `mobile_number`, `otp`) VALUES
(1, 'client', 'client', 'client', 'Male', '09493672526', 'blk17 lot 34 purok 4 San Martin II Area C Sanpedro Street', 'client@gmail.com', '$2y$10$tuccBVLyWMMMeyN/RJICWOeWa3dlI1HZSWgnPl7I8jLOokBGKc.Oq', '2024-09-17 16:09:08', NULL, 'active', 1, 0, NULL, NULL, NULL);
-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `orders`
--


--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
);
--
-- Dumping data for table `order_items`
--

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiry` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `password_resets`
--


-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_created` datetime DEFAULT current_timestamp(),
  `brand` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `product_list`
--

/* INSERT INTO `product_list` (`id`, `date_created`, `brand`, `category`, `name`, `price`, `status`, `action`, `description`, `image`, `quantity`) VALUES
(43, '2024-10-08 15:26:37', 'Bosch', 'Engine', 'Air Filter', 1400.00, 'Active', '', 'Filters out dust and debris from the air before it enters the engine, ensuring clean air intake.', 'Air_Filter-removebg-preview.png', 0),
(44, '2024-10-08 15:28:38', 'Bosch', 'Electrical', 'Alternator', 12880.00, 'Active', '', 'Generates electrical power to charge the battery and run electrical systems when the engine is running.', 'Alternator-removebg-preview (1).png', 0),
(45, '2024-10-08 15:30:24', 'Optima', 'Electrical', 'Battery', 10080.00, 'Active', '', 'Provides the initial electrical power needed to start the vehicle and power electrical components.', 'Battery-removebg-preview.png', 0),
(46, '2024-10-08 15:33:20', 'Brembo', 'Braking', 'Brake Pads', 2000.00, 'Active', '', 'Pads that create friction against the brake disc to slow or stop the vehicle effectively.', 'Brake_Pads-removebg-preview.png', 0),
(47, '2024-10-08 15:37:28', 'Walker', 'Braking', 'Catalytic Converter', 22400.00, 'Active', '', 'Converts harmful pollutants in the exhaust gases into less harmful emissions before they exit the tailpipe.', 'Catalytic_Converter-removebg-preview.png', 0),
(48, '2024-10-08 15:38:30', 'Exedy', 'Transmission', 'Clutch Kit', 11200.00, 'Active', '', 'Includes a clutch disc, pressure plate, and release bearing for smooth gear shifting in manual vehicles.', 'Clutch_Kit-removebg-preview.png', 0),
(49, '2024-10-08 15:39:22', 'Cardone', 'Suspension', 'CV Axle Shaft', 7280.00, 'Active', '', 'Transfers power from the transmission to the wheels, allowing the vehicle to move and steer.', 'CV_Axle_Shaft-removebg-preview.png', 0),
(50, '2024-10-08 15:40:14', 'Denso', 'Fuel System', 'Fuel Injector', 6720.00, 'Active', '', 'Delivers precise amounts of fuel to the engine\'s cylinders for optimal combustion and efficiency.', 'Fuel_Injector-removebg-preview.png', 0),
(51, '2024-10-08 15:41:40', 'Fel-Pro', 'Engine', 'Head Gasket', 1960.00, 'Active', '', 'Seals the engine block and cylinder head, preventing leaks of oil, coolant, and combustion gases.', 'Head_Gasket-removebg-preview.png', 0),
(52, '2024-10-08 15:43:05', 'Delphi ', 'Ignition', 'Ignition Coil', 3080.00, 'Active', '', 'Converts low voltage from the battery to high voltage needed to create a spark at the spark plug.', 'Ignition_Coil-removebg-preview.png', 0),
(53, '2024-10-08 15:44:15', 'Bosch', 'Engine', 'Oil Filter', 15.00, 'Active', '', 'A component that removes contaminants from engine oil, protecting engine parts and improving performance.', 'Oil_Filter-removebg-preview.png', 0),
(54, '2024-10-08 15:45:58', 'Bosch', 'Emission Control', 'Oxygen Sensor', 2000.00, 'Active', '', 'Measures oxygen levels in the exhaust gases to ensure proper air-fuel ratio for efficient combustion.', 'Oxygen_Sensor-removebg-preview (2).png', 0),
(55, '2024-10-08 15:46:42', 'Mahle', 'Engine', 'Piston Ring Set', 3920.00, 'Active', '', 'Seals the combustion chamber, regulates oil consumption, and helps dissipate heat from the piston.', 'Piston_Ring_Set-removebg-preview.png', 0),
(56, '2024-10-08 15:47:37', 'Spectra', 'Cooling System', 'Radiator', 7840.00, 'Active', '', 'A heat exchanger that dissipates heat from the coolant, keeping the engine temperature regulated.', 'Radiator-removebg-preview.png', 0),
(57, '2024-10-08 15:48:25', 'Bilstein', 'Suspension', 'Shock Absorbers', 11760.00, 'Active', '', 'Dampens vibrations from the road, providing a smoother and more controlled ride.', 'Shock_Absorbers-removebg-preview.png', 0),
(58, '2024-10-08 15:50:02', 'NGK', 'Ignition', 'Spark Plug', 448.00, 'Active', '', 'An ignition device that creates a spark to ignite the air-fuel mixture in the engine\'s cylinders.', 'Spark_Plug-removebg-preview.png', 0),
(59, '2024-10-08 15:50:52', 'Valeo', 'Electrical', 'Starter Motor', 8400.00, 'Active', '', 'An electrical motor that spins the engine on startup, allowing it to begin combustion.', 'Starter_Motor-removebg-preview.png', 0),
(60, '2024-10-08 15:52:19', 'Gates', 'Forced Induction', 'Timing Belt', 4200.00, 'Active', '', 'A belt that synchronizes the engine\'s camshaft and crankshaft, ensuring proper engine timing.', 'Timing_Belt-removebg-preview.png', 0),
(61, '2024-10-08 15:53:03', 'Garret', 'Forced Induction', 'Turbo Charger', 50400.00, 'Active', '', 'A turbine-driven device that forces extra air into the combustion chamber, boosting engine power.', 'Turbocharger-removebg-preview.png', 0),
(62, '2024-10-08 15:53:44', 'ACDelco', 'Cooling System', 'Water Pump', 3360.00, 'Active', '', 'Circulates coolant through the engine and radiator, maintaining proper operating temperature.', 'Water_Pump-removebg-preview.png', 0); */

-- --------------------------------------------------------

--
-- Table structure for table `stock_history`
--

CREATE TABLE `stock_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`)
);

--
-- Indexes for dumped tables
--

--

