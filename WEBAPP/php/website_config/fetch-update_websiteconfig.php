<?php

$webapp_config_file = $_SERVER['DOCUMENT_ROOT'] . '/CONFIG/medicedge_webapp_config.json';
$target_dir = $_SERVER['DOCUMENT_ROOT'] . "/CONFIG/branding/uploads/";

// Function to safely move uploaded files and handle errors
function safeMoveUploadedFile($file, $target) {
    if ($file["error"] === UPLOAD_ERR_OK) {
        $tmp_name = $file["tmp_name"];
        // Check if the file was uploaded through HTTP POST
        if (is_uploaded_file($tmp_name)) {
            // Move the uploaded file to its final destination
            if (move_uploaded_file($tmp_name, $target)) {
                return true; // File moved successfully
            } else {
                error_log("Failed to move uploaded file. Permissions error or directory does not exist.");
                return false; // Failed to move file
            }
        } else {
            error_log("File upload not initiated via HTTP POST.");
            return false; // File not uploaded via HTTP POST
        }
    } else {
        error_log("Upload error: " . $file["error"]);
        return false; // Upload error
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch configuration data
    if (file_exists($webapp_config_file)) {
        $config_data = json_decode(file_get_contents($webapp_config_file), true);
        echo json_encode($config_data);
    } else {
        echo json_encode(['error' => 'Configuration file not found.']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'config' is set in $_POST, indicating a standard form submission
    if (isset($_POST['config'])) {
        $config = json_decode($_POST['config'], true); // Decode the JSON string

        // Handle image uploads
        $image_fields = ['org-logo', 'favicon', 'navbar-logo', 'homepage-hero-bg-image', 'login-register-banner'];
        foreach ($image_fields as $field) {
            if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $image = $_FILES[$field];
                $file_name = basename($image["name"]);
                $target_file = $target_dir . $file_name;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Validate file type
                $allowed_types = ['jpg', 'jpeg', 'png', 'ico'];
                if (!in_array($imageFileType, $allowed_types)) {
                    echo json_encode(['error' => 'Invalid file type for ' . $field . '. Only JPG, JPEG, PNG, and ICO files are allowed.']);
                    exit;
                }

                // Check file size (example: 2MB limit)
                if ($image["size"] > 5000000) {
                    echo json_encode(['error' => 'File size exceeds the limit (5MB) for ' . $field . '.']);
                    exit;
                }

                // Attempt to move the uploaded file
                if (safeMoveUploadedFile($image, $target_file)) {
                    $config[$field] = 'CONFIG/branding/custom/' . $file_name;
                } else {
                    echo json_encode(['error' => 'Failed to upload image for ' . $field . '.']);
                    exit;
                }
            }
        }

        // Save the updated configuration
        if (file_put_contents($webapp_config_file, json_encode($config, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => 'Configuration updated successfully.']);
        } else {
            echo json_encode(['error' => 'Failed to update configuration.']);
        }
    } elseif (isset($_POST['reset'])) {
        // Reset to default configuration
        $default_config_file = $_SERVER['DOCUMENT_ROOT'] . '/CONFIG/medicedge_webapp_config.json';

        if (file_exists($default_config_file)) {
            $default_config_data = file_get_contents($default_config_file);
            if (file_put_contents($webapp_config_file, $default_config_data)) {
                echo json_encode(['success' => 'Configuration reset to default successfully.']);
            } else {
                echo json_encode(['error' => 'Failed to reset configuration to default.']);
            }
        } else {
            echo json_encode(['error' => 'Default configuration file not found.']);
        }
    } else {
        echo json_encode(['error' => 'No configuration data received.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
}
?>
