-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2024 at 08:32 AM
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
-- Database: `young6v2_production`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_data_limit`
--

CREATE TABLE `add_data_limit` (
  `id` bigint(123) UNSIGNED NOT NULL,
  `model_name` varchar(123) DEFAULT NULL,
  `action` varchar(123) DEFAULT NULL,
  `count` bigint(123) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `add_data_limit`
--

INSERT INTO `add_data_limit` (`id`, `model_name`, `action`, `count`, `created_at`, `updated_at`) VALUES
(2, 'App/Model/AddDataLimit', 'create', 7, '2024-01-25 07:29:53', '2024-01-25 15:29:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_data_limit`
--
ALTER TABLE `add_data_limit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_data_limit`
--
ALTER TABLE `add_data_limit`
  MODIFY `id` bigint(123) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
