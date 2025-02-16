<?php
include('../../php/connection.php');

// Verify session and get user type
function getUserType($conn) {
    if (!isset($_COOKIE['session_token'])) {
        return null;
    }

    $session_token = htmlspecialchars($_COOKIE['session_token'], ENT_QUOTES, 'UTF-8');
    
    // Get user information from session
    $stmt = $conn->prepare("
        SELECT u.user_type, u.user_id 
        FROM users_sessionlists s
        JOIN users_table u ON s.user_id = u.user_id
        WHERE s.session_token = ? AND s.expire_time > UTC_TIMESTAMP()
    ");
    
    $stmt->bind_param("s", $session_token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return null;
    }
    
    $user = $result->fetch_assoc();
    return $user['user_type'];
}

function getRedirectUrl($userType) {
    return match($userType) {
        'administrator' => '/pages/main/home_admin.php',
        'receptionist' => '/pages/main/home_receptionist.php',
        'healthcare_provider' => '/pages/main/home_healthcare_provider.php',
        'patient' => '/pages/main/home_patient.php',
        default => '/pages/auth/login.php'
    };
}

// Main redirect logic
if ($conn->connect_error) {
    header("Location: /pages/auth/login.php?message=Database connection failed&status=danger");
    exit();
}

$userType = getUserType($conn);

if ($userType === null) {
    // Invalid or expired session
    header("Location: /pages/auth/login.php?message=Please login to continue&status=danger");
    exit();
}

$redirectUrl = getRedirectUrl($userType);
header("Location: " . $redirectUrl);
exit();
?>