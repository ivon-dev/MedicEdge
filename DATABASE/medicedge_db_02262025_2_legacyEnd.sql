-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2025 at 06:45 PM
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
-- Database: `medicedge_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users_administrator_table`
--

CREATE TABLE `users_administrator_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_administrator_table`
--

INSERT INTO `users_administrator_table` (`id`, `user_id`) VALUES
('user_02142025_045426', 'user_02142025_045426'),
('user_02142025_184101', 'user_02142025_184101'),
('user_02172025_030839', 'user_02172025_030839');

-- --------------------------------------------------------

--
-- Table structure for table `users_forgotpassword`
--

CREATE TABLE `users_forgotpassword` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_code` varchar(255) NOT NULL,
  `expire_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_forgotpassword`
--

INSERT INTO `users_forgotpassword` (`id`, `user_id`, `email`, `email_code`, `expire_time`) VALUES
('resetrequest_02252025_151919', 'user_02142025_184101', 'studpaullll32@gmail.com', 'N0VZP43V', '2025-02-25 07:24:19'),
('resetrequest_02252025_151944', 'user_02142025_184101', 'studpaullll32@gmail.com', 'ODQKXYSY', '2025-02-25 07:24:44'),
('resetrequest_02252025_152904', 'user_02142025_184101', 'studpaullll32@gmail.com', 'PKV6L6AM', '2025-02-25 07:34:04'),
('resetrequest_02252025_153231', 'user_02142025_184101', 'studpaullll32@gmail.com', 'GWX8ZWLO', '2025-02-25 07:37:31');

-- --------------------------------------------------------

--
-- Table structure for table `users_healthcare_provider_table`
--

CREATE TABLE `users_healthcare_provider_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_healthcare_provider_table`
--

INSERT INTO `users_healthcare_provider_table` (`id`, `user_id`) VALUES
('user_02142025_074100', 'user_02142025_074100');

-- --------------------------------------------------------

--
-- Table structure for table `users_patient_table`
--

CREATE TABLE `users_patient_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_receptionist_table`
--

CREATE TABLE `users_receptionist_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_receptionist_table`
--

INSERT INTO `users_receptionist_table` (`id`, `user_id`) VALUES
('user_02142025_063500', 'user_02142025_063500');

-- --------------------------------------------------------

--
-- Table structure for table `users_sessionlists`
--

CREATE TABLE `users_sessionlists` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `expire_time` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_sessionlists`
--

