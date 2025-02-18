<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connection.php';

// Function to handle alerts and redirects
function showAlertAndRedirect($message, $redirectUrl) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Access Denied',
                text: '" . addslashes($message) . "',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                window.location.href = '" . addslashes($redirectUrl) . "';
            });
          </script>";
    exit();
}

// Function to delete session cookie
function deleteSessionCookie() {
    if (isset($_COOKIE['session_token'])) {
        unset($_COOKIE['session_token']);
        setcookie('session_token', '', time() - 3600, '/');
    }
}

// Check if session token exists
if (!isset($_COOKIE['session_token'])) {
    header("Location: /pages/auth/login.php");
    exit();
}

$session_token = $_COOKIE['session_token'];

// Validate session token in database
$stmt = $conn->prepare("
    SELECT u.user_type 
    FROM users_sessionlists us 
    JOIN users_table u ON us.user_id = u.user_id 
    WHERE us.session_token = ?
");

if (!$stmt) {
    deleteSessionCookie();
    header("Location: /pages/auth/login.php");
    exit();
}

$stmt->bind_param("s", $session_token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    deleteSessionCookie();
    header("Location: /pages/auth/login.php");
    exit();
}

$user_data = $result->fetch_assoc();
$user_type = $user_data['user_type'];

// Close database connection
$stmt->close();
$conn->close();

// Direct to appropriate dashboard based on user type
$redirect_url = match($user_type) {
    'administrator' => '/pages/main/home_admin.php',
    'healthcare_provider' => '/pages/main/home_healthcare_provider.php',
    'receptionist' => '/pages/main/home_receptionist.php',
    'patient' => '/pages/main/home_patient.php',
    default => '/pages/auth/login.php'
};

header("Location: " . $redirect_url);
exit();
?>