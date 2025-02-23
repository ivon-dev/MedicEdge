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
    
    <div class="container mt-5 pt-5 login-register-form-wrapper">
        <div class="row justify-content-center">
            <div class="col-md-6 login-register-form">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="/<?php echo htmlspecialchars($loginRegisterBanner); ?>" alt="<?php echo htmlspecialchars($orgName); ?> Login Banner" width="300" height="100" class="img-fluid login-register-form-banner" draggable="false">
                        </div>
                        <h2 class="text-center mb-4"><b>Login</b></h2>
                        <form id="loginForm" method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="action" value="login">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username*</label>
                                <input type="text" class="form-control" id="username" name="username" required data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="focus" title="Input your username here">
                                <div class="invalid-feedback">Please enter your username.</div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password*</label>
                                <input type="password" class="form-control" id="password" name="password" required data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="focus" title="Input your password here">
                                <div class="invalid-feedback">Please enter your password.</div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                                <label class="form-check-label" for="remember_me">Remember me</label>
                                <i class="fa fa-question-circle help-tooltip" data-bs-toggle="tooltip" data-bs-placement="right" title="Remember me for 30 days. Otherwise, it will only remember you for a day."></i>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                            <div class="text-center">
                                <a href="#" id="forgotPassword" class="text-muted">Forgot Password?</a>
                                <br>
                                <a href="/" class="text-muted">Back to Homepage</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#privacy">Privacy Policy</a></li>
                        <li><a href="#terms">Terms of Service</a></li>
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
                <p class="mb-0">&copy; <span id="currentYear"></span> MedicEdge. All rights reserved.</p>
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
        // Main Login Script
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }
            
            const formData = new FormData(this);
            
            // Disable form while submitting
            const submitButton = this.querySelector('button[type="submit"]');
            const inputs = this.querySelectorAll('input');
            submitButton.disabled = true;
            inputs.forEach(input => input.disabled = true);
            
            fetch('/php/credentials/login-register_handler.php', {
                method: 'POST',
                body: formData,
                credentials: 'include'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1850,
                        allowOutsideClick: false
                    }).then(() => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    });
                } else {
                    // Show error with timer if rate limited
                    if (data.message && data.message.includes('Too many failed attempts')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Account Locked',
                            text: data.message,
                            showConfirmButton: true,
                            allowOutsideClick: false,
                            timer: null
                        });
                        // Keep form disabled during lockout
                        return;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'An unexpected error occurred'
                    });
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again.'
                });
            })
            .finally(() => {
                // Only re-enable form if not rate limited
                if (!document.querySelector('.swal2-container')?.textContent?.includes('Too many failed attempts')) {
                    submitButton.disabled = false;
                    inputs.forEach(input => input.disabled = false);
                }
            });
        });

        // Already Logged in Check
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/php/credentials/login_onload_check.php', {
                method: 'GET',
                credentials: 'include'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.loggedIn) {
                    Swal.fire({
                        icon: 'info',
                        title: 'You are already logged in',
                        text: 'Redirecting to your dashboard...',
                        showConfirmButton: false,
                        timer: 1850,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Login check error:', error);
            });
        });

        // Forget Password
        document.getElementById('forgotPassword').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Reset Password',
                input: 'email',
                inputLabel: 'Enter your email address',
                inputPlaceholder: 'email@example.com',
                showCancelButton: true,
                confirmButtonText: 'Send Reset Link',
                showLoaderOnConfirm: true,
                preConfirm: (email) => {
                    return new Promise(resolve => setTimeout(resolve, 1000));
                }
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire({ icon: 'success', title: 'Reset Link Sent', text: 'Please check your email for password reset instructions.' });
                }
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