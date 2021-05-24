-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 24. Mai, 2021 10:18 AM
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
-- Tabellstruktur for tabell `auth_token`
--

CREATE TABLE `auth_token` (
  `token` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `auth_token`
--

INSERT INTO `auth_token` (`token`) VALUES
('SUPER_SECRET_HASHED_CODE');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `customer_rep` int(11) DEFAULT NULL
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
(1, 'Gunnar', 1),
(2, 'Johannes', 2),
(3, 'Ola', 2),
(4, 'Siri', 2),
(5, 'Kari', 2);

-- --------------------------------------------------------

--
-- Erstatningsstruktur for visning `employee_orders`
-- (See below for the actual view)
--
CREATE TABLE IF NOT EXISTS `employee_orders` (
`employee_number` int(11)
,`order_number` int(11)
,`customer_id` int(11)
,`customer_rep` int(11)
,`total_price` int(11)
,`order_state` int(11)
);

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
(1, 'manager'),
(2, 'customer_rep');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `grip_system`
--

CREATE TABLE `grip_system` (
  `id` int(11) NOT NULL,
  `grip` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `grip_system`
--

INSERT INTO `grip_system` (`id`, `grip`) VALUES
(1, 'skin'),
(2, 'plain');

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
  `order_state` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `orders`
--

INSERT INTO `orders` (`order_number`, `parent_number`, `customer_id`, `customer_rep`, `total_price`, `order_state`) VALUES
(1, NULL, 1, 4, 206200, 2),
(2, NULL, 1, 3, 267730, 3),
(3, NULL, 2, 2, 147000, 1),
(4, NULL, 2, 3, 136500, 2),
(5, NULL, 3, 5, 130600, 3),
(6, NULL, 3, 3, 269500, 2),
(7, NULL, 4, 5, 351000, 1),
(8, NULL, 4, 2, 326500, 2);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `order_state`
--

CREATE TABLE `order_state` (
  `id` int(11) NOT NULL,
  `state` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `order_state`
--

INSERT INTO `order_state` (`id`, `state`) VALUES
(1, 'New'),
(2, 'Open'),
(3, 'Filled'),
(4, 'Shipped'),
(5, 'Split');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `production_plan`
--

CREATE TABLE `production_plan` (
  `num_of_skies` int(11) NOT NULL,
  `ski_type` int(11) NOT NULL,
  `manager` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `production_plan`
--

INSERT INTO `production_plan` (`num_of_skies`, `ski_type`, `manager`) VALUES
(200, 2, 1),
(500, 1, 1);

-- --------------------------------------------------------

--
-- Erstatningsstruktur for visning `ready_shipments`
-- (See below for the actual view)
--
CREATE TABLE IF NOT EXISTS `ready_shipments` (
`shipment_number` int(11)
,`order_number` int(11)
,`pickup_date` date
,`street` varchar(50)
,`number` int(11)
,`postal_code` int(11)
,`city` varchar(50)
,`state` varchar(50)
,`name` varchar(50)
);

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
  `id` int(11) NOT NULL,
  `size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `size_class`
--

INSERT INTO `size_class` (`id`, `size`) VALUES
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
  `id` int(11) NOT NULL,
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

INSERT INTO `ski` (`id`, `temp_class`, `grip`, `description`, `historical`, `photo`, `msrp`, `type`, `model`, `weight`, `size`) VALUES
(1, 'cold', 'skin', 'Unisex model released in 2021', 0, NULL, 4900, 2, 3, 6, 8),
(2, 'warm', 'plain', 'Male model released in 2021', 0, NULL, 6530, 2, 3, 8, 9),
(3, 'cold', 'skin', 'Woman model released in 2021', 0, NULL, 4300, 2, 2, 5, 6),
(4, 'cold', 'skin', 'Unisex model released in 2020', 0, NULL, 3900, 2, 3, 6, 8);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `skis_in_order`
--

CREATE TABLE `skis_in_order` (
  `order_number` int(11) NOT NULL,
  `ski_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_state` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `skis_in_order`
--

INSERT INTO `skis_in_order` (`order_number`, `ski_id`, `quantity`, `order_state`) VALUES
(1, 3, 28, 2),
(1, 4, 22, 2),
(2, 2, 41, 3),
(3, 1, 30, 1),
(4, 4, 35, 2),
(5, 2, 20, 3),
(6, 1, 55, 2),
(7, 4, 90, 1),
(8, 2, 50, 2);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `ski_model`
--

CREATE TABLE `ski_model` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `ski_model`
--

INSERT INTO `ski_model` (`id`, `name`) VALUES
(1, 'Superspeed 2000'),
(2, 'Superspeed 3000'),
(3, 'Superspeed 4000');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `ski_type`
--

CREATE TABLE `ski_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `ski_type`
--

INSERT INTO `ski_type` (`id`, `name`) VALUES
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
  `id` int(11) NOT NULL,
  `temp` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `temperature`
--

INSERT INTO `temperature` (`id`, `temp`) VALUES
(1, 'warm'),
(2, 'cold');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `transporter`
--

CREATE TABLE `transporter` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `transporter`
--

INSERT INTO `transporter` (`id`, `name`) VALUES
(1, 'Bring'),
(2, 'Bring2');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `weight_class`
--

CREATE TABLE `weight_class` (
  `id` int(11) NOT NULL,
  `min_weight` int(11) NOT NULL,
  `max_weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `weight_class`
--

INSERT INTO `weight_class` (`id`, `min_weight`, `max_weight`) VALUES
(1, 20, 25),
(2, 25, 30),
(3, 30, 40),
(4, 40, 50),
(5, 50, 60),
(6, 60, 70),
(7, 70, 90),
(8, 90, 110),
(9, 110, 130);

-- --------------------------------------------------------

--
-- Visningsstruktur `employee_orders`
--
DROP TABLE IF EXISTS `employee_orders`;

CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `employee_orders`  AS SELECT `employee`.`number` AS `employee_number`, `orders`.`order_number` AS `order_number`, `orders`.`customer_id` AS `customer_id`, `orders`.`customer_rep` AS `customer_rep`, `orders`.`total_price` AS `total_price`, `orders`.`order_state` AS `order_state` FROM (`orders` join `employee` on(`employee`.`number` = `orders`.`customer_rep`)) ;

-- --------------------------------------------------------

--
-- Visningsstruktur `ready_shipments`
--
DROP TABLE IF EXISTS `ready_shipments`;

CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ready_shipments`  AS SELECT `shipment`.`shipment_number` AS `shipment_number`, `shipment`.`order_number` AS `order_number`, `shipment`.`pickup_date` AS `pickup_date`, `address`.`street` AS `street`, `address`.`number` AS `number`, `address`.`postal_code` AS `postal_code`, `address`.`city` AS `city`, `shipment_state`.`state` AS `state`, `transporter`.`name` AS `name` FROM (((`shipment` join `shipment_state` on(`shipment`.`shipment_state` = `shipment_state`.`id`)) join `address` on(`address`.`id` = `shipment`.`address_id`)) join `transporter` on(`transporter`.`id` = `shipment`.`transporter_id`)) WHERE `shipment_state`.`state` = 'Ready' ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_token`
--
ALTER TABLE `auth_token`
  ADD PRIMARY KEY (`token`);

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
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ski`
--
ALTER TABLE `ski`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skis_in_order`
--
ALTER TABLE `skis_in_order`
  ADD PRIMARY KEY (`order_number`,`ski_id`);

--
-- Indexes for table `ski_model`
--
ALTER TABLE `ski_model`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ski_type`
--
ALTER TABLE `ski_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teams_skier`
--
ALTER TABLE `teams_skier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temperature`
--
ALTER TABLE `temperature`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transporter`
--
ALTER TABLE `transporter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weight_class`
--
ALTER TABLE `weight_class`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_role`
--
ALTER TABLE `employee_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `grip_system`
--
ALTER TABLE `grip_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_state`
--
ALTER TABLE `order_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ski`
--
ALTER TABLE `ski`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ski_model`
--
ALTER TABLE `ski_model`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ski_type`
--
ALTER TABLE `ski_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `temperature`
--
ALTER TABLE `temperature`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transporter`
--
ALTER TABLE `transporter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `weight_class`
--
ALTER TABLE `weight_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
