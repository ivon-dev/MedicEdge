<?php
    $webapp_config_file = $_SERVER['DOCUMENT_ROOT'] . '/CONFIG/medicedge_webapp_config.json';
    if (file_exists($webapp_config_file)) {
        $config_data = json_decode(file_get_contents($webapp_config_file), true);
    } else {
        $config_data = [];
    }

    $orgName = $config_data['org-name'] ?? 'MedicEdge';
    $favicon = $config_data['favicon'] ?? 'CONFIG/branding/default/favicon.ico';
    $navbarLogo = $config_data['navbar-logo'] ?? 'CONFIG/branding/default/navbar_logo.png';

    $homepageTitle = $config_data['homepage-title'] ?? 'MedicEdge — Medical Records Simplified';
    $heroTextTitle = $config_data['hero-text-title'] ?? 'Medical Records Simplified';
    $heroTextSubtitle = $config_data['hero-text-subtitle'] ?? 'Say goodbye to manual process—streamline healthcare management with MedicEdge.';

    $loginRegisterBanner = $config_data['login-register-banner'] ?? 'CONFIG/branding/default/login_register_banner.png';

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

    <title><?php echo htmlspecialchars($orgName); ?> — Login</title>

    <link href="/styles/external/bootstrap.min.css" rel="stylesheet">
    <link href="/styles/external/aos.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/styles/global-styles/footer_globalstyles.css" rel="stylesheet">
    <link href="/styles/pages-styles/login-register_styles.css" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="/<?php echo htmlspecialchars($favicon); ?>">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a href="/">
                <img src="/<?php echo htmlspecialchars($navbarLogo); ?>" alt="<?php echo htmlspecialchars($orgName); ?> Logo" class="img-fluid" draggable="false">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/pages/legal/about-us.php">About</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-2" href="/pages/auth/register_as_admin.php">Register as Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container mt-5 pt-5 forgot-password-form-wrapper">
            <div class="row justify-content-center">
                <div class="col-md-7 login-register-form">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <img src="/<?php echo htmlspecialchars($loginRegisterBanner); ?>" alt="<?php echo htmlspecialchars($orgName); ?> Login Banner" width="300" height="100" class="img-fluid login-register-form-banner" draggable="false">
                            </div>
                            <h2 class="text-center mb-4"><b>Forgot Password</b></h2>
                            <form id="forgotPasswordForm" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username*</label>
                                    <input type="text" class="form-control" id="username" name="username" required data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="focus" title="Enter your username">
                                    <div class="invalid-feedback">Please enter your username.</div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                                </div>
                                <div class="text-center mt-2">
                                    <a href="/pages/auth/login.php" class="text-muted">Back to Login</a>
                                    <br>
                                    <a href="/" class="text-muted">Back to Homepage</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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

    <!-- Minor Scripts -->
    <script src="/scripts/copyright-year.js"></script>

    <!-- Major Scripts -->
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>
    <script src="/scripts/external/aos.js"></script>

    <!-- External Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Internal Scripts -->
    <script>
        document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const inputs = this.querySelectorAll('input');
            
            submitButton.disabled = true;
            inputs.forEach(input => input.disabled = true);

            fetch('/php/credentials/forgot-password_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show code input popup
                    Swal.fire({
                        title: 'Enter Reset Code',
                        input: 'text',
                        inputLabel: 'Check your email for the reset code',
                        inputPlaceholder: 'Enter 8-character code',
                        showCancelButton: true,
                        confirmButtonText: 'Verify Code',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Please enter the reset code!';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Verify the code
                            return fetch('/php/credentials/forgot-password_handler.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `action=verify_code&code=${result.value}`
                            }).then(response => response.json());
                        }
                    }).then(verifyResult => {
                        if (verifyResult && verifyResult.success) {
                            // Show password reset popup
                            return Swal.fire({
                                title: 'Set New Password',
                                html: `
                                    <input type="password" id="password" class="swal2-input" placeholder="New Password">
                                    <input type="password" id="confirmPassword" class="swal2-input" placeholder="Confirm Password">
                                `,
                                focusConfirm: false,
                                showCancelButton: true,
                                confirmButtonText: 'Reset Password',
                                preConfirm: () => {
                                    const password = document.getElementById('password').value;
                                    const confirmPassword = document.getElementById('confirmPassword').value;
                                    if (!password || !confirmPassword) {
                                        Swal.showValidationMessage('Please fill in all fields');
                                        return false;
                                    }
                                    if (password !== confirmPassword) {
                                        Swal.showValidationMessage('Passwords do not match');
                                        return false;
                                    }
                                    return { password: password };
                                }
                            });
                        } else {
                            throw new Error(verifyResult.message || 'Invalid code');
                        }
                    }).then(result => {
                        if (result.isConfirmed) {
                            // Update password
                            return fetch('/php/credentials/forgot-password_handler.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `action=update_password&password=${result.value.password}`
                            }).then(response => response.json());
                        }
                    }).then(updateResult => {
                        if (updateResult && updateResult.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Password Updated!',
                                text: 'You will be redirected to login page.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '/pages/auth/login.php';
                            });
                        }
                    }).catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'An error occurred'
                        });
                    });
                } else {
                    throw new Error(data.message || 'Email not found');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'An unexpected error occurred'
                });
            })
            .finally(() => {
                submitButton.disabled = false;
                inputs.forEach(input => input.disabled = false);
            });
        });
    </script>
    <script> // Bootstrap Tooltip
        document.addEventListener("DOMContentLoaded", function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>