<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connection.php';

header('Content-Type: application/json');

// Get session token from cookie
$session_token = $_COOKIE['session_token'] ?? null;

if (!$session_token) {
    echo json_encode("empty");
    exit;
}

try {
    // Query to get user info from session token
    $stmt = $conn->prepare("
        SELECT u.user_id, u.user_type 
        FROM users_sessionlists s
        JOIN users_table u ON s.user_id = u.user_id
        WHERE s.session_token = ?
    ");
    
    $stmt->bind_param("s", $session_token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error']);
}

$conn->close();