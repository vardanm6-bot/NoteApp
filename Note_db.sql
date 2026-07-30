-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 28, 2026 at 12:33 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Note_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `text` text COLLATE utf8mb4_general_ci,
  `content` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `title`, `text`, `content`) VALUES
(3, 1, 'dfdsad', NULL, 'dsadsadsadsadsa'),
(4, 1, 'd', NULL, 'ddddddddsadasdsadsadsad'),
(5, 1, 'ds', NULL, 'sdasdsadddddd');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `firstName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `password`) VALUES
(1, 'Vardan', 'Martirosyan', 'vardan.m6@tumo.org', '$2y$10$xUuIGV4bFnNRSViGUfIcUuxyimgrXvxBviEgTXVLAg5Su2Gc9Tvd.'),
(2, 'eadfthrnjy', 'rsthj', 'srym@qetsjryt', '1234'),
(3, 'dsdsa', 'sadasdasd', 'dsadsa@dsad', '1234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
