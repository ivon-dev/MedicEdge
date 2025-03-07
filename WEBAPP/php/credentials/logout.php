<?php
include($_SERVER['DOCUMENT_ROOT'] . '/php/connection.php');

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to securely expire session token
function expireSessionToken($conn) {
    $session_token = $_COOKIE['session_token'] ?? null;
    
    if ($session_token) {
        // Update the expire_time to current time in UTC
        $stmt = $conn->prepare("UPDATE users_sessionlists SET expire_time = CONVERT_TZ(CURRENT_TIMESTAMP, @@session.time_zone, '+00:00') WHERE session_token = ?");
        $stmt->bind_param("s", $session_token);
        $stmt->execute();
        
        // Remove the cookie by setting it to expire in the past
        $cookie_options = [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
            'httponly' => false,
            'samesite' => 'Lax'
        ];
        setcookie('session_token', '', $cookie_options);
    }
}

try {
    if ($conn->connect_error) {
        throw new Exception("Database connection failed");
    }

    // Start transaction
    $conn->begin_transaction();

    // Expire session token and cleanup
    expireSessionToken($conn);
    
    // Destroy PHP session
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_destroy();

    // Commit transaction
    $conn->commit();

    // Redirect to home page
    header("Location: /");
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    if ($conn && $conn->connect_error === false) {
        $conn->rollback();
    }

    // Still destroy local session data even if database cleanup fails
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_destroy();

    // Redirect to home page with error
    header("Location: /?error=logout_failed");
    exit();
}
?>