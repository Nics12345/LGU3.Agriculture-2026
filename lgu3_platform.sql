-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2026 at 11:03 AM
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
(26, 'Zyrill John David', 'Zyzy@gmail.com', '$2y$10$IubZgwvdVN8DQhXk1x5ftOrCqOzJPpNkYQ8IJep2tfjxWIPeW/Zgq', '2026-01-28 11:23:38'),
(37, 'Nicson Bustamante', 'EmersonBustamante@gmail.com', '$2y$10$TfUnJLUP8kHBezsREs2wSOTlQqMthEGuctjF/kBb76t9B6EJvky72', '2026-02-09 10:07:51'),
(38, 'Nicson Bustamante', 'wrightaudrey505@gmail.com', '$2y$10$ThunH0B6BVb/qBYF2aMnVOKc0r7eIZ5qR67U.AJhxvPzoVwvkH6MG', '2026-02-10 13:30:57');

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
(28, 12, 'kumusta?', '2026-02-06 04:35:04'),
(29, 17, 'Who is thius', '2026-02-11 10:00:50');

-- --------------------------------------------------------

--
-- Table structure for table `farm_images`
--

CREATE TABLE `farm_images` (
  `id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farm_images`
--

INSERT INTO `farm_images` (`id`, `file_path`, `title`, `description`, `created_at`) VALUES
(12, 'uploads/farm_images/1770882209_images (13).jpg', 'aewe', 'asdawdwae', '2026-02-12 07:43:29');

-- --------------------------------------------------------

--
-- Table structure for table `farm_videos`
--

CREATE TABLE `farm_videos` (
  `id` int(11) NOT NULL,
  `youtube_id` varchar(20) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farm_videos`
--

INSERT INTO `farm_videos` (`id`, `youtube_id`, `file_path`, `title`, `description`, `created_at`) VALUES
(36, 'gLpktnJgbbE', NULL, 'dwewewa', 'sdwasd', '2026-02-12 07:43:18'),
(37, '', 'uploads/farm_videos/1770882203_Screen Recording 2026-01-28 192357.mp4', 'awea', 'asdwqdaee', '2026-02-12 07:43:23');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `market_data`
--

INSERT INTO `market_data` (`id`, `product_name`, `price`, `unit`, `status`, `updated_at`, `category`) VALUES
(49, 'Basmati Rice', 207.86, 'kg', 'Stable', '2026-02-11 16:00:00', 'Imported Commercial Rice'),
(50, 'Glutinous Rice', 60.93, 'kg', 'Stable', '2026-02-11 16:00:00', 'Imported Commercial Rice'),
(51, 'Jasponica/Japonica Rice', 62.61, 'kg', 'Stable', '2026-02-11 16:00:00', 'Imported Commercial Rice'),
(52, 'Other Special Rice (White)', 60.60, 'kg', 'Stable', '2026-02-11 16:00:00', 'Imported Commercial Rice'),
(53, 'Glutinous Rice', 74.13, 'kg', 'Stable', '2026-02-11 16:00:00', 'Local Commercial Rice'),
(54, 'Other Special Rice (White)', 58.94, 'kg', 'Stable', '2026-02-11 16:00:00', 'Local Commercial Rice');

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
(42, 28, 'bot', 'Kumusta! Ano ang maitutulong ko sa iyo ngayon?', NULL, '2026-02-06 04:35:06'),
(43, 29, 'user', 'Who is thius', 'C:\\XAMPPP\\htdocs\\LGU3/uploads/1770804050_92bdb715-2730-4546-9440-c83cb89f296d.jpg', '2026-02-11 10:00:50'),
(44, 29, 'bot', 'OpenAI error: Failed to download image from file://C:\\XAMPPP\\htdocs\\LGU3/uploads/1770804050_92bdb715-2730-4546-9440-c83cb89f296d.jpg.', NULL, '2026-02-11 10:00:52');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `created_at`, `is_read`, `link`) VALUES
(10, '📘 New farm guide uploaded: asd', '2026-02-11 08:33:33', 1, 'guides.php'),
(11, '📘 New farm image uploaded: asd', '2026-02-11 08:58:05', 1, 'guides.php'),
(12, '📘 New farm image uploaded: Untitled Image', '2026-02-11 09:09:11', 1, 'guides.php'),
(13, '📘 New farm image uploaded: Untitled Image', '2026-02-11 09:14:03', 1, 'guides.php'),
(14, '📘 New farm image uploaded: e123123as3', '2026-02-11 09:40:07', 1, 'guides.php'),
(15, '📘 New farm video uploaded: e 12313sa3', '2026-02-11 09:40:19', 1, 'guides.php'),
(16, '📘 New farm image uploaded: 12313as3', '2026-02-11 09:40:40', 1, 'guides.php'),
(17, '📘 New farm image uploaded: qwe123as', '2026-02-11 09:42:20', 1, 'guides.php'),
(18, '📘 New farm image uploaded: qwease', '2026-02-11 09:42:39', 1, 'guides.php'),
(19, '📘 New farm image uploaded: 123123aw3', '2026-02-11 09:44:05', 1, 'guides.php'),
(20, '📘 New farm video uploaded: 31231as3a', '2026-02-11 09:44:20', 1, 'guides.php'),
(21, '📘 New farm image uploaded: qweqease', '2026-02-11 09:44:31', 1, 'guides.php'),
(22, '📘 New farm guide added: 123as313 as', '2026-02-11 11:56:28', 1, 'guides.php'),
(23, '📘 New farm guide added: e qe12312312', '2026-02-11 11:56:35', 1, 'guides.php'),
(24, '📘 New farm guide added: 1231231as', '2026-02-11 12:07:51', 0, 'guides.php'),
(25, '📘 New farm guide added: asdqwe ase', '2026-02-11 12:09:31', 0, 'guides.php'),
(26, '📘 New farm guide added: asdqwease', '2026-02-11 12:14:31', 0, 'guides.php'),
(27, '📘 New farm guide added: qweqweasd wqe', '2026-02-11 12:37:07', 0, 'guides.php'),
(28, '🐛 Pest guide deleted (ID: 32)', '2026-02-11 12:37:12', 0, 'pest.php'),
(29, '🐛 New pest guide added: asdqweasd', '2026-02-11 12:41:09', 0, 'pest.php'),
(30, '🐛 New pest guide added: sadasdqwe a', '2026-02-11 12:45:40', 0, 'pest.php'),
(31, '🐛 New pest guide added: qweaseqw', '2026-02-12 03:39:04', 0, 'pest.php'),
(32, '🐛 Pest guide deleted (ID: 1)', '2026-02-12 03:39:12', 0, 'pest.php'),
(33, '🐛 Pest guide deleted (ID: 2)', '2026-02-12 03:39:14', 0, 'pest.php'),
(34, '📘 New farm guide added: qwease', '2026-02-12 03:39:22', 0, 'guides.php'),
(35, '🐛 Pest guide deleted (ID: 33)', '2026-02-12 03:39:24', 0, 'pest.php'),
(36, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:04:21', 0, 'weather-notify.php'),
(37, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:04:21', 0, 'weather-notify.php'),
(38, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:04:21', 0, 'weather-notify.php'),
(39, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:04:21', 0, 'weather-notify.php'),
(40, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:04:24', 0, 'weather-notify.php'),
(41, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:04:24', 0, 'weather-notify.php'),
(42, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:04:24', 0, 'weather-notify.php'),
(43, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:04:24', 0, 'weather-notify.php'),
(44, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:05:54', 0, 'weather-notify.php'),
(45, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:05:54', 0, 'weather-notify.php'),
(46, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:05:54', 0, 'weather-notify.php'),
(47, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:05:54', 0, 'weather-notify.php'),
(48, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:06:01', 0, 'weather-notify.php'),
(49, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:06:01', 0, 'weather-notify.php'),
(50, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:06:01', 0, 'weather-notify.php'),
(51, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:06:01', 0, 'weather-notify.php'),
(52, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:06:01', 0, 'weather-notify.php'),
(53, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:06:01', 0, 'weather-notify.php'),
(54, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:06:01', 0, 'weather-notify.php'),
(55, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:06:01', 0, 'weather-notify.php'),
(56, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:06:02', 1, 'weather-notify.php'),
(57, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:06:02', 1, 'weather-notify.php'),
(58, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:06:02', 1, 'weather-notify.php'),
(59, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:06:02', 0, 'weather-notify.php'),
(60, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:06:56', 0, 'weather-notify.php'),
(61, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:06:56', 0, 'weather-notify.php'),
(62, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:06:56', 0, 'weather-notify.php'),
(63, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:06:56', 0, 'weather-notify.php'),
(64, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:06:59', 0, 'weather-notify.php'),
(65, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:06:59', 0, 'weather-notify.php'),
(66, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:06:59', 0, 'weather-notify.php'),
(67, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:07:00', 0, 'weather-notify.php'),
(68, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:07:08', 0, 'weather-notify.php'),
(69, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:07:08', 0, 'weather-notify.php'),
(70, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:07:08', 0, 'weather-notify.php'),
(71, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:07:08', 0, 'weather-notify.php'),
(72, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:10:06', 0, 'weather-notify.php'),
(73, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:10:06', 0, 'weather-notify.php'),
(74, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:10:06', 0, 'weather-notify.php'),
(75, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:10:06', 0, 'weather-notify.php'),
(76, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:10:08', 1, 'weather-notify.php'),
(77, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:10:08', 0, 'weather-notify.php'),
(78, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:10:08', 0, 'weather-notify.php'),
(79, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:10:08', 0, 'weather-notify.php'),
(80, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:10:12', 0, 'weather-notify.php'),
(81, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:10:12', 0, 'weather-notify.php'),
(82, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:10:12', 0, 'weather-notify.php'),
(83, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:10:12', 0, 'weather-notify.php'),
(84, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:10:44', 0, 'weather-notify.php'),
(85, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:10:44', 0, 'weather-notify.php'),
(86, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:10:44', 0, 'weather-notify.php'),
(87, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:10:44', 0, 'weather-notify.php'),
(88, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:11:12', 1, 'weather-notify.php'),
(89, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:11:12', 0, 'weather-notify.php'),
(90, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:11:12', 0, 'weather-notify.php'),
(91, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:11:12', 0, 'weather-notify.php'),
(92, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:22', 0, 'weather-notify.php'),
(93, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:22', 0, 'weather-notify.php'),
(94, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:22', 0, 'weather-notify.php'),
(95, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:22', 0, 'weather-notify.php'),
(96, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:22', 0, 'weather-notify.php'),
(97, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:22', 0, 'weather-notify.php'),
(98, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:22', 0, 'weather-notify.php'),
(99, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:22', 0, 'weather-notify.php'),
(100, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:24', 0, 'weather-notify.php'),
(101, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:24', 0, 'weather-notify.php'),
(102, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:24', 0, 'weather-notify.php'),
(103, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:24', 0, 'weather-notify.php'),
(104, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:25', 0, 'weather-notify.php'),
(105, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:25', 0, 'weather-notify.php'),
(106, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:25', 0, 'weather-notify.php'),
(107, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:25', 0, 'weather-notify.php'),
(108, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:26', 0, 'weather-notify.php'),
(109, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:27', 0, 'weather-notify.php'),
(110, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:27', 1, 'weather-notify.php'),
(111, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:27', 0, 'weather-notify.php'),
(112, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:27', 0, 'weather-notify.php'),
(113, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:27', 0, 'weather-notify.php'),
(114, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:27', 0, 'weather-notify.php'),
(115, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:27', 0, 'weather-notify.php'),
(116, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:33', 0, 'weather-notify.php'),
(117, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:33', 0, 'weather-notify.php'),
(118, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:33', 0, 'weather-notify.php'),
(119, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:33', 0, 'weather-notify.php'),
(120, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:33', 0, 'weather-notify.php'),
(121, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:33', 0, 'weather-notify.php'),
(122, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:33', 0, 'weather-notify.php'),
(123, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:33', 0, 'weather-notify.php'),
(124, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:34', 0, 'weather-notify.php'),
(125, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:34', 0, 'weather-notify.php'),
(126, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:34', 0, 'weather-notify.php'),
(127, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:34', 0, 'weather-notify.php'),
(128, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:34', 0, 'weather-notify.php'),
(129, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:34', 0, 'weather-notify.php'),
(130, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:34', 0, 'weather-notify.php'),
(131, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:34', 0, 'weather-notify.php'),
(132, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(133, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(134, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(135, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(136, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(137, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(138, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(139, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(140, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(141, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(142, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(143, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(144, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(145, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(146, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(147, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(148, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(149, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(150, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(151, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:36', 0, 'weather-notify.php'),
(152, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:59', 1, 'weather-notify.php'),
(153, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:59', 0, 'weather-notify.php'),
(154, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:59', 0, 'weather-notify.php'),
(155, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:59', 0, 'weather-notify.php'),
(156, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:59', 0, 'weather-notify.php'),
(157, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:59', 0, 'weather-notify.php'),
(158, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:59', 0, 'weather-notify.php'),
(159, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:59', 0, 'weather-notify.php'),
(160, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:27:00', 0, 'weather-notify.php'),
(161, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:27:00', 0, 'weather-notify.php'),
(162, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:27:00', 0, 'weather-notify.php'),
(163, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:27:00', 0, 'weather-notify.php'),
(164, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:27:00', 0, 'weather-notify.php'),
(165, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:27:00', 0, 'weather-notify.php'),
(166, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:27:00', 0, 'weather-notify.php'),
(167, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:27:00', 0, 'weather-notify.php'),
(168, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:27:00', 0, 'weather-notify.php'),
(169, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:27:00', 0, 'weather-notify.php'),
(170, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:27:00', 0, 'weather-notify.php'),
(171, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:27:00', 0, 'weather-notify.php'),
(172, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:27:01', 0, 'weather-notify.php'),
(173, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:27:01', 0, 'weather-notify.php'),
(174, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:27:01', 0, 'weather-notify.php'),
(175, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:27:01', 0, 'weather-notify.php'),
(176, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:27:03', 0, 'weather-notify.php'),
(177, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:27:03', 0, 'weather-notify.php'),
(178, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:27:03', 0, 'weather-notify.php'),
(179, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:27:03', 0, 'weather-notify.php'),
(180, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:30:51', 0, 'weather-notify.php'),
(181, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:30:51', 0, 'weather-notify.php'),
(182, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:30:51', 0, 'weather-notify.php'),
(183, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:30:51', 0, 'weather-notify.php'),
(184, '📊 DA Market Data updated for February 12, 2026', '2026-02-12 05:43:56', 1, 'user-market-data.php'),
(185, '📊 DA Market Data updated for February 12, 2026', '2026-02-12 05:44:30', 0, 'user-market-data.php'),
(186, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:44:42', 0, 'weather-notify.php'),
(187, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:44:42', 0, 'weather-notify.php'),
(188, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:44:42', 0, 'weather-notify.php'),
(189, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:44:42', 0, 'weather-notify.php'),
(190, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:44:42', 0, 'weather-notify.php'),
(191, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:44:42', 0, 'weather-notify.php'),
(192, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:44:42', 0, 'weather-notify.php'),
(193, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:44:42', 0, 'weather-notify.php'),
(194, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:44:42', 0, 'weather-notify.php'),
(195, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:44:42', 0, 'weather-notify.php'),
(196, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:44:42', 0, 'weather-notify.php'),
(197, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:44:42', 0, 'weather-notify.php'),
(198, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:44:43', 0, 'weather-notify.php'),
(199, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:44:43', 0, 'weather-notify.php'),
(200, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:44:43', 0, 'weather-notify.php'),
(201, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:44:43', 0, 'weather-notify.php'),
(202, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:44:44', 0, 'weather-notify.php'),
(203, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:44:44', 0, 'weather-notify.php'),
(204, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:44:44', 0, 'weather-notify.php'),
(205, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:44:44', 0, 'weather-notify.php'),
(206, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:44:46', 0, 'weather-notify.php'),
(207, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:44:46', 0, 'weather-notify.php'),
(208, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:44:46', 0, 'weather-notify.php'),
(209, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:44:46', 0, 'weather-notify.php'),
(210, '🐛 New pest guide added: 12313as', '2026-02-12 05:45:27', 1, 'pest.php'),
(211, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:49:13', 0, 'weather-notify.php'),
(212, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:49:13', 0, 'weather-notify.php'),
(213, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:49:13', 0, 'weather-notify.php'),
(214, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:49:13', 0, 'weather-notify.php'),
(215, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:52:17', 0, 'weather-notify.php'),
(216, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:52:17', 0, 'weather-notify.php'),
(217, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:52:17', 0, 'weather-notify.php'),
(218, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:52:17', 0, 'weather-notify.php'),
(219, '⚠ Severe Weather Alert - Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 06:01:34', 0, 'weather-notify.php'),
(220, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 06:01:34', 0, 'weather-notify.php'),
(221, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 06:01:34', 0, 'weather-notify.php'),
(222, '⚠ Severe Weather Alert - Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 06:01:34', 0, 'weather-notify.php'),
(223, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:50:41', 0, 'weather-notify.php'),
(224, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:50:41', 0, 'weather-notify.php'),
(225, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:50:41', 0, 'weather-notify.php'),
(226, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:50:41', 0, 'weather-notify.php'),
(227, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:50:41', 0, 'weather-notify.php'),
(228, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:50:41', 0, 'weather-notify.php'),
(229, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:50:42', 0, 'weather-notify.php'),
(230, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:50:42', 0, 'weather-notify.php'),
(231, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:50:43', 0, 'weather-notify.php'),
(232, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:50:43', 0, 'weather-notify.php'),
(233, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:50:43', 0, 'weather-notify.php'),
(234, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:50:43', 0, 'weather-notify.php'),
(235, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:52:23', 0, 'weather-notify.php'),
(236, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:52:23', 0, 'weather-notify.php'),
(237, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:52:23', 0, 'weather-notify.php'),
(238, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:52:23', 0, 'weather-notify.php'),
(239, '🐛 New pest guide added: qweaseqw eas', '2026-02-12 06:52:55', 0, 'pest.php'),
(240, '🐛 New pest guide added: 2313asd12ed12', '2026-02-12 06:53:03', 0, 'pest.php'),
(241, '📘 New farm guide added: 4yrtbrtgrttretret', '2026-02-12 06:53:10', 0, 'guides.php'),
(242, '📘 New farm video uploaded: 57575675675685', '2026-02-12 06:53:17', 0, 'guides.php'),
(243, '📘 New farm image uploaded: 5675675698', '2026-02-12 06:53:23', 1, 'guides.php'),
(244, '🐛 Pest guide deleted (ID: 35)', '2026-02-12 06:53:50', 0, 'pest.php'),
(245, '🐛 Pest guide deleted (ID: 6)', '2026-02-12 06:53:51', 0, 'pest.php'),
(246, '🐛 Pest guide deleted (ID: 5)', '2026-02-12 06:53:53', 0, 'pest.php'),
(247, '🐛 Pest guide deleted (ID: 5)', '2026-02-12 06:53:56', 0, 'pest.php'),
(248, '🐛 Pest guide deleted (ID: 4)', '2026-02-12 06:53:58', 0, 'pest.php'),
(249, '🐛 Pest guide deleted (ID: 3)', '2026-02-12 06:54:00', 0, 'pest.php'),
(250, '🐛 New pest guide added: 123123123', '2026-02-12 06:54:06', 0, 'pest.php'),
(251, '🐛 Pest guide updated: 123123123', '2026-02-12 06:54:09', 0, 'pest.php'),
(252, '🐛 Pest guide updated: 123123123', '2026-02-12 06:54:13', 0, 'pest.php'),
(253, '🐛 Pest guide deleted (ID: 35)', '2026-02-12 06:54:17', 0, 'pest.php'),
(254, '🐛 Pest guide deleted (ID: 35)', '2026-02-12 06:54:21', 0, 'pest.php'),
(255, '🐛 Pest guide deleted (ID: 35)', '2026-02-12 07:01:09', 0, 'pest.php'),
(256, '🐛 Pest guide deleted (ID: 7)', '2026-02-12 07:02:57', 0, 'pest.php'),
(257, '🐛 Pest guide updated: 57575675675685', '2026-02-12 07:03:02', 0, 'pest.php'),
(258, '🐛 Pest guide deleted (ID: 35)', '2026-02-12 07:03:40', 0, 'pest.php'),
(259, '📘 New farm image uploaded: qweaseaewqeq', '2026-02-12 07:06:25', 0, 'guides.php'),
(260, '🐛 New pest guide added: asdasdadsadsda', '2026-02-12 07:24:45', 0, 'pest.php'),
(261, '🐛 Pest guide deleted (ID: 8)', '2026-02-12 07:24:48', 0, 'pest.php'),
(262, '🐛 New pest guide added: 1 231312', '2026-02-12 07:24:54', 0, 'pest.php'),
(263, '🐛 Pest guide updated: 1 231312', '2026-02-12 07:24:57', 0, 'pest.php'),
(264, '🐛 Pest guide updated: 1 231312', '2026-02-12 07:25:01', 0, 'pest.php'),
(265, '🐛 New pest guide added: 12515125', '2026-02-12 07:25:17', 0, 'pest.php'),
(266, '🐛 Pest guide deleted (ID: 10)', '2026-02-12 07:26:04', 0, 'pest.php'),
(267, '🐛 Pest guide deleted (ID: 9)', '2026-02-12 07:26:05', 0, 'pest.php'),
(268, '🐛 New pest guide added: 31241151261', '2026-02-12 07:28:12', 0, 'pest.php'),
(269, '🐛 Pest guide deleted (ID: 11)', '2026-02-12 07:28:22', 0, 'pest.php'),
(270, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:17', 0, 'weather-notify.php'),
(271, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:17', 0, 'weather-notify.php'),
(272, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:19', 0, 'weather-notify.php'),
(273, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:19', 0, 'weather-notify.php'),
(274, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:19', 0, 'weather-notify.php'),
(275, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:19', 0, 'weather-notify.php'),
(276, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:20', 0, 'weather-notify.php'),
(277, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:20', 0, 'weather-notify.php'),
(278, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:20', 0, 'weather-notify.php'),
(279, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:20', 0, 'weather-notify.php'),
(280, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:21', 0, 'weather-notify.php'),
(281, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:21', 0, 'weather-notify.php'),
(282, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:21', 0, 'weather-notify.php'),
(283, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:21', 0, 'weather-notify.php'),
(284, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:21', 0, 'weather-notify.php'),
(285, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:21', 0, 'weather-notify.php'),
(286, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:21', 0, 'weather-notify.php'),
(287, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:21', 0, 'weather-notify.php'),
(288, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:42:42', 0, 'weather-notify.php'),
(289, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:42:42', 0, 'weather-notify.php'),
(290, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:42:42', 0, 'weather-notify.php'),
(291, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:42:42', 0, 'weather-notify.php'),
(292, '🐛 New pest guide added: Wow big', '2026-02-12 07:43:07', 0, 'pest.php'),
(293, '🐛 New pest guide added: wwewewe', '2026-02-12 07:43:12', 0, 'pest.php'),
(294, '📘 New farm guide added: dwewewa', '2026-02-12 07:43:18', 0, 'guides.php'),
(295, '📘 New farm video uploaded: awea', '2026-02-12 07:43:23', 0, 'guides.php'),
(296, '📘 New farm image uploaded: aewe', '2026-02-12 07:43:29', 0, 'guides.php'),
(297, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:43:45', 1, 'weather-notify.php'),
(298, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:43:45', 0, 'weather-notify.php'),
(299, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:43:45', 0, 'weather-notify.php'),
(300, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:43:45', 0, 'weather-notify.php'),
(301, '📊 DA Market Data updated for February 12, 2026', '2026-02-12 07:43:52', 1, 'user-market-data.php'),
(302, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:44:08', 1, 'weather-notify.php'),
(303, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:44:08', 1, 'weather-notify.php'),
(304, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:44:46', 0, 'weather-notify.php'),
(305, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:44:46', 1, 'weather-notify.php'),
(306, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:44:46', 1, 'weather-notify.php'),
(307, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:44:46', 1, 'weather-notify.php'),
(308, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:44:53', 1, 'weather-notify.php'),
(309, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:44:53', 0, 'weather-notify.php'),
(310, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:48', 1, 'weather-notify.php'),
(311, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:48', 1, 'weather-notify.php'),
(312, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:51', 0, 'weather-notify.php'),
(313, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:51', 0, 'weather-notify.php'),
(314, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:51', 0, 'weather-notify.php'),
(315, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:51', 0, 'weather-notify.php'),
(316, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:52', 0, 'weather-notify.php'),
(317, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:52', 0, 'weather-notify.php'),
(318, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:53', 0, 'weather-notify.php'),
(319, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:53', 0, 'weather-notify.php'),
(320, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:53', 0, 'weather-notify.php'),
(321, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:53', 0, 'weather-notify.php'),
(322, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:54', 0, 'weather-notify.php'),
(323, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:54', 0, 'weather-notify.php'),
(324, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:54', 0, 'weather-notify.php'),
(325, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:54', 0, 'weather-notify.php'),
(326, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:54', 0, 'weather-notify.php'),
(327, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:54', 0, 'weather-notify.php'),
(328, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:55', 0, 'weather-notify.php'),
(329, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:55', 0, 'weather-notify.php'),
(330, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:57', 0, 'weather-notify.php'),
(331, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:57', 0, 'weather-notify.php'),
(332, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:57', 0, 'weather-notify.php'),
(333, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:57', 0, 'weather-notify.php'),
(334, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:58', 0, 'weather-notify.php'),
(335, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:58', 0, 'weather-notify.php'),
(336, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:58', 0, 'weather-notify.php'),
(337, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:58', 0, 'weather-notify.php'),
(338, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:58', 0, 'weather-notify.php'),
(339, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:58', 0, 'weather-notify.php'),
(340, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:58', 0, 'weather-notify.php'),
(341, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:58', 0, 'weather-notify.php'),
(342, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:58', 0, 'weather-notify.php'),
(343, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:58', 0, 'weather-notify.php'),
(344, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:59', 0, 'weather-notify.php'),
(345, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:59', 0, 'weather-notify.php'),
(346, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:59', 0, 'weather-notify.php'),
(347, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:59', 0, 'weather-notify.php'),
(348, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:47:52', 0, 'weather-notify.php'),
(349, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:47:52', 0, 'weather-notify.php'),
(350, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:47:52', 0, 'weather-notify.php'),
(351, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:47:52', 0, 'weather-notify.php'),
(352, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:50:16', 1, 'weather-notify.php'),
(353, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:50:16', 0, 'weather-notify.php'),
(354, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:53:50', 1, 'weather-notify.php'),
(355, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:53:50', 0, 'weather-notify.php'),
(356, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:53:54', 0, 'weather-notify.php'),
(357, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:53:54', 0, 'weather-notify.php'),
(358, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:53:55', 0, 'weather-notify.php'),
(359, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:53:55', 0, 'weather-notify.php'),
(360, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:54:38', 0, 'weather-notify.php'),
(361, '⚠ Severe Weather Alert - Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:54:38', 0, 'weather-notify.php'),
(362, '📊 DA Market Data updated for February 12, 2026', '2026-02-12 10:03:36', 0, 'user-market-data.php');

-- --------------------------------------------------------

--
-- Table structure for table `pests`
--

CREATE TABLE `pests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `confidence` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pests`
--

INSERT INTO `pests` (`id`, `name`, `details`, `confidence`, `created_at`) VALUES
(1, 'brown-planthopper', '**Description:**  \nThe brown planthopper (Nilaparvata lugens) is a small, brown, winged insect known for its piercing-sucking mouthparts. It primarily feeds on rice plants, causing significant damage by extracting sap, which can lead to stunting, yellowing, and eventually death of the plants. It can also transmit plant viruses.\n\n**Control Methods:**  \n1. **Cultural Practices:**\n   - Use resistant rice varieties.\n   - Practice proper water management; avoid water stagnation.\n   - Implement crop rotation and intercropping.\n\n2. **Biological Control:**\n   - Introduce natural enemies such as spiders, ladybugs, or parasitoid wasps.\n\n3. **Chemical Control:**\n   - Apply insecticides judiciously, targeting nymphs and adults, following local guidelines to minimize resistance build-up.\n\n4. **Monitoring:**\n   - Regularly scout fields for early detection and threshold levels to optimize control measures. \n\n5.', 68.60, '2026-02-11 11:23:13'),
(2, 'leaf-folder', 'Ang leaf-folder ay isang uri ng peste na kilala sa kanyang kakayahang i-ikot ang mga dahon ng halaman upang gawing pugad o tirahan, na nagiging sanhi ng paglipas ng mga sustansya at pagkasira sa mga dahon. Upang kontrolin ang leaf-folder, maaaring gamitin ang mga sumusunod na pamamaraan: una, ang maganda at tamang pamamahala ng mga tanim sa pamamagitan ng pagsasagawa ng crop rotation at pag-aalis ng mga sirang dahon; pangalawa, ang paggamit ng mga natural na kaaway tulad ng mga parasito at predatory insects na kumakain ng leaf-folder; pangatlo, ang pag-spray ng mga insecticides na nakatutok sa mga caterpillar stage ng peste, at panghuli, ang regular na pagmamasid sa mga tanim upang maagapan ang pagdami ng mga peste.', 59.60, '2026-02-11 11:29:44'),
(3, 'rice-bug', 'Ang rice bug, o kilala rin bilang \"pests ng palay\" (din bug o rice weevil), ay isang maliit na insekto na maaaring magdulot ng malaking pinsala sa mga taniman ng palay sa pamamagitan ng pag-ubos ng mga butil at paghuhukay sa mga ito. Upang makontrol ang rice bug, maaaring gumamit ng mga sumusunod na pamamaraan: una, tiyaking malinis ang paligid ng mga taniman sa pamamagitan ng pagtanggal ng mga damo at iba pang mga labi na maaaring pagtaguan ng peste. Pangalawa, magtanim ng mga pest-resistant na varieties ng palay. Pangatlo, maaring gumamit ng insecticidal soap o natural na pestisidyo, gaya ng neem oil, upang patayin ang mga insekto habang pinoprotektahan ang kalikasan. Pang-apat, regular na mag-monitor ng sitwasyon ng mga taniman upang agad na', 74.20, '2026-02-12 07:45:08');

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
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `youtube_id` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pest_videos`
--

INSERT INTO `pest_videos` (`id`, `title`, `description`, `video_path`, `youtube_id`, `created_at`) VALUES
(12, 'Wow big', 'wowiw wowiw', 'uploads/pest_videos/1770882187_Screen Recording 2026-01-28 192357.mp4', NULL, '2026-02-12 07:43:07'),
(13, 'wwewewe', 'wewewewe', NULL, '06J4n0lEbmI', '2026-02-12 07:43:12');

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
  `reset_expires` datetime DEFAULT NULL,
  `last_active` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `address`, `password`, `created_at`, `reset_token`, `reset_expires`, `last_active`) VALUES
(12, 'Emerson Bustamante', 'EmersonBustamante@gmail.com', '1234567809', '1asd1312as', '$2y$10$ujAnvVxpCM0b4Nse2BMjZe/rM30db4gwNh0eNvSS6Ce4GnnG25pfq', '2026-01-28 11:22:19', NULL, NULL, '2026-02-09 16:14:40'),
(13, 'Cedrick dominiq barro', 'cedrickdominiqbarro@gmail.com', '1235451265', 'asdqweqwe', '$2y$10$UuYEC46eShMuLtR2SSCsbeAER.o3riIYABFXFEWi9XFfw9O8Knlni', '2026-02-04 07:37:08', 'efc7894d0221fa2a7c0467462df27954', '2026-02-04 14:51:21', '2026-02-09 16:14:40'),
(15, 'Cedrick dominiq barro', 'cdbarro@gmail.com', '1234123123', 'asdeqwewq', '$2y$10$E80jo00aTHZ5B0rvMohii.YsQJhlqWwhhXlASY4/0E8PPSfqyOjtm', '2026-02-04 12:38:19', NULL, NULL, '2026-02-09 16:14:40'),
(16, 'Nicson Bustamante', 'david@gqewqmail.com', '09933608401', '21 fortuna', '$2y$10$YfKrTwh7TxQiTgR63D2A.e8KA7JxLz.vXg7V6VbhfnRnhOSFrHCXC', '2026-02-06 23:26:18', NULL, NULL, '2026-02-09 16:14:40'),
(17, 'Cedrick dominiq barro', 'wrightaudrey505@gmail.com', '123121515125', '13wd1e122', '$2y$10$8lgUGgRlvX/1JQ.omHHoCuqd5CoBoG4GtrvZIe8nN6g3VjQoVqjfW', '2026-02-09 10:27:56', NULL, NULL, '2026-02-09 18:27:56');

-- --------------------------------------------------------

--
-- Table structure for table `weather_notifications`
--

CREATE TABLE `weather_notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `severity` enum('normal','warning','severe') DEFAULT 'normal',
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weather_notifications`
--

INSERT INTO `weather_notifications` (`id`, `title`, `description`, `created_at`, `severity`, `location`) VALUES
(1, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:04:21', 'normal', NULL),
(2, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:04:21', 'normal', NULL),
(3, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:04:21', 'normal', NULL),
(4, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:04:21', 'normal', NULL),
(5, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:04:24', 'normal', NULL),
(6, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:04:24', 'normal', NULL),
(7, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:04:24', 'normal', NULL),
(8, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:04:24', 'normal', NULL),
(9, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:05:54', 'normal', NULL),
(10, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:05:54', 'normal', NULL),
(11, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:05:54', 'normal', NULL),
(12, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:05:54', 'normal', NULL),
(13, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:06:01', 'normal', NULL),
(14, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:06:01', 'normal', NULL),
(15, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:06:01', 'normal', NULL),
(16, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:06:01', 'normal', NULL),
(17, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:06:01', 'normal', NULL),
(18, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:06:01', 'normal', NULL),
(19, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:06:01', 'normal', NULL),
(20, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:06:01', 'normal', NULL),
(21, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:06:02', 'normal', NULL),
(22, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:06:02', 'normal', NULL),
(23, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:06:02', 'normal', NULL),
(24, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:06:02', 'normal', NULL),
(25, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:06:56', 'normal', NULL),
(26, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:06:56', 'normal', NULL),
(27, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:06:56', 'normal', NULL),
(28, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:06:56', 'normal', NULL),
(29, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:06:59', 'normal', NULL),
(30, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:06:59', 'normal', NULL),
(31, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:06:59', 'normal', NULL),
(32, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:07:00', 'normal', NULL),
(33, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:07:08', 'normal', NULL),
(34, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:07:08', 'normal', NULL),
(35, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:07:08', 'normal', NULL),
(36, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:07:08', 'normal', NULL),
(37, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:10:06', 'normal', NULL),
(38, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:10:06', 'normal', NULL),
(39, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:10:06', 'normal', NULL),
(40, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:10:06', 'normal', NULL),
(41, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:10:08', 'normal', NULL),
(42, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:10:08', 'normal', NULL),
(43, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:10:08', 'normal', NULL),
(44, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:10:08', 'normal', NULL),
(45, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:10:12', 'normal', NULL),
(46, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:10:12', 'normal', NULL),
(47, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:10:12', 'normal', NULL),
(48, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:10:12', 'normal', NULL),
(49, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:10:44', 'normal', NULL),
(50, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:10:44', 'normal', NULL),
(51, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:10:44', 'normal', NULL),
(52, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:10:44', 'normal', NULL),
(53, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:11:12', 'normal', NULL),
(54, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:11:12', 'normal', NULL),
(55, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:11:12', 'normal', NULL),
(56, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:11:12', 'normal', NULL),
(57, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:22', 'normal', NULL),
(58, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:22', 'normal', NULL),
(59, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:22', 'normal', NULL),
(60, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:22', 'normal', NULL),
(61, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:22', 'normal', NULL),
(62, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:22', 'normal', NULL),
(63, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:22', 'normal', NULL),
(64, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:22', 'normal', NULL),
(65, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:24', 'normal', NULL),
(66, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:24', 'normal', NULL),
(67, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:24', 'normal', NULL),
(68, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:24', 'normal', NULL),
(69, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:25', 'normal', NULL),
(70, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:25', 'normal', NULL),
(71, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:25', 'normal', NULL),
(72, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:25', 'normal', NULL),
(73, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:26', 'normal', NULL),
(74, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:27', 'normal', NULL),
(75, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:27', 'normal', NULL),
(76, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:27', 'normal', NULL),
(77, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:27', 'normal', NULL),
(78, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:27', 'normal', NULL),
(79, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:27', 'normal', NULL),
(80, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:27', 'normal', NULL),
(81, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:33', 'normal', NULL),
(82, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:33', 'normal', NULL),
(83, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:33', 'normal', NULL),
(84, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:33', 'normal', NULL),
(85, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:33', 'normal', NULL),
(86, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:33', 'normal', NULL),
(87, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:33', 'normal', NULL),
(88, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:33', 'normal', NULL),
(89, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:34', 'normal', NULL),
(90, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:34', 'normal', NULL),
(91, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:34', 'normal', NULL),
(92, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:34', 'normal', NULL),
(93, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:34', 'normal', NULL),
(94, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:34', 'normal', NULL),
(95, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:34', 'normal', NULL),
(96, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:34', 'normal', NULL),
(97, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:36', 'normal', NULL),
(98, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:36', 'normal', NULL),
(99, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:36', 'normal', NULL),
(100, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:36', 'normal', NULL),
(101, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:36', 'normal', NULL),
(102, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:36', 'normal', NULL),
(103, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:36', 'normal', NULL),
(104, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:36', 'normal', NULL),
(105, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:36', 'normal', NULL),
(106, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:36', 'normal', NULL),
(107, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:36', 'normal', NULL),
(108, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:36', 'normal', NULL),
(109, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:36', 'normal', NULL),
(110, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:36', 'normal', NULL),
(111, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:36', 'normal', NULL),
(112, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:36', 'normal', NULL),
(113, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:36', 'normal', NULL),
(114, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:36', 'normal', NULL),
(115, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:36', 'normal', NULL),
(116, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:36', 'normal', NULL),
(117, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:59', 'normal', NULL),
(118, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:59', 'normal', NULL),
(119, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:59', 'normal', NULL),
(120, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:59', 'normal', NULL),
(121, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:26:59', 'normal', NULL),
(122, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:26:59', 'normal', NULL),
(123, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:26:59', 'normal', NULL),
(124, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:26:59', 'normal', NULL),
(125, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:27:00', 'normal', NULL),
(126, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:27:00', 'normal', NULL),
(127, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:27:00', 'normal', NULL),
(128, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:27:00', 'normal', NULL),
(129, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:27:00', 'normal', NULL),
(130, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:27:00', 'normal', NULL),
(131, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:27:00', 'normal', NULL),
(132, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:27:00', 'normal', NULL),
(133, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:27:00', 'normal', NULL),
(134, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:27:00', 'normal', NULL),
(135, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:27:00', 'normal', NULL),
(136, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:27:00', 'normal', NULL),
(137, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:27:01', 'normal', NULL),
(138, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:27:01', 'normal', NULL),
(139, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:27:01', 'normal', NULL),
(140, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:27:01', 'normal', NULL),
(141, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:27:03', 'normal', NULL),
(142, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:27:03', 'normal', NULL),
(143, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:27:03', 'normal', NULL),
(144, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:27:03', 'normal', NULL),
(145, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:30:51', 'normal', NULL),
(146, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:30:51', 'normal', NULL),
(147, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:30:51', 'normal', NULL),
(148, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:30:51', 'normal', NULL),
(149, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:44:42', 'normal', NULL),
(150, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:44:42', 'normal', NULL),
(151, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:44:42', 'normal', NULL),
(152, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:44:42', 'normal', NULL),
(153, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:44:42', 'normal', NULL),
(154, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:44:42', 'normal', NULL),
(155, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:44:42', 'normal', NULL),
(156, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:44:42', 'normal', NULL),
(157, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:44:42', 'normal', NULL),
(158, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:44:42', 'normal', NULL),
(159, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:44:42', 'normal', NULL),
(160, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:44:42', 'normal', NULL),
(161, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:44:43', 'normal', NULL),
(162, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:44:43', 'normal', NULL),
(163, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:44:43', 'normal', NULL),
(164, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:44:43', 'normal', NULL),
(165, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:44:44', 'normal', NULL),
(166, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:44:44', 'normal', NULL),
(167, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:44:44', 'normal', NULL),
(168, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:44:44', 'normal', NULL),
(169, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:44:46', 'normal', NULL),
(170, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:44:46', 'normal', NULL),
(171, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:44:46', 'normal', NULL),
(172, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:44:46', 'normal', NULL),
(173, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:49:13', 'normal', NULL),
(174, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:49:13', 'normal', NULL),
(175, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:49:13', 'normal', NULL),
(176, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:49:13', 'normal', NULL),
(177, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 05:52:17', 'normal', NULL),
(178, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 05:52:17', 'normal', NULL),
(179, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 05:52:17', 'normal', NULL),
(180, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 05:52:17', 'normal', NULL),
(181, '⚠ Severe Weather Alert', 'Expected light rain on 2/14/2026, 11:00:00 AM', '2026-02-12 06:01:34', 'normal', NULL),
(182, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 11:00:00 AM', '2026-02-12 06:01:34', 'normal', NULL),
(183, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 2:00:00 PM', '2026-02-12 06:01:34', 'normal', NULL),
(184, '⚠ Severe Weather Alert', 'Expected light rain on 2/15/2026, 5:00:00 PM', '2026-02-12 06:01:34', 'normal', NULL),
(185, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:50:41', 'normal', NULL),
(186, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:50:41', 'normal', NULL),
(187, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:50:41', 'normal', NULL),
(188, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:50:41', 'normal', NULL),
(189, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:50:41', 'normal', NULL),
(190, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:50:41', 'normal', NULL),
(191, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:50:42', 'normal', NULL),
(192, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:50:42', 'normal', NULL),
(193, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:50:43', 'normal', NULL),
(194, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:50:43', 'normal', NULL),
(195, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:50:43', 'normal', NULL),
(196, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:50:43', 'normal', NULL),
(197, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:52:23', 'normal', NULL),
(198, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:52:23', 'normal', NULL),
(199, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 06:52:23', 'normal', NULL),
(200, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 06:52:23', 'normal', NULL),
(201, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:17', 'normal', NULL),
(202, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:17', 'normal', NULL),
(203, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:19', 'normal', NULL),
(204, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:19', 'normal', NULL),
(205, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:19', 'normal', NULL),
(206, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:19', 'normal', NULL),
(207, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:20', 'normal', NULL),
(208, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:20', 'normal', NULL),
(209, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:20', 'normal', NULL),
(210, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:20', 'normal', NULL),
(211, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:21', 'normal', NULL),
(212, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:21', 'normal', NULL),
(213, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:21', 'normal', NULL),
(214, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:21', 'normal', NULL),
(215, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:21', 'normal', NULL),
(216, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:21', 'normal', NULL),
(217, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:35:21', 'normal', NULL),
(218, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:35:21', 'normal', NULL),
(219, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:42:42', 'normal', NULL),
(220, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:42:42', 'normal', NULL),
(221, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:42:42', 'normal', NULL),
(222, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:42:42', 'normal', NULL),
(223, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:43:45', 'normal', NULL),
(224, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:43:45', 'normal', NULL),
(225, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:43:45', 'normal', NULL),
(226, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:43:45', 'normal', NULL),
(227, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:44:08', 'normal', NULL),
(228, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:44:08', 'normal', NULL),
(229, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:44:45', 'normal', NULL),
(230, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:44:46', 'normal', NULL),
(231, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:44:46', 'normal', NULL),
(232, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:44:46', 'normal', NULL),
(233, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:44:53', 'normal', NULL),
(234, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:44:53', 'normal', NULL),
(235, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:48', 'normal', NULL),
(236, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:48', 'normal', NULL),
(237, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:51', 'normal', NULL),
(238, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:51', 'normal', NULL),
(239, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:51', 'normal', NULL),
(240, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:51', 'normal', NULL),
(241, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:52', 'normal', NULL),
(242, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:52', 'normal', NULL),
(243, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:53', 'normal', NULL),
(244, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:53', 'normal', NULL),
(245, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:53', 'normal', NULL),
(246, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:53', 'normal', NULL),
(247, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:54', 'normal', NULL),
(248, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:54', 'normal', NULL),
(249, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:54', 'normal', NULL),
(250, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:54', 'normal', NULL),
(251, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:54', 'normal', NULL),
(252, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:54', 'normal', NULL),
(253, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:55', 'normal', NULL),
(254, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:55', 'normal', NULL),
(255, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:57', 'normal', NULL),
(256, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:57', 'normal', NULL),
(257, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:57', 'normal', NULL),
(258, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:57', 'normal', NULL),
(259, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:58', 'normal', NULL),
(260, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:58', 'normal', NULL),
(261, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:58', 'normal', NULL),
(262, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:58', 'normal', NULL),
(263, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:58', 'normal', NULL),
(264, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:58', 'normal', NULL),
(265, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:58', 'normal', NULL),
(266, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:58', 'normal', NULL),
(267, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:58', 'normal', NULL),
(268, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:58', 'normal', NULL),
(269, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:59', 'normal', NULL),
(270, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:59', 'normal', NULL),
(271, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 07:46:59', 'normal', NULL),
(272, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 07:46:59', 'normal', NULL),
(273, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:47:52', 'normal', NULL),
(274, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:47:52', 'normal', NULL),
(275, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:47:52', 'normal', NULL),
(276, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:47:52', 'normal', NULL),
(277, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:50:16', 'normal', NULL),
(278, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:50:16', 'normal', NULL),
(279, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:53:50', 'normal', NULL),
(280, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:53:50', 'normal', NULL),
(281, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:53:54', 'normal', NULL),
(282, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:53:54', 'normal', NULL),
(283, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:53:55', 'normal', NULL),
(284, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:53:55', 'normal', NULL),
(285, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 5:00:00 PM', '2026-02-12 09:54:38', 'normal', NULL),
(286, '⚠ Severe Weather Alert', 'Expected light rain on 2/13/2026, 8:00:00 PM', '2026-02-12 09:54:38', 'normal', NULL);

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
-- Indexes for table `farm_images`
--
ALTER TABLE `farm_images`
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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pests`
--
ALTER TABLE `pests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `pest_categories`
--
ALTER TABLE `pest_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pest_videos`
--
ALTER TABLE `pest_videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `weather_notifications`
--
ALTER TABLE `weather_notifications`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `chatbot_logs`
--
ALTER TABLE `chatbot_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `farm_images`
--
ALTER TABLE `farm_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `farm_videos`
--
ALTER TABLE `farm_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `market_data`
--
ALTER TABLE `market_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- AUTO_INCREMENT for table `pests`
--
ALTER TABLE `pests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pest_categories`
--
ALTER TABLE `pest_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pest_videos`
--
ALTER TABLE `pest_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `weather_notifications`
--
ALTER TABLE `weather_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=287;

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
-- Constraints for table `weather_queries`
--
ALTER TABLE `weather_queries`
  ADD CONSTRAINT `weather_queries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
