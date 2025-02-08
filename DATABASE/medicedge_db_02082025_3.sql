-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2025 at 03:04 PM
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
-- Table structure for table `administrators_table`
--

CREATE TABLE `administrators_table` (
  `admin_id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments_table`
--

CREATE TABLE `appointments_table` (
  `appointment_id` varchar(255) NOT NULL,
  `patient_id` varchar(255) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `appointment_date` datetime NOT NULL,
  `status` enum('Scheduled','Completed','Cancelled') NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `healthcare_providers_table`
--

CREATE TABLE `healthcare_providers_table` (
  `provider_id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `license_number` varchar(50) NOT NULL,
  `clinic_address` text DEFAULT NULL,
  `availability` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_records_table`
--

CREATE TABLE `medical_records_table` (
  `record_id` varchar(255) NOT NULL,
  `patient_id` varchar(255) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `diagnosis` text NOT NULL,
  `prescription` text DEFAULT NULL,
  `record_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients_table`
--

CREATE TABLE `patients_table` (
  `patient_id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `medical_history` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients_table`
--

INSERT INTO `patients_table` (`patient_id`, `user_id`, `first_name`, `last_name`, `dob`, `gender`, `address`, `emergency_contact`, `medical_history`) VALUES
('cfc61b00-e5cb-11ef-be3b-98fa9b3b9d0b', 'user_02082025_042139', '', '', '2000-01-01', 'Other', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments_table`
--

CREATE TABLE `payments_table` (
  `payment_id` varchar(255) NOT NULL,
  `patient_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Completed','Failed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receptionists_table`
--

CREATE TABLE `receptionists_table` (
  `receptionist_id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `work_schedule` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_sessionlists_table`
--

CREATE TABLE `users_sessionlists_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `logintime` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiretime` timestamp NULL DEFAULT NULL,
  `ipaddress` varchar(45) DEFAULT NULL,
  `useragent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_table`
--

CREATE TABLE `users_table` (
  `user_id` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_num` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `user_type` enum('patient','provider','receptionist','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_table`
--

INSERT INTO `users_table` (`user_id`, `firstname`, `middlename`, `lastname`, `username`, `email`, `contact_num`, `password_hash`, `user_type`, `created_at`) VALUES
('user_02082025_040510', 'q', 'q', 'q', 'q', '', '09912345678', '$2y$10$GrAfRZTVgEBsJQS6.7VfY.Rt51Zbv2/91tCz9kM7Jgey03nApE.Te', 'patient', '2025-02-07 19:05:10'),
('user_02082025_042139', 'Kent', '', 'Abuyon', 'assss', '', '09912345678', '$2y$10$OOda6eTRpqboQaOsCJr3xuMQlOULHs.rCSruCJODuz2EYHQFcqlVW', 'patient', '2025-02-07 19:21:39');

--
-- Triggers `users_table`
--
DELIMITER $$
CREATE TRIGGER `after_user_insert` AFTER INSERT ON `users_table` FOR EACH ROW BEGIN
    CASE NEW.user_type
        WHEN 'patient' THEN
            INSERT INTO patients_table (patient_id, user_id, first_name, last_name, dob, gender, address, emergency_contact, medical_history)
            VALUES (UUID(), NEW.user_id, '', '', '2000-01-01', 'Other', NULL, NULL, NULL);
        WHEN 'provider' THEN
            INSERT INTO healthcare_providers_table (provider_id, user_id, first_name, last_name, specialization, license_number, clinic_address, availability)
            VALUES (UUID(), NEW.user_id, '', '', '', '', NULL, NULL);
        WHEN 'receptionist' THEN
            INSERT INTO receptionists_table (receptionist_id, user_id, first_name, last_name, work_schedule)
            VALUES (UUID(), NEW.user_id, '', '', NULL);
        WHEN 'admin' THEN
            INSERT INTO administrators_table (admin_id, user_id, role)
            VALUES (UUID(), NEW.user_id, 'Administrator');
    END CASE;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrators_table`
--
ALTER TABLE `administrators_table`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `appointments_table`
--
ALTER TABLE `appointments_table`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `healthcare_providers_table`
--
ALTER TABLE `healthcare_providers_table`
  ADD PRIMARY KEY (`provider_id`),
  ADD UNIQUE KEY `license_number` (`license_number`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `medical_records_table`
--
ALTER TABLE `medical_records_table`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `patients_table`
--
ALTER TABLE `patients_table`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `payments_table`
--
ALTER TABLE `payments_table`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `receptionists_table`
--
ALTER TABLE `receptionists_table`
  ADD PRIMARY KEY (`receptionist_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `users_table`
--
ALTER TABLE `users_table`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `email` (`email`) USING BTREE;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administrators_table`
--
ALTER TABLE `administrators_table`
  ADD CONSTRAINT `administrators_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_table` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `appointments_table`
--
ALTER TABLE `appointments_table`
  ADD CONSTRAINT `appointments_table_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients_table` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_table_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `healthcare_providers_table` (`provider_id`) ON DELETE CASCADE;

--
-- Constraints for table `healthcare_providers_table`
--
ALTER TABLE `healthcare_providers_table`
  ADD CONSTRAINT `healthcare_providers_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_table` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_records_table`
--
ALTER TABLE `medical_records_table`
  ADD CONSTRAINT `medical_records_table_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients_table` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medical_records_table_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `healthcare_providers_table` (`provider_id`) ON DELETE CASCADE;

--
-- Constraints for table `patients_table`
--
ALTER TABLE `patients_table`
  ADD CONSTRAINT `patients_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_table` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments_table`
--
ALTER TABLE `payments_table`
  ADD CONSTRAINT `payments_table_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients_table` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `receptionists_table`
--
ALTER TABLE `receptionists_table`
  ADD CONSTRAINT `receptionists_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_table` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
