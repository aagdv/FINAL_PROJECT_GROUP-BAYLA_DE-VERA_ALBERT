-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2025 at 08:33 PM
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
-- Database: `helpdeskdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `ticket_id`, `username`, `comment`, `created_at`) VALUES
(1, 1, 'admin', 'asdasd', '2025-06-15 00:36:26'),
(2, 5, 'admin', 'sasd', '2025-06-15 02:23:32'),
(3, 5, 'admin', 'asdasd', '2025-06-15 02:23:51'),
(4, 5, 'admin', 'hello', '2025-06-15 02:29:53'),
(5, 5, 'admin', 'hi', '2025-06-15 02:29:56'),
(6, 5, 'dave', 'ah', '2025-06-15 02:56:52'),
(7, 5, 'dave', 'ah', '2025-06-15 02:56:54'),
(8, 5, 'dave', 'haha', '2025-06-15 02:58:18'),
(9, 5, 'dave', 'hgehehe', '2025-06-15 03:04:50'),
(10, 5, 'admin', 'hahaha', '2025-06-15 03:07:46'),
(11, 5, 'dave', 'weeee', '2025-06-15 03:08:07'),
(12, 5, 'admin', 'lol', '2025-06-15 03:08:13'),
(13, 5, 'admin', 'hi po', '2025-06-15 03:12:27'),
(14, 5, 'dave', 'hello', '2025-06-15 03:12:39'),
(15, 5, 'admin', 'pakiayos naman po ng kuryente namin', '2025-06-15 03:12:52'),
(16, 5, 'dave', 'okay po', '2025-06-15 03:12:59'),
(17, 5, 'dave', 'haha', '2025-06-15 03:13:21'),
(18, 5, 'admin', 'hahahaa', '2025-06-15 03:13:26'),
(19, 5, 'admin', 'hahaha', '2025-06-15 03:13:31'),
(20, 5, 'admin', 'ahahahahah', '2025-06-15 03:13:38'),
(21, 6, 'jrbayla', 'hehehe', '2025-06-15 03:15:01'),
(22, 6, 'admin', 'ano', '2025-06-15 03:15:16'),
(23, 6, 'jrbayla', 'haha', '2025-06-15 03:15:26'),
(24, 13, 'admin', 'boss ano pong problema?', '2025-06-15 03:28:20'),
(25, 13, 'dave', 'nasira wifi namin boss eh', '2025-06-15 03:28:33'),
(26, 13, 'dave', 'ay ganun ba?', '2025-06-15 03:28:46'),
(27, 13, 'admin', 'ay ganun ba?', '2025-06-15 03:28:54'),
(28, 13, 'dave', 'okay', '2025-06-15 03:29:00'),
(29, 16, 'dave', 'Yb tayo bay', '2025-06-15 12:39:24'),
(30, 16, 'admin', 'tara kila boyet', '2025-06-15 12:39:32'),
(31, 16, 'admin', 'a', '2025-06-15 12:49:02'),
(32, 16, 'admin', 'a', '2025-06-15 12:49:05'),
(33, 16, 'admin', 'a', '2025-06-15 12:49:52'),
(34, 17, 'dave', 'boss', '2025-06-15 12:54:59'),
(35, 17, 'admin', 'yow', '2025-06-15 12:55:09'),
(36, 17, 'admin', 'balita?', '2025-06-15 12:55:13'),
(37, 17, 'dave', 'paayos sana ko database boss e nag eerrror', '2025-06-15 12:55:25'),
(38, 17, 'admin', 'awut sige', '2025-06-15 12:55:31'),
(39, 17, 'admin3', 'h', '2025-06-15 21:34:13'),
(40, 18, 'admin3', 'he', '2025-06-15 21:48:39'),
(41, 18, 'admin3', 'hehe', '2025-06-15 21:48:47'),
(42, 18, 'admin3', 'hehehe', '2025-06-15 21:48:51'),
(43, 18, 'admin3', 'asdasd', '2025-06-15 21:49:13'),
(44, 18, 'admin3', 'hehehe', '2025-06-15 21:52:18'),
(45, 18, 'admin3', 'hi', '2025-06-15 21:53:13'),
(46, 18, 'admin3', 'hu', '2025-06-15 21:55:01'),
(47, 18, 'admin3', 'hi', '2025-06-15 21:55:30'),
(48, 18, 'admin3', 'hhehehe', '2025-06-15 21:55:49'),
(49, 18, 'admin3', 'hu', '2025-06-15 21:55:53'),
(50, 18, 'admin3', 'bosss', '2025-06-15 21:57:19'),
(51, 18, 'admin3', 'ahahaha', '2025-06-15 21:59:44'),
(52, 18, 'admin3', 'hehe', '2025-06-15 21:59:52'),
(53, 18, 'admin3', 'hi', '2025-06-15 22:04:32'),
(54, 18, 'admin3', 'haha', '2025-06-15 22:05:48'),
(55, 18, 'admin3', 'ehehe', '2025-06-15 22:05:53'),
(56, 18, 'admin3', 'hehehe', '2025-06-15 22:05:56'),
(57, 18, 'admin3', 'hahaha', '2025-06-15 22:28:23'),
(58, 18, 'admin3', 'hahahaha', '2025-06-15 22:28:26'),
(59, 18, 'admin3', 'ahhahaha', '2025-06-15 22:28:27'),
(60, 18, 'admin3', 'hahahah', '2025-06-15 22:28:31'),
(61, 18, 'admin3', 'ahahah', '2025-06-15 22:28:32'),
(62, 18, 'admin3', 'bosss', '2025-06-15 22:33:16'),
(63, 18, 'dave', 'musta boss', '2025-06-15 22:33:52'),
(64, 18, 'admin3', 'ano na?', '2025-06-15 22:33:57'),
(65, 18, 'dave', 'weh', '2025-06-15 22:34:01'),
(66, 18, 'admin3', 'uowwewewewe', '2025-06-15 22:43:25'),
(67, 18, 'admin3', 'wowowow', '2025-06-15 22:43:29'),
(68, 19, 'admin3', 'hello sir', '2025-06-15 23:15:18'),
(69, 20, 'admin3', 'hahahaha', '2025-06-15 23:21:06'),
(70, 22, 'admin3', 'balita bro?', '2025-06-15 23:22:24'),
(71, 23, 'admin3', 'hi po', '2025-06-16 00:36:19'),
(72, 23, 'dave', 'problem po sir eh', '2025-06-16 00:36:29'),
(73, 23, 'admin3', 'saan?', '2025-06-16 00:36:38'),
(74, 22, 'admin3', 'hello', '2025-06-16 00:38:27');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `issue` text DEFAULT NULL,
  `priority` varchar(10) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Open',
  `submitted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `name`, `email`, `issue`, `priority`, `status`, `submitted_by`, `created_at`) VALUES
(22, 'jrbayla', '', 'hehee', 'Low', 'In Progress', 'jrbayla', '2025-06-15 15:22:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) DEFAULT 'client'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(9, 'dave', '$2y$10$B03vG9v80gGdjcWo1M.pkOErjJ5v92pMLhChJRuflOSxplS4Cry9C', 'client'),
(10, 'jrbayla', '$2y$10$x.mMGIo6/.wKShCbh5pz6uHmCF.yIoZ9sYnXbOxtT/Is3kpi0kiJy', 'client'),
(12, 'admin2', '$2y$10$VZ4SQxNCxs5NKCE7bsLKMucRDQpbJzzwIWcyBJQ.uqNjohKFNK1DK', 'admin'),
(13, 'admin3', '$2y$10$UfHjZ7UOhp45FrwNmsxAV.dU0Qd9doKhjHWq96PEOuDdREHwxlW0q', 'admin'),
(14, 'testadmin', '$2y$10$2PvGUkm8WYrxFtqZALfXz.ONR/l7ungSUyXMgu41mSN9Wa4bkOnU6', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
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
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
