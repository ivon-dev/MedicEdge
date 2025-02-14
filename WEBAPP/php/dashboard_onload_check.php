<?php
session_start();
include('connection.php');

// Check if session_token exists in cookies
if (!isset($_COOKIE['session_token'])) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'UserID should not be blank',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '/pages/auth/login.php';
        });
    </script>";
    exit();
}

$session_token = $_COOKIE['session_token'];

// Verify session_token and fetch user_type
$query = "SELECT user_type FROM users_table 
          WHERE id = (SELECT id FROM users_sessionlists WHERE session_token = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $session_token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Invalid session_token: Redirect & clear cookies
    setcookie("session_token", "", time() - 3600, "/"); 
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'UserID should be valid',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '/pages/auth/login.php';
        });
    </script>";
    exit();
}

$row = $result->fetch_assoc();
$user_type = $row['user_type'];

// Get the current script name
$current_page = basename($_SERVER['PHP_SELF']);

// Role-based redirection
$redirect_map = [
    "home_admin.php" => "administrator",
    "home_healthcare_provider.php" => "healthcare_provider",
    "home_receptionist.php" => "receptionist",
    "home_patient.php" => "patient"
];

if (!isset($redirect_map[$current_page]) || $user_type !== $redirect_map[$current_page]) {
    $message = "Sorry, you have no " . ucfirst(str_replace("_", " ", $redirect_map[$current_page])) . " role.";

    // Define redirection priorities
    $role_redirects = [
        "administrator" => "/pages/main/home_admin.php",
        "healthcare_provider" => "/pages/main/home_healthcare_provider.php",
        "receptionist" => "/pages/main/home_receptionist.php",
        "patient" => "/pages/main/home_patient.php"
    ];

    // Determine the correct redirect URL
    $redirect_url = $role_redirects[$user_type] ?? "/pages/auth/login.php";

    echo "<script>
        Swal.fire({
            icon: 'error',
            title: '$message',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '$redirect_url';
        });
    </script>";
    exit();
}
?>
