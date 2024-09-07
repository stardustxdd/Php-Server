-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2024 at 03:09 AM
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
-- Database: `stardustsii`
--

-- --------------------------------------------------------

--
-- Table structure for table `modmenus`
--

CREATE TABLE `modmenus` (
  `No` int(11) NOT NULL,
  `MenuName` text NOT NULL,
  `Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modmenus`
--

INSERT INTO `modmenus` (`No`, `MenuName`, `Id`) VALUES
(1, 'Subway Surf', 101),
(2, 'BGMI', 102);

-- --------------------------------------------------------

--
-- Table structure for table `userkeys`
--

CREATE TABLE `userkeys` (
  `No` int(11) NOT NULL,
  `CreatedKeys` varchar(20) NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `ModName` text NOT NULL,
  `ModID` int(11) NOT NULL,
  `OneDevLogin` tinyint(1) NOT NULL DEFAULT 0,
  `UUID` varchar(50) DEFAULT NULL,
  `Time` time NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userkeys`
--

INSERT INTO `userkeys` (`No`, `CreatedKeys`, `StartDate`, `EndDate`, `ModName`, `ModID`, `OneDevLogin`, `UUID`, `Time`) VALUES
(1, '2b6AU5CNxWyy6de7Gl1H', '2024-09-07', '2024-09-11', 'Subway Surf', 101, 1, 'THIS-IS-TESING-UUID', '04:52:28'),
(2, 'kBOfFeLrKoTduQGKxf0O', '2024-09-07', '2024-09-16', 'Subway Surf', 101, 0, NULL, '04:52:42'),
(3, 'ybwb2HGKqn8HennR4mLk', '2024-09-07', '2024-08-26', 'Subway Surf', 101, 0, NULL, '04:52:54'),
(4, 'fV5YmX3phNIXGBmYMw17', '2024-09-07', '2024-09-17', 'BGMI', 102, 1, 'test-uuid-69', '05:01:04'),
(5, 'UIgbLPg8CfNHas70a1GP', '2024-09-07', '2024-09-18', 'Subway Surf', 101, 1, NULL, '05:26:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `modmenus`
--
ALTER TABLE `modmenus`
  ADD PRIMARY KEY (`No`);

--
-- Indexes for table `userkeys`
--
ALTER TABLE `userkeys`
  ADD PRIMARY KEY (`No`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `modmenus`
--
ALTER TABLE `modmenus`
  MODIFY `No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `userkeys`
--
ALTER TABLE `userkeys`
  MODIFY `No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
