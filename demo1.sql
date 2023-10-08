-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2023 at 09:39 PM
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
-- Database: `demo1`
--

-- --------------------------------------------------------

--
-- Table structure for table `residents1`
--

CREATE TABLE `residents1` (
  `id` int(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `code` int(255) NOT NULL,
  `verification_status` varchar(255) NOT NULL,
  `geo_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents1`
--

INSERT INTO `residents1` (`id`, `firstname`, `lastname`, `username`, `email`, `contact`, `address`, `password`, `latitude`, `longitude`, `picture`, `code`, `verification_status`, `geo_url`) VALUES
(36, 'Chrisden Ann', 'Pizarro', 'dendenden', 'chrisdenpizarro@gmail.com', '09288382725', 'T Rodriguez Street, Cabanatuan, Nueva Ecija, Central Luzon, 3100, Philippines', 'db5c5169946dea282dd6852592a95cc9', '15.49345316452776', '120.96564983912172', '', 424532, 'verified', ''),
(50, 'Eubert', 'Mateo', 'eubs', 'tinyminski@gmail.com', '09686448609', 'T Rodriguez Street, Aduas Sur, Cabanatuan, Nueva Ecija, Central Luzon, 3100, Philippines', '0b4ce5dfe2b557f36f0259c6eb07a8bf', '15.493738705588907', '120.96617445697606', '', 0, 'verified', 'http://maps.google.com/maps?f=q&q=15.493738705588907,%20120.96617445697606');

-- --------------------------------------------------------

--
-- Table structure for table `sensor_logs`
--

CREATE TABLE `sensor_logs` (
  `id` int(10) NOT NULL,
  `gas_sensor` varchar(255) NOT NULL,
  `smoke_sensor` varchar(255) NOT NULL,
  `water_sensor` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensor_logs`
--

INSERT INTO `sensor_logs` (`id`, `gas_sensor`, `smoke_sensor`, `water_sensor`, `timestamp`) VALUES
(1, '114', '329', 'Low', '2023-09-01 15:32:35'),
(2, '140\r\n', '300', 'High', '2023-09-01 15:33:30'),
(3, '119', '388', 'Low', '2023-09-01 15:33:50'),
(4, '161', '323', 'High', '2023-09-01 15:34:35'),
(5, '117', '333', 'High', '2023-09-01 15:35:17'),
(6, '159', '299', 'Low', '2023-09-01 15:36:09'),
(8, '181', '345', 'Low', '2023-09-01 15:37:45'),
(9, '130', '400', 'Low', '2023-09-01 15:38:39'),
(10, '70', '188', 'High', '2023-09-01 15:38:55'),
(12, '99', '111', 'Low', '2023-09-01 15:40:59'),
(13, '190', '390', 'High', '2023-09-01 15:41:08'),
(14, '119', '78', 'High', '2023-09-01 15:41:35'),
(15, '80', '77', 'High', '2023-09-01 15:41:48'),
(16, '79', '339', 'High', '2023-09-01 15:40:35'),
(17, '140', '389', 'Medium', '2023-09-02 08:20:17'),
(18, '101', '393', 'Medium', '2023-09-02 09:10:18'),
(20, '99', '77', 'Low', '2023-09-02 09:40:08'),
(21, '112', '57', 'High', '2023-09-02 09:46:14'),
(22, '89', '342', 'Medium', '2023-09-02 09:55:19'),
(23, '89', '466', 'Low', '2023-09-03 15:32:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `residents1`
--
ALTER TABLE `residents1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sensor_logs`
--
ALTER TABLE `sensor_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `residents1`
--
ALTER TABLE `residents1`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `sensor_logs`
--
ALTER TABLE `sensor_logs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
