-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2025 at 04:24 AM
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
('user_02262025_174822', 'user_02262025_174822', 'administrator', 'studpaullll32@gmail.com', '09931234567', ''),
('user_03052025_170310', 'user_03052025_170310', 'co_admin', 'coadmin@medicedge.org', '123', '123');

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
('resetrequest_03032025_052353', 'user_02262025_174822', 'studpaullll32@gmail.com', 'ZCJAIOMF', '2025-03-02 21:28:53'),
('resetrequest_03032025_170512', 'user_02262025_174822', 'studpaullll32@gmail.com', 'SN06E1WA', '2025-03-03 09:10:12'),
('resetrequest_03062025_085249', 'user_02262025_174822', 'studpaullll32@gmail.com', 'JVOUBO5E', '2025-03-06 00:57:49'),
('resetrequest_03062025_085410', 'user_02262025_174822', 'studpaullll32@gmail.com', '4HRFT02O', '2025-03-06 00:59:10'),
('resetrequest_03072025_165409', 'user_02262025_174822', 'studpaullll32@gmail.com', 'YFQC4APF', '2025-03-07 08:59:09');

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
  `status` enum('success','failed') NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_sessionlists`
--

INSERT INTO `users_sessionlists` (`id`, `user_id`, `session_token`, `login_time`, `expire_time`, `status`, `ip_address`, `user_agent`) VALUES
('session_03072025_092159', 'user_02262025_174822', '5ec27fe1208154a6a97240b79a09d51b5ea35c90ab4482788457f4ee5f2894bc', '2025-03-06 17:21:59', '2025-03-07 08:53:17', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_092407', 'user_02262025_175059', 'd6b0b7d56b8f14798cb9e8a6e2c0e379e77f0ae34855219bd898840c0d7adaf0', '2025-03-06 17:24:07', '2025-03-06 18:23:59', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_092410', 'user_02262025_175202', '056af0eb682691f3d2e5e78fcddd019480ca815c77d6d97396180e5414b15644', '2025-03-06 17:24:10', '2025-03-07 02:12:02', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_092413', 'user_02262025_175245', 'e83eeb005b342eb7bc6a32caa40271315b538ad51982292ed9de039f42abe9c2', '2025-03-06 17:24:13', '2025-03-07 01:48:54', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_100807', 'user_02262025_175202', '1ba8a5fa49f3654d02321bd78768c1e1035ad321e6de7161ad3f29333317ad50', '2025-03-06 18:08:05', '2025-03-06 18:16:26', 'success', '192.168.1.13', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36'),
('session_03072025_101632', 'user_02262025_174822', '03155de923427f5269dc63e2cc5de52a8ffa0cc4e56ff3845ade1fde664dacf9', '2025-03-06 18:16:30', '2025-03-07 07:00:37', 'success', '192.168.1.13', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36'),
('session_03072025_101946', 'user_02262025_174822', 'c870b8afe6acf5eed28ef111919ee8ff2cb118896c61308f4c7eacb8a54d32e7', '2025-03-06 18:19:46', '2025-03-07 00:49:03', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_102403', 'user_02262025_175140', 'd26a572718ed53a175fba3eb2cad3a2a44ac1f1de57045d290602b8b0bd213a1', '2025-03-06 18:24:03', '2025-03-07 02:11:44', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_164906', 'user_02262025_174822', '45f267aae4c1410543bbd4a436e39d6f8d1d4675a651063c2f4b6a15f1821aed', '2025-03-07 00:49:06', '2025-03-07 02:11:35', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_174857', 'user_02262025_175245', '15ff5df48ffaa859e510c025c50f7c22eace4220019b2e21e974a9bce012fd25', '2025-03-07 01:48:57', '2025-03-08 01:48:57', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_180719', 'user_02262025_175059', '1465eed33c68565ff70e8d271fe9e4c480fb905caf943260891fd799cec8fb7d', '2025-03-07 02:07:19', '2025-03-07 02:25:46', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_181138', 'user_02262025_174822', 'a22648cb6209eedacff925dc2f83ab4c0f841a6a15838c91a4507a04486e6294', '2025-03-07 02:11:38', '2025-03-07 08:53:21', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_181147', 'user_02262025_175140', '301a6c6dd7cfa15c8533464622efc3a280ffd4ba24b0f2280a72a9f607188ca8', '2025-03-07 02:11:47', '2025-03-07 07:06:16', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_181207', 'user_02262025_175202', '19300dd1b2562a08685e734aa3f33cbf174d644144fea2064b1c02cb8f206de1', '2025-03-07 02:12:07', '2025-03-07 03:48:55', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_183151', 'user_02262025_175245', 'b0c7629e5989799a7dee9433aa9d987558b3cddb6322e81036491bc5200aecd8', '2025-03-07 02:31:51', '2025-03-07 02:31:54', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_183208', 'user_02262025_174822', 'c16f489785800831e90d3455c00dad06f0f078d6b7bea1c074112ad33891470d', '2025-03-07 02:32:08', '2025-03-07 02:36:02', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_183643', 'user_02262025_174822', '0ee495c3ba5b5da56ed5309641bc18eae80a1ee5ce03fd212e1979398df72c10', '2025-03-07 02:36:43', '2025-03-07 02:36:49', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_183731', 'user_02262025_174822', '8cb2626383a2fbbf191ff38bd9a54953d15ab780dd5c6d0b74839b4e96d5167e', '2025-03-07 02:37:31', '2025-03-07 02:37:46', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_183754', 'user_02262025_174822', '13e8451d3ad8bbd66545b1c2f28ef5c0f9d80edd73bbe97dd5fa3981080acad7', '2025-03-07 02:37:54', '2025-03-07 02:38:34', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_184023', 'user_02262025_175140', '3eb40a48bb4d9243c667829c0c0e477f234f728a522fe307b015935fc0201427', '2025-03-07 02:40:23', '2025-03-07 02:40:29', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_184057', 'user_02262025_175202', '67a16e7f5ceb9319735e4ac5e6ef10dc12a41726bba0c56909487d86119d31dc', '2025-03-07 02:40:57', '2025-03-07 02:49:19', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_184615', 'user_02262025_175202', 'd99a46a5db5ddbb5409d515a588f4affd2483b52dc46ceb3e285a484de852b45', '2025-03-07 02:46:13', '2025-03-07 02:47:56', 'success', '192.168.1.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36'),
('session_03072025_184834', 'user_02262025_175202', 'e0cedbcb5f9bb3418c577a12c834ba63c500424e9d0be65cc23ee8216e40d967', '2025-03-07 02:48:32', '2025-03-07 08:39:42', 'success', '192.168.1.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36'),
('session_03072025_185123', 'user_02262025_175202', '50bd8dbe2921a8b8de9644e2135bb9000aced21cc54cb5a0b3d4548e87f6dd7c', '2025-03-07 02:51:23', '2025-03-07 02:57:08', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_185712', 'user_02262025_175202', '4a8a16b4a845d7d2a7153c63de44cbb7c9afd7e6eecf71fdfafe1b8ebfed42b4', '2025-03-07 02:57:12', '2025-03-07 06:56:56', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_225725', 'user_02262025_175140', '4904cc99a670324a54b3849d8f00eed177ccc49c4b3525ae05c1f0060d40da88', '2025-03-07 06:57:25', '2025-03-07 07:02:07', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_230108', 'user_02262025_175140', 'b07ce871f4999f76a412f01f332d9f27551dcfe67a8307cdcc70a75eea672bd8', '2025-03-07 07:01:09', '2025-03-07 08:40:36', 'success', '192.168.1.13', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36'),
('session_03072025_230210', 'user_02262025_175140', '15d53419e4539a4a96957fc53a413bc54fe4962b4486ca3ac9e6b8889c320f77', '2025-03-07 07:02:10', '2025-03-07 07:03:42', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_230345', 'user_02262025_175140', 'c349d69e0a8d92e83b0e562430887f410ea6051e63debe8c4066bebfd559c6ba', '2025-03-07 07:03:45', '2025-03-08 07:03:45', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_230619', 'user_02262025_175140', 'bc48c442ada6ee2ec49cf883dc85ed41853d9fb107f7d3660a9dc3a55feb55fa', '2025-03-07 07:06:19', '2025-03-07 07:08:06', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_230809', 'user_02262025_175140', 'ce7db0f8c54c8573e04adcc3d4a9f7cbe1c1fd661a16783bf6ada357b8847a71', '2025-03-07 07:08:09', '2025-03-07 08:19:14', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_232644', 'user_02262025_175140', '9f93c1ff69d70d8e8d0a9b2cda16ac3884e271c7f2788ee90dfe2d54fe95c76f', '2025-03-07 07:26:44', '2025-03-07 07:28:13', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03072025_232816', 'user_02262025_174822', '38453fb67eb7095fc62df09cec8599bc302c3574481a6a76aeea9afa74427f62', '2025-03-07 07:28:16', '2025-03-07 08:15:52', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_001556', 'user_02262025_175059', '3e2a4191ddfea696f12f7f4750a146389f692eff361b50ba10f0cda55c48f591', '2025-03-07 08:15:56', '2025-03-07 08:16:52', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_001920', 'user_02262025_175059', '215710c1de601e27abb5a42f2ba768e8fa7b66529b3669c80820fdab22e52350', '2025-03-07 08:19:20', '2025-03-07 08:39:17', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_001923', 'user_02262025_175059', 'dff3d39b4497d6b8d2a91c525f9b626436a26250f24c3afde9bf39d1e47f4b8f', '2025-03-07 08:19:23', '2025-03-07 08:21:56', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_002202', 'user_02262025_175059', 'f1f18e4e74bd8a8f0ffae496bd9137d25e0f186eb111d9f38e5109fa5f8f6b85', '2025-03-07 08:22:02', '2025-03-07 08:25:48', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_002826', 'user_02262025_175059', '1b1e7e6c3a37353db9f481db2df13918acae2493c744738883b053d67b7fa473', '2025-03-07 08:28:26', '2025-03-07 08:28:35', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_002921', 'user_02262025_175059', '3ceb70dc5e7c80fe271047468f557188d32a0fac206793e0f29ef70d84e8af17', '2025-03-07 08:29:21', '2025-03-07 08:29:32', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_003014', 'user_02262025_175059', '4407a676095462848033a003a50b162b5fc2fe69952e059aa2439cbae9446cb6', '2025-03-07 08:30:14', '2025-03-07 08:30:19', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_003049', 'user_02262025_175059', '892861be11320df38a5d8dd38872254d1880f7c72bfc2bb8a17ce1efadcf0ae3', '2025-03-07 08:30:49', '2025-03-07 08:31:44', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_003316', 'user_02262025_175059', '561bcbf7ba39ce6a8693ecfbebd048d7a59c02e3d01a91dc8a853f945196021c', '2025-03-07 08:33:16', '2025-03-07 08:34:02', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_003741', 'user_02262025_175059', 'b8427ef5d2cee5a7949a813409b1e9ea67b7fb8f7c37d705cf1e920707b15440', '2025-03-07 08:37:41', '2025-03-07 08:37:48', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_003757', 'user_02262025_175059', '1341cafd33836296c81f2366fa40ab657c33b294447549496f8c769963ecfaca', '2025-03-07 08:37:57', '2025-03-07 08:38:02', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_003852', 'user_02262025_175059', '1f1302042d214ebdb4e6640af1abe564cdfab10cc148feb22f6a1acc4394175e', '2025-03-07 08:38:52', '2025-03-07 08:38:56', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_003925', 'user_02262025_175202', 'ebee1fd328166d6584491ec5c9ed8463b65bc0b3c7b8fb26e3e1aecb040f0daa', '2025-03-07 08:39:25', '2025-03-07 08:48:34', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_003928', 'user_02262025_175202', '04eba0fb1240d673c243545ac2c15f9e3158c8fb292972797c5289863b78bca5', '2025-03-07 08:39:28', '2025-03-07 08:39:45', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_004005', 'user_02262025_175202', 'dd4f4535a44569b05f5a3067437a76f5e17893faeee6c0c7a927409886f6e83d', '2025-03-07 08:40:05', '2025-03-07 08:48:35', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_004039', 'user_02262025_175202', '40937fb6f53e59e0650d93bfc09096367cbff46473d72184ead4cd4c68155981', '2025-03-07 08:40:40', '2025-03-07 08:40:52', 'success', '192.168.1.13', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36'),
('session_03082025_004128', 'user_02262025_175202', '0de2c4c5b2d3c527c34a3f55992c80838ee4f9237c38538b2b4fefb31cfe2f56', '2025-03-07 08:41:29', '2025-03-07 08:41:41', 'success', '192.168.1.13', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36'),
('session_03082025_004328', 'user_02262025_175202', '188334637ab094327a928606c134b7d32bc270b9c975a340b98b6e5930af4214', '2025-03-07 08:43:29', '2025-03-07 08:43:37', 'success', '192.168.1.13', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36'),
('session_03082025_004838', 'user_02262025_175059', '9073f497dfb61073c3fd59fb996e41168e88b9dee815dfc16e40fef13ebbe5a2', '2025-03-07 08:48:38', '2025-03-07 08:50:06', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_005013', 'user_02262025_175202', 'afdc9dee2cbbddf32e26c9709d3c31ac5c0017c065dceeef3ecd8a211d399ca4', '2025-03-07 08:50:13', '2025-03-08 08:50:13', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_005105', 'user_02262025_175202', '8083a9eb907ef3e29228cd4967601107a585ca8cf1e5e316b86d891ed50ce6b1', '2025-03-07 08:51:05', '2025-03-07 08:51:13', 'success', '192.168.1.13', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36'),
('session_03082025_005121', 'user_02262025_175202', '1735401ab01e077d1f240b834ad4e9993b0f95c4d50f83801768f7c55ded1963', '2025-03-07 08:51:21', '2025-03-07 08:52:50', 'success', '192.168.1.13', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36'),
('session_03082025_005301', 'user_02262025_174822', 'd6f75618922607447396d0167c43cc485cd3a4d7b4f8febeeca1d2914d44fd97', '2025-03-07 08:53:02', '2025-03-07 08:53:25', 'success', '192.168.1.13', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36'),
('session_03082025_005307', 'user_02262025_174822', 'a5ac5afeee5d27b559ac390d20381d8755e44020f65161b69b6708a2a27f4c6a', '2025-03-07 08:53:07', '2025-03-08 08:53:07', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0'),
('session_03082025_005335', 'user_02262025_174822', 'db22381218af7243b12e66abd8d441f06e3efa94882dc348bbe0fd292d184cf6', '2025-03-07 08:53:36', '2025-03-07 08:53:47', 'success', '192.168.1.13', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36');

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
('user_02262025_174822', 'administrator', 'Admin', 'Nis', 'Trator', '$argon2id$v=19$m=65536,t=4,p=1$RUhsRlBiUEEvWkFTZHJTdA$Tac7vm13VjVIJVMZnZX2XlVXNKZB3xB38x7xqyKMRpQ', 'studpaullll32@gmail.com', '09931234567', '123 Health Street', 'DATA/user-data/user_02262025_174822/profile_picture_1.png', 'administrator', '2025-02-26 08:48:23'),
('user_02262025_175059', 'doctor', 'Pinoy', '', 'MD 1', '$argon2id$v=19$m=65536,t=4,p=1$blhmOGwuUHBLUXgzZzc5bA$WZ0zzrHe5hV0uU5vm6mYhEVeOsgfqEdwujShQHmYC+A', '', '', '', 'DATA/user-data/generic_user.png', 'healthcare_provider', '2025-02-26 16:50:59'),
('user_02262025_175140', 'doctor_2', 'Pinoy', '', 'MD 2', '$argon2id$v=19$m=65536,t=4,p=1$ZnV2UTFrU3ZCS2JQaGk5Tw$yiIkkZ9q4QQQaHy1WSZjjtGgd67eeaAgj+vDtv+PVPQ', '', '', '', 'DATA/user-data/generic_user.png', 'healthcare_provider', '2025-02-26 16:51:40'),
('user_02262025_175202', 'receptionist', 'Recep', 'Tio', 'Nist', '$argon2id$v=19$m=65536,t=4,p=1$SjlxdDM3UFlYZTYuNW9NVQ$mqv6AAvWzogkAbC9xpa3Y1/rmzKxaF+tTfJrpJDQfcI', '', '', '', 'DATA/user-data/generic_user.png', 'receptionist', '2025-02-26 16:52:02'),
('user_02262025_175245', 'gerard_morino', 'Gerard', 'Reyes', 'Morino', '$argon2id$v=19$m=65536,t=4,p=1$bnViU3ZGcjd4QzlhN2h6MQ$4Nhn5wjdRuGuGuC1YviQMz3THFgl9iUlKa0rJ60Aa0c', '', '', '', 'DATA/user-data/generic_user.png', 'patient', '2025-02-26 16:52:46'),
('user_02262025_175949', 'yorme_isko', 'Isko', '', 'Moreno', '$argon2id$v=19$m=65536,t=4,p=1$UWRoSUpvTzlsQW85Y05aZQ$PWImfEhow6uivcaKzJDyZr5rvwF+T52IGqLSv5F6Eco', 'iskoooo@gmail.com', '', '123 Manila Street', 'DATA/user-data/generic_user.png', 'patient', '2025-02-26 16:59:49'),
('user_03052025_170310', 'co_admin', 'Co', '', 'Admin', '$argon2id$v=19$m=65536,t=4,p=1$RVFXUFl6QmJDU3lLWEZCcg$jah0tcEo4u99HH9kSueQHZ1TvYCO2KRbdZ8JbA/uYe0', 'coadmin@medicedge.org', '123', '123', 'DATA/user-data/generic_user.png', 'administrator', '2025-03-05 16:03:10');

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
