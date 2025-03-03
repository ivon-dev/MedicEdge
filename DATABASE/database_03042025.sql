-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2025 at 05:57 PM
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `handle_user_deletion` (IN `p_user_id` VARCHAR(255))   BEGIN
    DELETE FROM users_table WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sync_role_data_to_users_table` ()   BEGIN
    -- Update from administrator table
    UPDATE users_table u
    JOIN users_administrator_table a ON u.user_id = a.user_id
    SET u.username = a.username,
        u.email = a.email,
        u.contact_num = a.contact_num,
        u.address = a.address
    WHERE u.user_type = 'administrator';
    
    -- Update from healthcare provider table
    UPDATE users_table u
    JOIN users_healthcare_provider_table h ON u.user_id = h.user_id
    SET u.username = h.username,
        u.email = h.email,
        u.contact_num = h.contact_num,
        u.address = h.address
    WHERE u.user_type = 'healthcare_provider';
    
    -- Update from receptionist table
    UPDATE users_table u
    JOIN users_receptionist_table r ON u.user_id = r.user_id
    SET u.username = r.username,
        u.email = r.email,
        u.contact_num = r.contact_num,
        u.address = r.address
    WHERE u.user_type = 'receptionist';
    
    -- Update from patient table
    UPDATE users_table u
    JOIN users_patient_table p ON u.user_id = p.user_id
    SET u.username = p.username,
        u.email = p.email,
        u.contact_num = p.contact_num,
        u.address = p.address
    WHERE u.user_type = 'patient';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sync_user_data_to_role_tables` (IN `p_user_id` VARCHAR(255))   BEGIN
    DECLARE v_user_type VARCHAR(50);
    
    -- Get user type
    SELECT user_type INTO v_user_type 
    FROM users_table 
    WHERE user_id = p_user_id;
    
    -- Handle administrator
    IF v_user_type = 'administrator' THEN
        INSERT INTO users_administrator_table (id, user_id, username, email, contact_num, address)
        SELECT user_id, user_id, username, email, contact_num, address
        FROM users_table
        WHERE user_id = p_user_id
        ON DUPLICATE KEY UPDATE
            username = VALUES(username),
            email = VALUES(email),
            contact_num = VALUES(contact_num),
            address = VALUES(address);
    END IF;
    
    -- Handle healthcare provider
    IF v_user_type = 'healthcare_provider' THEN
        INSERT INTO users_healthcare_provider_table (id, user_id, username, email, contact_num, address)
        SELECT user_id, user_id, username, email, contact_num, address
        FROM users_table
        WHERE user_id = p_user_id
        ON DUPLICATE KEY UPDATE
            username = VALUES(username),
            email = VALUES(email),
            contact_num = VALUES(contact_num),
            address = VALUES(address);
    END IF;
    
    -- Handle receptionist
    IF v_user_type = 'receptionist' THEN
        INSERT INTO users_receptionist_table (id, user_id, username, email, contact_num, address)
        SELECT user_id, user_id, username, email, contact_num, address
        FROM users_table
        WHERE user_id = p_user_id
        ON DUPLICATE KEY UPDATE
            username = VALUES(username),
            email = VALUES(email),
            contact_num = VALUES(contact_num),
            address = VALUES(address);
    END IF;
    
    -- Handle patient
    IF v_user_type = 'patient' THEN
        INSERT INTO users_patient_table (id, user_id, username, email, contact_num, address)
        SELECT user_id, user_id, username, email, contact_num, address
        FROM users_table
        WHERE user_id = p_user_id
        ON DUPLICATE KEY UPDATE
            username = VALUES(username),
            email = VALUES(email),
            contact_num = VALUES(contact_num),
            address = VALUES(address);
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appointments_table`
--

CREATE TABLE `appointments_table` (
  `id` varchar(255) NOT NULL,
  `patient` varchar(255) NOT NULL,
  `healthcare_provider` varchar(255) NOT NULL,
  `type` text NOT NULL,
  `time_start` timestamp NULL DEFAULT NULL,
  `time_end` timestamp NULL DEFAULT NULL,
  `symptoms` tinytext NOT NULL,
  `diagnosis` tinytext NOT NULL,
  `remarks` text NOT NULL,
  `status` enum('upcoming','completed','cancelled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments_table`
--

INSERT INTO `appointments_table` (`id`, `patient`, `healthcare_provider`, `type`, `time_start`, `time_end`, `symptoms`, `diagnosis`, `remarks`, `status`) VALUES
('appointment_02262025_180151', 'user_02262025_175949', 'user_02262025_175059', 'General Consultation', '2004-05-11 10:00:00', '2004-05-11 12:00:00', '', '', '', 'upcoming'),
('appointment_02262025_182251', 'user_02262025_175245', 'user_02262025_175059', 'General Consultation', '2004-05-10 23:00:00', '2004-05-11 02:00:00', '', '', '', 'upcoming');

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
('user_02262025_174822', 'user_02262025_174822', 'administrator', 'studpaullll32@gmail.com', '09931234567', '');

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

