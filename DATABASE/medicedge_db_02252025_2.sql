-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2025 at 03:08 PM
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
('user_02142025_184101', 'administrator', 'Paul', '', 'Begino', '$argon2id$v=19$m=65536,t=4,p=1$MHpDdXR3akN4RlV4dVMyeQ$SEaOh98QivMm3AQsewXlXZdkb53xh6c3o/D3XFiO730', 'begino@gmail.com', '09912345678', '', 'DATA/user-data/generic_user.png', 'administrator', '2025-02-14 09:41:01'),
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
-- Indexes for table `users_table`
--
ALTER TABLE `users_table`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
