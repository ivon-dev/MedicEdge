<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connection.php';

    header('Content-Type: application/json');

    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    switch ($action) {
        case 'getPatients':
            getUsers('patient');
            break;
        case 'addPatient':
            addUser('patient');
            break;
        case 'removeUser':
            removeUser();
            break;
        case 'getUser':
            getUser();
            break;
        case 'getMedicalRecords':
            getMedicalRecords();
            break;
        case 'getMedicalRecord':
            getMedicalRecord();
            break;
        case 'updateMedicalRecord':
            updateMedicalRecord();
            break;
        case 'getPatientsList':
            getPatientsList();
            break;
        case 'getHealthcareProvidersList':
            getHealthcareProvidersList();
            break;
        case 'addAppointment':
            addAppointment();
            break;
        case 'getAppointments':
            getAppointments();
            break;
        case 'getAppointment':
            getAppointment();
            break;
        case 'updateAppointment':
            updateAppointment();
            break;
        case 'deleteAppointment':
            deleteAppointment();
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
    }

    function getUsers($userType) {
        global $conn;
        $query = "SELECT user_id, username, firstname, middlename, lastname, email, picture FROM users_table WHERE user_type = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $userType);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $row['fullname'] = $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'];
            $users[] = $row;
        }
        $stmt->close();
        echo json_encode(['status' => 'success', 'users' => $users]);
    }

    function addUser($userType) {
        global $conn;

        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $contact_num = $_POST['contact_num'];
        $address = $_POST['address'];

        $user_id = 'user_' . date('mdY') . '_' . date('His');
        $password_hash = password_hash($password, PASSWORD_ARGON2ID);
        $picture = "DATA/user-data/generic_user.png";

        $query = "INSERT INTO users_table (user_id, username, firstname, middlename, lastname, password_hash, email, contact_num, address, picture, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssssss", $user_id, $username, $firstname, $middlename, $lastname, $password_hash, $email, $contact_num, $address, $picture, $userType);

        if ($stmt->execute()) {
            $stmt->close();
            echo json_encode(['status' => 'success', 'message' => 'User added successfully.']);
        } else {
            $stmt->close();
            echo json_encode(['status' => 'error', 'message' => 'Error adding user: ' . $stmt->error]);
        }
    }

    function removeUser() {
        global $conn;
        $user_id = $_POST['user_id'];

        // First, check if the user exists and is a patient
        $checkQuery = "SELECT user_type FROM users_table WHERE user_id = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $user_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $user = $result->fetch_assoc();
        $checkStmt->close();

        if ($user && $user['user_type'] === 'patient') {
            // Setup directories
            $sourceDir = $_SERVER['DOCUMENT_ROOT'] . "/DATA/patients/$user_id";
            $deletedDir = $_SERVER['DOCUMENT_ROOT'] . "/DATA/patients/deleted";
            $targetDir = $deletedDir . "/$user_id";

            // Create deleted directory if it doesn't exist
            if (!is_dir($deletedDir)) {
                mkdir($deletedDir, 0777, true);
            }

            // Move patient directory if it exists
            if (is_dir($sourceDir)) {
                rename($sourceDir, $targetDir);
            }

            // Delete user from database
            $query = "DELETE FROM users_table WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $user_id);

            if ($stmt->execute()) {
                $stmt->close();
                echo json_encode(['status' => 'success', 'message' => 'User removed successfully.']);
            } else {
                $stmt->close();
                echo json_encode(['status' => 'error', 'message' => 'Error removing user: ' . $stmt->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid user or not a patient.']);
        }
    }

    function getUser() {
        global $conn;
        $user_id = $_GET['user_id'];

        $query = "SELECT user_id, username, firstname, middlename, lastname, email, contact_num, address, user_type FROM users_table WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            echo json_encode(['status' => 'success', 'user' => $user]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        }
    }

    function getMedicalRecords() {
        global $conn;
        $searchTerm = $_GET['search'] ?? '';
        
        $query = "SELECT user_id, username, firstname, middlename, lastname, picture 
                  FROM users_table 
                  WHERE user_type = 'patient'
                  AND (username LIKE ? OR firstname LIKE ? OR lastname LIKE ?)";
        
        $searchPattern = "%$searchTerm%";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $patients = [];
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
        $stmt->close();
        
        echo json_encode(['status' => 'success', 'patients' => $patients]);
    }

    function getMedicalRecord() {
        $userId = $_GET['user_id'];
        $filePath = $_SERVER['DOCUMENT_ROOT'] . "/DATA/patients/$userId/medical_records.json";
        
        if (file_exists($filePath)) {
            $medicalRecord = json_decode(file_get_contents($filePath), true);
            echo json_encode(['status' => 'success', 'medicalRecord' => $medicalRecord]);
        } else {
            // Return empty medical record if file doesn't exist
            echo json_encode(['status' => 'success', 'medicalRecord' => [
                'Date of Birth' => '',
                'Gender' => '',
                'Blood Type' => '',
                'Height' => '',
                'Weight' => '',
                'Allergies' => '',
                'Chronic Conditions' => '',
                'Current Medications' => '',
                'Medical History' => '',
                'Surgical History' => '',
                'Family Medical History' => '',
                'Emergency Contact' => ''
            ]]);
        }
    }

    function updateMedicalRecord() {
        $userId = $_POST['user_id'];
        $data = json_decode($_POST['data'], true);
        
        $directory = $_SERVER['DOCUMENT_ROOT'] . "/DATA/patients/$userId";
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        
        $filePath = "$directory/medical_records.json";
        
        if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT))) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save medical record']);
        }
    }

    function getPatientsList() {
        global $conn;
        $query = "SELECT user_id, firstname, lastname FROM users_table WHERE user_type = 'patient'";
        $result = $conn->query($query);
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode(['status' => 'success', 'users' => $users]);
    }

    function getHealthcareProvidersList() {
        global $conn;
        $query = "SELECT user_id, firstname, lastname FROM users_table WHERE user_type = 'healthcare_provider'";
        $result = $conn->query($query);
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode(['status' => 'success', 'users' => $users]);
    }

    function checkTimeSlotAvailability($providerId, $timeStart, $timeEnd) {
        global $conn;
        
        $query = "SELECT COUNT(*) as count FROM appointments_table 
                  WHERE healthcare_provider = ? 
                  AND status != 'cancelled'
                  AND (
                      (time_start BETWEEN ? AND ?) 
                      OR (time_end BETWEEN ? AND ?)
                      OR (time_start <= ? AND time_end >= ?)
                  )";
                  
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", 
            $providerId, 
            $timeStart, $timeEnd, 
            $timeStart, $timeEnd,
            $timeStart, $timeEnd
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] == 0;
    }

    function addAppointment() {
        global $conn;
        
        $patient = $_POST['patient'];
        $provider = $_POST['provider'];
        $type = $_POST['type'];
        $timeStart = $_POST['timeStart'];
        $timeEnd = $_POST['timeEnd'];
        $symptoms = $_POST['symptoms'];
        $diagnosis = $_POST['diagnosis'];
        $remarks = $_POST['remarks'];
        $status = $_POST['status'];
        
        // Convert times to UTC
        $timeStart = (new DateTime($timeStart))->format('Y-m-d H:i:s');
        $timeEnd = (new DateTime($timeEnd))->format('Y-m-d H:i:s');
        
        // Check time slot availability
        if (!checkTimeSlotAvailability($provider, $timeStart, $timeEnd)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'The selected time slot is not available for this healthcare provider.'
            ]);
            return;
        }
        
        // Generate appointment ID
        $id = 'appointment_' . date('mdY') . '_' . date('His');
        
        $query = "INSERT INTO appointments_table (id, patient, healthcare_provider, type, time_start, time_end, symptoms, diagnosis, remarks, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                  
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssssss", 
            $id, $patient, $provider, $type, 
            $timeStart, $timeEnd, 
            $symptoms, $diagnosis, $remarks, $status
        );
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Appointment created successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error creating appointment: ' . $stmt->error]);
        }
    }

    function getAppointments() {
        global $conn;
        $searchTerm = $_GET['search'] ?? '';
        
        $query = "SELECT 
                    a.*,
                    CONCAT(p.firstname, ' ', p.lastname) as patient_name,
                    CONCAT(h.firstname, ' ', h.lastname) as provider_name
                  FROM appointments_table a
                  JOIN users_table p ON a.patient = p.user_id
                  JOIN users_table h ON a.healthcare_provider = h.user_id
                  WHERE 
                    p.firstname LIKE ? OR 
                    p.lastname LIKE ? OR 
                    h.firstname LIKE ? OR 
                    h.lastname LIKE ? OR
                    a.type LIKE ?
                  ORDER BY a.time_start ASC";
        
        $searchPattern = "%$searchTerm%";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", 
            $searchPattern, 
            $searchPattern, 
            $searchPattern, 
            $searchPattern, 
            $searchPattern
        );
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            // Convert timestamps to local timezone strings
            $row['time_start'] = (new DateTime($row['time_start']))->format('Y-m-d H:i:s');
            $row['time_end'] = (new DateTime($row['time_end']))->format('Y-m-d H:i:s');
            $appointments[] = $row;
        }
        
        $stmt->close();
        echo json_encode(['status' => 'success', 'appointments' => $appointments]);
    }

    function getAppointment() {
        global $conn;
        $id = $_GET['id'];
        
        $query = "SELECT * FROM appointments_table WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();
        
        if ($appointment) {
            echo json_encode(['status' => 'success', 'appointment' => $appointment]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Appointment not found']);
        }
        $stmt->close();
    }

    function updateAppointment() {
        global $conn;
        
        $id = $_POST['id'];
        $patient = $_POST['patient'];
        $provider = $_POST['provider'];
        $type = $_POST['type'];
        $timeStart = (new DateTime($_POST['timeStart']))->format('Y-m-d H:i:s');
        $timeEnd = (new DateTime($_POST['timeEnd']))->format('Y-m-d H:i:s');
        $symptoms = $_POST['symptoms'];
        $diagnosis = $_POST['diagnosis'];
        $remarks = $_POST['remarks'];
        $status = $_POST['status'];
        
        // Check time slot availability (excluding the current appointment)
        $query = "SELECT COUNT(*) as count FROM appointments_table 
                  WHERE healthcare_provider = ? 
                  AND id != ?
                  AND status != 'cancelled'
                  AND (
                      (time_start BETWEEN ? AND ?) 
                      OR (time_end BETWEEN ? AND ?)
                      OR (time_start <= ? AND time_end >= ?)
                  )";
                  
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssss", 
            $provider,
            $id,
            $timeStart, $timeEnd, 
            $timeStart, $timeEnd,
            $timeStart, $timeEnd
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'The selected time slot is not available for this healthcare provider.'
            ]);
            return;
        }
        
        $query = "UPDATE appointments_table SET 
                  patient = ?,
                  healthcare_provider = ?,
                  type = ?,
                  time_start = ?,
                  time_end = ?,
                  symptoms = ?,
                  diagnosis = ?,
                  remarks = ?,
                  status = ?
                  WHERE id = ?";
                  
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssssss", 
            $patient, $provider, $type,
            $timeStart, $timeEnd,
            $symptoms, $diagnosis, $remarks,
            $status, $id
        );
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update appointment']);
        }
        $stmt->close();
    }

    function deleteAppointment() {
        global $conn;
        $id = $_POST['id'];
        
        $query = "DELETE FROM appointments_table WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete appointment']);
        }
        $stmt->close();
    }
?>
