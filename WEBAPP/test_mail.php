<?php
$to = "paulbegino@protonmail.com";
$subject = "Test Email from XAMPP";
$headers = "From: studpaullll32@gmail.com";

$message = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Test Email</title>
</head>
<body>
    <h2 style='color:blue;'>Hello from XAMPP!</h2>
    <p>This is a <b>test email</b> with HTML content.</p>
    <p>Regards,<br>MedicEdge Team</p>
</body>
</html>
";

// Set proper headers
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
$headers .= "From: Your Name <studpaullll32@gmail.com>" . "\r\n";
$headers .= "Reply-To: studpaullll32@gmail.com" . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email
if (mail($to, $subject, $message, $headers)) {
    echo "HTML Email sent successfully!";
} else {
    echo "Failed to send email.";
}
?>
