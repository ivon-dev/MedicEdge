-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2025 at 06:49 PM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `backfill_user_data` ()   BEGIN
    UPDATE users_administrator_table admin
    JOIN users_table users ON admin.user_id = users.user_id
    SET
        admin.username = users.username,
        admin.email = users.email,
        admin.contact_num = users.contact_num,
        admin.address = users.address;
    UPDATE users_healthcare_provider_table hp
    JOIN users_table users ON hp.user_id = users.user_id
    SET
        hp.username = users.username,
        hp.email = users.email,
        hp.contact_num = users.contact_num,
        hp.address = users.address;
    UPDATE users_receptionist_table recep
    JOIN users_table users ON recep.user_id = users.user_id
    SET
        recep.username = users.username,
        recep.email = users.email,
        recep.contact_num = users.contact_num,
        recep.address = users.address;
    UPDATE users_patient_table pat
    JOIN users_table users ON pat.user_id = users.user_id
    SET
        pat.username = users.username,
        pat.email = users.email,
        pat.contact_num = users.contact_num,
        pat.address = users.address;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users_administrator_table`
--

CREATE TABLE `users_administrator_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_num` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_administrator_table`
--

INSERT INTO `users_administrator_table` (`id`, `user_id`, `username`, `email`, `contact_num`, `address`) VALUES
('user_02142025_045426', 'user_02142025_045426', 'admin', 'admin@medicedge.org', '09123456789', '123 Health Drive'),
('user_02142025_184101', 'user_02142025_184101', 'administrator', 'studpaullll32@gmail.com', '09912345678', ''),
('user_02172025_030839', 'user_02172025_030839', 'admin_2', 'gggggg@gmail.com', '0921243872348', '');

--
-- Triggers `users_administrator_table`
--
DELIMITER $$
CREATE TRIGGER `update_users_table_admin` AFTER UPDATE ON `users_administrator_table` FOR EACH ROW BEGIN
    UPDATE users_table
    SET
        username = NEW.username,
        email = NEW.email,
        contact_num = NEW.contact_num,
        address = NEW.address
    WHERE user_id = NEW.user_id;
END
$$
DELIMITER ;

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

-- --------------------------------------------------------

--
-- Table structure for table `users_healthcare_provider_table`
--

CREATE TABLE `users_healthcare_provider_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_num` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_healthcare_provider_table`
--

INSERT INTO `users_healthcare_provider_table` (`id`, `user_id`, `username`, `email`, `contact_num`, `address`) VALUES
('user_02142025_074100', 'user_02142025_074100', 'doctor', 'begino_phd@gmail.com', '09931234567', '123 Liver Street');

--
-- Triggers `users_healthcare_provider_table`
--
DELIMITER $$
CREATE TRIGGER `update_users_table_healthcare_provider` AFTER UPDATE ON `users_healthcare_provider_table` FOR EACH ROW BEGIN
    UPDATE users_table
    SET
        username = NEW.username,
        email = NEW.email,
        contact_num = NEW.contact_num,
        address = NEW.address
    WHERE user_id = NEW.user_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users_patient_table`
--

CREATE TABLE `users_patient_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_num` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_patient_table`
--

INSERT INTO `users_patient_table` (`id`, `user_id`, `username`, `email`, `contact_num`, `address`) VALUES
('user_02142025_074200', 'user_02142025_074200', 'paul_begino', 'begino@gmail.com', '09931234567', '');

--
-- Triggers `users_patient_table`
--
DELIMITER $$
CREATE TRIGGER `update_users_table_patient` AFTER UPDATE ON `users_patient_table` FOR EACH ROW BEGIN
    UPDATE users_table
    SET
        username = NEW.username,
        email = NEW.email,
        contact_num = NEW.contact_num,
        address = NEW.address
    WHERE user_id = NEW.user_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users_receptionist_table`
--

