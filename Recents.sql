-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 09, 2022 at 06:52 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `customerInfo`
--

-- --------------------------------------------------------

--
-- Table structure for table `Recents`
--

CREATE TABLE `Recents` (
  `ipaddress` varchar(255) NOT NULL,
  `Time` datetime NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Amount` double NOT NULL,
  `hashV` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Recents`
--

INSERT INTO `Recents` (`ipaddress`, `Time`, `Address`, `Amount`, `hashV`) VALUES
('192.123.232.232', '2022-02-08 11:30:51', 'mpUc1c2sn9Ri93REoCD6vGspD6HkpGtdHP', 1000, 'cd182ca49cd1212483f80c0ec55329d29517a2853a41fde4c47be788782c76e9');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Recents`
--
ALTER TABLE `Recents`
  ADD PRIMARY KEY (`ipaddress`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
