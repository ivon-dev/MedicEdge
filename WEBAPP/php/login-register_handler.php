<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medicedge_db";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    switch($action) {
        case 'register':
            $username = trim($_POST['username']);
            $firstname = trim($_POST['firstname']);
            $middlename = trim($_POST['middlename']);
            $lastname = trim($_POST['lastname']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $user_type = $_POST['user_type'];
            $contact_num = trim($_POST['contact_num']);

            // Validate required fields
            if (empty($username) || empty($firstname) || empty($lastname) || empty($password) || empty($user_type) || empty($contact_num)) {
                header("Location: /pages/auth/register.php?message=All required fields must be filled&status=danger");
                exit();
            }

            // Generate user_id
            $base_user_id = "user_" . date("mdY_His");
            $user_id = $base_user_id;
            $counter = 2;

            // Check for duplicate user_id
            while (true) {
                $check_query = "SELECT user_id FROM users_table WHERE user_id = ?";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows == 0) {
                    break;
                }
                
                $user_id = $base_user_id . "_" . $counter;
                $counter++;
            }

            // Check if username already exists
            $check_username = "SELECT username FROM users_table WHERE username = ?";
            $stmt = $conn->prepare($check_username);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                header("Location: /pages/auth/register.php?message=Username already exists&status=danger");
                exit();
            }

            // Hash password
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            
            // Get UTC timestamp
            $created_at = gmdate('Y-m-d H:i:s');

            // Prepare and execute insert query
            $query = "INSERT INTO users_table (user_id, firstname, middlename, lastname, username, email, contact_num, password_hash, user_type, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssssss", 
                $user_id, 
                $firstname, 
                $middlename, 
                $lastname, 
                $username, 
                $email, 
                $contact_num, 
                $password_hash, 
                $user_type, 
                $created_at
            );
            
            // Execute the statement and handle the result
            if ($stmt->execute()) {
                header("Location: /pages/auth/login.php?message=Registration successful! Please login.&status=success");

                // Debug statement
                error_log("Debug - User Type: " . $user_type);
                error_log("Debug - Parameters: " . print_r([$user_id, $firstname, $middlename, $lastname, $username, $email, $contact_num, $password_hash, $user_type, $created_at], true));

                exit();
            } else {
                header("Location: /pages/auth/register.php?message=Error during registration. Please try again.&status=danger");
                exit();
            }

        default:
            header("Location: /pages/auth/login.php?message=Invalid action&status=danger");
            exit();
    }
}
?>