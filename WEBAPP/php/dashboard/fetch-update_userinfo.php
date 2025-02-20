<?php
require_once ('connection.php');

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
        $timestamp = time();
        $filename = "profile_picture_{$timestamp}.jpg";
        $filepath = $userDir . "/" . $filename;
            
        // Process and save image
        try {
            // Create image from uploaded file
            $sourceImage = null;
            switch($file['type']) {
                case 'image/jpeg':
                case 'image/jpg':
                    $sourceImage = imagecreatefromjpeg($file['tmp_name']);
                    break;
                case 'image/png':
                    $sourceImage = imagecreatefrompng($file['tmp_name']);
                    break;
            }
        
            if (!$sourceImage) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to process image']);
                exit;
            }
        
            // Get original dimensions
            $origWidth = imagesx($sourceImage);
            $origHeight = imagesy($sourceImage);
        
            // Set max dimensions
            $maxWidth = 800;
            $maxHeight = 800;
        
            // Calculate new dimensions
            if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
                if ($origWidth > $origHeight) {
                    $newWidth = $maxWidth;
                    $newHeight = floor($origHeight * ($maxWidth / $origWidth));
                } else {
                    $newHeight = $maxHeight;
                    $newWidth = floor($origWidth * ($maxHeight / $origHeight));
                }
            } else {
                $newWidth = $origWidth;
                $newHeight = $origHeight;
            }
        
            // Create new image with calculated dimensions
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
                
            // Preserve transparency for PNG
            if ($file['type'] === 'image/png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }
        
            // Resize image
            imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        
            // Save image
            imagejpeg($newImage, $filepath, 90);
        
            // Clean up
            imagedestroy($sourceImage);
            imagedestroy($newImage);
        
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
        
        } catch (Exception $e) {
            error_log("Profile picture upload error: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Failed to process and save image']);
            exit;
        }
    break;

    case 'get_profile_pictures':
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit;
        }

        $userDir = $_SERVER['DOCUMENT_ROOT'] . "/DATA/user-data/" . $user['user_id'];
        $pictures = glob($userDir . "/profile_picture*.jpg");
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
}
?>