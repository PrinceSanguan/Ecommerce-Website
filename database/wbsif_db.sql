-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2024 at 03:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wbsif_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_list`
--

CREATE TABLE `admin_list` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_list`
--

INSERT INTO `admin_list` (`id`, `firstname`, `lastname`, `email`, `password`, `created_at`, `status`, `avatar`) VALUES
(5, 'adrian', 'alonzo', 'rodman32222@gmail.com', '$2y$10$TmbcUz7X6H/NCo/vtsPvj.f7S4yA7hPiDK6P6yfufXtwOG7wCUas.', '2024-10-16 13:32:00', 'active', NULL),
(8, 'kenshi', 'alonzo', 'kenshi863@gmail.com', '$2y$10$ERfM2dNVwrt1chpVH.lqKO/aF4KuBDhfE27LGnGEhCGxM.j.IduOC', '2024-10-16 13:35:54', 'active', NULL),
(10, 'katrinasss', 'alonzoww', 'katrina221333tt@gmail.com', '$2y$10$euiCKZIE/DVO3fJa1CzOke1VHu2ClzFQrwzHHEeI6UwaVnj1nvkhK', '2024-10-17 14:52:35', 'active', NULL),
(11, 'R', 'K E', 'rke49127@gmail.com', '$2y$10$tuccBVLyWMMMeyN/RJICWOeWa3dlI1HZSWgnPl7I8jLOokBGKc.Oq', '2024-10-22 00:27:27', 'active', NULL),
(13, 'adrian', 'alonzo', 'adrianalonzo866663@gmail.com', '$2y$10$ekbmMA6gUYfZt2SnGfm3q.hWd.ivLC3oTt81KFG46xdebtVITSSOe', '2024-10-22 00:51:59', 'active', 'uploads/6716f7356f533_Screenshot_16-10-2024_152631_.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `brand_list`
--

CREATE TABLE `brand_list` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand_list`
--

INSERT INTO `brand_list` (`id`, `brand_name`, `date_added`, `logo`, `status`) VALUES
(34, 'Bosch', '2024-10-08 02:05:57', NULL, 'active'),
(35, 'NGK', '2024-10-08 02:06:08', NULL, 'active'),
(36, 'K&N', '2024-10-08 02:06:20', NULL, 'active'),
(37, 'Brembo', '2024-10-08 02:06:30', NULL, 'active'),
(38, 'Gates', '2024-10-08 02:06:47', NULL, 'active'),
(39, 'Denso', '2024-10-08 02:06:56', NULL, 'active'),
(40, 'Valeo', '2024-10-08 02:07:06', NULL, 'active'),
(41, 'ACDelco', '2024-10-08 02:07:16', NULL, 'active'),
(42, 'Garret', '2024-10-08 02:07:26', NULL, 'active'),
(43, 'Exedy', '2024-10-08 02:07:34', NULL, 'active'),
(44, 'Mahle', '2024-10-08 02:07:43', NULL, 'active'),
(45, 'Optima', '2024-10-08 02:07:54', NULL, 'active'),
(46, 'Delphi ', '2024-10-08 02:08:05', NULL, 'active'),
(47, 'Cardone', '2024-10-08 02:08:37', NULL, 'active'),
(48, 'Spectra', '2024-10-08 02:08:51', NULL, 'active'),
(49, 'Bilstein', '2024-10-08 02:09:00', NULL, 'active'),
(50, 'Fel-Pro', '2024-10-08 02:09:11', NULL, 'active'),
(51, 'Walker', '2024-10-08 02:09:23', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_email`, `product_id`, `product_name`, `quantity`, `price`, `created_at`) VALUES
(1, 'joshoa@gmail.com', 32, 'Oil', 1, 566.00, '2024-10-06 10:28:02'),
(2, 'joshoa@gmail.com', 31, 'Spark Plugs', 1, 6000.00, '2024-10-06 10:29:36'),
(3, 'joshoa@gmail.com', 31, 'Spark Plugs', 1, 6000.00, '2024-10-06 10:32:07'),
(4, 'joshoa@gmail.com', 30, 'Tire', 1, 400.00, '2024-10-06 10:33:25'),
(5, 'joshoa@gmail.com', 31, 'Spark Plugs', 1, 6000.00, '2024-10-06 10:33:42'),
(6, 'joshoa@gmail.com', 32, 'Oil', 1, 566.00, '2024-10-06 10:33:44'),
(7, 'joshoa@gmail.com', 33, 'Crash Guard', 1, 455.00, '2024-10-06 10:33:45'),
(8, 'joshoa@gmail.com', 42, 'screw', 1, 100.00, '2024-10-06 10:33:47'),
(9, 'joshoa@gmail.com', 41, 'muffle', 1, 300.00, '2024-10-06 10:33:48'),
(10, 'joshoa@gmail.com', 34, 'Shock', 1, 457.00, '2024-10-06 10:33:49'),
(11, 'joshoa@gmail.com', 30, 'Tire', 1, 400.00, '2024-10-06 10:33:50'),
(12, 'joshoa@gmail.com', 31, 'Spark Plugs', 1, 6000.00, '2024-10-06 10:35:06'),
(13, 'joshoa@gmail.com', 32, 'Oil', 1, 566.00, '2024-10-06 10:35:08'),
(14, 'joshoa@gmail.com', 30, 'Tire', 1, 400.00, '2024-10-06 10:35:37'),
(15, 'joshoa@gmail.com', 31, 'Spark Plugs', 1, 6000.00, '2024-10-06 10:35:38'),
(16, 'joshoa@gmail.com', 32, 'Oil', 1, 566.00, '2024-10-06 10:35:40'),
(17, 'joshoa@gmail.com', 33, 'Crash Guard', 1, 455.00, '2024-10-06 10:35:41'),
(18, 'joshoa@gmail.com', 30, 'Tire', 1, 400.00, '2024-10-06 10:37:47'),
(19, 'joshoa@gmail.com', 31, 'Spark Plugs', 1, 6000.00, '2024-10-06 10:42:45'),
(20, 'joshoa@gmail.com', 32, 'Oil', 1, 566.00, '2024-10-06 10:48:21'),
(21, 'joshoa@gmail.com', 30, 'Tire', 1, 400.00, '2024-10-06 10:58:28'),
(22, 'joshoa@gmail.com', 33, 'Crash Guard', 1, 455.00, '2024-10-06 11:01:20'),
(23, 'joshoa@gmail.com', 32, 'Oil', 1, 566.00, '2024-10-06 11:01:22'),
(24, 'joshoa@gmail.com', 42, 'screw', 1, 100.00, '2024-10-06 11:01:24'),
(25, 'joshoa@gmail.com', 30, 'Tire', 1, 400.00, '2024-10-06 11:02:31'),
(26, 'adrianalonzo863@gmail.com', 30, 'Tire', 1, 400.00, '2024-10-08 01:49:52'),
(27, 'adrianalonzo863@gmail.com', 31, 'Spark Plugs', 1, 6000.00, '2024-10-08 01:49:55'),
(28, 'adrianalonzo863@gmail.com', 43, 'Air Filter', 1, 1400.00, '2024-10-08 07:29:12'),
(29, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 07:55:03'),
(30, 'lloyd@gmail.com', 43, 'Air Filter', 1, 1400.00, '2024-10-08 07:58:37'),
(31, 'lloyd@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 07:58:39'),
(32, 'lloyd@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 07:58:40'),
(33, 'lloyd@gmail.com', 46, 'Brake Pads', 1, 2000.00, '2024-10-08 07:58:43'),
(34, 'lloyd@gmail.com', 50, 'Fuel Injector', 1, 6720.00, '2024-10-08 07:58:45'),
(35, 'lloyd@gmail.com', 49, 'CV Axle Shaft', 1, 7280.00, '2024-10-08 07:58:47'),
(36, 'lloyd@gmail.com', 48, 'Clutch Kit', 1, 11200.00, '2024-10-08 07:58:48'),
(37, 'adrianalonzo863@gmail.com', 46, 'Brake Pads', 1, 2000.00, '2024-10-08 08:16:31'),
(38, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 08:16:34'),
(39, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 08:16:36'),
(40, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 08:18:20'),
(41, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 08:18:51'),
(42, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 08:29:13'),
(43, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 08:31:17'),
(44, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 08:31:42'),
(45, 'adrianalonzo863@gmail.com', 49, 'CV Axle Shaft', 1, 7280.00, '2024-10-08 08:32:29'),
(46, 'adrianalonzo863@gmail.com', 48, 'Clutch Kit', 1, 11200.00, '2024-10-08 08:34:38'),
(47, 'adrianalonzo863@gmail.com', 43, 'Air Filter', 1, 1400.00, '2024-10-08 08:35:41'),
(48, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 08:36:10'),
(49, 'adrianalonzo863@gmail.com', 50, 'Fuel Injector', 1, 6720.00, '2024-10-08 08:36:53'),
(50, 'adrianalonzo863@gmail.com', 46, 'Brake Pads', 1, 2000.00, '2024-10-08 08:36:54'),
(51, 'adrianalonzo863@gmail.com', 46, 'Brake Pads', 1, 2000.00, '2024-10-08 08:37:26'),
(52, 'adrianalonzo863@gmail.com', 55, 'Piston Ring Set', 1, 3920.00, '2024-10-08 08:44:12'),
(53, 'adrianalonzo863@gmail.com', 48, 'Clutch Kit', 1, 11200.00, '2024-10-08 08:44:15'),
(54, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 08:44:48'),
(55, 'adrianalonzo863@gmail.com', 52, 'Ignition Coil', 1, 3080.00, '2024-10-08 08:45:56'),
(56, 'adrianalonzo863@gmail.com', 53, 'Oil Filter', 1, 15.00, '2024-10-08 08:46:46'),
(57, 'adrianalonzo863@gmail.com', 50, 'Fuel Injector', 1, 6720.00, '2024-10-08 08:51:20'),
(58, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 08:51:45'),
(59, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 08:51:46'),
(60, 'adrianalonzo863@gmail.com', 46, 'Brake Pads', 1, 2000.00, '2024-10-08 08:51:48'),
(61, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 08:52:58'),
(62, 'adrianalonzo863@gmail.com', 48, 'Clutch Kit', 1, 11200.00, '2024-10-08 08:53:00'),
(63, 'adrianalonzo863@gmail.com', 56, 'Radiator', 1, 7840.00, '2024-10-08 08:53:03'),
(64, 'adrianalonzo863@gmail.com', 49, 'CV Axle Shaft', 1, 7280.00, '2024-10-08 08:55:08'),
(65, 'adrianalonzo863@gmail.com', 64, 'WWW', 1, 34.00, '2024-10-08 08:55:51'),
(66, 'adrianalonzo863@gmail.com', 49, 'CV Axle Shaft', 1, 7280.00, '2024-10-08 08:56:10'),
(67, 'adrianalonzo863@gmail.com', 46, 'Brake Pads', 1, 2000.00, '2024-10-08 08:56:12'),
(68, 'adrianalonzo863@gmail.com', 58, 'Spark Plug', 1, 448.00, '2024-10-08 08:57:39'),
(69, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 09:00:14'),
(70, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 09:08:21'),
(71, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 09:09:35'),
(72, 'adrianalonzo863@gmail.com', 62, 'Water Pump', 1, 3360.00, '2024-10-08 09:31:37'),
(73, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 09:32:34'),
(74, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 09:33:07'),
(75, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 09:34:11'),
(76, 'adrianalonzo863@gmail.com', 50, 'Fuel Injector', 1, 6720.00, '2024-10-08 09:35:41'),
(77, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 09:38:14'),
(78, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 09:39:13'),
(79, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 09:39:43'),
(80, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 09:39:58'),
(81, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 09:44:38'),
(82, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 09:48:47'),
(83, 'adrianalonzo863@gmail.com', 46, 'Brake Pads', 1, 2000.00, '2024-10-08 09:55:08'),
(84, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 09:55:58'),
(85, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 09:56:32'),
(86, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 09:57:55'),
(87, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 10:02:00'),
(88, 'adrianalonzo863@gmail.com', 64, 'WWW', 1, 34.00, '2024-10-08 10:07:35'),
(89, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 10:12:43'),
(90, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 10:13:39'),
(91, 'adrianalonzo863@gmail.com', 59, 'Starter Motor', 1, 8400.00, '2024-10-08 10:14:06'),
(92, 'adrianalonzo863@gmail.com', 43, 'Air Filter', 1, 1400.00, '2024-10-08 10:24:45'),
(93, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 10:26:10'),
(94, 'adrianalonzo863@gmail.com', 51, 'Head Gasket', 1, 1960.00, '2024-10-08 10:32:28'),
(95, 'adrianalonzo863@gmail.com', 60, 'Timing Belt', 1, 4200.00, '2024-10-08 10:33:15'),
(96, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 10:35:24'),
(97, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 10:39:05'),
(98, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 10:39:30'),
(99, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 10:40:01'),
(100, 'adrianalonzo863@gmail.com', 43, 'Air Filter', 1, 1400.00, '2024-10-08 10:56:14'),
(101, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 10:56:18'),
(102, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 11:00:52'),
(103, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 11:01:45'),
(104, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 11:06:40'),
(105, 'adrianalonzo863@gmail.com', 46, 'Brake Pads', 1, 2000.00, '2024-10-08 11:06:42'),
(106, 'adrianalonzo863@gmail.com', 46, 'Brake Pads', 1, 2000.00, '2024-10-08 11:23:59'),
(107, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 11:24:00'),
(108, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 11:24:02'),
(109, 'adrianalonzo863@gmail.com', 43, 'Air Filter', 1, 1400.00, '2024-10-08 11:24:03'),
(110, 'adrianalonzo863@gmail.com', 46, 'Brake Pads', 1, 2000.00, '2024-10-08 11:36:49'),
(111, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 11:36:50'),
(112, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 11:36:53'),
(113, 'adrianalonzo863@gmail.com', 43, 'Air Filter', 1, 1400.00, '2024-10-08 11:36:55'),
(114, 'adrianalonzo863@gmail.com', 48, 'Clutch Kit', 1, 11200.00, '2024-10-08 11:37:07'),
(115, 'adrianalonzo863@gmail.com', 49, 'CV Axle Shaft', 1, 7280.00, '2024-10-08 11:37:09'),
(116, 'adrianalonzo863@gmail.com', 50, 'Fuel Injector', 1, 6720.00, '2024-10-08 11:37:11'),
(117, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-08 11:37:58'),
(118, 'adrianalonzo863@gmail.com', 43, 'Air Filter', 1, 1400.00, '2024-10-08 11:38:00'),
(119, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-08 11:38:02'),
(120, 'adrianalonzo863@gmail.com', 46, 'Brake Pads', 1, 2000.00, '2024-10-08 11:38:04'),
(121, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-16 08:10:13'),
(122, 'joshoa@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-16 13:58:34'),
(123, 'rodman@gmail.com', 43, 'Air Filter', 1, 1400.00, '2024-10-17 15:48:32'),
(124, 'joshoa@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-18 12:20:13'),
(125, 'adrianalonzo863@gmail.com', 53, 'Oil Filter', 1, 15.00, '2024-10-20 07:41:08'),
(126, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-20 07:41:56'),
(127, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-20 07:42:28'),
(128, 'adrianalonzo863@gmail.com', 46, 'Brake Pads', 1, 2000.00, '2024-10-20 07:42:30'),
(129, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-21 16:35:34'),
(130, 'adrianalonzo863@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-21 16:35:36'),
(131, 'adrnalnz55555@gmail.com', 45, 'Battery', 1, 10080.00, '2024-10-22 00:13:38'),
(132, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-22 01:47:14'),
(133, 'adrianalonzo863@gmail.com', 44, 'Alternator', 1, 12880.00, '2024-10-22 01:47:40');

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `category_name`, `date_added`, `status`) VALUES
(17, 'Engine', '2024-10-08 02:10:02', 'active'),
(18, 'Ignition', '2024-10-08 02:10:16', 'active'),
(19, 'Intake', '2024-10-08 02:10:34', 'active'),
(20, 'Braking', '2024-10-08 02:10:45', 'active'),
(21, 'Gates', '2024-10-08 02:10:55', 'active'),
(22, 'Fuel System', '2024-10-08 07:22:51', 'active'),
(23, 'Electrical', '2024-10-08 07:23:04', 'active'),
(24, 'Cooling System', '2024-10-08 07:23:17', 'active'),
(25, 'Forced Induction', '2024-10-08 07:23:29', 'active'),
(26, 'Transmission', '2024-10-08 07:23:41', 'active'),
(27, 'Engine', '2024-10-08 07:23:52', 'active'),
(28, 'Ignition', '2024-10-08 07:24:08', 'active'),
(29, 'Emission Control', '2024-10-08 07:24:18', 'active'),
(30, 'Suspension', '2024-10-08 07:24:29', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `client_list`
--

CREATE TABLE `client_list` (
  `id` int(11) NOT NULL,
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
  `otp` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_list`
--

INSERT INTO `client_list` (`id`, `firstname`, `middlename`, `lastname`, `gender`, `phone`, `address`, `email`, `password`, `date_created`, `reset_token`, `status`, `is_active`, `verified`, `verify_token`, `mobile_number`, `otp`) VALUES
(2, 'joshoa', 'caagbay', 'siena', 'Male', '09493672526', 'blk17 lot 34 purok 4 San Martin II Area C Sanpedro Street', 'joshoa@gmail.com', '$2y$10$.E2k5euW8czEiNNDZyBJsOqIIHDNOq1vv5lTBjQNdFbsuSiVZi7DG', '2024-09-17 16:09:08', NULL, 'active', 1, 0, NULL, NULL, NULL),
(4, 'katrina', 'balimbing', 'alonzo', 'Female', '0956784233', 'blk 17 lot knoffrfw', 'katrina@gmail.com', '$2y$10$/LLcEAoA6Su1jru2UnaD2.sYrRZyv/H3EFmp2uD1iAqXmYSeAvoEG', '2024-09-18 11:47:59', NULL, 'active', 1, 0, NULL, NULL, NULL),
(5, 'leah', 'dillera', 'sansano', 'Female', '09166883413', 'Brgy. St. Martin de Porres, CSJDMI, Bulacan', 'sansanoleah@gmail.com', '$2y$10$EjIAJ8JeUgDwJP88LilHQumAzKmuYK4KqotLtMGbAKp/gLejqajKO', '2024-10-01 00:46:58', NULL, 'active', 1, 0, NULL, NULL, NULL),
(31, 'jayrius', 'balimbing', 'valdez', 'Male', '0908924929', 'sjffsfif', 'valdezjayrius01@gmail.com', '$2y$10$GDJhlGkzfN0jwPXSxOzKm.AVDMPxZOm7LCO2pNBqiJi/xGCYzgjq2', '2024-10-20 12:50:41', NULL, 'active', 1, 0, NULL, NULL, NULL),
(71, 'katrina', 'R', 'alonzo', 'Female', '967487333', 'weFEEFEF', 'adrnalnz55555@gmail.com', '$2y$10$bC79ZATo2Kbbv2THYQClQOmIrogC9eb0Zs69rXHrJElcPWPyfGDyK', '2024-10-21 23:50:41', NULL, 'active', 1, 0, NULL, NULL, NULL),
(72, 'adrian', 'balimbing', 'alonzo', 'Male', '3089782973232', 'dgeeef', 'adrianalonzo863@gmail.com', '$2y$10$Gj8pYaWj0dUCM1ZEinysDevbjPYBGg.y2j0T1RPnTHfJWpO5qTNM6', '2024-10-22 01:25:02', NULL, 'active', 1, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `total_amount`, `payment_method`, `order_date`, `status`) VALUES
(58, NULL, 1, 12880.00, 'cod', '2024-10-08 12:35:30', 'Pending'),
(59, NULL, 1, 10080.00, 'gcash', '2024-10-08 12:39:37', 'Pending'),
(60, NULL, 1, 12880.00, 'cod', '2024-10-08 12:40:10', 'Pending'),
(61, NULL, 1, 12880.00, 'cod', '2024-10-08 12:40:10', 'Pending'),
(62, NULL, 1, 11480.00, 'gcash', '2024-10-08 12:56:27', 'Pending'),
(63, NULL, 1, 11480.00, 'gcash', '2024-10-08 12:56:27', 'Pending'),
(64, NULL, 1, 40320.00, 'gcash', '2024-10-08 13:01:05', 'Pending'),
(65, NULL, 1, 10080.00, 'pickup', '2024-10-08 13:01:54', 'Pending'),
(66, NULL, 1, 10080.00, 'pickup', '2024-10-08 13:01:54', 'Pending'),
(67, NULL, 1, 10080.00, 'pickup', '2024-10-08 13:04:33', 'Pending'),
(68, NULL, 1, 10080.00, 'pickup', '2024-10-08 13:04:33', 'Pending'),
(69, NULL, 1, 10080.00, 'pickup', '2024-10-08 13:04:33', 'Pending'),
(70, NULL, 1, 10080.00, 'pickup', '2024-10-08 13:06:25', 'Pending'),
(71, NULL, 1, 14080.00, 'gcash', '2024-10-08 13:06:50', 'Pending'),
(72, NULL, 1, 26360.00, 'cod', '2024-10-08 13:24:12', 'Pending'),
(73, NULL, 1, 73960.00, 'cod', '2024-10-08 13:37:22', 'Pending'),
(74, '546858', 1, 12880.00, 'pickup', '2024-10-16 10:10:46', 'Pending'),
(75, '956206', 1, 12880.00, 'pickup', '2024-10-16 10:11:15', 'Pending'),
(76, '860814', 2, 12880.00, 'pickup', '2024-10-18 14:20:26', 'Pending'),
(77, '143517', 1, 15.00, 'gcash', '2024-10-20 09:41:31', 'Pending'),
(78, '256681', 1, 12880.00, 'pickup', '2024-10-20 09:42:03', 'Pending'),
(79, '441220', 1, 12080.00, 'gcash', '2024-10-20 09:42:42', 'Pending'),
(80, '788275', 70, 22960.00, 'pickup', '2024-10-21 18:35:47', 'Pending'),
(81, '721721', 70, 22960.00, 'pickup', '2024-10-21 18:35:56', 'Pending'),
(82, '254965', 72, 12880.00, 'pickup', '2024-10-22 03:47:48', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_name`, `quantity`, `price`, `image_url`) VALUES
(8, 58, 'Alternator', 1, 12, NULL),
(9, 59, 'Battery', 1, 10, NULL),
(10, 60, 'Alternator', 1, 12, NULL),
(11, 61, 'Alternator', 1, 12, NULL),
(12, 62, 'Air Filter', 1, 1, NULL),
(13, 62, 'Battery', 1, 10, NULL),
(14, 63, 'Air Filter', 1, 1, NULL),
(15, 63, 'Battery', 1, 10, NULL),
(16, 64, 'Battery', 4, 10, NULL),
(17, 65, 'Battery', 1, 10, NULL),
(18, 66, 'Battery', 1, 10, NULL),
(19, 71, 'Battery', 1, 10, NULL),
(20, 71, 'Brake Pads', 2, 2, NULL),
(21, 72, 'Brake Pads', 1, 2, NULL),
(22, 72, 'Battery', 1, 10, NULL),
(23, 72, 'Alternator', 1, 12, NULL),
(24, 72, 'Air Filter', 1, 1, NULL),
(25, 73, 'Brake Pads', 1, 2, NULL),
(26, 73, 'Battery', 1, 10, NULL),
(27, 73, 'Alternator', 1, 12, NULL),
(28, 73, 'Air Filter', 1, 1, NULL),
(29, 73, 'Catalytic Converter', 1, 22, NULL),
(30, 73, 'Clutch Kit', 1, 11, NULL),
(31, 73, 'CV Axle Shaft', 1, 7, NULL),
(32, 73, 'Fuel Injector', 1, 6, NULL),
(33, 74, NULL, 1, 12880, NULL),
(34, 76, NULL, 1, 12880, NULL),
(35, 77, NULL, 1, 15, NULL),
(36, 78, NULL, 1, 12880, NULL),
(37, 79, NULL, 1, 10080, NULL),
(38, 79, NULL, 1, 2000, NULL),
(39, 80, NULL, 1, 12880, NULL),
(40, 80, NULL, 1, 10080, NULL),
(41, 82, NULL, 1, 12880, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiry` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expiry`, `created_at`) VALUES
(1, 'adrnalnz@gmail.com', 'bd25caf016281a39ed6b4ea176710f71a1982979c69a40104aa0673ef0c10008cb249299d80e453002b126c8dff920906a86', '2024-10-20 18:19:42', '2024-10-20 15:19:42'),
(2, 'adrnalnz@gmail.com', 'de3f5de980ab5339dbe33d8278c2a2b3b8c7650830889b25e66ce3380b7b603472e5c305722ba1188b5351881322b3cf1eee', '2024-10-20 18:20:51', '2024-10-20 15:20:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `id` int(11) NOT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `brand` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_list`
--

INSERT INTO `product_list` (`id`, `date_created`, `brand`, `category`, `name`, `price`, `status`, `action`, `description`, `image`, `quantity`) VALUES
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
(62, '2024-10-08 15:53:44', 'ACDelco', 'Cooling System', 'Water Pump', 3360.00, 'Active', '', 'Circulates coolant through the engine and radiator, maintaining proper operating temperature.', 'Water_Pump-removebg-preview.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `stock_history`
--

CREATE TABLE `stock_history` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_list`
--
ALTER TABLE `admin_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `brand_list`
--
ALTER TABLE `brand_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_list`
--
ALTER TABLE `client_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `firstname` (`firstname`),
  ADD KEY `lastname` (`lastname`),
  ADD KEY `phone` (`phone`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_history`
--
ALTER TABLE `stock_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_list`
--
ALTER TABLE `admin_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `brand_list`
--
ALTER TABLE `brand_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `client_list`
--
ALTER TABLE `client_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `stock_history`
--
ALTER TABLE `stock_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `stock_history`
--
ALTER TABLE `stock_history`
  ADD CONSTRAINT `stock_history_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
