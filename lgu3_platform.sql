-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2026 at 04:50 AM
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
-- Database: `lgu3_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `fullname`, `email`, `password`, `created_at`) VALUES
(3, 'Emerson Bustamante', 'emerson@gmail.com', '$2y$10$EC55F7G2xVADIzO4s5KdjO6dNf0nW1Z3SNbM7aJ4hHJvvLn3HaZDK', '2026-01-26 08:02:14'),
(26, 'Zyrill John David', 'Zyzy@gmail.com', '$2y$10$IubZgwvdVN8DQhXk1x5ftOrCqOzJPpNkYQ8IJep2tfjxWIPeW/Zgq', '2026-01-28 11:23:38'),
(32, 'Cedrick Dominiq Barro', '123456@gmail.com', '$2y$10$NqAmxUPXYAs45I..efYrlu2irOIP.fXlIgOijBgtsWtxWjhi.GfnC', '2026-02-01 08:36:14'),
(33, 'Cedrick Dominiq Barro', 'qwer@gmail.com', '$2y$10$SoX6rzFLZIkBeHM.pTpFteHvYRRKYRKMkwEL0IB9gyyV5GhgwRGuK', '2026-02-01 10:20:48');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_logs`
--

CREATE TABLE `chatbot_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `prompt` text NOT NULL,
  `reply` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatbot_logs`
--

INSERT INTO `chatbot_logs` (`id`, `user_id`, `prompt`, `reply`, `created_at`) VALUES
(1, NULL, '', 'No response from OpenAI.', '2026-02-03 13:09:41'),
(2, NULL, '', 'No response from OpenAI.', '2026-02-03 13:13:20'),
(3, NULL, '', 'No response from OpenAI.', '2026-02-03 13:13:28'),
(4, NULL, '', 'No response from OpenAI.', '2026-02-03 13:13:35'),
(5, NULL, '', 'No response from OpenAI.', '2026-02-03 13:13:37'),
(6, NULL, '', 'No response from OpenAI.', '2026-02-03 13:15:07'),
(7, NULL, '', 'No response from OpenAI.', '2026-02-03 13:15:11'),
(8, NULL, '', 'No response from OpenAI.', '2026-02-03 13:15:12'),
(9, NULL, '', 'No response from OpenAI.', '2026-02-03 13:15:12');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `user_id`, `title`, `created_at`) VALUES
(4, 14, 'testst...', '2026-02-04 11:51:56'),
(5, 14, 'asdasdad...', '2026-02-04 11:52:27'),
(27, 12, 'I need some help with farming tools', '2026-02-04 12:38:00'),
(28, 12, 'kumusta?', '2026-02-06 04:35:04');

-- --------------------------------------------------------

--
-- Table structure for table `farm_videos`
--

CREATE TABLE `farm_videos` (
  `id` int(11) NOT NULL,
  `youtube_id` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `market_data`
--

CREATE TABLE `market_data` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `status` enum('Stable','Increasing','Decreasing') DEFAULT 'Stable',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `role` enum('user','bot') NOT NULL,
  `content` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `role`, `content`, `image_path`, `created_at`) VALUES
