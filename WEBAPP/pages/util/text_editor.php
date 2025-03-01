<?php
// Text Editor Functions
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/external/Parsedown.php';

if (!isset($_GET['file']) && !isset($_POST['action'])) {
    header("HTTP/1.0 403 Forbidden");
    exit;
}

$document_map = [
    'terms_of_service' => '/CONFIG/legal/terms.md',
    'privacy_policy' => '/CONFIG/legal/privacy.md',
    'about_us' => '/CONFIG/legal/about-us.md'
];

function parseMarkdown($markdown) {
    $Parsedown = new Parsedown();
    return $Parsedown->text($markdown);
}

function saveFile($key, $content) {
    global $document_map;

    if (!isset($document_map[$key])) {
        header("HTTP/1.0 404 Not Found");
        return "Document not found.";
    }

    $file_path = $_SERVER['DOCUMENT_ROOT'] . $document_map[$key];

    $allowed_dir = realpath($_SERVER['DOCUMENT_ROOT'] . '/CONFIG/legal');
    if (strpos(realpath($file_path), $allowed_dir) !== 0) {
        header("HTTP/1.0 403 Forbidden");
        return "Unauthorized";
    }

    if (file_put_contents($file_path, $content) !== false) {
        return "File saved successfully!";
    } else {
        header("HTTP/1.0 500 Internal Server Error");
        return "Error saving file.";
    }
}

// Handle actions
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'parse_markdown':
            $markdown = $_POST['markdown'];
            echo parseMarkdown($markdown);
            exit;
        case 'save_file':
            $file = $_POST['file'];
            $content = $_POST['content'];
            echo saveFile($file, $content);
            exit;
    }
}

$key = $_GET['file'];

if (!isset($document_map[$key])) {
    header("HTTP/1.0 404 Not Found");
    echo "Document not found.";
    exit;
}

$file_path = $_SERVER['DOCUMENT_ROOT'] . $document_map[$key];

$allowed_dir = realpath($_SERVER['DOCUMENT_ROOT'] . '/CONFIG/legal');
if (strpos(realpath($file_path), $allowed_dir) !== 0) {
    header("HTTP/1.0 403 Forbidden");
    exit;
}

if (!file_exists($file_path)) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

$file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
if ($file_extension !== 'txt' && $file_extension !== 'md') {
    header("HTTP/1.0 400 Bad Request");
    echo "Invalid file type. Only .txt and .md files are allowed.";
    exit;
}

$file_content = file_get_contents($file_path);

if ($file_extension === 'md') {
    $parsed_content = parseMarkdown($file_content);
}

