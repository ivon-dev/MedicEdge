
-- Trigger to copy data to role-specific tables after insert in users_table
CREATE TRIGGER after_insert_user
AFTER INSERT ON users_table
FOR EACH ROW
BEGIN
    CASE NEW.user_type
        WHEN 'administrator' THEN
            UPDATE users_administrator_table
            SET username = NEW.username,
                email = NEW.email,
                contact_num = NEW.contact_num,
                address = NEW.address
            WHERE user_id = NEW.user_id;
        WHEN 'healthcare_provider' THEN
            UPDATE users_healthcare_provider_table
            SET username = NEW.username,
                email = NEW.email,
                contact_num = NEW.contact_num,
                address = NEW.address
            WHERE user_id = NEW.user_id;
        WHEN 'receptionist' THEN
            UPDATE users_receptionist_table
            SET username = NEW.username,
                email = NEW.email,
                contact_num = NEW.contact_num,
                address = NEW.address
            WHERE user_id = NEW.user_id;
        WHEN 'patient' THEN
            UPDATE users_patient_table
            SET username = NEW.username,
                email = NEW.email,
                contact_num = NEW.contact_num,
                address = NEW.address
            WHERE user_id = NEW.user_id;
    END CASE;
END;

-- Trigger to update users_table after update in users_administrator_table
CREATE TRIGGER after_update_administrator
AFTER UPDATE ON users_administrator_table
FOR EACH ROW
BEGIN
    UPDATE users_table
    SET username = NEW.username,
        email = NEW.email,
        contact_num = NEW.contact_num,
        address = NEW.address
    WHERE user_id = NEW.user_id;
END;

-- Trigger to update users_table after update in users_healthcare_provider_table
CREATE TRIGGER after_update_healthcare_provider
AFTER UPDATE ON users_healthcare_provider_table
FOR EACH ROW
BEGIN
    UPDATE users_table
    SET username = NEW.username,
        email = NEW.email,
        contact_num = NEW.contact_num,
        address = NEW.address
    WHERE user_id = NEW.user_id;
END;

-- Trigger to update users_table after update in users_receptionist_table
CREATE TRIGGER after_update_receptionist
AFTER UPDATE ON users_receptionist_table
FOR EACH ROW
BEGIN
    UPDATE users_table
    SET username = NEW.username,
        email = NEW.email,
        contact_num = NEW.contact_num,
        address = NEW.address
    WHERE user_id = NEW.user_id;
END;

-- Trigger to update users_table after update in users_patient_table
CREATE TRIGGER after_update_patient
AFTER UPDATE ON users_patient_table
FOR EACH ROW
BEGIN
    UPDATE users_table
    SET username = NEW.username,
        email = NEW.email,
        contact_num = NEW.contact_num,
        address = NEW.address
    WHERE user_id = NEW.user_id;
END;

-- Trigger to update role-specific tables after update in users_table
CREATE TRIGGER after_update_user_table
AFTER UPDATE ON users_table
FOR EACH ROW
BEGIN
    CASE NEW.user_type
        WHEN 'administrator' THEN
            UPDATE users_administrator_table
            SET username = NEW.username,
                email = NEW.email,
                contact_num = NEW.contact_num,
                address = NEW.address
            WHERE user_id = NEW.user_id;
        WHEN 'healthcare_provider' THEN
            UPDATE users_healthcare_provider_table
            SET username = NEW.username,
                email = NEW.email,
                contact_num = NEW.contact_num,
                address = NEW.address
            WHERE user_id = NEW.user_id;
        WHEN 'receptionist' THEN
            UPDATE users_receptionist_table
            SET username = NEW.username,
                email = NEW.email,
                contact_num = NEW.contact_num,
                address = NEW.address
            WHERE user_id = NEW.user_id;
        WHEN 'patient' THEN
            UPDATE users_patient_table
            SET username = NEW.username,
                email = NEW.email,
                contact_num = NEW.contact_num,
                address = NEW.address
            WHERE user_id = NEW.user_id;
    END CASE;
END;
