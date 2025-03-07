<?php
// Set timezone to UTC for all operations
date_default_timezone_set('UTC');
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connection.php';

header('Content-Type: application/json');

// Get session token from cookie
$session_token = $_COOKIE['session_token'] ?? null;

if (!$session_token) {
    echo json_encode("empty");
    exit;
}

try {
    // Convert current time to UTC explicitly
    $currentUTC = new DateTime('now', new DateTimeZone('UTC'));
    
    // Query to get user info from session token
    $stmt = $conn->prepare("
        SELECT 
            s.id as session_id,
            u.user_id,
            u.username,
            u.firstname,
            u.middlename,
            u.lastname,
            u.email,
            u.contact_num,
            u.address,
            u.picture,
            s.login_time,
            s.expire_time,
            s.expire_time <= ? as session_expired,
            u.user_type
        FROM users_sessionlists s
        JOIN users_table u ON s.user_id = u.user_id
        WHERE s.session_token = ?
    ");
    
    $currentUTCString = $currentUTC->format('Y-m-d H:i:s');
    $stmt->bind_param("ss", $currentUTCString, $session_token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Convert timestamps to UTC ISO format
        $login_time = new DateTime($user['login_time'], new DateTimeZone('UTC'));
        $expire_time = new DateTime($user['expire_time'], new DateTimeZone('UTC'));
        
        $user['login_time'] = $login_time->format('c');
        $user['expire_time'] = $expire_time->format('c');
        $user['session_expired'] = (bool)$user['session_expired'];
        
        echo json_encode($user);
    } else {
        echo json_encode("empty");
    }
    
} catch (Exception $e) {
    echo json_encode("empty");
}

$conn->close();