--
-- Dumping data for table `users_forgotpassword`
--

INSERT INTO `users_forgotpassword` (`id`, `user_id`, `email`, `email_code`, `expire_time`) VALUES
('resetrequest_03032025_052158', 'user_02262025_174822', 'studpaullll32@gmail.com', 'N9LM41ED', '2025-03-02 21:26:58'),
('resetrequest_03032025_052353', 'user_02262025_174822', 'studpaullll32@gmail.com', 'ZCJAIOMF', '2025-03-02 21:28:53');

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
('user_02262025_175059', 'user_02262025_175059', 'doctor', '', '', ''),
('user_02262025_175140', 'user_02262025_175140', 'doctor_2', '', '', '');

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
('user_02262025_175245', 'user_02262025_175245', 'gerard_morino', '', '', ''),
('user_02262025_175949', 'user_02262025_175949', 'yorme_isko', 'iskoooo@gmail.com', '', '123 Manila Street');

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
('user_02262025_175202', 'user_02262025_175202', 'receptionist', '', '', '');

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
('session_02262025_174829', 'user_02262025_174822', '80b276ca447a2ee346d194c57b831c9c00cee805992c8a0a8198903c9eb2bd14', '2025-02-26 08:48:29', '2025-02-27 08:48:29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02262025_175310', 'user_02262025_175202', 'ed614e14786ec4259f057ca3f5d05a86b6175c81a72cad32e489625009cf5ec6', '2025-02-26 08:53:10', '2025-02-27 08:53:10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02262025_175655', 'user_02262025_175140', '77e67bb8a3c7f867fe2f62fde2ec0b358a158d98fb92b6d67cc3649900312968', '2025-02-26 08:56:55', '2025-02-26 08:57:12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02262025_175715', 'user_02262025_175059', '8dcb5e0608fefc77ab72ad5152bf5816406cf31fe3a3e0a121d45d7409c6616d', '2025-02-26 08:57:15', '2025-02-27 08:57:15', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02262025_180221', 'user_02262025_175949', '58854735e1b38905d124c065ffd64829024399a37c8ec6d3856afd32d7326b65', '2025-02-26 09:02:21', '2025-02-27 09:02:21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_03022025_184928', 'user_02262025_174822', '3bc579b4f1c15765721e824b6faf1eca997e4c9d1e373e43ba583670b17d34a2', '2025-03-02 09:49:28', '2025-03-03 09:49:28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_03032025_153840', 'user_02262025_174822', 'a3e7ba6bdc5c5e9c905d07c11277e3044e4280f42d30a8c422a277fe155b1bf4', '2025-03-03 06:38:40', '2025-03-03 06:39:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_03032025_154254', 'user_02262025_174822', 'bc6f63a0622bf667a78cea7b02d709549b3f3016c794b10c39424e958cad7f50', '2025-03-03 06:42:54', '2025-03-03 06:43:16', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_03032025_154333', 'user_02262025_174822', '27fd8d70943c85bd3790c4506d1fcd151c9643607cb45a6e5f74f7b8451052b1', '2025-03-03 06:43:33', '2025-03-03 06:44:10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_03032025_154955', 'user_02262025_174822', 'b71df34edbb7b22c1e4f0e5d0dd141b00b3d1f92d325679e96193577d5acf43f', '2025-03-03 06:49:55', '2025-03-03 06:50:55', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_03032025_155519', 'user_02262025_174822', '58b76415e2cf185e3a16f36ebc8eb0c8e054e3c002a58538b69709e269fde885', '2025-03-03 06:55:19', '2025-03-03 06:55:21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_03032025_155551', 'user_02262025_174822', '6275084974404c92d94a876765973f0fea64c2d202b8fbd0dc51ffd81346039c', '2025-03-03 06:55:51', '2025-03-03 06:58:47', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_03032025_160221', 'user_02262025_174822', '1e6aa2391e1f0444a0d8cd6bdf0522274111e5ab563654de8d2f9c15dbd19449', '2025-03-03 07:02:21', '2025-03-03 08:24:57', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_03032025_165010', 'user_02262025_174822', '0588ef1f3548c14b705cc1376c6568928e14c3e790d183333ae2ee55eee68d46', '2025-03-03 08:50:10', '2025-03-03 08:50:21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_03032025_165201', 'user_02262025_174822', '80d4d67789ddb62fb4c38a8fb243e4c2592ad2208ee652f4d6e933a0a4169d19', '2025-03-03 08:52:01', '2025-03-03 08:53:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_03032025_165512', 'user_02262025_174822', '4aba62a3181c1737b4324b559ba1282979cc9b85e28c2ab1b07b9131fa20d1a7', '2025-03-03 08:55:12', '2025-03-03 08:56:01', '192.168.1.2', 'Mozilla/5.0 (Android 14; Mobile; rv:135.0) Gecko/135.0 Firefox/135.0'),
('session_03032025_165703', 'user_02262025_174822', '3bd53ec1e6f8a26ee8ed2b282b73462747d64539315f110b8b2e7e07ae4d75d9', '2025-03-03 08:57:03', '2025-03-03 08:57:06', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0');

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

--
-- Dumping data for table `users_session_blocklist`
--

INSERT INTO `users_session_blocklist` (`id`, `ip_address`, `block_end`, `issued_on`) VALUES
('block_03032025_165714', '127.0.0.1', '2025-03-03 09:02:14', '2025-03-03 08:57:14');

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
('user_02262025_174822', 'administrator', 'Admin', 'Nis', 'Trator', '$argon2id$v=19$m=65536,t=4,p=1$RUhsRlBiUEEvWkFTZHJTdA$Tac7vm13VjVIJVMZnZX2XlVXNKZB3xB38x7xqyKMRpQ', 'studpaullll32@gmail.com', '09931234567', '', 'DATA/user-data/user_02262025_174822/profile_picture_1.png', 'administrator', '2025-02-26 08:48:23'),
('user_02262025_175059', 'doctor', 'Pinoy', '', 'MD 1', '$argon2id$v=19$m=65536,t=4,p=1$blhmOGwuUHBLUXgzZzc5bA$WZ0zzrHe5hV0uU5vm6mYhEVeOsgfqEdwujShQHmYC+A', '', '', '', 'DATA/user-data/generic_user.png', 'healthcare_provider', '2025-02-26 16:50:59'),
('user_02262025_175140', 'doctor_2', 'Pinoy', '', 'MD 2', '$argon2id$v=19$m=65536,t=4,p=1$ZnV2UTFrU3ZCS2JQaGk5Tw$yiIkkZ9q4QQQaHy1WSZjjtGgd67eeaAgj+vDtv+PVPQ', '', '', '', 'DATA/user-data/generic_user.png', 'healthcare_provider', '2025-02-26 16:51:40'),
('user_02262025_175202', 'receptionist', 'Recep', 'Tio', 'Nist', '$argon2id$v=19$m=65536,t=4,p=1$SjlxdDM3UFlYZTYuNW9NVQ$mqv6AAvWzogkAbC9xpa3Y1/rmzKxaF+tTfJrpJDQfcI', '', '', '', 'DATA/user-data/generic_user.png', 'receptionist', '2025-02-26 16:52:02'),
('user_02262025_175245', 'gerard_morino', 'Gerard', 'Reyes', 'Morino', '$argon2id$v=19$m=65536,t=4,p=1$bnViU3ZGcjd4QzlhN2h6MQ$4Nhn5wjdRuGuGuC1YviQMz3THFgl9iUlKa0rJ60Aa0c', '', '', '', 'DATA/user-data/generic_user.png', 'patient', '2025-02-26 16:52:46'),
('user_02262025_175949', 'yorme_isko', 'Isko', '', 'Moreno', '$argon2id$v=19$m=65536,t=4,p=1$UWRoSUpvTzlsQW85Y05aZQ$PWImfEhow6uivcaKzJDyZr5rvwF+T52IGqLSv5F6Eco', 'iskoooo@gmail.com', '', '123 Manila Street', 'DATA/user-data/generic_user.png', 'patient', '2025-02-26 16:59:49');

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
-- Indexes for table `appointments_table`
--
ALTER TABLE `appointments_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient` (`patient`,`healthcare_provider`),
  ADD KEY `healthcare_provider` (`healthcare_provider`);

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
-- Constraints for table `appointments_table`
--
ALTER TABLE `appointments_table`
  ADD CONSTRAINT `appointments_table_ibfk_1` FOREIGN KEY (`patient`) REFERENCES `users_patient_table` (`user_id`),
  ADD CONSTRAINT `appointments_table_ibfk_2` FOREIGN KEY (`healthcare_provider`) REFERENCES `users_healthcare_provider_table` (`user_id`);

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
-- Constraints for table `users_patient_table`
--
ALTER TABLE `users_patient_table`
  ADD CONSTRAINT `users_patient_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_table` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
