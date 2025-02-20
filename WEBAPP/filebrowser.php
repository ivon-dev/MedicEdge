<?php
// Database connection
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connection.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function showAlertAndRedirect($message, $redirectUrl) {
    // Ensure no output has been sent before
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=utf-8');
    }
    
    // Output the complete HTML structure
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Error</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <link href='/styles/external/bootstrap.min.css' rel='stylesheet'>
        <link href='/styles/external/aos.css' rel='stylesheet'>

        <link rel='icon' type='image/x-icon' href='/img/favicon.ico'>
    </head>
    <body>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Access Denied',
                text: '" . addslashes($message) . "',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                window.location.href = '" . addslashes($redirectUrl) . "';
            });
        });
        </script>
    </body>
    </html>";
    exit();
}

// Function to check if the user is an administrator
function isAdmin() {
    global $conn;

    if (!isset($_COOKIE['session_token'])) {
        return false;
    }

    $session_token = $_COOKIE['session_token'];

    $stmt = $conn->prepare("
        SELECT u.user_type
        FROM users_sessionlists us
        JOIN users_table u ON us.user_id = u.user_id
        WHERE us.session_token = ?
    ");

    if (!$stmt) {
        error_log("Database preparation failed: " . $conn->error);
        return false;
    }

    $stmt->bind_param("s", $session_token);

    if (!$stmt->execute()) {
        error_log("Query execution failed: " . $stmt->error);
        return false;
    }

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return false;
    }

    $user_data = $result->fetch_assoc();
    return $user_data['user_type'] === 'administrator';
}

// Check if the user is an administrator
if (!isAdmin()) {
    showAlertAndRedirect("Sorry, you have no Administrator role.", "/pages/auth/login.php");
}

// Configuration
$ROOT_DIR = __DIR__; // Set this to your desired root directory
$ALLOWED_EXTENSIONS = ['txt', 'php', 'html', 'css', 'js', 'json', 'md'];

// Get current directory from query parameter or default to root
$current_dir = isset($_GET['dir']) ? $_GET['dir'] : '';
$absolute_path = realpath($ROOT_DIR . DIRECTORY_SEPARATOR . $current_dir);

// Security check - Ensure we're not accessing directories outside root
if (!$absolute_path || strpos($absolute_path, $ROOT_DIR) !== 0) {
    $absolute_path = $ROOT_DIR;
    $current_dir = '';
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'create_dir':
            if (isset($_POST['dirname'])) {
                $new_dir = $absolute_path . DIRECTORY_SEPARATOR . $_POST['dirname'];
                if (!file_exists($new_dir)) {
                    mkdir($new_dir);
                }
            }
            break;
            
        case 'delete':
            if (isset($_POST['path'])) {
                $path = realpath($absolute_path . DIRECTORY_SEPARATOR . $_POST['path']);
                if (strpos($path, $ROOT_DIR) === 0) {
                    is_dir($path) ? rmdir($path) : unlink($path);
                }
            }
            break;
            
        case 'save_file':
            if (isset($_POST['filename']) && isset($_POST['content'])) {
                $path = $absolute_path . DIRECTORY_SEPARATOR . $_POST['filename'];
                file_put_contents($path, $_POST['content']);
            }
            break;
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?dir=' . urlencode($current_dir));
    exit;
}

