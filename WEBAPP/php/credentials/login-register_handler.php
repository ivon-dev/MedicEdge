<?php
// Set timezone to UTC
date_default_timezone_set('UTC');

// Add session start at the beginning
session_start();

include($_SERVER['DOCUMENT_ROOT'] . '/php/connection.php');

// Constants
define('SESSION_LIFETIME', 86400); // 24 hours
define('MAX_LOGIN_ATTEMPTS', 5);
define('COOLDOWN_PERIOD', 300); // 5 minutes in seconds

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
        try {
            // Check for active blocks using UTC_TIMESTAMP()
            $stmt = $conn->prepare("
                SELECT block_end 
                FROM users_session_blocklist 
                WHERE ip_address = ? 
                    AND block_end > UTC_TIMESTAMP() 
                ORDER BY block_end DESC 
                LIMIT 1
            ");
            
            if (!$stmt) {
                error_log("Failed to prepare block check statement: " . $conn->error);
                return ['locked' => false, 'remaining' => 0];
            }
            
            $stmt->bind_param("s", $ip);
            if (!$stmt->execute()) {
                error_log("Failed to execute block check: " . $stmt->error);
                return ['locked' => false, 'remaining' => 0];
            }
            
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $remaining = ceil((strtotime($row['block_end']) - strtotime('UTC')) / 60);
                return ['locked' => true, 'remaining' => $remaining];
            }

            // Rest of rate limit checking logic
            // Track attempts in session
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
                // Generate the block end time in UTC
                $block_end = gmdate('Y-m-d H:i:s', time() + COOLDOWN_PERIOD);
                
                // Generate block_id with format: block_[MMDDYYYY]_[HHMMSS] in UTC
                $block_id = "block_" . gmdate('mdY_His');
                
                try {
                    $conn->begin_transaction();

                    // First, check if there's already an active block using UTC_TIMESTAMP()
                    $check_stmt = $conn->prepare("
                        SELECT COUNT(*) as active_blocks 
                        FROM users_session_blocklist 
                        WHERE ip_address = ? AND block_end > UTC_TIMESTAMP()
                    ");
                    $check_stmt->bind_param("s", $ip);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();
                    $active_blocks = $check_result->fetch_assoc()['active_blocks'];

                    // Only insert new block if no active blocks exist
                    if ($active_blocks == 0) {
                        // Insert using UTC for issued_on (handled by default UTC_TIMESTAMP())
                        $stmt = $conn->prepare("
                            INSERT INTO users_session_blocklist 
                            (id, ip_address, block_end, issued_on) 
                            VALUES (?, ?, ?, UTC_TIMESTAMP())
                        ");
                        
                        if (!$stmt) {
                            throw new Exception("Failed to prepare block statement: " . $conn->error);
                        }
                        
                        $stmt->bind_param("sss", $block_id, $ip, $block_end);
                        if (!$stmt->execute()) {
                            throw new Exception("Failed to execute block statement: " . $stmt->error);
                        }
                    }

                    $conn->commit();
                    unset($_SESSION['login_attempts'][$ip]);
                    return ['locked' => true, 'remaining' => COOLDOWN_PERIOD / 60];

                } catch (Exception $e) {
                    $conn->rollback();
                    error_log("Block insertion error: " . $e->getMessage());
                    return ['locked' => true, 'remaining' => COOLDOWN_PERIOD / 60];
                }
            }

            $attempts['attempts']++;
            $attempts['last_attempt'] = time();

            return ['locked' => false, 'remaining' => 0];

        } catch (Exception $e) {
            error_log("Rate limit check error: " . $e->getMessage());
            return ['locked' => false, 'remaining' => 0];
        }
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

        // Get request_id from POST data
        $request_id = AuthUtils::sanitizeInput($_POST['request_id'] ?? '');
        if (empty($request_id)) {
            exit(json_encode([
                'success' => false,
                'message' => 'Invalid request'
            ]));
        }

        // STEP 1: Check if IP is currently blocked using UTC_TIMESTAMP()
        $stmt = $conn->prepare("
            SELECT block_end 
            FROM users_session_blocklist 
            WHERE ip_address = ? 
                AND block_end > UTC_TIMESTAMP() 
            ORDER BY block_end DESC 
            LIMIT 1
        ");
        $stmt->bind_param("s", $_SERVER['REMOTE_ADDR']);
        $stmt->execute();
        $blockResult = $stmt->get_result();
        
        if ($blockResult->num_rows > 0) {
            $blockData = $blockResult->fetch_assoc();
            $remaining = ceil((strtotime($blockData['block_end']) - time()) / 60);
            exit(json_encode([
                'success' => false,
                'message' => "Too many login attempts. Please try again in {$remaining} minutes."
            ]));
        }

        // Get and sanitize inputs
        $username = AuthUtils::sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember_me = isset($_POST['remember_me']);
            
        // Start transaction
        $conn->begin_transaction();
            
        try {
            // STEP 2: Check rate limits
            $rateLimitCheck = AuthUtils::checkRateLimit($_SERVER['REMOTE_ADDR'], $conn);
            if ($rateLimitCheck['locked']) {
                throw new Exception("Too many failed attempts. Please try again in {$rateLimitCheck['remaining']} minutes.");
            }

            // STEP 3: Process login
            // Query user data
            $stmt = $conn->prepare("SELECT * FROM users_table WHERE username = ?");
            $stmt->bind_param("s", $username);
            if (!$stmt->execute()) {
                throw new Exception('Database error during user lookup');
            }
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            // Check if a session entry with this request_id already exists
            $stmt = $conn->prepare("SELECT id FROM users_sessionlists WHERE id = ?");
            $stmt->bind_param("s", $request_id);
            $stmt->execute();
            $existing_session = $stmt->get_result()->fetch_assoc();

            $login_time = gmdate('Y-m-d H:i:s');
            $expire_time = gmdate('Y-m-d H:i:s', strtotime('UTC') + ($remember_me ? SESSION_LIFETIME * 7 : SESSION_LIFETIME));
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $status = 'failed';

            if (!$user || !password_verify($password, $user['password_hash'])) {
                // Handle failed login
                if (!$existing_session) {
                    // Store empty session token as variable
                    $empty_token = '';
                    $failed_user_id = $user['user_id'] ?? ''; // Get user_id if exists, otherwise empty string
                    $status = 'failed'; // Explicitly set status to failed
                    
                    // Insert new failed attempt only if no existing session
                    $stmt = $conn->prepare("INSERT INTO users_sessionlists (id, user_id, session_token, login_time, expire_time, status, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssss", 
                        $request_id,
                        $failed_user_id,
                        $empty_token,
                        $login_time,
                        $expire_time,
                        $status,
                        $ip_address,
                        $user_agent
                    );
                    if (!$stmt->execute()) {
                        throw new Exception('Failed to record login attempt');
                    }
                }
                
                // Commit the transaction before throwing the exception
                $conn->commit();
                throw new Exception('Invalid username or password');
            }

            // Login successful
            $session_token = AuthUtils::generateSessionToken();
            $status = 'success';

            // Always look for existing session with request_id and update it
            $stmt = $conn->prepare("UPDATE users_sessionlists SET status = 'success', session_token = ? WHERE id = ?");
            $stmt->bind_param("ss", $session_token, $request_id);
            $result = $stmt->execute();

            // If no rows were updated, create new session record
            if ($stmt->affected_rows === 0) {
                $stmt = $conn->prepare("INSERT INTO users_sessionlists (id, user_id, session_token, login_time, expire_time, status, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", 
                    $request_id,
                    $user['user_id'],
                    $session_token,
                    $login_time,
                    $expire_time,
                    $status,
                    $ip_address,
                    $user_agent
                );
                if (!$stmt->execute()) {
                    throw new Exception('Failed to save session: ' . $stmt->error);
                }
            }

            // Set session cookie with more permissive domain for LAN compatibility
            setcookie('session_token', $session_token, [
                'expires' => strtotime($expire_time),
                'path' => '/',
                'domain' => '',  // Empty domain makes it work across LAN
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'httponly' => false,
                'samesite' => 'Lax'
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
                
            // Double check if this attempt triggered a block
            $stmt = $conn->prepare("SELECT block_end FROM users_session_blocklist WHERE ip_address = ? AND block_end > UTC_TIMESTAMP() ORDER BY block_end DESC LIMIT 1");
            $stmt->bind_param("s", $_SERVER['REMOTE_ADDR']);
            $stmt->execute();
            $blockResult = $stmt->get_result();
            
            if ($blockResult->num_rows > 0) {
                $blockData = $blockResult->fetch_assoc();
                $remaining = ceil((strtotime($blockData['block_end']) - time()) / 60);
                exit(json_encode([
                    'success' => false,
                    'message' => "Too many login attempts. Please try again in {$remaining} minutes."
                ]));
            }
                
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