<?php
include('connection.php');

// Constants
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutes

// Utility Functions
class AuthUtils {
    public static function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validatePassword($password) {
        return strlen($password) >= PASSWORD_MIN_LENGTH &&
               preg_match('/[A-Z]/', $password) &&
               preg_match('/[a-z]/', $password) &&
               preg_match('/[0-9]/', $password) &&
               preg_match('/[^A-Za-z0-9]/', $password);
    }

    public static function generateUserId($prefix, $conn) {
        $base_id = $prefix . "_" . date("mdY_His");
        $user_id = $base_id;
        $counter = 1;
        
        do {
            $stmt = $conn->prepare("SELECT user_id FROM users_administrator_table WHERE user_id = ?");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            if ($stmt->get_result()->num_rows === 0) break;
            $user_id = $base_id . "_" . (++$counter);
        } while (true);
        
        return $user_id;
    }

    public static function checkRateLimit($ip) {
        $attempts = isset($_SESSION['login_attempts'][$ip]) ? $_SESSION['login_attempts'][$ip] : 0;
        $lastAttempt = isset($_SESSION['last_attempt'][$ip]) ? $_SESSION['last_attempt'][$ip] : 0;
        
        if ($attempts >= MAX_LOGIN_ATTEMPTS && (time() - $lastAttempt) < LOCKOUT_TIME) {
            $remainingTime = LOCKOUT_TIME - (time() - $lastAttempt);
            return ['locked' => true, 'remaining' => ceil($remainingTime / 60)];
        }
        return ['locked' => false];
    }
}

// Initialize session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check database connection
if ($conn->connect_error) {
    http_response_code(500);
    exit(json_encode(['error' => 'Database connection failed']));
}

// Main request handler
$action = isset($_REQUEST['action']) ? AuthUtils::sanitizeInput($_REQUEST['action']) : '';

switch ($action) {
    case 'check_admin':
        if ($_SERVER["REQUEST_METHOD"] !== "GET") break;
        
        $stmt = $conn->prepare("SELECT email FROM users_administrator_table");
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

    case 'login':
        if ($_SERVER["REQUEST_METHOD"] !== "POST") break;
        
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $remember = isset($_POST['remember_me']);
        
        try {
            $stmt = $conn->prepare("SELECT user_id, password_hash FROM users_administrator_table WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    
                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        setcookie('remember_token', $token, time() + (86400 * 30), "/");
                        
                        $stmt = $conn->prepare("UPDATE users_administrator_table SET remember_token = ? WHERE user_id = ?");
                        $stmt->bind_param("ss", $token, $user['user_id']);
                        $stmt->execute();
                    }
                    
                    echo json_encode(['success' => true, 'message' => 'Login successful']);
                    exit;
                }
            }
            echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Login failed. Please try again.']);
        }
        exit;

    case 'register':
        if ($_SERVER["REQUEST_METHOD"] !== "POST") break;
        
        // Rate limit check
        $rateLimitCheck = AuthUtils::checkRateLimit($_SERVER['REMOTE_ADDR']);
        if ($rateLimitCheck['locked']) {
            header("Location: /pages/auth/register_as_admin.php?message=Too many attempts. Please try again in {$rateLimitCheck['remaining']} minutes.&status=danger");
            exit();
        }
        
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
        
        if (!AuthUtils::validateEmail($email)) {
            header("Location: /pages/auth/register_as_admin.php?message=Invalid email format&status=danger");
            exit();
        }
        
        if (!AuthUtils::validatePassword($password)) {
            header("Location: /pages/auth/register_as_admin.php?message=Password must be at least 8 characters and include uppercase, lowercase, number, and special character&status=danger");
            exit();
        }
        
        if ($password !== $confirm_password) {
            header("Location: /pages/auth/register_as_admin.php?message=Passwords do not match&status=danger");
            exit();
        }
        
        // Process registration
        $conn->begin_transaction();
        
        try {
            // Check existing admin
            $stmt = $conn->prepare("SELECT COUNT(*) as admin_count FROM users_administrator_table");
            $stmt->execute();
            if ($stmt->get_result()->fetch_assoc()['admin_count'] > 0) {
                throw new Exception("Administrator already exists");
            }
            
            // Check username uniqueness
            $stmt = $conn->prepare("SELECT username FROM users_administrator_table WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                throw new Exception("Username already exists");
            }
            
            // Check email uniqueness
            $stmt = $conn->prepare("SELECT email FROM users_administrator_table WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                throw new Exception("Email already exists");
            }
            
            // Create new admin
            $user_id = AuthUtils::generateUserId("admin", $conn);
            $password_hash = password_hash($password, PASSWORD_ARGON2ID);
            $created_at = gmdate('Y-m-d H:i:s');
            
            $stmt = $conn->prepare("INSERT INTO users_administrator_table (user_id, firstname, middlename, lastname, 
                                  username, email, contact_num, password_hash, created_at) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->bind_param("sssssssss", 
                $user_id, $firstname, $middlename, $lastname, 
                $username, $email, $contact_num, $password_hash, $created_at
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

    default:
        header("Location: /pages/auth/login.php?message=Invalid action&status=danger");
        exit();
}
?>