// Get directory contents
$items = array_diff(scandir($absolute_path), ['.', '..']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Browser</title>
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <style>
        body {
            font-family: monospace;
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
            background: #f5f5f5;
        }
        h1 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .breadcrumb {
            margin-bottom: 20px;
        }
        .listing {
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .item {
            display: flex;
            padding: 8px 15px;
            border-bottom: 1px solid #eee;
            align-items: center;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item:hover {
            background: #f8f8f8;
        }
        .icon {
            margin-right: 10px;
        }
        .name {
            flex-grow: 1;
        }
        .name a {
            text-decoration: none;
            color: #0366d6;
        }
        .actions {
            visibility: hidden;
        }
        .item:hover .actions {
            visibility: visible;
        }
        .actions button {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 4px 8px;
        }
        .actions button:hover {
            color: #000;
        }
        .controls {
            margin: 20px 0;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 20px;
            max-width: 600px;
            border-radius: 4px;
        }
        textarea {
            width: 100%;
            min-height: 300px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Index of <?= htmlspecialchars($current_dir ?: '/') ?></h1>
    
    <div class="breadcrumb">
        <?php
        $paths = explode('/', trim($current_dir, '/'));
        $built_path = '';
        echo '<a href="?dir=">/</a>';
        foreach ($paths as $path) {
            if ($path) {
                $built_path .= '/' . $path;
                echo ' / <a href="?dir=' . urlencode($built_path) . '">' . htmlspecialchars($path) . '</a>';
            }
        }
        ?>
    </div>

    <div class="controls">
        <button onclick="showCreateDirModal()">Create Directory</button>
    </div>

    <div class="listing">
        <?php if ($current_dir) : ?>
            <div class="item">
                <span class="icon">📁</span>
                <span class="name">
                    <a href="?dir=<?= urlencode(dirname($current_dir)) ?>">..</a>
                </span>
            </div>
        <?php endif; ?>
        
        <?php foreach ($items as $item) : ?>
            <?php
            $path = $absolute_path . DIRECTORY_SEPARATOR . $item;
            $is_dir = is_dir($path);
            $ext = pathinfo($item, PATHINFO_EXTENSION);
            ?>
            <div class="item">
                <span class="icon"><?= $is_dir ? '📁' : '📄' ?></span>
                <span class="name">
                    <?php if ($is_dir) : ?>
                        <a href="?dir=<?= urlencode($current_dir . '/' . $item) ?>"><?= htmlspecialchars($item) ?></a>
                    <?php else : ?>
                        <a href="#" onclick="viewFile('<?= htmlspecialchars($item) ?>')"><?= htmlspecialchars($item) ?></a>
                    <?php endif; ?>
                </span>
                <div class="actions">
                    <?php if (!$is_dir && in_array($ext, $ALLOWED_EXTENSIONS)) : ?>
                        <button onclick="editFile('<?= htmlspecialchars($item) ?>')">Edit</button>
                    <?php endif; ?>
                    <button onclick="deleteItem('<?= htmlspecialchars($item) ?>')">Delete</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Create Directory Modal -->
    <div id="createDirModal" class="modal">
        <div class="modal-content">
            <h2>Create Directory</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create_dir">
                <input type="text" name="dirname" required placeholder="Directory Name">
                <button type="submit">Create</button>
                <button type="button" onclick="hideModals()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Edit File Modal -->
    <div id="editFileModal" class="modal">
        <div class="modal-content">
            <h2>Edit File</h2>
            <form method="POST">
                <input type="hidden" name="action" value="save_file">
                <input type="hidden" name="filename" id="editFilename">
                <textarea name="content" id="editContent"></textarea>
                <button type="submit">Save</button>
                <button type="button" onclick="hideModals()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- View File Modal -->
    <div id="viewFileModal" class="modal">
        <div class="modal-content">
            <h2>View File</h2>
            <pre id="viewContent"></pre>
            <button onclick="hideModals()">Close</button>
        </div>
    </div>

    <script>
        function showCreateDirModal() {
            document.getElementById('createDirModal').style.display = 'block';
        }

        function hideModals() {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.style.display = 'none';
            });
        }

        function deleteItem(path) {
            if (confirm('Are you sure you want to delete this item?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="path" value="${path}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        async function viewFile(filename) {
            const response = await fetch(filename);
            const content = await response.text();
            document.getElementById('viewContent').textContent = content;
            document.getElementById('viewFileModal').style.display = 'block';
        }

        async function editFile(filename) {
            const response = await fetch(filename);
            const content = await response.text();
            document.getElementById('editFilename').value = filename;
            document.getElementById('editContent').value = content;
            document.getElementById('editFileModal').style.display = 'block';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                hideModals();
            }
        }
    </script>
</body>
</html>