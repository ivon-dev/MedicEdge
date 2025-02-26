-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2025 at 08:29 AM
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
  `status` enum('upcoming','completed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('user_02142025_184101', 'user_02142025_184101', 'administrator', 'studpaullll32@gmail.com', '09912345678', '');

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
('user_02142025_074100', 'user_02142025_074100', 'doctor', 'begino_phd@gmail.com', '09931234567', '123 Liver Street'),
('user_02262025_065455', 'user_02262025_065455', 'renan_bacit', 'renskieee@yahoo.com', '09999999969', '123 Diamond Street');

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
('user_02142025_074200', 'user_02142025_074200', 'paul_begino', 'begino@gmail.com', '09931234567', ''),
('user_02252025_192717', 'user_02252025_192717', 'juan_delacruz', 'jdcruz@gmail.com', '09940278111', '123 Mangga Street'),
('user_02262025_032333', 'user_02262025_032333', '', '', '', ''),
('user_02262025_051727', 'user_02262025_051727', '', '', '', ''),
('user_02262025_064647', 'user_02262025_064647', 'l_lamperouge', 'l_l@medford.edu.bri', '69', '169 Medford Street'),
('user_02262025_080232', 'user_02262025_080232', 'tiff', 't@i.ff', '123', '123'),
('user_02262025_080610', 'user_02262025_080610', 'l_l', 'l@l.com', '123', '123'),
('user_02262025_080808', 'user_02262025_080808', 'l_lll', '1@l.com', '123', '123'),
('user_02262025_081643', 'user_02262025_081643', 'ezra_realino', 'ez_realino1903@gmail.com', '09876543210', '123 Onyx Street');

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
('session_02262025_022017', 'user_02142025_074100', '1a033dd2819dcd58c58f27b09daa3359bcc9b6d06ebb41742ff59f5ff0a05cfd', '2025-02-25 17:20:17', '2025-02-25 17:33:39', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02262025_022042', 'user_02142025_063500', '6f5fcc66964ed4927bde71299e5693df3f4a0ddb7b8bd0391c958a6986303b92', '2025-02-25 17:20:42', '2025-02-25 17:33:45', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02262025_023546', 'user_02142025_074100', '07996e5e4b6f6d30344b4ac5fd4165fe70a49cee547ed560af60be1a95238137', '2025-02-25 17:35:46', '2025-02-25 21:57:55', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02262025_023555', 'user_02142025_063500', '6dc8223ddc81b98cdabf3c9dd8a7df8e99e593d346340ded237986f6361f385d', '2025-02-25 17:35:55', '2025-02-26 17:35:55', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02262025_031646', 'user_02142025_184101', '26715c8b1b06a294b0156bdc1ef49ed8328e465e15013f30b03d60c6e35fc1c8', '2025-02-25 18:16:46', '2025-02-25 19:29:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02262025_042911', 'user_02142025_184101', '4c0a8bf45c499ce687662792d5cf38c8453143be12bead7909dc115647211a41', '2025-02-25 19:29:11', '2025-02-26 19:29:11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02262025_065759', 'user_02262025_065455', '0926ed8790983a822ae0427ebab063ff3ce29123bdd6dc7d6806ccf967273f8e', '2025-02-25 21:57:59', '2025-02-26 21:57:59', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0'),
('session_02262025_070834', 'user_02142025_184101', '6aadfe7c85faeb4fa67b8f4918f6f42d33e2c5462f703289f12536efc0f744b3', '2025-02-25 22:08:34', '2025-02-25 22:09:39', '192.168.1.2', 'Mozilla/5.0 (Android 14; Mobile; rv:135.0) Gecko/135.0 Firefox/135.0');

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
('user_02142025_063500', 'receptionist', 'Marie', 'Cruz', 'Jabadino', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'begino_recep@gmail.com', '09931234567', '69 Kuan Street', 'DATA/user-data/user_02142025_063500/profile_picture.png', 'receptionist', '2025-02-13 14:35:00'),
('user_02142025_074100', 'doctor', 'Juanito', '', 'Ciamco', '$argon2id$v=19$m=65536,t=4,p=1$UXNYaXI5d0JTL1ZaYlY0aQ$U3XtVRcM+6T16THvagHXTcMc89uXFSUXRgQ7y8OinfI', 'juanito887_md@gmail.com', '09931234567', '123 Liver Street', 'DATA/user-data/user_02142025_074100/profile_picture.jpg', 'healthcare_provider', '2025-02-13 15:41:00'),
('user_02142025_184101', 'administrator', 'Paul Ivon', 'Admin', 'Begino', '$argon2id$v=19$m=65536,t=4,p=1$VDh1ejFVZ3ZlTmlqM2kvQw$58zEtyE2/MnbZNewv/NAb0k388sXie3PkmJR/CDI05o', 'studpaullll32@gmail.com', '09912345678', '123 Liver Street', 'DATA/user-data/user_02142025_184101/profile_picture_1.png', 'administrator', '2025-02-14 01:41:01'),
('user_02262025_065455', 'renan_bacit', 'Renan', 'Delos Santos', 'Bacit', '$argon2id$v=19$m=65536,t=4,p=1$eGk2bXdXY2M2ZURZQUJ6QQ$/zYk6D5xKS6v6BNpxf7Dij1qsHZ/Z8Qixlk9Y5wItLs', 'renskieee@yahoo.com', '09999999969', '123 Diamond Street', 'DATA/user-data/generic_user.png', 'healthcare_provider', '2025-02-26 05:54:55'),
('user_02262025_081643', 'ezra_realino', 'Ezra', '', 'Realino', '$argon2id$v=19$m=65536,t=4,p=1$ZUNOS2JycmR1U1Q2c1VZVw$3LgNLY9OF4r6T3+J3S8ui0E9ZVmeeplfMk9XHZ8v8wE', 'ez_realino1903@gmail.com', '09876543210', '123 Onyx Street', 'DATA/user-data/generic_user.png', 'patient', '2025-02-26 07:16:43');

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
