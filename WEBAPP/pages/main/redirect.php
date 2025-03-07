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

// Make API call to userinfo.php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://' . $_SERVER['HTTP_HOST'] . '/php/api/userinfo.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, 'session_token=' . $_COOKIE['session_token']);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode === 200) {
    $data = json_decode($response, true);
    
    if ($response === '"empty"') {
        deleteSessionCookie();
        header("Location: /pages/auth/login.php");
        exit();
    }

    if (isset($data['user_type'])) {
        // Direct to appropriate dashboard based on user type
        $redirect_url = match($data['user_type']) {
            'administrator' => '/pages/main/home_admin.php',
            'healthcare_provider' => '/pages/main/home_healthcare_provider.php',
            'receptionist' => '/pages/main/home_receptionist.php',
            'patient' => '/pages/main/home_patient.php',
            default => '/pages/auth/login.php'
        };

        header("Location: " . $redirect_url);
        exit();
    }
}

// Fallback: If API call fails or returns invalid data
deleteSessionCookie();
header("Location: /pages/auth/login.php");
exit();
?>