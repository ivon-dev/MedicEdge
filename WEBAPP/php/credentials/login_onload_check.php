<?php
include('../connection.php');

// Check if session token exists
if (!isset($_COOKIE['session_token'])) {
    exit(json_encode(['loggedIn' => false]));
}

// Make API call to userinfo.php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://' . $_SERVER['HTTP_HOST'] . '/php/api/userinfo.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, 'session_token=' . ($_COOKIE['session_token'] ?? ''));

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode === 200) {
    if ($response === '"empty"') {
        exit(json_encode(['loggedIn' => false]));
    }

    $data = json_decode($response, true);
    
    if (isset($data['user_type'])) {
        // Determine redirect URL based on user type
        $redirect_url = match($data['user_type']) {
            'administrator' => '/pages/main/home_admin.php',
            'receptionist' => '/pages/main/home_receptionist.php',
            'healthcare_provider' => '/pages/main/home_healthcare_provider.php',
            'patient' => '/pages/main/home_patient.php',
            default => '/pages/main/redirect.php'
        };

        exit(json_encode([
            'loggedIn' => true,
            'redirect' => $redirect_url
        ]));
    }
}

exit(json_encode([
    'loggedIn' => false,
    'error' => 'Invalid session'
]));