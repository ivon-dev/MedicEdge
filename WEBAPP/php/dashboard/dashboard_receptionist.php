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
                'medical_history' => '',
                'allergies' => '',
                'current_medications' => '',
                'blood_type' => ''
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
?>
