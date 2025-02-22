<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get input data (assumes form submission via POST)
$action = isset($_POST['action']) ? $_POST['action'] : '';
$inputData = $_POST;

// Get user data from session token
function getUserFromSession($sessionToken) {
    global $conn;

    if (empty($sessionToken) || !$conn) {
        error_log("getUserFromSession: Empty session token or database connection.");
        return null;
    }

    try {
        $stmt = $conn->prepare("
            SELECT ut.* 
            FROM users_table ut
            JOIN users_sessionlists us ON ut.user_id = us.user_id
            WHERE us.session_token = ?
            AND us.expire_time > NOW()
        ");
        
        if (!$stmt) {
            error_log("Failed to prepare statement: " . $conn->error);
            return null;
        }

        $stmt->bind_param("s", $sessionToken);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        $stmt->close();
        return $user;
    } catch (Exception $e) {
        error_log("Error in getUserFromSession: " . $e->getMessage());
        return null;
    }
}

// Verify session exists
if (!isset($_COOKIE['session_token'])) {
    error_log("No session token found in cookie.");
    echo json_encode(['status' => 'error', 'message' => 'No session token found']);
    exit;
}

// Get user data
$sessionToken = $_COOKIE['session_token'];
$user = getUserFromSession($sessionToken);

if (!$user && $action !== 'login') {
    error_log("Invalid or expired session for token: " . $sessionToken);
    echo json_encode(['status' => 'error', 'message' => 'Invalid or expired session']);
    exit;
}

// Handle different profile operations
switch ($action) {
    case 'fetch_profile':
        if ($user) {
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'picture' => $user['picture'],
                    'firstname' => $user['firstname'],
                    'middlename' => $user['middlename'],
                    'lastname' => $user['lastname'],
                    'email' => $user['email'],
                    'contact_num' => $user['contact_num'],
                    'address' => $user['address']
                ]
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
        }
        break;

    case 'upload_profile_picture':
        if (!isset($_FILES['profile_picture'])) {
            echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
            exit;
        }
        
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit;
        }
        
        // Validate file
        $file = $_FILES['profile_picture'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, JPEG and PNG allowed']);
            exit;
        }
        
        if ($file['size'] > $maxSize) {
            echo json_encode(['status' => 'error', 'message' => 'File too large. Maximum size is 5MB']);
            exit;
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['status' => 'error', 'message' => 'File upload failed']);
            exit;
        }
        
        // Create user directory if it doesn't exist
        $userDir = $_SERVER['DOCUMENT_ROOT'] . "/DATA/user-data/" . $user['user_id'];
        if (!is_dir($userDir)) {
            if (!mkdir($userDir, 0755, true)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create user directory']);
                exit;
            }
        }
        
        // Generate unique filename
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $baseFilename = "profile_picture";
        $filename = $baseFilename . "." . $fileExtension;
        $filepath = $userDir . "/" . $filename;

        // Check if the file already exists and rename if necessary
        $i = 1;
        while (file_exists($filepath)) {
            $filename = $baseFilename . "_" . $i . "." . $fileExtension;
            $filepath = $userDir . "/" . $filename;
            $i++;
        }
            
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Update database with new picture path
            $newPicturePath = "DATA/user-data/" . $user['user_id'] . "/" . $filename;
            $stmt = $conn->prepare("UPDATE users_table SET picture = ? WHERE user_id = ?");
            $stmt->bind_param("ss", $newPicturePath, $user['user_id']);
                
            if (!$stmt->execute()) {
                unlink($filepath); // Delete uploaded file if database update fails
                echo json_encode(['status' => 'error', 'message' => 'Failed to update database']);
                exit;
            }
                
            $stmt->close();
        
            echo json_encode([
                'status' => 'success',
                'filepath' => $newPicturePath
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
            exit;
        }
    break;

    case 'get_profile_pictures':
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit;
        }

        $userDir = $_SERVER['DOCUMENT_ROOT'] . "/DATA/user-data/" . $user['user_id'];
        $pictures = array_merge(
            glob($userDir . "/profile_picture*.jpg"),
            glob($userDir . "/profile_picture*.jpeg"),
            glob($userDir . "/profile_picture*.png")
        );
        $pictures = array_map(function ($path) use ($user) {
            return "DATA/user-data/" . $user['user_id'] . "/" . basename($path);
        }, $pictures);

        echo json_encode([
            'status' => 'success',
            'pictures' => $pictures
        ]);
        break;

    case 'update_profile':
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit;
        }

        $stmt = $conn->prepare("
            UPDATE users_table 
            SET firstname = ?, middlename = ?, lastname = ?, 
                email = ?, contact_num = ?, address = ?
            WHERE user_id = ?
        ");
        
        $stmt->bind_param("sssssss", 
            $_POST['firstname'],
            $_POST['middlename'],
            $_POST['lastname'],
            $_POST['email'],
            $_POST['contact_num'],
            $_POST['address'],
            $user['user_id']
        );
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Update failed: ' . $stmt->error]);
        }
        
        $stmt->close();
        break;

    case 'update_profile_picture':
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit;
        }

        $picturePath = $_POST['picture'];

        $stmt = $conn->prepare("UPDATE users_table SET picture = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $picturePath, $user['user_id']);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Update failed: ' . $stmt->error]);
        }

        $stmt->close();
        break;

    case 'delete_profile_picture':
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit;
        }

        $picturePath = $_POST['picture'];
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $picturePath;

        if (file_exists($fullPath)) {
            if (unlink($fullPath)) {
                echo json_encode(['status' => 'success', 'message' => 'Picture deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete picture']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Picture not found']);
        }
        break;
}
?>