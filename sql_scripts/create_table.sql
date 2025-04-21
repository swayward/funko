-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 21, 2025 at 03:18 PM
-- Server version: 10.6.21-MariaDB-cll-lve-log
-- PHP Version: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `funko`
--

-- --------------------------------------------------------

--
-- Table structure for table `funko_character`
--

CREATE TABLE `funko_character` (
  `funko_character_id` int(11) NOT NULL,
  `funko_number` int(11) DEFAULT NULL,
  `owned` tinyint(1) DEFAULT 0,
  `funko_series` varchar(512) DEFAULT NULL,
  `funko_character` varchar(512) DEFAULT NULL,
  `funko_status` tinyint(1) DEFAULT 0,
  `exclusive` varchar(128) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `available_date` varchar(24) DEFAULT NULL,
  `tags` varchar(256) DEFAULT NULL,
  `quantity_owned` int(11) DEFAULT NULL,
  `value` decimal(6,2) DEFAULT NULL,
  `value_date` date DEFAULT NULL,
  `value_source` varchar(256) DEFAULT NULL,
  `purchased_price` decimal(6,2) DEFAULT 5.95,
  `purchased_date` date DEFAULT NULL,
  `purchased_from` varchar(128) DEFAULT NULL,
  `barcode` bigint(20) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `ordered_date` date DEFAULT NULL,
  `google_image` varchar(256) DEFAULT NULL,
  `momento_id` varchar(56) DEFAULT NULL,
  `insert_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `insert_by` int(11) NOT NULL,
  `update_date` timestamp NULL DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `funko_character`
--
ALTER TABLE `funko_character`
  ADD PRIMARY KEY (`funko_character_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `funko_character`
--
ALTER TABLE `funko_character`
  MODIFY `funko_character_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
