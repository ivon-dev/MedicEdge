<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connection.php';

// Function to handle alerts and redirects
function showAlertAndRedirect($message, $redirectUrl) {
    // Ensure no output has been sent before
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=utf-8');
    }
    
    // Output the complete HTML structure
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Redirect</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Access Denied',
                text: '" . addslashes($message) . "',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                window.location.href = '" . addslashes($redirectUrl) . "';
            });
        });
        </script>
    </body>
    </html>";
    exit();
}

// Function to delete session cookie
function deleteSessionCookie() {
    if (isset($_COOKIE['session_token'])) {
        unset($_COOKIE['session_token']);
        setcookie('session_token', '', time() - 3600, '/');
    }
}

// Error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Check if session token exists
    if (!isset($_COOKIE['session_token'])) {
        showAlertAndRedirect("UserID should not be blank", "/pages/auth/login.php");
    }

    $session_token = $_COOKIE['session_token'];

    // Validate session token in database
    $stmt = $conn->prepare("
        SELECT u.user_type, u.user_id 
        FROM users_sessionlists us 
        JOIN users_table u ON us.user_id = u.user_id 
        WHERE us.session_token = ?
    ");

    if (!$stmt) {
        throw new Exception("Database preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $session_token);
    
    if (!$stmt->execute()) {
        throw new Exception("Query execution failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        deleteSessionCookie();
        showAlertAndRedirect("UserID should be valid", "/pages/auth/login.php");
    }

    $user_data = $result->fetch_assoc();
    $user_type = $user_data['user_type'];

    // Get current page name
    $current_page = basename($_SERVER['PHP_SELF']);

    // Role-based access control logic
    switch ($current_page) {
        case 'home_admin.php':
            if ($user_type !== 'administrator') {
                $redirect_url = match($user_type) {
                    'healthcare_provider' => '/pages/main/home_healthcare_provider.php',
                    'receptionist' => '/pages/main/home_receptionist.php',
                    'patient' => '/pages/main/home_patient.php',
                    default => '/pages/auth/login.php'
                };
                showAlertAndRedirect("Sorry, you have no Administrator role.", $redirect_url);
            }
            break;

        case 'home_healthcare_provider.php':
            if ($user_type !== 'healthcare_provider') {
                $redirect_url = match($user_type) {
                    'administrator' => '/pages/main/home_admin.php',
                    'receptionist' => '/pages/main/home_receptionist.php',
                    'patient' => '/pages/main/home_patient.php',
                    default => '/pages/auth/login.php'
                };
                showAlertAndRedirect("Sorry, you have no Healthcare Provider role.", $redirect_url);
            }
            break;

        case 'home_receptionist.php':
            if ($user_type !== 'receptionist') {
                $redirect_url = match($user_type) {
                    'administrator' => '/pages/main/home_admin.php',
                    'healthcare_provider' => '/pages/main/home_healthcare_provider.php',
                    'patient' => '/pages/main/home_patient.php',
                    default => '/pages/auth/login.php'
                };
                showAlertAndRedirect("Sorry, you have no Receptionist role.", $redirect_url);
            }
            break;

        case 'home_patient.php':
            if ($user_type !== 'patient') {
                $redirect_url = match($user_type) {
                    'administrator' => '/pages/main/home_admin.php',
                    'healthcare_provider' => '/pages/main/home_healthcare_provider.php',
                    'receptionist' => '/pages/main/home_receptionist.php',
                    default => '/pages/auth/login.php'
                };
                showAlertAndRedirect("Sorry, you have no Patient role.", $redirect_url);
            }
            break;
    }

    // Close database connection
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // Log the error (in production, use proper error logging)
    error_log($e->getMessage());
    showAlertAndRedirect("An error occurred. Please try again.", "/pages/auth/login.php");
}
?>