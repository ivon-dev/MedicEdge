<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connection.php';

function handleSessionRequests($action, $params) {
    switch($action) {
        case 'get_history':
            return getSessionHistory($params['session_token']);
            
        case 'revoke_session':
            return revokeSession($params['session_id']);
            
        case 'delete_session':
            return deleteSession($params['session_id']);
            
        default:
            return ['error' => 'Invalid session action'];
    }
}

// Main request handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['action'])) {
        $result = [];
        
        // Main action type switch
        switch ($data['type'] ?? 'session') {
            case 'session':
                $result = handleSessionRequests($data['action'], $data);
                break;
                
            // You can add more types here like:
            // case 'profile':
            //     $result = handleProfileRequests($data['action'], $data);
            //     break;
            
            default:
                $result = ['error' => 'Invalid request type'];
        }
        
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'No action specified']);
    }
}

// Helper Functions
function getSessionHistory($session_token) {
    global $conn;
    
    // Get user_id from current session token
    $stmt = $conn->prepare("SELECT user_id FROM users_sessionlists WHERE session_token = ?");
    $stmt->bind_param("s", $session_token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        return ['error' => 'Invalid session'];
    }
    
    // Get all sessions for this user
    $stmt = $conn->prepare("SELECT id, login_time, expire_time, ip_address, user_agent, session_token FROM users_sessionlists WHERE user_id = ? ORDER BY login_time DESC");
    $stmt->bind_param("s", $user['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sessions = [];
    while ($row = $result->fetch_assoc()) {
        $row['is_current'] = ($row['session_token'] === $session_token);
        $sessions[] = $row;
    }
    
    return ['sessions' => $sessions];
}

function revokeSession($session_id) {
    global $conn;
    
    // Set expire_time to current UTC time
    $current_time = gmdate('Y-m-d H:i:s');
    $stmt = $conn->prepare("UPDATE users_sessionlists SET expire_time = ? WHERE id = ?");
    $stmt->bind_param("ss", $current_time, $session_id);
    
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['error' => 'Failed to revoke session'];
    }
}

function deleteSession($session_id) {
    global $conn;
    
    $stmt = $conn->prepare("DELETE FROM users_sessionlists WHERE id = ?");
    $stmt->bind_param("s", $session_id);
    
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['error' => 'Failed to delete session'];
    }
}
