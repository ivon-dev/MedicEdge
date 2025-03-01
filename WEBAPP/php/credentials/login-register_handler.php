<?php
include('../connection.php');

// Constants
define('SESSION_LIFETIME', 86400); // 24 hours
define('MAX_LOGIN_ATTEMPTS', 5);
define('COOLDOWN_PERIOD', 900); // 15 minutes in seconds

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

    public static function checkRateLimit($ip, $conn) {
        // First, clean up expired blocks
        $stmt = $conn->prepare("DELETE FROM users_session_blocklist WHERE block_end <= NOW()");
        $stmt->execute();
        
        // Check if IP is currently blocked
        $stmt = $conn->prepare("SELECT block_end FROM users_session_blocklist WHERE ip_address = ? AND block_end > NOW()");
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $remaining = ceil((strtotime($row['block_end']) - time()) / 60);
            return ['locked' => true, 'remaining' => $remaining];
        }
        
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
        
        // Reset attempts if cooldown period has passed
        if (time() - $attempts['last_attempt'] > COOLDOWN_PERIOD) {
            $attempts['attempts'] = 0;
        }

        // Check if max attempts reached
        if ($attempts['attempts'] >= MAX_LOGIN_ATTEMPTS) {
            // Add to blocklist
            $block_id = 'block_' . date('mdY_His');
            $block_end = date('Y-m-d H:i:s', time() + COOLDOWN_PERIOD);
            
            $stmt = $conn->prepare("INSERT INTO users_session_blocklist (id, ip_address, block_end) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $block_id, $ip, $block_end);
            $stmt->execute();
            
            // Clear session attempts
            unset($_SESSION['login_attempts'][$ip]);
            
            return ['locked' => true, 'remaining' => COOLDOWN_PERIOD / 60];
        }

        $attempts['attempts']++;
        $attempts['last_attempt'] = time();

        return ['locked' => false, 'remaining' => 0];
    }

    public static function clearLoginAttempts($ip) {
        if (isset($_SESSION['login_attempts'][$ip])) {
            unset($_SESSION['login_attempts'][$ip]);
        }
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
            
            AuthUtils::clearLoginAttempts($_SERVER['REMOTE_ADDR']);
            header("Location: /pages/auth/login.php?message=Registration successful&status=success");
            exit();
            
        } catch (Exception $e) {
            $conn->rollback();
            header("Location: /pages/auth/register_as_admin.php?message=" . urlencode($e->getMessage()) . "&status=danger");
            exit();
        }
        break;

    case 'login':
        header('Content-Type: application/json');
            
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            exit(json_encode([
                'success' => false, 
                'message' => 'Invalid request method'
            ]));
        }
            
        // Check rate limit first
        $rateLimitCheck = AuthUtils::checkRateLimit($_SERVER['REMOTE_ADDR'], $conn);
        if ($rateLimitCheck['locked']) {
            exit(json_encode([
                'success' => false,
                'message' => "Too many failed attempts. Please try again in {$rateLimitCheck['remaining']} minutes."
            ]));
        }
            
        // Get and sanitize inputs
        $username = AuthUtils::sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember_me = isset($_POST['remember_me']);
            
        // Start transaction
        $conn->begin_transaction();
            
        try {
            // Query user data
            $stmt = $conn->prepare("SELECT * FROM users_table WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
                
            // Get user data once and store it
            $user = $result->fetch_assoc();
                
            // Check if user exists and verify password
            if (!$user || !password_verify($password, $user['password_hash'])) {
                throw new Exception('Invalid username or password');
            }
                
            // Generate unique session ID
            $session_id = '';
            $max_attempts = 5;
            $attempt = 0;
                
            do {
                $base_id = "session_" . date("mdY_His");
                $session_id = $attempt === 0 ? $base_id : $base_id . "_" . $attempt;
                    
                $check_stmt = $conn->prepare("SELECT id FROM users_sessionlists WHERE id = ?");
                $check_stmt->bind_param("s", $session_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                    
                if ($check_result->num_rows === 0) {
                    break;
                }
                    
                $attempt++;
                if ($attempt >= $max_attempts) {
                    throw new Exception('Failed to generate unique session ID');
                }
                    
                usleep(100000); // Wait 0.1 seconds before next attempt
                    
            } while (true);
                
            // Generate session data
            $session_token = AuthUtils::generateSessionToken();
            $login_time = gmdate('Y-m-d H:i:s');
            $expire_time = gmdate('Y-m-d H:i:s', time() + ($remember_me ? SESSION_LIFETIME * 7 : SESSION_LIFETIME));
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
                
            // Insert session record
            $stmt = $conn->prepare("INSERT INTO users_sessionlists (id, user_id, session_token, login_time, expire_time, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", 
                $session_id, 
                $user['user_id'], 
                $session_token, 
                $login_time, 
                $expire_time, 
                $ip_address, 
                $user_agent
            );
            $stmt->execute();
                
            // Set session cookie
            setcookie('session_token', $session_token, [
                'expires' => time() + ($remember_me ? SESSION_LIFETIME * 7 : SESSION_LIFETIME),
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // Conditionally set secure
                'httponly' => false, // Recommending to set it to true, but instead set to false for index_onload_check cookie checker
                'samesite' => 'Lax' // Adjust based on your needs, consider 'None' if necessary but ensure 'Secure' is true
            ]);
                
            // Clear login attempts on successful login
            AuthUtils::clearLoginAttempts($ip_address);
                
            // Commit transaction
            $conn->commit();
                
            exit(json_encode([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => '/pages/main/redirect.php',
            ]));
                
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
                
            // Log error
            error_log("Login error: " . $e->getMessage());
                
            // Send error response
            exit(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
        }
    break;
    
    default:
        header("Location: /pages/auth/login.php?message=Invalid action&status=danger");
        exit();
}