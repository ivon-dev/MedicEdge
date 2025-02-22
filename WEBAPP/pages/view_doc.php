<?php
// Prevent direct file path access
if (!isset($_GET['file'])) {
    header("HTTP/1.0 403 Forbidden");
    exit;
}

// Define the mapping of keys to PDF files
$document_map = [
    'qhnA00ECdx7g2ue1jqUrecLU3k4hC01yfy3vn6m4VmrTQG4SaywaFCGMpxyHvd6R' => '/DOCS/Documentation.pdf'
];

$key = $_GET['file'];

// Check if the key exists in our mapping
if (!isset($document_map[$key])) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

$file_path = $_SERVER['DOCUMENT_ROOT'] . $document_map[$key];

// Verify file exists and is a PDF
if (!file_exists($file_path) || pathinfo($file_path, PATHINFO_EXTENSION) !== 'pdf') {
    header("HTTP/1.0 404 Not Found");
    exit;
}

// Set headers for PDF display
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
header('Cache-Control: public, max-age=0');
header('Accept-Ranges: bytes');
header('Content-Length: ' . filesize($file_path));

// Output the file
readfile($file_path);
exit;
?>