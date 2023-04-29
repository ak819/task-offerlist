-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2023 at 04:39 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taskoffers`
--

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) UNSIGNED NOT NULL,
  `guid` varchar(100) NOT NULL,
  `heading` varchar(250) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `start_date_time` datetime DEFAULT NULL,
  `end_date_time` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `guid`, `heading`, `description`, `photo`, `start_date_time`, `end_date_time`, `is_active`, `date_created`, `date_updated`) VALUES
(1, 'c6158a7b-d4b2-9b90-382c-1f804dbe2210', 'summer sale 2', '20%off on tshirt, jeans', '1682772211041.png', '2023-04-28 20:00:00', '2023-05-08 12:00:00', 1, '2023-04-29 13:57:34', NULL),
(3, '5d78a521-c8d4-6ef6-a2a1-6919cc28f61c', 'summer sale 2', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa, nam laborum voluptatum, molestias unde porro a, maiores recusandae blanditiis.', '1682772201443.png', '2023-04-29 16:56:00', '2023-05-09 16:56:00', 1, '2023-04-29 16:56:57', NULL),
(4, '93487eff-f163-b07e-0a10-2a3f15211d3f', 'summer sale 3', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa, nam laborum voluptatum, molestias unde porro a, maiores recusandae blanditiis.', '1682772286110.png', '2023-05-02 18:14:00', '2023-05-11 18:14:00', 1, '2023-04-29 18:14:46', NULL),
(5, '97f6a5f0-1401-5031-4d7e-ed609f56e96f', 'summer sale 4', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa, nam laborum voluptatum, molestias unde porro a, maiores recusandae blanditiis.', '1682772316853.png', '2023-05-02 18:15:00', '2023-05-12 18:15:00', 1, '2023-04-29 18:15:16', NULL),
(6, '676a9243-f721-e0eb-135f-580dd447a962', 'summer sale 5', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa, nam laborum voluptatum, molestias unde porro a, maiores recusandae blanditiis.', '1682772342719.png', '2023-05-03 18:15:00', '2023-05-19 18:15:00', 1, '2023-04-29 18:15:42', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
