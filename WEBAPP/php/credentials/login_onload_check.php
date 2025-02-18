<?php
include('../connection.php');

// Check if session token exists
if (!isset($_COOKIE['session_token'])) {
    exit(json_encode(['loggedIn' => false]));
}

// Validate session token
$session_token = htmlspecialchars($_COOKIE['session_token'], ENT_QUOTES, 'UTF-8');

try {
    // Get session and user information
    $stmt = $conn->prepare("
        SELECT s.user_id, u.user_type 
        FROM users_sessionlists s
        JOIN users_table u ON s.user_id = u.user_id
        WHERE s.session_token = ? AND s.expire_time > UTC_TIMESTAMP()
    ");
    
    $stmt->bind_param("s", $session_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Session expired or invalid
        setcookie('session_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        exit(json_encode(['loggedIn' => false]));
    }

    $user = $result->fetch_assoc();
    
    // Determine redirect URL based on user type
    $redirect_url = match($user['user_type']) {
        'administrator' => '/pages/main/home_admin.php',
        'receptionist' => '/pages/main/home_receptionist.php',
        'healthcare_provider' => '/pages/main/home_healthcare_provider.php',
        default => '/pages/main/redirect.php'
    };

    exit(json_encode([
        'loggedIn' => true,
        'redirect' => $redirect_url
    ]));

} catch (Exception $e) {
    exit(json_encode([
        'loggedIn' => false,
        'error' => 'Database error occurred'
    ]));
}