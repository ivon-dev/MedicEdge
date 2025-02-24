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

    $loginRegisterBanner = $config_data['login-register-banner'] ?? 'CONFIG/branding/default/login_register_banner.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($orgName); ?> — Administrator Registration</title>

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
            <img src="/<?php echo htmlspecialchars($navbarLogo); ?>" alt="MedicEdge Logo" class="img-fluid" draggable="false">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/pages/legal/about-us.php">About</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-2" href="/pages/auth/login.php">Login</a></li>
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
                            <img src="/<?php echo htmlspecialchars($loginRegisterBanner); ?>" alt="<?php echo htmlspecialchars($orgName); ?> Registration Banner" width="300" height="100" class="img-fluid login-register-form-banner" draggable="false">
                        </div>
                        <h2 class="text-center mb-4"><b>Administrator Registration</b></h2>
                        
                        <?php
                        if (isset($_GET['message'])) {
                            $message = htmlspecialchars($_GET['message']);
                            $status = isset($_GET['status']) ? $_GET['status'] : 'danger';
                            echo "<div class='alert alert-{$status}'>{$message}</div>";
                        }
                        ?>

                        <form method="POST" action="/php/credentials/login-register_handler.php" class="needs-validation" novalidate>
                            <input type="hidden" name="action" value="register">
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Username*</label> <i class="fa fa-question-circle help-tooltip" data-bs-toggle="tooltip" data-bs-placement="right" title="You can signup with a space on your username, but it is not recommended."></i>
                                <input type="text" class="form-control" id="username" name="username" required>
                                <div class="invalid-feedback">Please choose a username.</div>
                            </div>

                            <div class="mb-3">
                                <label for="firstname" class="form-label">First Name*</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" required>
                                <div class="invalid-feedback">First name is required.</div>
                            </div>

                            <div class="mb-3">
                                <label for="middlename" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middlename" name="middlename">
                            </div>

                            <div class="mb-3">
                                <label for="lastname" class="form-label">Last Name*</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" required>
                                <div class="invalid-feedback">Last name is required.</div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email*</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="contact_num" class="form-label">Contact Number*</label> <i class="fa fa-question-circle help-tooltip" data-bs-toggle="tooltip" data-bs-placement="right" title="You can encode both cellphone and landline numbers in this field. You can also add hyphens and other symbols here."></i>
                                <input type="tel" class="form-control" id="contact_num" name="contact_num" required>
                                <div class="invalid-feedback">Contact number is required.</div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password*</label> <i class="fa fa-question-circle help-tooltip" data-bs-toggle="tooltip" data-bs-placement="right" title="A strong password is recommended (a mix of letters, numbers, and symbols), especially since you're signing up as an admin."></i>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback">Please enter a password.</div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Retype Password*</label> <i class="fa fa-question-circle help-tooltip" data-bs-toggle="tooltip" data-bs-placement="right" title="Passwords must match!"></i>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <div class="invalid-feedback">Please retype your password.</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                            <div class="text-center">
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
                    <h5>MedicEdge</h5>
                    <p>Revolutionizing Healthcare Management with Innovation and Security.</p>
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
                    <p>Email: contact@medicedge.com<br>
                    Phone: (555) 123-4567<br>
                    Address: 123 Health Street</p>
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
    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                // Password Match Validation
                const password = form.querySelector('#password')
                const confirmPassword = form.querySelector('#confirm_password')
                if (password.value !== confirmPassword.value) {
                    event.preventDefault()
                    confirmPassword.setCustomValidity('Passwords do not match')
                } else {
                    confirmPassword.setCustomValidity('')
                }
                
                form.classList.add('was-validated')
            }, false)
        })
    })()
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/php/credentials/login-register_handler.php?action=check_admin')
                .then(response => response.json())
                .then(data => {
                    if (data.adminExists) {
                        Swal.fire({
                            title: 'Administrator already exists',
                            html: `Your organization already has an administrator. Please contact your administrator via email to create an administrator account for you.<br><br>
                                <a href="mailto:${data.adminEmails}?subject=Request for Administrator Access&body=Dear Admin,%0D%0A%0D%0AI would like to request administrator access. Please let me know the steps to create an account.%0D%0A%0D%0AThank you." class="btn btn-primary" onclick="window.open(this.href, '_blank'); return false;">Email Admin</a>`,
                            icon: 'info',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false
                        }).then((result) => {
                            window.location.href = 'login.php';
                        });
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