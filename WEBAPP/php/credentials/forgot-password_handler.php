<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connection.php';

$webapp_config_file = $_SERVER['DOCUMENT_ROOT'] . '/CONFIG/medicedge_webapp_config.json';
if (file_exists($webapp_config_file)) {
    $config_data = json_decode(file_get_contents($webapp_config_file), true);
} else {
    $config_data = [];
}

$orgName = $config_data['org-name'] ?? 'MedicEdge';
$contactEmail = $config_data['contact-email'] ?? 'Unavailable';
$contactNumber = $config_data['contact-num'] ?? 'Unavailable';
$address = $config_data['address'] ?? 'Unavailable';

date_default_timezone_set('UTC');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Validate required parameters based on action
    switch($action) {
        case 'verify_code':
            if (!isset($_POST['code']) || empty($_POST['code'])) {
                echo json_encode(['success' => false, 'message' => 'Operation cancelled']);
                exit;
            }
            verifyResetCode($conn, $_POST['code']);
            break;
        
        case 'update_password':
            if (!isset($_POST['password']) || empty($_POST['password'])) {
                echo json_encode(['success' => false, 'message' => 'Operation cancelled']);
                exit;
            }
            updatePassword($conn, $_POST['password']);
            break;
        
        default:
            if (!isset($_POST['username']) || empty($_POST['username'])) {
                echo json_encode(['success' => false, 'message' => 'Operation cancelled']);
                exit;
            }
            handleInitialRequest($conn);
            break;
    }
}

function handleInitialRequest($conn) {
    global $orgName, $contactEmail, $contactNumber, $address;
    $username = $_POST['username'] ?? '';
    
    // Check if username exists and get email
    $stmt = $conn->prepare("SELECT user_id, email FROM users_table WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Username not found']);
        exit;
    }
    
    $user = $result->fetch_assoc();
    $user_id = $user['user_id'];
    $email = $user['email'];
    
    // Generate reset ID with new format
    $reset_id = 'resetrequest_' . date('mdY_His');
    
    // Generate random code
    $email_code = generateRandomCode();
    
    // Set expiry time (5 minutes from now)
    $expire_time = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users_forgotpassword (id, user_id, email, email_code, expire_time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $reset_id, $user_id, $email, $email_code, $expire_time);
    
    if ($stmt->execute()) {
        // Set cookie
        setcookie('resetpwd_id', $reset_id, [
            'expires' => time() + 300,
            'path' => '/',
            'samesite' => 'Lax'
        ]);
        
        // Create HTML email
        $to = $email;
        $subject = "$orgName - Password Reset Code";
        
        // HTML Email Template
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Password Reset</title>
        </head>
        <body style='margin: 0; padding: 0; font-family: Arial, sans-serif;'>
            <!-- Navbar -->
            <div style='background-color: #ffffff; padding: 20px; text-align: center; border-bottom: 2px solid #f0f0f0;'>
                <h2 style='color: #2c3e50; margin: 0;'>{$orgName}</h2>
            </div>

            <!-- Main Content -->
            <div style='padding: 40px 20px; text-align: center; background-color: #f8f9fa;'>
                <h3 style='color: #2c3e50;'>Password Reset Code</h3>
                <p style='color: #666; margin-bottom: 30px;'>Hello {$username},<br>This is your password reset code:</p>
                <div style='background-color: #fff; padding: 20px; border-radius: 5px; display: inline-block; margin: 0 auto;'>
                    <p style='font-size: 24px; font-weight: bold; color: #2c3e50; margin: 0;'>{$email_code}</p>
                </div>
                <p style='color: #666; margin-top: 30px;'>This code will expire in 5 minutes.</p>
                <p style='color: #999; font-size: 12px;'>If you didn't request this code, please ignore this email.<br><br>This is an automated email. Do not reply.</p>
            </div>

            <!-- Footer -->
            <div style='background-color: #2c3e50; color: white; padding: 20px; text-align: center; font-size: 12px;'>
                <p style='margin: 5px 0;'>{$orgName}</p>
                <p style='margin: 5px 0;'>Email: {$contactEmail}</p>
                <p style='margin: 5px 0;'>Phone: {$contactNumber}</p>
                <p style='margin: 5px 0;'>{$address}</p>
                <p style='margin-top: 15px; font-size: 11px;'>© " . date('Y') . " {$orgName}. All rights reserved.</p>
            </div>
        </body>
        </html>";

        // Email Headers
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$orgName}\r\n";
        
        // Attempt to send email with error handling
        if (!mail($to, $subject, $message, $headers)) {
            // If mail fails, delete the reset request and return error
            $delete_stmt = $conn->prepare("DELETE FROM users_forgotpassword WHERE id = ?");
            $delete_stmt->bind_param("s", $reset_id);
            $delete_stmt->execute();
            
            // Remove cookie
            setcookie('resetpwd_id', '', time() - 3600, '/');
            
            // echo json_encode(['success' => false, 'message' => 'Unable to send email. Please try again later or contact support.']);
            exit;
        }
        
        echo json_encode(['success' => true, 'message' => 'Reset code sent to your email']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error processing request']);
    }
}

function verifyResetCode($conn, $code) {
    if (empty($code)) {
        echo json_encode(['success' => false, 'message' => 'Operation cancelled']);
        exit;
    }
    
    if (!isset($_COOKIE['resetpwd_id'])) {
        echo json_encode(['success' => false, 'message' => 'Reset session expired']);
        exit;
    }
    
    $reset_id = $_COOKIE['resetpwd_id'];
    $current_time = date('Y-m-d H:i:s');
    
    $stmt = $conn->prepare("SELECT * FROM users_forgotpassword WHERE id = ? AND email_code = ? AND expire_time > ?");
    $stmt->bind_param("sss", $reset_id, $code, $current_time);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired code']);
    }
}

function updatePassword($conn, $password) {
    if (empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Operation cancelled']);
        exit;
    }
    
    if (!isset($_COOKIE['resetpwd_id'])) {
        echo json_encode(['success' => false, 'message' => 'Reset session expired']);
        exit;
    }
    
    $reset_id = $_COOKIE['resetpwd_id'];
    
    // Get user_id from reset request
    $stmt = $conn->prepare("SELECT user_id FROM users_forgotpassword WHERE id = ?");
    $stmt->bind_param("s", $reset_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid reset session']);
        exit;
    }
    
    $user = $result->fetch_assoc();
    $user_id = $user['user_id'];
    
    // Update password using Argon2id
    $argon_options = [
        'memory_cost' => 65536,  // 64MB
        'time_cost' => 4,        // 4 iterations
        'threads' => 1           // 1 thread
    ];
    $password_hash = password_hash($password, PASSWORD_ARGON2ID, $argon_options);
    
    $stmt = $conn->prepare("UPDATE users_table SET password_hash = ? WHERE user_id = ?");
    $stmt->bind_param("ss", $password_hash, $user_id);
    
    if ($stmt->execute()) {
        // Remove cookie only
        setcookie('resetpwd_id', '', time() - 3600, '/');
        
        echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating password']);
    }
}

function generateRandomCode() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < 8; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}