CREATE TABLE `users_receptionist_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_num` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_receptionist_table`
--

INSERT INTO `users_receptionist_table` (`id`, `user_id`, `username`, `email`, `contact_num`, `address`) VALUES
('user_02142025_063500', 'user_02142025_063500', 'receptionist', 'begino_recep@gmail.com', '09931234567', '69 Kuan Street');

--
-- Triggers `users_receptionist_table`
--
DELIMITER $$
CREATE TRIGGER `update_users_table_receptionist` AFTER UPDATE ON `users_receptionist_table` FOR EACH ROW BEGIN
    UPDATE users_table
    SET
        username = NEW.username,
        email = NEW.email,
        contact_num = NEW.contact_num,
        address = NEW.address
    WHERE user_id = NEW.user_id;
END
$$
DELIMITER ;

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
('session_02252025_184838', 'user_02142025_045426', 'ce4e36cd2ca81e4237c76a6d598da71647841cab114f9a372d9f868f4a157b33', '2025-02-25 09:48:38', '2025-02-26 09:48:38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0');

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
('user_02142025_045426', 'admin', 'Administrator', 'Will_of_D', 'Begino', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'admin@medicedge.org', '09123456789', '123 Health Drive', 'DATA/user-data/user_02142025_045426/profile_picture_1.png', 'administrator', '2025-02-13 11:54:26'),
('user_02142025_063500', 'receptionist', 'Recep', 'Tio', 'Nist', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'begino_recep@gmail.com', '09931234567', '69 Kuan Street', 'DATA/user-data/user_02142025_063500/profile_picture.png', 'receptionist', '2025-02-13 14:35:00'),
('user_02142025_074100', 'doctor', 'Doc', '', 'Tor', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'begino_phd@gmail.com', '09931234567', '123 Liver Street', 'DATA/user-data/user_02142025_074100/profile_picture.jpg', 'healthcare_provider', '2025-02-13 15:41:00'),
('user_02142025_074200', 'paul_begino', 'Paul Ivon', 'Barcelon', 'Begino', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'begino@gmail.com', '09931234567', '', 'DATA/user-data/generic_user.png', 'patient', '2025-02-13 15:42:00'),
('user_02142025_184101', 'administrator', 'Paul', '', 'Begino', '$argon2id$v=19$m=65536,t=4,p=1$VDh1ejFVZ3ZlTmlqM2kvQw$58zEtyE2/MnbZNewv/NAb0k388sXie3PkmJR/CDI05o', 'studpaullll32@gmail.com', '09912345678', '', 'DATA/user-data/generic_user.png', 'administrator', '2025-02-14 01:41:01'),
('user_02172025_030839', 'admin_2', 'wfowf', 'edgege', 'gegefg', '$argon2id$v=19$m=65536,t=4,p=1$Qjc0VGMzNTIxNTdWMnZLcQ$MIRcsBXTyiStDob9Ynmyq67+xj/Py6qbqHG42lEqvY8', 'gggggg@gmail.com', '0921243872348', '', 'DATA/user-data/generic_user.png', 'administrator', '2025-02-16 10:08:39');

--
-- Triggers `users_table`
--
DELIMITER $$
CREATE TRIGGER `users_table_sync_tables` AFTER INSERT ON `users_table` FOR EACH ROW BEGIN
    IF NEW.user_type = 'administrator' THEN
        INSERT INTO users_administrator_table (id, user_id, username, email, contact_num, address)
        VALUES (NEW.user_id, NEW.user_id, NEW.username, NEW.email, NEW.contact_num, NEW.address);
    ELSEIF NEW.user_type = 'healthcare_provider' THEN
        INSERT INTO users_healthcare_provider_table (id, user_id, username, email, contact_num, address)
        VALUES (NEW.user_id, NEW.user_id, NEW.username, NEW.email, NEW.contact_num, NEW.address);
    ELSEIF NEW.user_type = 'receptionist' THEN
        INSERT INTO users_receptionist_table (id, user_id, username, email, contact_num, address)
        VALUES (NEW.user_id, NEW.user_id, NEW.username, NEW.email, NEW.contact_num, NEW.address);
    ELSEIF NEW.user_type = 'patient' THEN
        INSERT INTO users_patient_table (id, user_id, username, email, contact_num, address)
        VALUES (NEW.user_id, NEW.user_id, NEW.username, NEW.email, NEW.contact_num, NEW.address);
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
-- Indexes for table `users_patient_table`
--
ALTER TABLE `users_patient_table`
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
