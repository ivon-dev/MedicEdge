<?php
include('connection.php');

// Constants
define('SESSION_LIFETIME', 86400); // 24 hours

// Utility Functions
class AuthUtils {
    public static function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function generateSessionToken() {
        return bin2hex(random_bytes(32));
    }

    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function generateUserId($prefix, $conn) {
        do {
            // Get current date and time in the desired format
            // Format: user_DDMMYYYY_HHMMSS
            $userId = sprintf(
                "%s_%s_%s",
                $prefix,
                date('mdY'),
                date('His')
            );
            
            // Check if this ID already exists in the database
            $stmt = $conn->prepare("SELECT user_id FROM users_table WHERE user_id = ?");
            $stmt->bind_param("s", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // If ID doesn't exist, return it
            if ($result->num_rows === 0) {
                return $userId;
            }
            
            // If ID exists, wait for a millisecond to get a different timestamp
            usleep(1000);
        } while (true);
    }

    public static function checkRateLimit($ip) {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = [];
        }

        if (!isset($_SESSION['login_attempts'][$ip])) {
            $_SESSION['login_attempts'][$ip] = [
                'attempts' => 0,
                'last_attempt' => time()
            ];
        }

        $attempts = &$_SESSION['login_attempts'][$ip];
        
        // Reset attempts if more than 15 minutes have passed
        if (time() - $attempts['last_attempt'] > 900) {
            $attempts['attempts'] = 0;
        }

        // Check if locked
        if ($attempts['attempts'] >= 5) {
            $remaining = ceil((900 - (time() - $attempts['last_attempt'])) / 60);
            return ['locked' => true, 'remaining' => $remaining];
        }

        $attempts['attempts']++;
        $attempts['last_attempt'] = time();

        return ['locked' => false, 'remaining' => 0];
    }
}

// Check database connection
if ($conn->connect_error) {
    http_response_code(500);
    exit(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Main request handler
$action = isset($_REQUEST['action']) ? AuthUtils::sanitizeInput($_REQUEST['action']) : '';

switch ($action) {
    case 'check_admin':
        if ($_SERVER["REQUEST_METHOD"] !== "GET") break;
        
        $stmt = $conn->prepare("SELECT email FROM users_table WHERE user_type = 'administrator'");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $response = ['adminExists' => false, 'adminEmails' => ''];
        
        if ($result->num_rows > 0) {
            $emails = array_map(fn($row) => $row['email'], $result->fetch_all(MYSQLI_ASSOC));
            $response = [
                'adminExists' => true,
                'adminEmails' => implode(',', $emails)
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();

    case 'register':
        if ($_SERVER["REQUEST_METHOD"] !== "POST") break;
        
        // Validate required fields
        $required_fields = ['username', 'firstname', 'lastname', 'email', 'password', 'confirm_password', 'contact_num'];
        $input_data = array_map([AuthUtils::class, 'sanitizeInput'], array_intersect_key($_POST, array_flip($required_fields)));
        
        if (count(array_filter($input_data)) !== count($required_fields)) {
            header("Location: /pages/auth/register_as_admin.php?message=All required fields must be filled&status=danger");
            exit();
        }
        
        // Extract and validate data
        extract($input_data);
        $middlename = AuthUtils::sanitizeInput($_POST['middlename'] ?? '');
        $picture = "DATA/user-data/generic_user.png"; // Default picture path
        
        if (!AuthUtils::validateEmail($email)) {
            header("Location: /pages/auth/register_as_admin.php?message=Invalid email format&status=danger");
            exit();
        }
        
        if ($password !== $confirm_password) {
            header("Location: /pages/auth/register_as_admin.php?message=Passwords do not match&status=danger");
            exit();
        }
        
        // Process registration
        $conn->begin_transaction();
        
        try {
            $user_id = AuthUtils::generateUserId("user", $conn);
            $password_hash = password_hash($password, PASSWORD_ARGON2ID);
            $created_at = gmdate('Y-m-d H:i:s');
            
            $stmt = $conn->prepare("INSERT INTO users_table (user_id, username, firstname, middlename, lastname, email, contact_num, password_hash, user_type, picture, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'administrator', ?, ?)");
            $stmt->bind_param("ssssssssss", 
                $user_id, $username, $firstname, $middlename, $lastname, 
                $email, $contact_num, $password_hash, $picture, $created_at
            );
            $stmt->execute();
            
            $conn->commit();
            
            unset($_SESSION['login_attempts'][$_SERVER['REMOTE_ADDR']]);
            header("Location: /pages/auth/login.php?message=Registration successful&status=success");
            exit();
            
        } catch (Exception $e) {
            $conn->rollback();
            header("Location: /pages/auth/register_as_admin.php?message=" . urlencode($e->getMessage()) . "&status=danger");
            exit();
        }
    break;

    case 'login':
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            exit(json_encode(['success' => false, 'message' => 'Invalid request method']));
        }
    
        $username = AuthUtils::sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember_me = isset($_POST['remember_me']);
    
        if (empty($username) || empty($password)) {
            exit(json_encode(['success' => false, 'message' => 'Please enter both username and password']));
        }
    
        $conn->begin_transaction();
    
        try {
            $stmt = $conn->prepare("SELECT user_id, password_hash, user_type FROM users_table WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows === 0) {
                throw new Exception('Invalid username or password');
            }
    
            $user = $result->fetch_assoc();
            
            if (!password_verify($password, $user['password_hash'])) {
                throw new Exception('Invalid username or password');
            }
    
            // Generate session ID
            $base_id = "session_" . date("mdY_His");
            $session_id = $base_id;
            $counter = 2;
    
            while (true) {
                $check_stmt = $conn->prepare("SELECT id FROM users_sessionlists WHERE id = ?");
                $check_stmt->bind_param("s", $session_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
    
                if ($check_result->num_rows === 0) {
                    break;
                }
    
                $session_id = $base_id . "_" . $counter;
                $counter++;
            }
    
            $session_token = AuthUtils::generateSessionToken();
            $login_time = gmdate('Y-m-d H:i:s');
            $expire_time = gmdate('Y-m-d H:i:s', time() + ($remember_me ? SESSION_LIFETIME * 7 : SESSION_LIFETIME));
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
            $stmt = $conn->prepare("INSERT INTO users_sessionlists (id, user_id, session_token, login_time, expire_time, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $session_id, $user['user_id'], $session_token, $login_time, $expire_time, $ip_address, $user_agent);
            $stmt->execute();
    
            setcookie('session_token', $session_token, [
                'expires' => time() + ($remember_me ? SESSION_LIFETIME * 7 : SESSION_LIFETIME),
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
    
            $conn->commit();
    
            $redirect_url = match($user['user_type']) {
                'administrator' => '/pages/main/home_admin.php',
                'receptionist' => '/pages/main/home_receptionist.php',
                'healthcare_provider' => '/pages/main/home_healthcare_provider.php',
                default => '/pages/auth/login.php'
            };
    
            exit(json_encode(['success' => true, 'message' => 'Login successful', 'redirect' => $redirect_url]));
    
        } catch (Exception $e) {
            $conn->rollback();
            exit(json_encode(['success' => false, 'message' => $e->getMessage()]));
        }
    break;    
    
    default:
        header("Location: /pages/auth/login.php?message=Invalid action&status=danger");
        exit();
}