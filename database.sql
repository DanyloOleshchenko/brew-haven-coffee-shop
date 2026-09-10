-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 11:03 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brew_haven`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `orders_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total_amount` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_each` decimal(6,2) NOT NULL,
  `line_total` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(6,2) NOT NULL,
  `category` enum('food','specialty','coffee') NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `full_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `category`, `image`, `full_description`) VALUES
(1, 'Americano', 'Classic Americano coffee', '3.50', 'coffee', 'img/americano.png', 'Roast: medium-dark\r\nPerfect for a quick energy boost\r\nPairs well with butter croissant'),
(2, 'Espresso', 'Strong single espresso shot', '2.80', 'coffee', 'img/espresso.png', 'Served as a single shot\r\nBest enjoyed in a few sips\r\nIdeal base for lattes and cappuccinos'),
(3, 'Double Espresso', 'Double shot of rich espresso', '3.80', 'coffee', 'img/double-espresso.png', '2 shots of Arabica blend\r\nDeep crema and chocolatey notes\r\nGreat as a morning kick-start'),
(4, 'Cappuccino', 'Espresso with steamed milk and foam', '4.20', 'coffee', 'img/cappuccino.png', '1/3 espresso, 1/3 milk, 1/3 foam\r\nLight cocoa dusting on top\r\nPerfect mid-morning treat'),
(5, 'Latte', 'Espresso with steamed milk', '4.50', 'coffee', 'img/latte.png', 'Subtle coffee flavour\r\nGreat canvas for syrups\r\nComfort drink for any time of day'),
(6, 'Flat White', 'Smooth espresso with microfoam', '4.40', 'coffee', 'img/flat-white.png', 'Stronger than a latte\r\nSilky, glossy texture\r\nOrigin inspired by Australia & New Zealand'),
(7, 'Mocha', 'Espresso with chocolate and milk', '4.90', 'coffee', 'img/mocha.png', 'Real cocoa blend\r\nOptional whipped cream topping\r\nPerfect dessert coffee'),
(8, 'Iced Coffee', 'Chilled brewed coffee with ice', '3.90', 'coffee', 'img/iced-coffee.png', 'Brewed fresh daily\r\nCustomise with milk or syrups\r\nBest on warm days (or after exams)'),
(9, 'Cold Brew', 'Slow-steeped cold brew coffee', '4.70', 'coffee', 'img/cold-brew.png', 'Less bitterness, more flavour\r\nServed over ice\r\nGreat with a splash of milk'),
(10, 'Butter Croissant', 'Flaky French butter croissant', '3.00', 'food', 'img/butter-croissant.png', 'Baked fresh every morning\r\nDelicious plain or with jam\r\nClassic pairing with Americano'),
(11, 'Chocolate Croissant', 'Croissant filled with chocolate', '3.50', 'food', 'img/chocolate-croissant.png', 'Soft inside, crisp outside\r\nNot overly sweet\r\nGreat with latte or mocha'),
(12, 'Blueberry Muffin', 'Soft muffin with blueberries', '2.80', 'food', 'img/blueberry-muffin.png', 'Moist and fluffy texture\r\nServed slightly warm\r\nPerfect grab-and-go snack'),
(13, 'Banana Bread', 'Moist banana bread slice', '3.20', 'food', 'img/banana-bread.png', 'Soft crumb, not too sweet\r\nTasty toasted with butter\r\nComfort food with any coffee'),
(14, 'Ham & Cheese Sandwich', 'Toasted sandwich with ham and cheese', '5.50', 'food', 'img/ham-&-cheese-sandwich.png', 'Served warm and crunchy\r\nAdd mustard or mayo on request\r\nHearty, quick lunch option'),
(15, 'Turkey Club Sandwich', 'Club sandwich with turkey and veggies', '6.20', 'food', 'img/turkey-club-sandwich.png', 'Served on toasted bread\r\nIncludes lettuce & tomato\r\nFilling but still light'),
(16, 'Caesar Salad', 'Classic Caesar with croutons and parmesan', '6.80', 'food', 'img/caesar-salad.png', 'Creamy house Caesar dressing\r\nOption to add chicken\r\nLight but satisfying'),
(17, 'Veggie Wrap', 'Tortilla wrap with grilled vegetables', '5.90', 'food', 'img/veggie-wrap.png', 'Vegetarian-friendly\r\nServed with light dressing\r\nGood choice for a quick, fresh lunch'),
(18, 'Pumpkin Spice Latte', 'Seasonal pumpkin spice latte', '5.20', 'specialty', 'img/pumpkin-spice-latte.png', 'Limited seasonal favourite\r\nTopped with spiced foam\r\nTastes like an autumn hug'),
(19, 'Caramel Macchiato', 'Espresso with vanilla, milk and caramel', '5.10', 'specialty', 'img/caramel-macchiato.png', 'Sweet but balanced\r\nBeautiful layered look\r\nPopular signature drink'),
(20, 'Hazelnut Latte', 'Latte with hazelnut syrup', '5.00', 'specialty', 'img/hazelnut-latte.png', 'Comforting roasted flavour\r\nDelicious with oat milk\r\nPerfect afternoon pick-me-up'),
(21, 'Vanilla Cold Brew', 'Cold brew with vanilla sweet cream', '5.30', 'specialty', 'img/vanilla-cold-brew.png', 'Low acidity, high flavour\r\nSlow-melting cream layer\r\nGreat for long study sessions'),
(22, 'Affogato', 'Espresso poured over vanilla ice cream', '4.80', 'specialty', 'img/affogato.png', 'Contrast of hot & cold\r\nCreamy, sweet and intense\r\nPerfect after-dinner treat'),
(23, 'Irish Coffee', 'Coffee with cream and flavored syrup', '5.60', 'specialty', 'img/irish-coffee.png', 'Inspired by classic Irish recipe\r\nSilky cream cap, no stirring\r\nBest enjoyed slowly'),
(24, 'Matcha Latte', 'Green tea matcha latte with milk', '4.90', 'specialty', 'img/matcha-latte.png', 'Slow-release caffeine\r\nVivid green colour\r\nGreat dairy-free with oat milk'),
(25, 'Signature Combo', 'Coffee with pastry of the day', '7.50', 'specialty', 'img/signature-combo.png', 'Best value set\r\nDaily changing pastry selection\r\nPerfect quick breakfast');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