// Apply Webapp Config
$webapp_config_file = $_SERVER['DOCUMENT_ROOT'] . '/CONFIG/medicedge_webapp_config.json';
    if (file_exists($webapp_config_file)) {
        $config_data = json_decode(file_get_contents($webapp_config_file), true);
    } else {
        $config_data = [];
    }

    $orgName = $config_data['org-name'] ?? 'MedicEdge';
    $favicon = $config_data['favicon'] ?? 'CONFIG/branding/default/favicon.ico';
    $navbarLogo = $config_data['navbar-logo'] ?? 'CONFIG/branding/default/navbar_logo.png';

    $sloganFooter = $config_data['slogan-footer'] ?? 'Revolutionizing Healthcare Management with Innovation and Security.';

    $contactEmail = $config_data['contact-email'] ?? 'Unavailable';
    $contactNumber = $config_data['contact-num'] ?? 'Unavailable';
    $address = $config_data['address'] ?? 'Unavailable';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Text Editor</title>

    <link rel="stylesheet" href="/styles/external/bootstrap.min.css">
    <link href="/styles/global-styles/footer_globalstyles.css" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="/<?php echo htmlspecialchars($favicon); ?>">
    
    <style>
        body {
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa; /* Light background */
            color: #495057; /* Darker text for readability */
        }
        .editor-container {
            width: 100%; /* Full width for the container */
            margin: 20px auto;
            background-color: #fff;
            margin: 100px 0 50px 0;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #0056b3;
        }
        textarea {
            width: 100%;
            height: 48vh; /* Set height to 75% of viewport height */
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-family: monospace;
            font-size: 14px;
            resize: none; /* Disable textarea resizing */
        }
        .preview {
            padding: 15px;
            margin-top: 10px;
            border-radius: 4px;
            background-color: #f9f9f9;
            height: 48vh; /* Set height to 75% of viewport height */
            overflow-y: auto; /* Enable vertical scrolling if content overflows */
        }
        .preview h1, .preview h2, .preview h3, .preview h4, .preview h5, .preview h6 {
            color: #0056b3;
            margin-top: 1em;
            margin-bottom: 0.5em;
        }
        .preview p {
            margin-bottom: 1em;
        }
        .preview code {
            background-color: #eee;
            padding: 2px 5px;
            border-radius: 4px;
        }
        .preview pre {
            background-color: #eee;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .save-button:hover {
            background-color: #3e8e41;
        }

        /* Media query for side-by-side layout */
        @media (min-width: 992px) { /* Bootstrap's lg breakpoint */
            .editor-container {
                display: flex;
            }
            textarea,
            .preview {
                width: 50%;
            }
            textarea {
                margin-right: 10px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a href="/" class="navbar-brand">
                <img src="/<?php echo htmlspecialchars($navbarLogo); ?>" alt="MedicEdge Logo" class="img-fluid" draggable="false">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/main/home_admin.php">Back to Admin Panel</a>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-primary save-button" onclick="saveFile()">Save</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="editor-container">
            <textarea id="editor" class="form-control"><?php echo htmlspecialchars($file_content); ?></textarea>
            <div class="preview" id="preview">
                <?php echo isset($parsed_content) ? $parsed_content : $file_content; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><?php echo htmlspecialchars($orgName); ?></h5>
                    <p><?php echo htmlspecialchars($sloganFooter); ?></p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="/pages/legal/about-us.php">About Us</a></li>
                        <li><a href="/pages/legal/privacy.php">Privacy Policy</a></li>
                        <li><a href="/pages/legal/terms.php">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p>Email: <a href="mailto:<?php echo htmlspecialchars($contactEmail); ?>" onclick="window.open(this.href, '_blank'); return false;"> <?php echo htmlspecialchars($contactEmail); ?></a><br>
                    Phone: <?php echo htmlspecialchars($contactNumber); ?><br>
                    Address: <?php echo htmlspecialchars($address); ?></p>
                </div>
            </div>
            <hr class="mt-4 mb-4 border-light">
            <div class="text-center">
                <p class="mb-0">&copy; <span id="currentYear"></span> <?php echo htmlspecialchars($orgName); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- External Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Minor Scripts -->
    <script src="/scripts/copyright-year.js"></script>

    <!-- Major Scripts -->
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>
    <script src="/scripts/external/aos.js"></script>

    <!-- Minor Scripts -->
    <script src="/scripts/bootstrap-tooltip.js"></script>

    <script src="/scripts/external/jquery-3.6.0.min.js"></script>
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>
    <script>
        let editorContent = $('#editor').val();
        let changesMade = false;

        $(document).ready(function() {
            $('#editor').on('input', function() {
                updatePreview();
                if ($('#editor').val() !== editorContent) {
                    changesMade = true;
                } else {
                    changesMade = false;
                }
            });

            function updatePreview() {
                var markdownText = $('#editor').val();
                $.ajax({
                    url: 'text_editor.php',
                    type: 'POST',
                    data: {
                        action: 'parse_markdown',
                        markdown: markdownText
                    },
                    success: function(data) {
                        $('#preview').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error parsing markdown:", error);
                        $('#preview').html("Error updating preview.");
                    }
                });
            }
        });

        function saveFile() {
            var content = document.getElementById('editor').value;
            $.ajax({
                url: 'text_editor.php',
                type: 'POST',
                data: {
                    action: 'save_file',
                    file: '<?php echo $key; ?>',
                    content: content
                },
                success: function(response) {
                    alert(response);
                    editorContent = document.getElementById('editor').value;
                    changesMade = false;
                },
                error: function(xhr, status, error) {
                    console.error("Error saving file:", error);
                    alert("Error saving file. Please check the console.");
                }
            });
        }

        window.addEventListener('beforeunload', function (e) {
            if (changesMade) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>
</body>
</html>
