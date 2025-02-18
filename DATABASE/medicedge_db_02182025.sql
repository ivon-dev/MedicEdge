-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2025 at 09:00 PM
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
('session_02142025_090636', 'user_02142025_045426', '55afed7bc237c0ec4e55853a7fbd5b40d2ceb2f056c36d4d2dd017b208ae82ae', '2025-02-14 00:06:36', '2025-02-15 00:06:36', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_090701', 'user_02142025_045426', '6f31e3ae782b98d7dc8c6a7975120dd7697213c415912315dceb4b33f410913d', '2025-02-14 00:07:01', '2025-02-15 00:07:01', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_093518', 'user_02142025_045426', '1500aba538cb250924e58fe05fe8535040fec783031d56bd76e7d2025b9bf080', '2025-02-14 00:35:18', '2025-02-15 00:35:18', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_175726', 'user_02142025_074100', '72d915c9e885d98aa9260570e2ba30c7926bb2ec43dd393be7357e286549fb97', '2025-02-14 08:57:26', '2025-02-15 08:57:26', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_175751', 'user_02142025_045426', '6d69d27b49b22551c517e1cea289160bd0acfae4457e715cbfd5832b7deb188e', '2025-02-14 08:57:51', '2025-02-15 08:57:51', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_175921', 'user_02142025_045426', '0236844112ce865a83f195199621038cbd114e08442b3fb9a74ee940a635372d', '2025-02-14 08:59:21', '2025-02-15 08:59:21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_175952', 'user_02142025_045426', '4e11cfd6675b27848852f9558a698b1b78cffdbe956d1733b8f1eb5cae19b2bc', '2025-02-14 08:59:52', '2025-02-15 08:59:52', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_175959', 'user_02142025_074100', '37341794556e868f333f87288145d948b9f3c66e4a13713656d20bf32ac1974f', '2025-02-14 08:59:59', '2025-02-15 08:59:59', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_180329', 'user_02142025_074100', '48d6212b190753d03d085c912b51455966ab38897eb43b0b0bd73355092c0344', '2025-02-14 09:03:29', '2025-02-15 09:03:29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_180352', 'user_02142025_074200', '4fd0dc9f00f82281a5fee4482530058cade40fb9bfff96226c52c70d0373358d', '2025-02-14 09:03:52', '2025-02-15 09:03:52', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_180437', 'user_02142025_074200', '379977471a0171ad0c189924fa045b481554708a08318235ad0845e381a5485a', '2025-02-14 09:04:37', '2025-02-15 09:04:37', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_182224', 'user_02142025_045426', '23fef0d6ee18f13060bf014a87c6a878cc621f479c127d4c2837be23ccd0bf13', '2025-02-14 09:22:24', '2025-02-15 09:22:24', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_235544', 'user_02142025_045426', '96229d583f9f92823c0da99cb21222284e93bf8896030b8619b72aa497d0b9b8', '2025-02-14 14:55:44', '2025-02-15 14:55:44', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_235552', 'user_02142025_045426', 'f5374155a93291426dca2c7a68756db27649d14d8f880fc4deed28f6a4ae73e4', '2025-02-14 14:55:52', '2025-02-15 14:55:52', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02142025_235612', 'user_02142025_045426', '27c11c791fb7ba6dae061de0cad82b6b037182bb410f9367004b61f71c0ecab5', '2025-02-14 14:56:12', '2025-02-15 14:56:12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02152025_001221', 'user_02142025_045426', 'a709f2c2101b6fff0678539c74818d72e0908319c5d8e4c2104a8c6ff3820b14', '2025-02-14 15:12:21', '2025-02-15 15:12:21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02152025_001228', 'user_02142025_045426', '94eab4d90b4a54585a0c8003dafca97af9960881a004074618bee19cd5d6b96e', '2025-02-14 15:12:28', '2025-02-15 15:12:28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02152025_001458', 'user_02142025_045426', 'b8eb6f4b685ca68231de8f7647559c32f97914e4f5db4271ef8df7d47e7a5ac1', '2025-02-14 15:14:58', '2025-02-15 15:14:58', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02152025_001502', 'user_02142025_045426', '703c24c716ae47039d0efff0e1bbfa4133167c864aaceacfab7cdfaba06c69c2', '2025-02-14 15:15:02', '2025-02-15 15:15:02', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02152025_002312', 'user_02142025_045426', 'cf4d012ea0eba59555355fbfa15eb60802a3df972419f5dde38ae2a917425091', '2025-02-14 15:23:12', '2025-02-15 15:23:12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02152025_002322', 'user_02142025_045426', '5b20c72681e2415084ead07ab29b79974921abbddd24b819cf327fc254c9e59e', '2025-02-14 15:23:22', '2025-02-15 15:23:22', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02152025_002406', 'user_02142025_045426', '695e81f64c04b7e2689d7f4d52468cb978599b70e2f0acb882b67fe9d48ad6ca', '2025-02-14 15:24:06', '2025-02-15 15:24:06', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02152025_002411', 'user_02142025_045426', '5ac24a8027939dbd325843cbed386c1bf1c795f59d5771ce3e5291fea4c361b2', '2025-02-14 15:24:11', '2025-02-15 15:24:11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02152025_002513', 'user_02142025_045426', '4902eea18c8f1bf4b2196731040c01593fc5a902e0963767ab177b2657abab31', '2025-02-14 15:25:13', '2025-02-15 15:25:13', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02152025_003132', 'user_02142025_045426', '37c90d1af4fb673bbf1033801a05dfd6865306a17dcd8f6ddef7d041d8b20ce9', '2025-02-14 15:31:32', '2025-02-15 15:31:32', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02152025_003438', 'user_02142025_045426', '53a45893bec7e820e5cb79afc9c8c161d353777c94ed900e1f0bf7cf8c25e50b', '2025-02-14 15:34:38', '2025-02-15 15:34:38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02152025_003507', 'user_02142025_045426', '8d744d25b401625293b05a1ffee88ab8acb6d1169369be181606c75eb1e17337', '2025-02-14 15:35:07', '2025-02-15 15:35:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02172025_023144', 'user_02142025_045426', '53af370fc5961e458f8385d3c4b89647c654214142801bdb41b134d90bfac102', '2025-02-16 17:31:44', '2025-02-17 17:31:44', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02172025_025925', 'user_02142025_045426', '823f3414e735f86599f3ce58f6297c3be40f73ecf534d715e85a85fbce9c296a', '2025-02-16 17:59:25', '2025-02-17 17:59:25', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02172025_034211', 'user_02142025_045426', 'cfd325fd292fab2ec7ecacb93c6dd9b526e79a33d577cf87d9646fdeacc22701', '2025-02-16 18:42:11', '2025-02-17 18:42:11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02172025_034219', 'user_02142025_045426', '3aeb1d066102488caf115edae9ef39e3987f1948ab651db21a949965de5ad6f6', '2025-02-16 18:42:19', '2025-02-17 18:42:19', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02172025_204530', 'user_02142025_045426', 'd3869942026d8522b0dd9962152c542cd8362270426770090ac7e6234ecb5d49', '2025-02-17 11:45:30', '2025-02-18 11:45:30', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02172025_204732', 'user_02142025_045426', 'b6926d81d8d195754a1f6caabaf4ea13ff43d9cfa6480a268c1bebf1a1d01528', '2025-02-17 11:47:32', '2025-02-18 11:47:32', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0');

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
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_num` varchar(50) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `user_type` enum('patient','healthcare_provider','receptionist','administrator') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_table`
--

INSERT INTO `users_table` (`user_id`, `username`, `firstname`, `middlename`, `lastname`, `password_hash`, `email`, `contact_num`, `picture`, `user_type`, `created_at`) VALUES
('user_02142025_045426', 'admin', 'Admin', 'Nis', 'Trator', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'begino_kun@gmail.com', '09931234567', 'DATA/user-data/generic_user.png', 'patient', '2025-02-13 19:54:26'),
('user_02142025_063500', 'receptionist', 'Recep', 'Tio', 'Nist', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'begino_recep@gmail.com', '09931234567', 'DATA/user-data/generic_user.png', 'receptionist', '2025-02-13 22:35:00'),
('user_02142025_074100', 'doctor', 'Doc', '', 'Tor', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'begino_phd@gmail.com', '09931234567', 'DATA/user-data/generic_user.png', 'healthcare_provider', '2025-02-13 23:41:00'),
('user_02142025_074200', 'paul_begino', 'Paul Ivon', 'Barcelon', 'Begino', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'begino@gmail.com', '09931234567', 'DATA/user-data/generic_user.png', 'administrator', '2025-02-13 23:42:00'),
('user_02142025_184101', 'administrator', 'Paul', '', 'Begino', '$argon2id$v=19$m=65536,t=4,p=1$MHpDdXR3akN4RlV4dVMyeQ$SEaOh98QivMm3AQsewXlXZdkb53xh6c3o/D3XFiO730', 'begino@gmail.com', '09912345678', 'DATA/user-data/generic_user.png', 'administrator', '2025-02-14 09:41:01'),
('user_02172025_030839', 'admin_2', 'wfowf', 'edgege', 'gegefg', '$argon2id$v=19$m=65536,t=4,p=1$Qjc0VGMzNTIxNTdWMnZLcQ$MIRcsBXTyiStDob9Ynmyq67+xj/Py6qbqHG42lEqvY8', 'gggggg@gmail.com', '0921243872348', 'DATA/user-data/generic_user.png', 'administrator', '2025-02-16 18:08:39');

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
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users_administrator_table`
--
ALTER TABLE `users_administrator_table`
  ADD CONSTRAINT `users_administrator_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_table` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
