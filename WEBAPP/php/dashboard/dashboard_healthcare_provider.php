<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connection.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'getPatients':
        getUsers('patient');
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
    case 'getAppointments':
        getAppointments();
        break;
    case 'completeAppointment':
        completeAppointment();
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

function getAppointments() {
    global $conn;
    $status = $_GET['status'] ?? 'upcoming';
    $providerId = $_GET['provider'] ?? '';
    $searchTerm = $_GET['search'] ?? '';
    
    $query = "SELECT 
                a.*,
                CONCAT(p.firstname, ' ', p.lastname) as patient_name
              FROM appointments_table a
              JOIN users_table p ON a.patient = p.user_id
              WHERE a.healthcare_provider = ? 
              AND a.status = ?
              AND (
                  p.firstname LIKE ? OR 
                  p.lastname LIKE ? OR
                  a.type LIKE ?
              )
              ORDER BY a.time_start ASC";
    
    $searchPattern = "%$searchTerm%";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", 
        $providerId,
        $status,
        $searchPattern, 
        $searchPattern, 
        $searchPattern
    );
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $row['time_start'] = (new DateTime($row['time_start']))->format('Y-m-d H:i:s');
        $row['time_end'] = (new DateTime($row['time_end']))->format('Y-m-d H:i:s');
        $appointments[] = $row;
    }
    
    $stmt->close();
    echo json_encode(['status' => 'success', 'appointments' => $appointments]);
}

function completeAppointment() {
    global $conn;
    $id = $_POST['id'];
    
    $query = "UPDATE appointments_table SET status = 'completed' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update appointment']);
    }
    $stmt->close();
}
