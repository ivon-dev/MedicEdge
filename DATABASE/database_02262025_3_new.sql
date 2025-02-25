CREATE TABLE IF NOT EXISTS `users_administrator_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_num` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `users_forgotpassword`
--

CREATE TABLE IF NOT EXISTS `users_forgotpassword` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_code` varchar(255) NOT NULL,
  `expire_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `users_healthcare_provider_table`
--

CREATE TABLE IF NOT EXISTS `users_healthcare_provider_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
    `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_num` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `users_patient_table`
--

CREATE TABLE IF NOT EXISTS `users_patient_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
    `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_num` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `users_receptionist_table`
--

CREATE TABLE IF NOT EXISTS `users_receptionist_table` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
    `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_num` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `users_sessionlists`
--

CREATE TABLE IF NOT EXISTS `users_sessionlists` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `expire_time` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `users_session_blocklist`
--

CREATE TABLE IF NOT EXISTS `users_session_blocklist` (
  `id` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `block_end` timestamp NULL DEFAULT NULL,
  `issued_on` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `users_table`
--

CREATE TABLE IF NOT EXISTS `users_table` (
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Triggers
--

DROP TRIGGER IF EXISTS `users_table_sync_tables`;
DELIMITER $$
CREATE TRIGGER `users_table_sync_tables` AFTER INSERT ON `users_table` FOR EACH ROW
BEGIN
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
END$$
DELIMITER ;

-- Trigger to update users_table from users_administrator_table
DROP TRIGGER IF EXISTS `update_users_table_admin`;
DELIMITER $$
CREATE TRIGGER `update_users_table_admin` AFTER UPDATE ON `users_administrator_table`
FOR EACH ROW
BEGIN
    UPDATE users_table
    SET
        username = NEW.username,
        email = NEW.email,
        contact_num = NEW.contact_num,
        address = NEW.address
    WHERE user_id = NEW.user_id;
END$$
DELIMITER ;

-- Trigger to update users_table from users_healthcare_provider_table
DROP TRIGGER IF EXISTS `update_users_table_healthcare_provider`;
DELIMITER $$
CREATE TRIGGER `update_users_table_healthcare_provider` AFTER UPDATE ON `users_healthcare_provider_table`
FOR EACH ROW
BEGIN
    UPDATE users_table
    SET
        username = NEW.username,
        email = NEW.email,
        contact_num = NEW.contact_num,
        address = NEW.address
    WHERE user_id = NEW.user_id;
END$$
DELIMITER ;

-- Trigger to update users_table from users_receptionist_table
DROP TRIGGER IF EXISTS `update_users_table_receptionist`;
DELIMITER $$
CREATE TRIGGER `update_users_table_receptionist` AFTER UPDATE ON `users_receptionist_table`
FOR EACH ROW
BEGIN
    UPDATE users_table
    SET
        username = NEW.username,
        email = NEW.email,
        contact_num = NEW.contact_num,
        address = NEW.address
    WHERE user_id = NEW.user_id;
END$$
DELIMITER ;

-- Trigger to update users_table from users_patient_table
DROP TRIGGER IF EXISTS `update_users_table_patient`;
DELIMITER $$
CREATE TRIGGER `update_users_table_patient` AFTER UPDATE ON `users_patient_table`
FOR EACH ROW
BEGIN
    UPDATE users_table
    SET
        username = NEW.username,
        email = NEW.email,
        contact_num = NEW.contact_num,
        address = NEW.address
    WHERE user_id = NEW.user_id;
END$$
DELIMITER ;

--
-- Stored procedure to backfill existing data
--
DROP PROCEDURE IF EXISTS `backfill_user_data`;
DELIMITER $$
CREATE PROCEDURE `backfill_user_data` ()
BEGIN
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