-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2024 at 04:09 AM
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
-- Database: `week18`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pw` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `pw`) VALUES
(1, 'alok', 'alok@jumpsut', '$2y$10$6xh3j5wIn8NG1YUzZe70B.6Qkvp1VwZTVBSjkmT7KDQLIX.0vlm5i'),
(2, 'madara', 'madara@kalah', '$2y$10$ZoZM4HIYTcB4rSC9QGdZS.TVEWY4G7eYn548fLsPdvg4/0ZrhSY8a'),
(4, 'rifqy', 'rifqy@rajaiblis', '$2y$10$DaHxDlnbyu51qoqQT0wSreiBNEPMGJOAzWf9gs9S/suNayVACwQau'),
(6, 'Malik', 'Malik@gmail.com', '$2y$10$Vp3YOKgiRN96RUJj2BccA.W2agbmmCTY9drpvwH9GseKEu3p3cOau'),
(9, 'rayhan', 'sembarang@gmail.com', '$2y$10$0UIjhLq2yEYeEmEzcZL8Te715a1j0meHp72C8BufWZuz41GChr7Ne'),
(10, 'gibraltar', 'gibral@gmail.com', '$2y$10$Z.rdJXq57iyjU5l/lfuWAeJb/t5SIVb5CVoO0MRwiABZ4jenGkTk.'),
(11, 'Anjay', 'saya@gmail.com', '$2y$10$mLqgCFgur32phAISg8dogOjte144paxIV/VzNor0sh0RLTMCAy5KC');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `nama`) VALUES
(3, 'Beverage'),
(4, 'Main Course'),
(5, 'Appetizers'),
(6, 'Snacks'),
(7, 'Other'),
(8, 'Ahay'),
(10, 'Cihuy');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `nama`, `email`, `phone`, `alamat`) VALUES
(1, 'Madara Senju', 'alok@jumpsutknock', '0833299', 'isekai'),
(2, 'rayhan', 'rayhan@gmail.com', '082133579', 'OHIO'),
(3, 'rasya', 'rasya@gmail.com', '0812369896', 'Delta Asri'),
(4, 'fais', 'fais@gmail.com', '083727849372', 'Bumi'),
(5, 'evan', 'evan@gmail.com', '0844728742', 'Bima Sakti'),
(6, 'bimo', 'bimo@gmail.com', '097887787878', 'isekai');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_payment` decimal(10,2) NOT NULL,
  `total_product` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `admin_id`, `customer_id`, `created_at`, `total_payment`, `total_product`) VALUES
(37, 1, 1, '2024-10-02 01:08:34', 56000.00, 5),
(38, 4, 1, '2024-10-02 01:47:43', 550000.00, 5),
(39, 1, 1, '2024-10-03 04:25:41', 32000.00, 3),
(40, 1, 1, '2024-10-03 05:07:48', 23000.00, 2),
(41, 1, 1, '2024-10-03 05:29:06', 11000.00, 1),
(42, 4, 2, '2024-10-03 06:02:16', 5000000.00, 1),
(43, 1, 1, '2024-10-03 06:05:44', 367000.00, 21),
(44, 4, 1, '2024-10-05 02:25:08', 72000.00, 6),
(45, 1, 1, '2024-10-05 03:00:46', 10000.00, 1),
(46, 1, 1, '2024-10-05 03:02:49', 11000.00, 1),
(47, 10, 6, '2024-10-05 09:00:54', 44000.00, 4),
(48, 6, 6, '2024-10-05 09:48:12', 35000000.00, 6);

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_products`
--

INSERT INTO `order_products` (`id`, `order_id`, `product_id`, `quantity`, `total_price`) VALUES
(4, 37, 3, 2, 56000.00),
(5, 37, 2, 2, 56000.00),
(6, 37, 1, 1, 56000.00),
(7, 38, 6, 5, 550000.00),
(8, 39, 1, 1, 32000.00),
(9, 39, 2, 2, 32000.00),
(10, 40, 3, 1, 23000.00),
(11, 40, 2, 1, 23000.00),
(12, 41, 2, 1, 11000.00),
(13, 42, 26, 1, 5000000.00),
(14, 43, 3, 16, 367000.00),
(15, 43, 8, 2, 367000.00),
(16, 43, 9, 3, 367000.00),
(17, 44, 3, 6, 72000.00),
(18, 45, 1, 1, 10000.00),
(19, 46, 2, 1, 11000.00),
(20, 47, 2, 4, 44000.00),
(21, 48, 26, 5, 35000000.00),
(22, 48, 29, 1, 35000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `price`, `stock`, `image`) VALUES
(3, 'Coffee Milk', 3, 12000.00, 0, 'uploads/kopi_susu.jpg'),
(4, 'Fried Rice', 4, 20000.00, 18, 'uploads/nasi_goreng.jpg'),
(5, 'Smashed Chicken', 4, 18000.00, 8, 'uploads/ayam_geprek.jpg'),
(6, 'Grilled Salmon', 4, 110000.00, 0, 'uploads/salmon.jpg'),
(7, 'Quesadilla', 5, 110000.00, 10, 'uploads/Quesadillas.jpg'),
(8, 'Greek Salad', 5, 50000.00, 4, 'uploads/salad.jpg'),
(9, 'Popcorn', 6, 25000.00, 14, 'uploads/popcorn.jpg'),
(10, 'Nachos', 6, 50000.00, 10, 'uploads/Nachos.jpg'),
(25, 'WDP', 7, 28000.00, 25, 'uploads/wdp.jpg'),
(26, 'Tesla Lite', 7, 5000000.00, 0, 'uploads/tesla.jpg'),
(27, 'Raiden', 7, 1000.00, 1, 'uploads/images.jpeg'),
(28, 'Lisa', 7, 2000.00, 1, 'uploads/lisa.jpg'),
(29, 'Alok', 8, 10000000.00, 0, 'uploads/shogun.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
