-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 04:27 AM
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
-- Database: `origato_b2b`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_info_id` int(11) NOT NULL,
  `prdct_dsgn_id` int(11) NOT NULL,
  `item_qty` int(11) NOT NULL,
  `item_price` decimal(10,2) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `login_id` int(11) NOT NULL,
  `user_info_id` int(11) DEFAULT NULL,
  `e_mail` text NOT NULL,
  `date_login` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`login_id`, `user_info_id`, `e_mail`, `date_login`) VALUES
(1, NULL, 'joliev@gmail.com', '2025-05-19 09:57:34'),
(2, NULL, 'joliev@gmail.com', '2025-05-19 09:57:58'),
(3, NULL, 'joliev@gmail.com', '2025-05-19 10:00:23'),
(4, NULL, 'joliev@gmail.com', '2025-05-19 10:00:36'),
(5, NULL, 'joliev@gmail.com', '2025-05-19 10:01:32'),
(6, 2, 'gwyn@gmail.com', '2025-05-19 10:22:26'),
(7, 3, 'joliev@gmail.com', '2025-05-19 10:56:08'),
(8, 3, 'joliev@gmail.com', '2025-05-20 01:05:26'),
(9, 3, 'joliev@gmail.com', '2025-05-20 01:11:03'),
(10, 3, 'joliev@gmail.com', '2025-05-20 01:35:08'),
(11, 4, 'uno@gmail.com', '2025-05-20 23:57:56'),
(12, 2, 'gwyn@gmail.com', '2025-05-21 09:54:33'),
(13, 2, 'gwyn@gmail.com', '2025-05-21 10:44:40'),
(14, 4, 'uno@gmail.com', '2025-05-21 10:54:59'),
(15, 3, 'joliev@gmail.com', '2025-05-21 11:31:49'),
(16, 4, 'uno@gmail.com', '2025-05-21 12:07:56'),
(17, 3, 'joliev@gmail.com', '2025-05-21 12:12:25');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_ref` varchar(9) NOT NULL,
  `user_info_id` int(11) NOT NULL,
  `prdct_dsgn_id` int(11) NOT NULL,
  `order_phase` varchar(55) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_ref`, `user_info_id`, `prdct_dsgn_id`, `order_phase`, `date_added`, `date_updated`, `order_qty`) VALUES
(1, 'ORDER1234', 2, 20, 'pending', '2025-05-21 05:26:12', '2025-05-21 05:26:12', 0),
(2, 'ORDER1234', 2, 30, 'pending', '2025-05-21 05:27:01', '2025-05-21 05:27:01', 0),
(3, 'ORDER1235', 3, 30, 'pending', '2025-05-21 05:27:28', '2025-05-21 05:27:28', 0),
(4, 'ORD-OT8AW', 4, 13, 'Completed', '2025-05-21 09:13:48', '2025-05-21 18:06:13', 32),
(5, 'ORD-OT8AW', 4, 58, 'Completed', '2025-05-21 09:13:48', '2025-05-21 18:06:13', 8),
(6, 'ORD-Z9NJB', 4, 8, 'Shipping', '2025-05-21 09:41:12', '2025-05-21 18:06:06', 8),
(7, 'ORD-EE8T1', 4, 11, 'Pending', '2025-05-21 09:48:18', '2025-05-21 09:48:18', 24),
(8, 'ORD-74D5M', 2, 16, 'Pending', '2025-05-21 09:55:23', '2025-05-21 09:55:23', 8);

-- --------------------------------------------------------

--
-- Table structure for table `product_design`
--

CREATE TABLE `product_design` (
  `prdct_dsgn_id` int(11) NOT NULL,
  `item_name` varchar(55) NOT NULL,
  `item_price` decimal(20,2) NOT NULL,
  `item_description` text NOT NULL,
  `item_qty` int(11) NOT NULL,
  `item_color` varchar(55) NOT NULL,
  `item_type` varchar(55) NOT NULL,
  `item_brand` varchar(55) NOT NULL,
  `item_photo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_design`
--

INSERT INTO `product_design` (`prdct_dsgn_id`, `item_name`, `item_price`, `item_description`, `item_qty`, `item_color`, `item_type`, `item_brand`, `item_photo`) VALUES
(5, 'Monkey Brush', 2331.43, 'Made of monkey hair. Tip50×Φ7mm.', 5000, '', 'Paint Brush', 'Namura', 'Monkey Brush 1.jpg'),
(7, 'Chicken Feather Brush', 1073.50, 'Made of chicken feather. Large About Tip 120×Φ20mm.', 5000, '', 'Paint Brush', 'Namura', 'Chicken Feather Brush 1.webp'),
(8, 'Bamboo Handle Squirrel Hair Paintbrush', 7206.23, 'Made of squirrel hair. About Tip55×Φ13mm.', 5000, '', 'Paint Brush', 'Namura', 'Bamboo Handle Squirrel Hair Paintbrush 1.jpg'),
(9, 'Bamboo Handle Goat Hair Paintbrush', 7206.23, 'Made of goat hair. About Tip55×Φ13mm.', 5000, '', 'Paint Brush', 'Namura', 'Bamboo Handle Goat Hair Paintbrush 1.jpg'),
(10, 'Thin Brush Oimatsu', 1568.42, 'Made of sable, flying squirrel hair. Tip34×Φ8mm.', 5000, '', 'Paint Brush', 'Namura', 'Thin Brush Oimatsu.webp'),
(11, 'Shironeko Menso', 1695.58, 'Made of white cat hair. Tip22×Φ4mm.', 5000, '', 'Paint Brush', 'Namura', 'Shironeko Menso.webp'),
(12, 'Acrylic Paint Mars Black', 123.32, 'Made of PBk11 / Iron Oxide. 20ml.', 5000, '', 'Paint', 'Matsuda', 'Acrylic Paint Mars Black 1.webp'),
(13, 'Acrylic Paint Cerulean Blue', 200.39, 'Made of PB35 / Cerulean. 20ml.', 5000, '', 'Paint', 'Matsuda', 'Acrylic Paint Cerulean Blue 1.webp'),
(14, 'Acrylic Paint Raw Umber', 123.32, 'Made of PBr7 / Brown Iron Oxide. 20ml.', 5000, '', 'Paint', 'Matsuda', 'Acrylic Paint Raw Umber 1.webp'),
(15, 'Acrylic Paint Permanent Green Deep', 177.27, 'Made of PG7 / Polyclorinated Copper Phthalocyanine、PY81 / Disazo. 20ml.', 5000, '', 'Paint', 'Matsuda', 'Acrylic Paint Permanent Green Deep 1.webp'),
(16, 'Acrylic Paint Permanent Orange', 177.27, 'Made of PO43 / Perinone. 20ml.', 5000, '', 'Paint', 'Matsuda', 'Acrylic Paint Permanent Orange 1.webp'),
(17, 'Acrylic Paint Permanent Pink', 177.27, 'Made of PR245 / Monoazo. 20ml.', 5000, '', 'Paint', 'Matsuda', 'Acrylic Paint Permanent Pink 1.webp'),
(18, 'Acrylic Paint Bright Red', 200.39, 'Made of PR3 / Azo. 20ml.', 5000, '', 'Paint', 'Matsuda', 'Acrylic Paint Bright Red 1.webp'),
(19, 'Acrylic Paint Permanent Violet', 177.27, 'Made of PV23 / Dioxazine. 20ml.', 5000, '', 'Paint', 'Matsuda', 'Acrylic Paint Permanent Violet 1.webp'),
(20, 'Acrylic Paint Titanium White', 123.32, 'Made of PW6 / Zinc Oxide. 20ml.', 5000, '', 'Paint', 'Matsuda', 'Acrylic Paint Titanium White 1.webp'),
(21, 'Acrylic Paint Permanent Yellow', 177.27, 'Made of PY12 / Disazo. 20ml.', 5000, '', 'Paint', 'Matsuda', 'Acrylic Paint Permanent Yellow 1.webp'),
(22, 'Crayon Pastel - Bold Roll', 928.08, 'Made of Pigments, Waxes, Oils. 12 colors/9.6 x 76mm.', 5000, '', 'Crayon', 'Sakura', '1747793038_682d348e5d582_1747792972_682d344c4bb59_Sakura CRAY-PAS Pastels - Bold Roll 1.webp'),
(23, '12 Colors Washable Pastel Crayons', 1374.58, 'Made of Beeswax. 12 colors/15 X 70mm.', 5000, '', 'Crayon', 'Sakura', 'Sakura Craypas - 12 Colors of Washable Crayons 1.webp'),
(24, 'Crayon Oil Pastel', 1374.58, 'Made of Pigments, Oils, Waxes. 12 colors/8 x 60.8mm.', 5000, '', 'Crayon', 'Sakura', 'Sakura CRAY-PAS Pastels - Bold Roll 1.webp'),
(25, 'Artists\' Pastel Tone 50 Colored Pencils Set OP936', 2134.28, 'Made of Wax, Oil, Fat, Maple. 50 colors/3.88 x 7.8mm.', 5000, '', 'Colored Pencil', 'Holbein', '1747880703_682e8aff9c2fe_Holbein Artists_ Pastel Tone 50 Colored Pencils Set OP936 2.webp'),
(26, 'Artists\' Colored Pencils OP920 24-color set', 1742.88, 'Made of Oil, Wax, Premium Pigments, Maple. 24 colors/3.88 x 7.8mm.', 5000, '', 'Colored Pencil', 'Holbein', '1747880749_682e8b2de7747_Holbein ARTISTS_ COLORED PENCILS OP920 24-color set 1.webp'),
(27, 'Holbein Artists\' Colored Pencils 100 Color Set Paper Bo', 3746.39, 'Made of Fats, Oils, Wax, Maple. 100 colors/3.88 x 7.8mm.', 5000, '', 'Colored Pencil', 'Holbein', '1747880766_682e8b3e72ade_Holbein Artists_ Colored Pencils 100 Color Set Paper Box OP940 2.webp'),
(28, 'MONO Zero Eraser, Silver Round', 157.73, 'Synthetic rubber, resin. 2.3mm, 6g.', 5000, '', 'Eraser', 'Tombow', 'MONO Zero Eraser, Silver Round 4.jpg'),
(29, 'Refill, MONO Zero Eraser, Round Tip', 99.46, 'Synthetic rubber, resin. 2.33mm, 6g.', 5000, '', 'Eraser', 'Tombow', 'Refill, MONO Zero Eraser, Round Tip 4.jpg'),
(30, 'MONO Sand Eraser', 237.67, 'Natural rubber latex, silica grit.', 5000, '', 'Eraser', 'Tombow', 'MONO Sand Eraser 4.jpg'),
(31, 'MONO Dust Catch Eraser', 180.47, 'Polyvinyl Chloride. 11 x 23mm, 19g.', 5000, '', 'Eraser', 'Tombow', 'MONO Dust Catch Eraser 1.jpg'),
(32, 'MONO Natural Eraser', 58.32, 'Biomass-derived. 11 x 23mm, 19g.', 5000, '', 'Eraser', 'Tombow', 'MONO Natural Eraser 1.jpg'),
(33, 'MEEDEN Versatile Studio H-Frame Artist Easel-W14', 8873.81, 'Breechwood. 150cm to 241cm, 11.48kg', 5000, '', 'Easel', 'Meeden', 'MEEDEN Versatile Studio H-Frame Artist Easel-W14 1.webp'),
(34, 'MEEDEN Deluxe Multi-Function Heavy Duty Large Artist Ea', 12576.44, 'German Pollmeier SUP-grade European beech wood.', 5000, '', 'Easel', 'Meeden', 'MEEDEN Deluxe Multi-Function Heavy Duty Large Artist Easel-DHJ-3 1.webp'),
(35, 'MEEDEN Artist Metal Watercolor Plein Air Easel Stand-MD', 3572.58, 'Aluminum alloy, rubber sole. 43cm to 165 cm, 5kg.', 5000, '', 'Easel', 'Meeden', 'MEEDEN Artist Metal Watercolor Plein Air Easel Stand-MDBSCHJ 1.webp'),
(36, 'Plum Motif Palette', 189.50, 'Ceramic. 8.5cm', 5000, '', 'Palette', 'Nakagawa Gofun Enogu', 'Plum Motif Palette.webp'),
(37, 'Plum Motif Palette', 246.29, 'Ceramic\r\n12cm', 5000, '', 'Palette', 'Nakagawa Gofun Enogu', 'Plum Motif Palette.webp'),
(38, 'Plum Motif Palette', 453.35, 'Ceramic\r\n15cm', 5000, '', 'Palette', 'Nakagawa Gofun Enogu', 'Plum Motif Palette.webp'),
(39, 'Plum Motif Palette', 718.57, 'Ceramic\r\n18cm', 5000, '', 'Palette', 'Nakagawa Gofun Enogu', 'Plum Motif Palette.webp'),
(40, 'Palette Plate (Deep)', 80.00, 'Ceramic\r\n8.5cm', 5000, '', 'Palette', 'Nakagawa Gofun Enogu', 'Palette Plate (Deep).jpg'),
(41, 'Palette Plate (Deep)', 120.00, 'Ceramic\r\n10.5cm', 5000, '', 'Palette', 'Nakagawa Gofun Enogu', 'Palette Plate (Deep).jpg'),
(42, 'Palette Plate (Deep)', 160.00, 'Ceramic\r\n13cm', 5000, '', 'Palette', 'Nakagawa Gofun Enogu', 'Palette Plate (Deep).jpg'),
(43, 'Palette Plate (Deep)', 220.00, 'Ceramic\r\n15cm', 5000, '', 'Palette', 'Nakagawa Gofun Enogu', 'Palette Plate (Deep).jpg'),
(44, 'Palette Plate (Deep)', 280.00, 'Ceramic\r\n18cm', 5000, '', 'Palette', 'Nakagawa Gofun Enogu', 'Palette Plate (Deep).jpg'),
(45, 'Midori Clip Ruler', 327.68, 'Silver, 10cm', 5000, '', 'Ruler', 'Midori', 'Midori Clip Ruler - Silver - Daily Life.jpg'),
(46, 'Midori Multi Ruler', 737.23, 'Clear, 30cm, functions as protractor and compass', 5000, '', 'Ruler', 'Midori', 'Midori Multi Ruler.webp'),
(47, 'Midori Aluminum Multi Ruler', 937.72, 'Black, 30cm, functions as protractor and compass', 5000, '', 'Ruler', 'Midori', 'Midori Aluminum Multi Ruler.webp'),
(48, 'Midori Non Slip Aluminum Ruler', 187.26, 'Black, 15cm, non slip', 5000, '', 'Ruler', 'Midori', 'Midori Non Slip Aluminum Ruler.webp'),
(49, 'Saxa Scissors Titanium Coating Type Green', 394.57, 'Saxa Scissors Titanium Coating Type Green', 5000, '', 'Scissors', 'Saxa', 'Scissors Titanium Coating Type Green.jpg'),
(50, 'Saxa Scissors Greige', 492.68, 'Standard Blade', 5000, '', 'Scissors', 'Saxa', 'Scissors Greige.jpg'),
(51, 'Saxa All Purpose Scissors', 938.78, 'Black, 45mm x 189mm x 83 mm, fluorine coated', 5000, '', 'Scissors', 'Saxa', 'SAXA All-purpose scissors Black.jpg'),
(52, 'Saxa Scissors', 175.00, 'White, Non-stick', 5000, '', 'Scissors', 'Saxa', 'SAXA Scissors x Non-stick blade x White.jpg'),
(53, 'Saxa Poche Compact Scissors', 416.85, 'Pen type portable, black', 5000, '', 'Scissors', 'Saxa', 'SAXA poche compact scissors Black.jpg'),
(54, 'Small Pencil Sharpener', 106.00, 'Lightweight, portable', 5000, '', 'Sharpener', 'Muji', 'Small Pencil Sharpener.webp'),
(55, 'Large Pencil Sharpener', 552.11, 'With built in compartment collecting pencil shavings.', 5000, '', 'Sharpener', 'Muji', 'Large Pencil Sharpener.webp'),
(56, 'MAX HD-10FL3 Mini Stapler', 595.71, 'White, capable of stapling 25 sheets, 80mm long x 65mm high.', 5000, '', 'Stapler', 'Max', 'MAX HD-10FL3 Mini Stapler.webp'),
(57, 'MAX HD-10NL Mini Stapler', 483.87, 'Black, capable of stapling 16 sheets, 80mm long x 55mm high.', 5000, '', 'Stapler', 'Max', 'MAX HD-10NL Mini Stapler.webp'),
(58, 'MAX HD-10XS Micro Stapler', 334.76, 'Sky blue, capable of stapling 10 sheets, 65mm long x 18mm high.', 5000, '', 'Stapler', 'Max', 'MAX HD-10XS Micro Stapler.webp'),
(59, 'MAX HD-55FL Stapler', 1083.33, 'Red, capable of stapling 35 sheets, 130mm long x 85mm high.', 5000, '', 'Stapler', 'Max', 'MAX HD-55FL Stapler.webp'),
(60, 'Midori Sticky Notes - Cat', 245.00, 'Cat Stretching Design, 15 sheets', 5000, '', 'Sticky Notes', 'Midori', 'Midori Sticky Notes - Cat.webp'),
(61, 'Midori MD Sticky Memo', 245.00, '24 sheets', 5000, '', 'Sticky Notes', 'Midori', 'Midori MD Sticky Memo.webp'),
(62, 'Midori Sticky Notes – Dog', 245.00, 'White Dog Design, 15 sheets', 5000, '', 'Sticky Notes', 'Midori', 'Midori Sticky Notes – Dog.webp'),
(63, 'Midori Sticky Notes – Book and Cat', 245.00, 'Book and Cat Design, 15 sheets', 5000, '', 'Sticky Notes', 'Midori', 'Midori Sticky Notes – Book and Cat.webp');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `user_info_id` int(11) NOT NULL,
  `user_name` varchar(55) NOT NULL,
  `e_mail` text NOT NULL,
  `pass_word` varchar(255) NOT NULL,
  `add_ress` text NOT NULL,
  `contact_no` varchar(11) NOT NULL,
  `user_type` char(1) NOT NULL DEFAULT 'U',
  `user_status` char(1) NOT NULL DEFAULT 'A',
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`user_info_id`, `user_name`, `e_mail`, `pass_word`, `add_ress`, `contact_no`, `user_type`, `user_status`, `date_registered`, `date_updated`, `profile_picture`) VALUES
(2, 'gwyn', 'gwyn@gmail.com', '$2y$10$/brlYHFD6FDb5aB0c0KopeBkPSk03qth28OF7MAvRZz81aOUhF7xS', 'bahay', '09999999999', 'U', 'A', '2025-05-19 16:22:01', '2025-05-21 09:55:23', ''),
(3, 'jolie_v', 'joliev@gmail.com', '$2y$10$aK.eyLvUss40XEWWwo.IhO/Mn0GoEB066EVl7eANXpVirkOJI3Wrq', '', '', 'A', 'A', '2025-05-19 16:55:12', '2025-05-19 16:55:12', ''),
(4, 'uno', 'uno@gmail.com', '$2y$10$wQPPVj8A4/FakyguyE1aY.aYXHhgcVE42QTMAHHYR7qiAYfI88C1S', 'bahay 2', '09999999999', 'U', 'A', '2025-05-21 05:57:40', '2025-05-21 09:48:18', '../photos/4.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`login_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `product_design`
--
ALTER TABLE `product_design`
  ADD PRIMARY KEY (`prdct_dsgn_id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`user_info_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_design`
--
ALTER TABLE `product_design`
  MODIFY `prdct_dsgn_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `user_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
