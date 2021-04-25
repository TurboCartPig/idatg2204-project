-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 25. Apr, 2021 22:08 PM
-- Tjener-versjon: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_project`
--

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `street` varchar(50) NOT NULL,
  `number` int(11) NOT NULL,
  `postal_code` int(11) NOT NULL,
  `city` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `address`
--

INSERT INTO `address` (`id`, `street`, `number`, `postal_code`, `city`) VALUES
(1, 'Sverre Iversens Vei ', 31, 972, 'Oslo'),
(2, 'Begnaveien', 19, 3517, 'Honefoss');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `customer_rep` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `customer`
--

INSERT INTO `customer` (`id`, `start_date`, `end_date`, `customer_rep`) VALUES
(1, '2020-01-01', '2021-01-01', 2),
(2, '1990-01-01', '1995-01-01', 3),
(3, '2002-01-01', '2005-01-01', 4),
(4, '2006-01-01', '2010-01-01', 4);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `customer_franchise`
--

CREATE TABLE `customer_franchise` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` int(11) NOT NULL,
  `buying_price` int(11) NOT NULL,
  `store_information` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `employee`
--

CREATE TABLE `employee` (
  `number` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `employee`
--

INSERT INTO `employee` (`number`, `name`, `role`) VALUES
(1, 'Gunnar', 1);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `employee_role`
--

CREATE TABLE `employee_role` (
  `id` int(11) NOT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `employee_role`
--

INSERT INTO `employee_role` (`id`, `role`) VALUES
(1, 'manager');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `grip_system`
--

CREATE TABLE `grip_system` (
  `grip` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `grip_system`
--

INSERT INTO `grip_system` (`grip`) VALUES
('plain'),
('skin');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `individual_store`
--

CREATE TABLE `individual_store` (
  `id` int(11) NOT NULL,
  `franchise_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `address` int(11) NOT NULL,
  `buying_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `orders`
--

CREATE TABLE `orders` (
  `order_number` int(11) NOT NULL,
  `parent_number` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_rep` int(11) DEFAULT NULL,
  `total_price` int(11) NOT NULL,
  `order_state` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `orders`
--

INSERT INTO `orders` (`order_number`, `parent_number`, `customer_id`, `customer_rep`, `total_price`, `order_state`) VALUES
(1, 2, 1, 1, 120000, 'In production'),
(2, 2, 1, 1, 234000, 'Ready for shipping'),
(3, 1, 2, 1, 320000, 'In production'),
(4, 1, 2, 1, 120000, 'Ready for shipping'),
(5, 3, 3, 1, 410000, 'In production'),
(6, 3, 3, 1, 120000, 'Ready for shipping'),
(7, 4, 4, 1, 210000, 'In production'),
(8, 4, 4, 1, 110000, 'Ready for shipping');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `order_state`
--

CREATE TABLE `order_state` (
  `state` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `order_state`
--

INSERT INTO `order_state` (`state`) VALUES
('In production'),
('Ready for shipping');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `production_plan`
--

CREATE TABLE `production_plan` (
  `num_of_skies` int(11) NOT NULL,
  `ski_type` int(11) NOT NULL,
  `manager` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `production_plan`
--

INSERT INTO `production_plan` (`num_of_skies`, `ski_type`, `manager`) VALUES
(200, 2, 1),
(500, 1, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `shipment`
--

CREATE TABLE `shipment` (
  `shipment_number` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `pickup_date` date NOT NULL,
  `shipment_state` int(11) NOT NULL,
  `order_number` int(11) NOT NULL,
  `transporter_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `shipment`
--

INSERT INTO `shipment` (`shipment_number`, `address_id`, `pickup_date`, `shipment_state`, `order_number`, `transporter_id`, `driver_id`) VALUES
(1, 1, '2021-01-01', 2, 2, 1, 2),
(2, 2, '2021-02-01', 1, 4, 2, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `shipment_state`
--

CREATE TABLE `shipment_state` (
  `id` int(11) NOT NULL,
  `state` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `shipment_state`
--

INSERT INTO `shipment_state` (`id`, `state`) VALUES
(1, 'Transit'),
(2, 'Ready');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `size_class`
--

CREATE TABLE `size_class` (
  `class_id` int(11) NOT NULL,
  `size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `size_class`
--

INSERT INTO `size_class` (`class_id`, `size`) VALUES
(1, 135),
(2, 140),
(3, 145),
(4, 150),
(5, 155),
(6, 160),
(7, 165),
(8, 170),
(9, 175),
(10, 180),
(11, 185),
(12, 190),
(13, 195),
(14, 200);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `ski`
--

CREATE TABLE `ski` (
  `ski_id` int(11) NOT NULL,
  `temp_class` varchar(10) NOT NULL,
  `grip` varchar(50) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `historical` tinyint(1) DEFAULT NULL,
  `photo` longblob DEFAULT NULL,
  `msrp` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `model` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `ski`
--

INSERT INTO `ski` (`ski_id`, `temp_class`, `grip`, `description`, `historical`, `photo`, `msrp`, `type`, `model`, `weight`, `size`) VALUES
(1, 'cold', 'skin', 'Unisex model released in 2021', 0, NULL, 4900, 2, 3, 6, 8),
(2, 'warm', 'plain', 'Male model released in 2021', 0, NULL, 6530, 2, 3, 8, 9),
(3, 'cold', 'skin', 'Woman model released in 2021', 0, NULL, 4300, 2, 2, 5, 6),
(4, 'cold', 'skin', 'Unisex model released in 2020', 0, NULL, 3900, 2, 3, 6, 8);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `skiis_in_order`
--

CREATE TABLE `skiis_in_order` (
  `order_number` int(11) NOT NULL,
  `ski_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `ski_model`
--

CREATE TABLE `ski_model` (
  `model_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `ski_model`
--

INSERT INTO `ski_model` (`model_id`, `name`) VALUES
(1, 'Superspeed 2000'),
(2, 'Superspeed 3000'),
(3, 'Superspeed 4000');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `ski_type`
--

CREATE TABLE `ski_type` (
  `type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `ski_type`
--

INSERT INTO `ski_type` (`type_id`, `name`) VALUES
(1, 'classic'),
(2, 'sprint');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `teams_skier`
--

CREATE TABLE `teams_skier` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `club` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `temperature`
--

CREATE TABLE `temperature` (
  `temp` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `temperature`
--

INSERT INTO `temperature` (`temp`) VALUES
('cold'),
('warm');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `transporter`
--

CREATE TABLE `transporter` (
  `company_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `transporter`
--

INSERT INTO `transporter` (`company_id`, `name`) VALUES
(1, 'Bring'),
(2, 'Bring2');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `weight_class`
--

CREATE TABLE `weight_class` (
  `class_id` int(11) NOT NULL,
  `min_weight` int(11) NOT NULL,
  `max_weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `weight_class`
--

INSERT INTO `weight_class` (`class_id`, `min_weight`, `max_weight`) VALUES
(1, 20, 25),
(2, 25, 30),
(3, 30, 40),
(4, 40, 50),
(5, 50, 60),
(6, 60, 70),
(7, 70, 90),
(8, 90, 110),
(9, 110, 130);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_franchise`
--
ALTER TABLE `customer_franchise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`number`);

--
-- Indexes for table `employee_role`
--
ALTER TABLE `employee_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grip_system`
--
ALTER TABLE `grip_system`
  ADD PRIMARY KEY (`grip`);

--
-- Indexes for table `individual_store`
--
ALTER TABLE `individual_store`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_number`);

--
-- Indexes for table `order_state`
--
ALTER TABLE `order_state`
  ADD PRIMARY KEY (`state`);

--
-- Indexes for table `production_plan`
--
ALTER TABLE `production_plan`
  ADD PRIMARY KEY (`num_of_skies`,`ski_type`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`shipment_number`);

--
-- Indexes for table `shipment_state`
--
ALTER TABLE `shipment_state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `size_class`
--
ALTER TABLE `size_class`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `ski`
--
ALTER TABLE `ski`
  ADD PRIMARY KEY (`ski_id`);

--
-- Indexes for table `skiis_in_order`
--
ALTER TABLE `skiis_in_order`
  ADD PRIMARY KEY (`order_number`,`ski_id`);

--
-- Indexes for table `ski_model`
--
ALTER TABLE `ski_model`
  ADD PRIMARY KEY (`model_id`);

--
-- Indexes for table `ski_type`
--
ALTER TABLE `ski_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `teams_skier`
--
ALTER TABLE `teams_skier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temperature`
--
ALTER TABLE `temperature`
  ADD PRIMARY KEY (`temp`);

--
-- Indexes for table `transporter`
--
ALTER TABLE `transporter`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `weight_class`
--
ALTER TABLE `weight_class`
  ADD PRIMARY KEY (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_role`
--
ALTER TABLE `employee_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `shipment_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shipment_state`
--
ALTER TABLE `shipment_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `size_class`
--
ALTER TABLE `size_class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ski`
--
ALTER TABLE `ski`
  MODIFY `ski_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ski_model`
--
ALTER TABLE `ski_model`
  MODIFY `model_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ski_type`
--
ALTER TABLE `ski_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transporter`
--
ALTER TABLE `transporter`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `weight_class`
--
ALTER TABLE `weight_class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
