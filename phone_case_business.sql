-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2023 at 05:21 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phone_case_business`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` varchar(15) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
('ap', 'apple'),
('ss', 'samsung');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_items` int(11) NOT NULL DEFAULT 0,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total_items`, `total`, `status`) VALUES
(4, 62, '2023-05-03 17:23:50', 9, 199.91, 1),
(6, 112, '2023-05-06 15:25:40', 5, 124.95, 0),
(7, 117, '2023-05-06 15:29:40', 3, 74.97, 0),
(8, 70, '2023-05-07 17:55:03', 10, 249.90, 1),
(9, 70, '2023-05-07 17:55:26', 12, 269.88, 1),
(10, 62, '2023-05-07 22:33:57', 10, 249.90, 1),
(11, 70, '2023-05-08 01:21:55', 1, 24.99, 0),
(12, 62, '2023-05-09 15:10:39', 6, 149.94, 1),
(14, 106, '2023-05-09 15:25:08', 1, 19.99, 0),
(18, 83, '2023-05-09 23:31:26', 1, 24.99, 1),
(27, 62, '2023-05-10 10:02:29', 3, 74.97, 1),
(28, 62, '2023-05-10 12:15:44', 7, 169.93, 1),
(30, 62, '2023-05-11 00:41:38', 10, 459.90, 1),
(31, 156, '2023-05-11 18:10:36', 14, 588.86, 0),
(32, 62, '2023-05-11 20:08:08', 2, 107.98, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ordersline`
--

CREATE TABLE `ordersline` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordersline`
--

INSERT INTO `ordersline` (`order_id`, `product_id`, `quantity`) VALUES
(4, 5, 1),
(4, 8, 3),
(4, 11, 5),
(6, 10, 5),
(7, 5, 2),
(7, 8, 1),
(8, 5, 10),
(9, 8, 1),
(9, 10, 5),
(9, 11, 6),
(10, 5, 5),
(10, 8, 3),
(10, 10, 2),
(11, 8, 1),
(12, 5, 6),
(14, 11, 1),
(18, 5, 1),
(27, 5, 1),
(27, 8, 2),
(28, 5, 5),
(28, 8, 1),
(28, 11, 1),
(30, 8, 4),
(30, 19, 6),
(31, 8, 7),
(31, 14, 1),
(31, 17, 6),
(32, 14, 1),
(32, 16, 1);

--
-- Triggers `ordersline`
--
DELIMITER $$
CREATE TRIGGER `Delete_a_orders` AFTER DELETE ON `ordersline` FOR EACH ROW DELETE FROM orders WHERE total_items = 0
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_order_total` AFTER INSERT ON `ordersline` FOR EACH ROW UPDATE orders
SET total = (SELECT SUM(price * quantity) FROM ordersline INNER JOIN products ON products.id = ordersline.product_id WHERE order_id = NEW.order_id)
WHERE id = NEW.order_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_order_total_on_delete` AFTER DELETE ON `ordersline` FOR EACH ROW UPDATE orders
SET total = (SELECT SUM(price * quantity) FROM ordersline INNER JOIN products ON products.id = ordersline.product_id WHERE order_id = OLD.order_id)
WHERE id = OLD.order_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_order_total_on_update` AFTER UPDATE ON `ordersline` FOR EACH ROW UPDATE orders
SET total = (SELECT SUM(price * quantity) FROM ordersline INNER JOIN products ON products.id = ordersline.product_id WHERE order_id = NEW.order_id)
WHERE id = NEW.order_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_orders_total_item` AFTER INSERT ON `ordersline` FOR EACH ROW UPDATE orders
    SET total_items = (SELECT (SUM(ordersline.quantity)) FROM ordersline WHERE order_id = NEW.order_id)
    WHERE id = NEW.order_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_orders_total_item_on_delete` AFTER DELETE ON `ordersline` FOR EACH ROW UPDATE orders
    SET total_items = (SELECT (SUM(ordersline.quantity)) FROM ordersline WHERE order_id = OLD.order_id)
    WHERE id = OLD.order_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_orders_total_item_on_update` AFTER UPDATE ON `ordersline` FOR EACH ROW UPDATE orders
    SET total_items = (SELECT (SUM(ordersline.quantity)) FROM ordersline WHERE order_id = NEW.order_id)
    WHERE id = NEW.order_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `brand_id` varchar(15) NOT NULL,
  `description` varchar(10000) DEFAULT NULL,
  `image_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `brand_id`, `description`, `image_url`) VALUES
(5, 'APPLE IPHONE 13 PRO SMOKED OAK WOOD CASE', 24.99, 'ap', 'Smoked oak – is a special variation of wood made from trees or rarely high bushes of the Fagaceae family (Fagaceae Dumort). This family consists of 200 species occurring mainly in temperate zones in the Northern Hemisphere (among others in Poland) and in high terrains in the tropical zone. Oaks are famous for large sizes, they are the thickest Polish trees and their diameter may exceed 3 metres for the height up to 25-35 metres. Oak wood is relatively heavy and tough, therefore it is commonly used in woodwork and furniture industry. Moreover, it is highly resistant to abrasion. Oak wood is usually yellowish brown and has clearly defined structure with knots characteristic of oak. Smoked oak colour becomes dark as a result of long-lasting and remarkably precise process of heat treatment – smoking. The diversity of rings, revealed as a result of ‘smoking’, reacts on the light in an unusual manner changing the complexion depending on the angle of incidence of the light. The above mentioned features will make our case change in front of our eyes depending on what angle we look at it from.', 'img/products/oak_wood_case.jpg'),
(8, 'Natural Wood Owl', 24.99, 'ap', 'Shockproof, natural wooden phone cases for iPhone\r\nFeatures: Anti-knock, Dirt-resistant, Anti-skid \r\nDesign: Natural wood, plain design & quotes. \r\nType: Fitted Case Compatible \r\nBrand: Apple iPhones', 'img/products/Natural_Wood_Owl.jpg'),
(10, 'Natural Real Wood Wooden TPU Case For Samsung Galaxy S20', 24.99, 'ss', 'Comprised of 0.2mm wood chip and TPU for 2 layers of protection(Not full wood)\r\n100% Handcrafted by Natural Eco-Friendly Material.\r\nElegant: the unique designs, superior quality wood (bamboo) material made more show your elegance and high quality temperament\r\nREAL WOOD GRAIN: Every case is unique with its own distinct wood grain giving you a one of a kind case', 'img/products/SS_Elephent.jpg'),
(11, 'OXSY Walnut Wood Case | Genuine Apple iPhone 7+ | ', 19.99, 'ap', 'Simple products. Naturally beautiful.\r\n\r\nOXSY fashion products are created from natural, sustainable sources.\r\n\r\nAt OXSY, we want to ensure that the products we offer are of the highest quality and are manufactured using a range of sustainable, natural sources. Our vision is to produce beautiful, fashionable accessories that we are proud to put our logo on. Due to the natural materials used on our products, every item is unique.\r\n\r\nUnique is what we stand for. Unique is what we do.\r\n\r\nOur cases are produced from sustainably sourced timber, hand crafted to fit your iPhone with precision.\r\nYour OXSY case will offer a protective outer shell from scuffs caused by your coins, or keys whilst improving the aesthetics of your phone.', 'img/products/iPhone7+.png'),
(14, 'iPhone 14 Lainey', 53.99, 'ap', 'Our Phone Cases are inspired by the beauty of our earth from the perspective of a satellite. This phone case is 1 of One and is made in MSW and MJ. A using real wood burls & epoxy resin.', 'img/products/iphone14_lainey.png'),
(16, 'iPhone 13 Pro Hayes', 53.99, 'ap', 'We could put a fancy badge here, or make up some snazzy name for our \"exclusive\" technology, but we think there is one simple way to answer this question:\r\n\r\nAlmost everyone who works at Jackarry uses one of our cases on their personal phones. We didn\'t just design this case to sell it to you, we designed it to use it ourselves.', 'img/products/hayes.png'),
(17, 'Samsung Galaxy S23 Hayes', 59.99, 'ss', '<p>Our Phone Cases are inspired by the beauty of our earth from the perspective of a satellite. This phone case is <strong>1 of One</strong> and is made in Elkhart, Indiana using real wood burls &amp; epoxy resin.</p>\r\n<p>Perfectly fits the <strong>Galaxy S23. \r\n      </strong></p>', 'img/products/hayes_galaxy_s23.png'),
(19, 'EMILEE IPHONE 14 PRO MAX', 59.99, 'ap', '<p>Our Phone Cases are inspired by the beauty of our earth from the perspective of a satellite. This phone case is <strong>1 of One</strong> and is made in Elkhart, Indiana using real wood burls &amp; epoxy resin.</p><p>Perfectly fits the <strong>iPhone 14 Pro Max. \r\n      </strong></p>', 'img/products/ip14promax_emille.jpg'),
(20, 'JESUS\r\nGALAXY S23 PHONE CASE', 53.99, 'ss', 'Our Phone Cases are inspired by the beauty of our earth from the perspective of a satellite. This phone case is 1 of One and is made in MSW and MJ. A using real wood burls & epoxy resin.', 'img/products/s23_jesus.jpg'),
(21, 'EMILEE\r\nGALAXY S23 PHONE CASE', 53.99, 'ss', 'Our Phone Cases are inspired by the beauty of our earth from the perspective of a satellite. This phone case is 1 of One and is made in MSW and MJ. A using real wood burls & epoxy resin.', 'img/products/s23_emille.jpg'),
(22, 'CINDY\r\nGALAXY S23 ULTRA PHONE CASE', 53.99, 'ss', 'Our Phone Cases are inspired by the beauty of our earth from the perspective of a satellite. This phone case is 1 of One and is made in MSW and MJ. A using real wood burls & epoxy resin.', 'img/products/s23ultra_cindy.jpg'),
(23, 'HAYES\r\nGALAXY S23 ULTRA PHONE CASE', 53.99, 'ss', 'Our Phone Cases are inspired by the beauty of our earth from the perspective of a satellite. This phone case is 1 of One and is made in MSW and MJ. A using real wood burls & epoxy resin.', 'img/products/s23ultra_hayes.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `password` char(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstname`, `lastname`, `password`, `email`, `reg_date`) VALUES
(62, 'AHgaming', 'Huy', 'Nguyen', '$2y$10$Wx7ssg8cuDWrYGYItXTy3.0Y35Eri0n7sow8/9qbbWJixnEa2GIq.', 'ahgamingofficial03@gmail.com', '2023-04-27 21:19:01'),
(70, 'dooddeed', 'Jack', 'Connor', '$2y$10$wN3xEBjH517U7YYrp5VTmeznl0hLyYENzcHoUkmwgBT2alymNw8lW', 'tatuanthunohomo@gmail.com', '2023-04-27 22:01:06'),
(83, 'AHgaming03', 'Harry', 'Nguyen', '$2y$10$IBK2OnC937k01Z4l.TA3TezeEkjxTS/CLUQL08EgY5sFqJbf8yBdS', 'ahgamingofficial03@gmail.com', '2023-04-27 22:23:22'),
(84, 'jep03', 'Jepp', 'Connor', '$2y$10$c.8tCUmUzPwZ6NDWH4GfVOMTR160QduZpcE4JqOmdYLj6gSibi5U.', 'fake99@email.com', '2023-05-05 18:16:46'),
(106, 'john12', 'John', 'Nathan', '$2y$10$BI5Oyd9YbUV3PJHmFYg1Aez1WZyOef3wZk05ihVXMe/JxpuWVA9fq', 'fake999@email.com', '2023-05-05 19:26:42'),
(107, 'Long99', 'Long', 'Lee', '$2y$10$Ltxg3fXfj33LtyDcoZhpou2XFkLBFAuivvJnc8s9vkpE2Id9LpqdO', 'fake93@gmail.com', '2023-05-05 19:32:08'),
(108, 'jenifer14', 'Jenifer', 'Connor', '$2y$10$orQVvvPSLwVLcMSIJ3qCXOHzZyWKKLJGK9sVvIua1BrsY79SbSrma', 'facemail@face.com', '2023-05-05 22:32:37'),
(110, 'bobr78', 'Bob', 'Roll', '$2y$10$yhen.RPJkyH2VUWFkvOWIO2VaI0nJ7fsViS9CQwHI4jGlehBasf3m', 'facemail2@mail.com', '2023-05-05 22:34:47'),
(112, 'elephen4', 'Elephen', 'Pop', '$2y$10$JPVS.7k1i3Q./64c0FnsoOwRSoAH67henWUyyUfkdviWkjUA59a6u', 'fakemail@fake.com', '2023-05-05 22:38:10'),
(117, 'jump1', 'jump', 'Roll', '$2y$10$nF7T1AFbcFiR8XzQL3v8BO6xhyicytZ/yvp2B.liMUuE3qgKAo9Qe', 'fakemail54@fake.com', '2023-05-05 23:06:59'),
(119, 'jep123', 'Jepp', 'Nguyen', '$2y$10$oWSRZGw/cUlx4fKQHsChX.yIwNjDdgmoraMcKivSx0Jb55K41sd/W', 'jep111@fake.com', '2023-05-10 15:42:11'),
(120, 'jepp112', 'Jepp', 'Nguyen', '$2y$10$eaRJex782KQf7WVcTTAD.OQpw0GbHgCCBf2Y1VCl9.xEgaUxTff0S', 'jep111@fake.com', '2023-05-10 15:44:03'),
(121, 'ronn333', 'Ronn', 'Nathan', '$2y$10$AXTzzM7Zeky.MHHWCrUjhuXkMEY9ErL0VLhhUE7JKQPmMtHwii7/6', 'ron3fake@fake.com', '2023-05-10 15:57:09'),
(122, '', '', '', '$2y$10$ln9vNgbl.LV1/eo9Dq7z4ODQxTk49I8GGUCCtVTkzQSGZA0dkSKAK', '', '2023-05-10 16:54:52'),
(147, 'Cuong113', 'Cuong', 'Nguyen', '$2y$10$OmQLma38GcGXsvZNcZqAS.4JpjlvytcQvYuP8GPN/kB7jhoJ6StRG', 'cuong113@fake.com', '2023-05-11 13:56:12'),
(153, 'huycon111', 'Huy', 'Connor', '$2y$10$Iv0rHPxfVWiIXl4n4/nv9umP3IzD6iqeanhaqGBdZupDMZDmXEfwC', 'huycon111@gmail.com', '2023-05-11 14:17:43'),
(154, 'huynat222', 'Huy', 'Nathan', '$2y$10$Qz2qPt0mzjDSqCwrABuwOuk4cEeutkaLgIYhMtJ.uktddZ4Lv1D..', 'huynat222@gmail.com', '2023-05-11 14:19:17'),
(155, 'huyal333', 'Huy', 'Alone', '$2y$10$i8eECS8ND7p71icpsezYjudsGp1NUHhQx.9NzGqAGljYquhdRBZ3e', 'huyla@gmail.com', '2023-05-11 14:21:50'),
(156, 'harryp99', 'Harry', 'Potter', '$2y$10$T.nor.2SgQzhfX96VLaYYuSDnQOq7gHMXXNi31EG7iCGJXTSytLcG', 'harrypotter99@gmail.com', '2023-05-11 15:10:44'),
(159, 'haol123', 'Hao', 'Lee', '$2y$10$NclHtlxPVskiM2Oj5mpaIOJ/jF6wKfvH.YPihkSk5JAA.8VL2aOXy', 'haol113@gmail.com', '2023-05-11 17:51:54'),
(172, 'kelp45', 'Kelp', 'Jomn', '$2y$10$XMIs/Jw1AcshaP10CBLXO.p7mqwfenxEvObPasnieIqqhQh3vCbrW', 'Kelpj@gmail.com', '2023-05-11 19:40:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user_id` (`user_id`);

--
-- Indexes for table `ordersline`
--
ALTER TABLE `ordersline`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `ordersline`
--
ALTER TABLE `ordersline`
  ADD CONSTRAINT `ordersline_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `ordersline_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_brands_products` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
