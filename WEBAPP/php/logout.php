<?php
include('connection.php');

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to securely delete session token
function removeSessionToken($conn) {
    $session_token = $_COOKIE['session_token'] ?? null;
    
    if ($session_token) {
        // Delete the session record from database
        $stmt = $conn->prepare("DELETE FROM users_sessionlists WHERE session_token = ?");
        $stmt->bind_param("s", $session_token);
        $stmt->execute();
        
        // Remove the cookie by setting it to expire in the past
        $cookie_options = [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
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

    // Remove session token and cleanup
    removeSessionToken($conn);
    
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