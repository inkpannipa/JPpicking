-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 30, 2025 at 02:47 PM
-- Server version: 10.6.19-MariaDB-log
-- PHP Version: 8.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hanshin_japan`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_parts`
--

CREATE TABLE `order_parts` (
  `id` int(11) NOT NULL,
  `customer` varchar(100) NOT NULL,
  `nameproduct` varchar(255) NOT NULL,
  `nameproduct1` varchar(255) NOT NULL,
  `duedate` varchar(50) NOT NULL,
  `order_no` varchar(50) DEFAULT NULL,
  `main` varchar(50) DEFAULT NULL,
  `mainstatus` varchar(50) NOT NULL,
  `qtymain` varchar(50) NOT NULL,
  `nt` varchar(50) DEFAULT NULL,
  `ntstatus` varchar(50) NOT NULL,
  `w` varchar(50) DEFAULT NULL,
  `wstatus` varchar(50) NOT NULL,
  `sw` varchar(50) DEFAULT NULL,
  `swstatus` varchar(50) NOT NULL,
  `tw` varchar(50) DEFAULT NULL,
  `twstatus` varchar(50) NOT NULL,
  `cs` varchar(50) DEFAULT NULL,
  `csstatus` varchar(50) NOT NULL,
  `status_check` varchar(50) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `order_parts`
--

INSERT INTO `order_parts` (`id`, `customer`, `nameproduct`, `nameproduct1`, `duedate`, `order_no`, `main`, `mainstatus`, `qtymain`, `nt`, `ntstatus`, `w`, `wstatus`, `sw`, `swstatus`, `tw`, `twstatus`, `cs`, `csstatus`, `status_check`, `uploaded_at`) VALUES
(182, 'テスト', 'CAPﾎﾞﾙﾄ8X20', '', '20250730', '01960293', '1-F-3-6\"', '', '100', '4-E-1-3\"', '', '4-E-1-2\"', '', '4-E-1-1\"', '', '', '', '', '', '', '2025-07-30 07:23:13'),
(183, 'テスト', '生地六角ﾎﾞﾙﾄ全ﾈｼﾞ20X50', '', '20250730', '01960294', '1-L-4-3\"', '', '120', '4-E-3-3\"', '', '4-E-3-2\"', '', '', '', '', '', '', '', '2025-07-30 14:34:10', '2025-07-30 07:23:13'),
(184, 'テスト', 'SUS六角ﾎﾞﾙﾄ全ねじ輸入12X30', '', '20250730', '01960295', '13-A-1-2\"', '', '100', '', '', '', '', '5-C-3-1\"', '', '5-E-2-5\"', '', '', '', '2025-07-30 14:27:51', '2025-07-30 07:23:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order_parts`
--
ALTER TABLE `order_parts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_parts`
--
ALTER TABLE `order_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
