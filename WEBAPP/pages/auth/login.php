<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MedicEdge — Login</title>

    <link href="/styles/external/bootstrap.min.css" rel="stylesheet">
    <link href="/styles/external/aos.css" rel="stylesheet">
    <link href="/styles/global-styles/footer_globalstyles.css" rel="stylesheet">
    <link href="/styles/pages-styles/login-register_styles.css" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <img src="/img/navbar/logo.png" alt="MedicEdge Logo" class="img-fluid" draggable="false">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-2" href="/pages/auth/register.php">Register</a></li>
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
                            <img src="/img/login-register/banner.png" alt="Login Banner" width="300" height="100" class="img-fluid login-register-form-banner">
                        </div>
                        <h2 class="text-center mb-4"><b>Login</b></h2>
                        
                        <?php
                        if (isset($_GET['message'])) {
                            $message = htmlspecialchars($_GET['message']);
                            $status = isset($_GET['status']) ? $_GET['status'] : 'danger';
                            echo "<div class='alert alert-{$status}'>{$message}</div>";
                        }
                        ?>

                        <form method="POST" action="/php/login-register_handler.php" class="needs-validation" novalidate>
                            <input type="hidden" name="action" value="login">
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Username*</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                                <div class="invalid-feedback">Please enter your username.</div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password*</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback">Please enter your password.</div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                                <label class="form-check-label" for="remember_me">Remember me</label>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>

                            <div class="text-center">
                                <a href="#" class="text-muted">Forgot Password?</a>
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
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#privacy">Privacy Policy</a></li>
                        <li><a href="#terms">Terms of Service</a></li>
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
                <p class="mb-0">&copy; <span id="currentYear"></span> MedicEdge. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Minor Scripts -->
    <script src="/scripts/copyright-year.js"></script>

    <!-- Major Scripts -->
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>
    <script src="/scripts/external/aos.js"></script>

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
                form.classList.add('was-validated')
            }, false)
        })
    })()
    </script>

</body>
</html>