<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connection.php';
session_start();

// Set Content-Type header
header('Content-Type: application/json');

// Get session token from cookie
$session_token = $_COOKIE['session_token'] ?? '';
if (empty($session_token)) {
    echo json_encode(['error' => 'Session token not found in cookie']);
    exit;
}

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'fetch':
            try {
                $stmt = $pdo->prepare("
                    SELECT u.user_id, u.username, u.email
                    FROM users_table u
                    INNER JOIN users_sessionlists s ON u.user_id = s.user_id
                    WHERE s.session_token = ?
                ");
                $stmt->execute([$session_token]);
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($userData) {
                    // Store user_id in session if not already set
                    if (!isset($_SESSION['user_id'])) {
                        $_SESSION['user_id'] = $userData['user_id'];
                    }
                    echo json_encode(['success' => true, 'data' => $userData]);
                } else {
                    echo json_encode(['error' => 'User data not found or invalid session']);
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                echo json_encode(['error' => 'Failed to fetch user data']);
            }
            break;

        case 'update':
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(['error' => 'Not logged in']);
                break;
            }
            $user_id = $_SESSION['user_id'];
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['error' => 'Invalid email format']);
                break;
            }

            try {
                // Check if username already exists (excluding current user)
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users_table WHERE username = ? AND user_id != ?");
                $stmt->execute([$username, $user_id]);
                $usernameCount = $stmt->fetchColumn();

                if ($usernameCount > 0) {
                    echo json_encode(['error' => 'Username already exists']);
                    break;
                }

                // Check if email already exists (excluding current user)
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users_table WHERE email = ? AND user_id != ?");
                $stmt->execute([$email, $user_id]);
                $emailCount = $stmt->fetchColumn();

                if ($emailCount > 0) {
                    echo json_encode(['error' => 'Email already exists']);
                    break;
                }

                $stmt = $pdo->prepare("UPDATE users_table SET username = ?, email = ? WHERE user_id = ?");
                $stmt->execute([$username, $email, $user_id]);

                if ($stmt->rowCount() > 0) {
                    echo json_encode(['success' => 'Account updated successfully']);
                } else {
                    echo json_encode(['error' => 'No changes were made or user not found']);
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                echo json_encode(['error' => 'Failed to update account data']);
            }
            break;

        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'No action specified']);
}

$pdo = null; // Close the database connection
?>