(7, 4, 'user', 'testst', NULL, '2026-02-04 11:51:56'),
(8, 4, 'bot', 'OpenAI error: You exceeded your current quota, please check your plan and billing details. For more information on this error, read the docs: https://platform.openai.com/docs/guides/error-codes/api-errors.', NULL, '2026-02-04 11:51:57'),
(9, 5, 'user', 'asdasdad', 'C:\\XAMPPP\\htdocs\\LGU3/uploads/1770205948_Screenshot 2025-12-18 100112.png', '2026-02-04 11:52:28'),
(10, 5, 'bot', 'OpenAI error: You exceeded your current quota, please check your plan and billing details. For more information on this error, read the docs: https://platform.openai.com/docs/guides/error-codes/api-errors.', NULL, '2026-02-04 11:52:29'),
(39, 27, 'user', 'I need some help with farming tools', NULL, '2026-02-04 12:38:06'),
(40, 27, 'bot', 'Sure! What specific information or assistance do you need regarding farming tools? Are you looking for recommendations, descriptions of tools, maintenance tips, or something else? Let me know how I can help you!', NULL, '2026-02-04 12:38:08'),
(41, 28, 'user', 'kumusta?', NULL, '2026-02-06 04:35:04'),
(42, 28, 'bot', 'Kumusta! Ano ang maitutulong ko sa iyo ngayon?', NULL, '2026-02-06 04:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `pest_categories`
--

CREATE TABLE `pest_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pest_categories`
--

INSERT INTO `pest_categories` (`id`, `name`, `created_at`) VALUES
(4, 'test', '2026-02-03 11:39:35');

-- --------------------------------------------------------

--
-- Table structure for table `pest_videos`
--

CREATE TABLE `pest_videos` (
  `id` int(11) NOT NULL,
  `youtube_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pest_videos`
--

INSERT INTO `pest_videos` (`id`, `youtube_id`, `title`, `category_id`, `created_at`) VALUES
(2, 'IAG1zOgQ7fk', '1234', 4, '2026-02-03 11:39:41'),
(3, 'PLrd-rILKtc', '1', 4, '2026-02-03 11:43:32'),
(4, '0yjMwZylvyk', '2', 4, '2026-02-03 11:43:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `address`, `password`, `created_at`, `reset_token`, `reset_expires`) VALUES
(12, 'Emerson Bustamante', 'EmersonBustamante@gmail.com', '1234567809', '1asd1312as', '$2y$10$ujAnvVxpCM0b4Nse2BMjZe/rM30db4gwNh0eNvSS6Ce4GnnG25pfq', '2026-01-28 11:22:19', NULL, NULL),
(13, 'Cedrick dominiq barro', 'cedrickdominiqbarro@gmail.com', '1235451265', 'asdqweqwe', '$2y$10$UuYEC46eShMuLtR2SSCsbeAER.o3riIYABFXFEWi9XFfw9O8Knlni', '2026-02-04 07:37:08', 'efc7894d0221fa2a7c0467462df27954', '2026-02-04 14:51:21'),
(15, 'Cedrick dominiq barro', 'cdbarro@gmail.com', '1234123123', 'asdeqwewq', '$2y$10$E80jo00aTHZ5B0rvMohii.YsQJhlqWwhhXlASY4/0E8PPSfqyOjtm', '2026-02-04 12:38:19', NULL, NULL),
(16, 'Nicson Bustamante', 'david@gmail.com', '09933608401', '21 fortuna', '$2y$10$YfKrTwh7TxQiTgR63D2A.e8KA7JxLz.vXg7V6VbhfnRnhOSFrHCXC', '2026-02-06 23:26:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `weather_queries`
--

CREATE TABLE `weather_queries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `forecast_date` date DEFAULT NULL,
  `temperature` decimal(5,2) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `chatbot_logs`
--
ALTER TABLE `chatbot_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `farm_videos`
--
ALTER TABLE `farm_videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `market_data`
--
ALTER TABLE `market_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`);

--
-- Indexes for table `pest_categories`
--
ALTER TABLE `pest_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pest_videos`
--
ALTER TABLE `pest_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pest_category` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `weather_queries`
--
ALTER TABLE `weather_queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `chatbot_logs`
--
ALTER TABLE `chatbot_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `farm_videos`
--
ALTER TABLE `farm_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `market_data`
--
ALTER TABLE `market_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `pest_categories`
--
ALTER TABLE `pest_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pest_videos`
--
ALTER TABLE `pest_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `weather_queries`
--
ALTER TABLE `weather_queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pest_videos`
--
ALTER TABLE `pest_videos`
  ADD CONSTRAINT `fk_pest_category` FOREIGN KEY (`category_id`) REFERENCES `pest_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `weather_queries`
--
ALTER TABLE `weather_queries`
  ADD CONSTRAINT `weather_queries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
