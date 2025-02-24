<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get input data (assumes form submission via POST)
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Get user data from session token
function getUserFromSession($sessionToken) {
    global $conn;

    if (empty($sessionToken) || !$conn) {
        error_log("getUserFromSession: Empty session token or database connection.");
        return null;
    }

    try {
        $stmt = $conn->prepare("
            SELECT ut.user_id, ut.username, ut.email
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

// Handle different account operations
switch ($action) {
    case 'fetch':
        if ($user) {
            echo json_encode([
                'status' => 'success',
                'message' => 'User data fetched successfully',
                'data' => [
                    'username' => $user['username'],
                    'email' => $user['email']
                ]
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
        }
        break;

    case 'update':
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit;
        }

        // Validate input
        $username = $_POST['username'];
        $email = $_POST['email'];

        if (empty($username) || empty($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Username and email are required']);
            exit;
        }

        // Prepare and execute update query
        $stmt = $conn->prepare("
            UPDATE users_table 
            SET username = ?, email = ?
            WHERE user_id = ?
        ");

        // Bind parameters
        $stmt->bind_param("sss", $username, $email, $user['user_id']);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Account updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Account update failed: ' . $stmt->error]);
        }

        // Close statement
        $stmt->close();
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}
?>