INSERT INTO `users_sessionlists` (`id`, `user_id`, `session_token`, `login_time`, `expire_time`, `ip_address`, `user_agent`) VALUES
('session_02182025_223820', 'user_02142025_045426', '4879747a7004809db27fb58686da02050d752bc893f28d592fb411af47579e79', '2025-02-18 13:38:20', '2025-02-19 13:38:20', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02192025_161214', 'user_02142025_045426', '02cfb35c58a980b4f5685de72e178ebbf80f4d16737eaa9472559515dd46cb29', '2025-02-19 07:12:14', '2025-02-20 07:12:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02202025_051828', 'user_02142025_045426', '9cac044834ccc8f796d4af5c78f31c25b3348b82659cd65713f2828a4818cdd8', '2025-02-19 20:18:28', '2025-02-20 20:18:28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02202025_080730', 'user_02142025_045426', '6bd3188ad6e90fb7d8547f27e969646b22074e7fe18105396eb25aca9c44f773', '2025-02-19 23:07:30', '2025-02-20 23:07:30', '192.168.1.6', 'Mozilla/5.0 (Android 14; Mobile; rv:135.0) Gecko/135.0 Firefox/135.0'),
('session_02212025_065159', 'user_02142025_045426', '9f1d94d18e1324c8fa0a449f151a363c45aee896227a76a302a0b82cfa7bec7d', '2025-02-20 21:51:59', '2025-02-21 21:51:59', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02222025_082743', 'user_02142025_045426', '0ea6a8071e65829d0253f1152e5e450c30f7de35d6def47a4028f26e11f1d991', '2025-02-21 23:27:43', '2025-02-22 23:27:43', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02222025_083526', 'user_02142025_045426', '5e79e40ae736c38ab263e38ece830dc39177343460c7519d2c9f55475eed2003', '2025-02-21 23:35:26', '2025-02-22 23:35:26', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02222025_165101', 'user_02142025_045426', 'f0730b0f1cd6063418e8de61b140963b3aee76dbf3ba49b72b2cfa58ef34db80', '2025-02-22 07:51:01', '2025-02-22 16:21:08', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02222025_172214', 'user_02142025_045426', '90e2bd0128b60bbb0eb2ac22b26e85178f3cd7caac74bcebff36e7edbd528cbc', '2025-02-22 08:22:14', '2025-02-22 08:22:54', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02222025_172330', 'user_02142025_045426', 'f6108fa0416d0a18181b76c6bc4684d991e3d506e95dbb2fd8164985e1ce5ee0', '2025-02-22 08:23:30', '2025-02-22 10:10:03', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02222025_191213', 'user_02142025_045426', '0c86fe2a5d25b2d6c72cfbb685d5afc4c51407197392dc2eac430fdf489ed739', '2025-02-22 10:12:13', '2025-02-22 10:14:18', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02222025_191421', 'user_02142025_063500', '69ea67c6001a9e4a504ecc9295e7bd2dbab7c30aee9bec1a680abcf212a466e6', '2025-02-22 10:14:21', '2025-02-22 10:14:45', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02232025_123824', 'user_02142025_045426', 'c404368ca639a808cd9db45fc7ef40982d2d818b9cf71841e63d016b0e5e68c9', '2025-02-23 03:38:24', '2025-02-23 07:21:22', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02232025_164115', 'user_02142025_045426', 'b7c5b5a650d395a5eb535bc359cb29d6246347063f8351077e8a579bad726ab6', '2025-02-23 07:41:15', '2025-02-24 07:41:15', '192.168.1.6', 'Mozilla/5.0 (Android 14; Mobile; rv:135.0) Gecko/135.0 Firefox/135.0'),
('session_02232025_171542', 'user_02142025_045426', '3bf94aa11caee2534a010bb8ff56ed2a34518ae8553ba1e0bb44ccdb3b3cb092', '2025-02-23 08:15:42', '2025-02-23 08:33:09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02232025_172810', 'user_02142025_045426', 'f4047c3c15beacd8e344e980e70f25663c8f0fdcb1d7aa7b85a3637f88743c0d', '2025-02-23 08:28:10', '2025-02-23 08:30:49', '192.168.1.6', 'Mozilla/5.0 (Android 14; Mobile; rv:135.0) Gecko/135.0 Firefox/135.0'),
('session_02232025_173131', 'user_02142025_045426', '5e20d1fa53763e9bb4e2d0388dcf08633702880dbddf5d7ce16558e6b3a6a4a4', '2025-02-23 08:31:31', '2025-02-23 08:31:57', '192.168.1.6', 'Mozilla/5.0 (Android 14; Mobile; rv:135.0) Gecko/135.0 Firefox/135.0'),
('session_02232025_173208', 'user_02142025_045426', 'b2555bbd5c4058ed90dd13b84d2241aee1def9a70921911b16a7a0aa8baad4d8', '2025-02-23 08:32:08', '2025-02-23 08:33:58', '192.168.1.6', 'Mozilla/5.0 (Android 14; Mobile; rv:135.0) Gecko/135.0 Firefox/135.0'),
('session_02232025_173318', 'user_02142025_045426', 'de4a4e2064e3019214968ef609a619c0d83ff18f42815004e08db229a9c80ef7', '2025-02-23 08:33:18', '2025-02-23 08:58:29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02232025_173426', 'user_02142025_074200', '9a0e53c725d0f85a5f1d89f4399d1b8650d0620791d681e22521048ef7d58329', '2025-02-23 08:34:26', '2025-02-23 08:34:48', '192.168.1.6', 'Mozilla/5.0 (Android 14; Mobile; rv:135.0) Gecko/135.0 Firefox/135.0'),
('session_02242025_031009', 'user_02142025_045426', '45874ccfe775a5e2d9d3309d78fd91cae6e69ad8b6a83d75340e1f4c9e783f33', '2025-02-23 18:10:09', '2025-02-24 06:13:44', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02242025_041350', 'user_02142025_045426', '9dd0e451b1118c3ab08671db0935144b2a3ab543ec6d9ec8c8e3c2eb8e461244', '2025-02-23 19:13:50', '2025-03-02 19:13:50', '192.168.1.6', 'Mozilla/5.0 (Android 14; Mobile; rv:135.0) Gecko/135.0 Firefox/135.0'),
('session_02242025_152723', 'user_02142025_063500', 'f19274dc31c9f3b6a997639b57e81af871698004376980c137172558e881738d', '2025-02-24 06:27:23', '2025-02-24 06:27:35', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02242025_152936', 'user_02142025_045426', '931f5a1cc2fd8703d65349b5d5a3a0d8a6de131b8e4ade5182d48ef5f21d766e', '2025-02-24 06:29:36', '2025-02-24 07:49:52', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02242025_164957', 'user_02142025_045426', '761f20838519c6c44bbbb1ff6863cec59bee2b05e00232a95311a84ce8cfb4ec', '2025-02-24 07:49:57', '2025-02-25 04:11:40', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02242025_165547', 'user_02142025_045426', 'f47283fac43bb8680eabc198e8d1f3bbd94ca4638ac9e7b788b23d6ea6f758ba', '2025-02-24 07:55:47', '2025-02-25 07:55:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36'),
('session_02252025_131144', 'user_02142025_045426', '376c3c8eef522e9e6aded7a553c2367665d2d942625be04c7947b968a9d0cc3e', '2025-02-25 04:11:44', '2025-02-25 05:25:48', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_134355', 'user_02142025_074100', '74da0828df4aca3c1ba66d47196d1be6f12b2dbb70c41f123f6026e88aadb0fb', '2025-02-25 04:43:55', '2025-02-26 04:43:55', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_134728', 'user_02142025_063500', '9450c3fa58316b4c08a061db3c757e760b3671119740fdea82cbe725636bde3e', '2025-02-25 04:47:28', '2025-02-25 04:57:56', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_135804', 'user_02142025_063500', '1d78276cf7d8cd70e233cdb8c8bfd469a9c3f1ba1d1ea7fc2f7c5c6ad55d9c37', '2025-02-25 04:58:04', '2025-02-25 05:46:30', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_142558', 'user_02142025_063500', '1fcfd7821938f997b20b0f6bf3ba95ce68e8ee3cfbf85a682939a77f1c5b5cd3', '2025-02-25 05:25:58', '2025-02-25 05:26:18', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_142641', 'user_02142025_045426', '32fd5b9a01bcdeb7fc15d114e734511bd9d63bc0f5fd45ed4f1b0af904fbe571', '2025-02-25 05:26:41', '2025-02-25 05:39:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_144638', 'user_02142025_074200', '65239bb14d5fe126768b8cf4ad589a81a1199bc86fddb78b3774d44db90c4882', '2025-02-25 05:46:38', '2025-02-26 05:46:38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_160409', 'user_02142025_184101', '113ebec00cddf43f1e8b6a6b54c7c176a6bfb6ca1f79751c19241de30444fe80', '2025-02-25 07:04:09', '2025-02-25 07:04:16', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_161654', 'user_02142025_184101', '4e7536f99e65650a6b55eb2021e82f02b41b46c813b5c42ebf8c080dd459e0e1', '2025-02-25 07:16:54', '2025-02-25 07:16:57', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_161855', 'user_02142025_184101', '1dc42c4afd685b7810c47effbfb8591e467d4638b8d117bad31761c7ef4a4753', '2025-02-25 07:18:55', '2025-02-25 07:19:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_163008', 'user_02142025_184101', '07c3fd772a1bd8bb0926900ea87f682292e6937e9227c2b770eb87fc9d338380', '2025-02-25 07:30:08', '2025-02-25 07:30:11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_163036', 'user_02142025_184101', 'e59c5e82c6d8aed266806a986d4850b5f1019baf57e2ae60b4a9065315b98a55', '2025-02-25 07:30:36', '2025-02-25 07:30:40', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_163444', 'user_02142025_184101', '484f319e221d2839592517256c4ed148d711e0101a09e2ae6086cda01b3c8f0c', '2025-02-25 07:34:44', '2025-02-25 07:34:47', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02252025_171741', 'user_02142025_045426', 'f2b53e43e13483960397ec11b83e99ed23d18aac322e2eb492a3c28b3edf85d0', '2025-02-25 08:17:41', '2025-02-26 08:17:41', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0');

-- --------------------------------------------------------

--
-- Table structure for table `users_session_blocklist`
--

CREATE TABLE `users_session_blocklist` (
  `id` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `block_end` timestamp NULL DEFAULT NULL,
  `issued_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_table`
--

CREATE TABLE `users_table` (
  `user_id` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `password_hash` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_num` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `picture` varchar(255) NOT NULL,
  `user_type` enum('patient','healthcare_provider','receptionist','administrator') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_table`
--

INSERT INTO `users_table` (`user_id`, `username`, `firstname`, `middlename`, `lastname`, `password_hash`, `email`, `contact_num`, `address`, `picture`, `user_type`, `created_at`) VALUES
('user_02142025_045426', 'admin', 'Administrator', 'Will_of_D', 'Begino', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'admin@medicedge.org', '09123456789', '123 Health Drive', 'DATA/user-data/user_02142025_045426/profile_picture_1.png', 'administrator', '2025-02-13 19:54:26'),
('user_02142025_063500', 'receptionist', 'Recep', 'Tio', 'Nist', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'begino_recep@gmail.com', '09931234567', '69 Kuan Street', 'DATA/user-data/user_02142025_063500/profile_picture.png', 'receptionist', '2025-02-13 22:35:00'),
('user_02142025_074100', 'doctor', 'Doc', '', 'Tor', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'begino_phd@gmail.com', '09931234567', '123 Liver Street', 'DATA/user-data/user_02142025_074100/profile_picture.jpg', 'healthcare_provider', '2025-02-13 23:41:00'),
('user_02142025_074200', 'paul_begino', 'Paul Ivon', 'Barcelon', 'Begino', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'begino@gmail.com', '09931234567', '', 'DATA/user-data/generic_user.png', 'patient', '2025-02-13 23:42:00'),
('user_02142025_184101', 'administrator', 'Paul', '', 'Begino', '$argon2id$v=19$m=65536,t=4,p=1$VDh1ejFVZ3ZlTmlqM2kvQw$58zEtyE2/MnbZNewv/NAb0k388sXie3PkmJR/CDI05o', 'studpaullll32@gmail.com', '09912345678', '', 'DATA/user-data/generic_user.png', 'administrator', '2025-02-14 09:41:01'),
('user_02172025_030839', 'admin_2', 'wfowf', 'edgege', 'gegefg', '$argon2id$v=19$m=65536,t=4,p=1$Qjc0VGMzNTIxNTdWMnZLcQ$MIRcsBXTyiStDob9Ynmyq67+xj/Py6qbqHG42lEqvY8', 'gggggg@gmail.com', '0921243872348', '', 'DATA/user-data/generic_user.png', 'administrator', '2025-02-16 18:08:39');

--
-- Triggers `users_table`
--
DELIMITER $$
CREATE TRIGGER `sync_tables` AFTER INSERT ON `users_table` FOR EACH ROW BEGIN
    IF NEW.user_type = 'administrator' THEN
        INSERT INTO users_administrator_table (id, user_id)
        VALUES (NEW.user_id, NEW.user_id);
    ELSEIF NEW.user_type = 'healthcare_provider' THEN
        INSERT INTO users_healthcare_provider_table (id, user_id)
        VALUES (NEW.user_id, NEW.user_id);
    ELSEIF NEW.user_type = 'receptionist' THEN
        INSERT INTO users_receptionist_table (id, user_id)
        VALUES (NEW.user_id, NEW.user_id);
    ELSEIF NEW.user_type = 'patient' THEN
    	INSERT INTO users_patient_table (id, user_id)
		VALUES (NEW.user_id, NEW.user_id);
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users_administrator_table`
--
ALTER TABLE `users_administrator_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users_forgotpassword`
--
ALTER TABLE `users_forgotpassword`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users_healthcare_provider_table`
--
ALTER TABLE `users_healthcare_provider_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users_receptionist_table`
--
ALTER TABLE `users_receptionist_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users_sessionlists`
--
ALTER TABLE `users_sessionlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users_session_blocklist`
--
ALTER TABLE `users_session_blocklist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_table`
--
ALTER TABLE `users_table`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users_administrator_table`
--
ALTER TABLE `users_administrator_table`
  ADD CONSTRAINT `users_administrator_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_table` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_forgotpassword`
--
ALTER TABLE `users_forgotpassword`
  ADD CONSTRAINT `users_forgotpassword_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_table` (`user_id`);

--
-- Constraints for table `users_healthcare_provider_table`
--
ALTER TABLE `users_healthcare_provider_table`
  ADD CONSTRAINT `users_healthcare_provider_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_table` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_receptionist_table`
--
ALTER TABLE `users_receptionist_table`
  ADD CONSTRAINT `users_receptionist_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_table` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_sessionlists`
--
ALTER TABLE `users_sessionlists`
  ADD CONSTRAINT `users_sessionlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_table` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
