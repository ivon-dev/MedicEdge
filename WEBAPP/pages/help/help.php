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

    <title>Help — <?php echo htmlspecialchars($orgName); ?></title>

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

    <main class="mt-5 pt-5 px-4">
        <div class="container-fluid" style="min-height: 59.5vh;">
            <div class="row">
                <div class="col-lg-4 col-sm-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">User Guides</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <i class="fas fa-file-pdf me-2 text-danger"></i>
                                    <a href="/pages/util/view_doc.php?file=qhnA00ECdx7g2ue1jqUrecLU3k4hC01yfy3vn6m4VmrTQG4SaywaFCGMpxyHvd6R" target="_blank">Administrator Manual</a>
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-file-pdf me-2 text-danger"></i>
                                    <a href="/docs/provider_guide.pdf" target="_blank">Healthcare Provider Guide</a>
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-file-pdf me-2 text-danger"></i>
                                    <a href="/docs/receptionist_guide.pdf" target="_blank">Receptionist Guide</a>
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-file-pdf me-2 text-danger"></i>
                                    <a href="/docs/patient_guide.pdf" target="_blank">Patient Portal Guide</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Technical Documentation</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <i class="fas fa-file-code me-2 text-primary"></i>
                                    <a href="/docs/api_documentation.html" target="_blank">API Documentation</a>
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-file-code me-2 text-primary"></i>
                                    <a href="/docs/database_schema.html" target="_blank">Database Schema</a>
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-file-code me-2 text-primary"></i>
                                    <a href="/docs/deployment_guide.html" target="_blank">Deployment Guide</a>
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-file-code me-2 text-primary"></i>
                                    <a href="/docs/security_guidelines.html" target="_blank">Security Guidelines</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Support Resources</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <i class="fas fa-question-circle me-2 text-warning"></i>
                                    <a href="/docs/faq.html" target="_blank">Frequently Asked Questions</a>
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-exclamation-circle me-2 text-warning"></i>
                                    <a href="/docs/known_issues.html" target="_blank">Known Issues</a>
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-life-ring me-2 text-warning"></i>
                                    <a href="/support" target="_blank">Support Portal</a>
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-comments me-2 text-warning"></i>
                                    <a href="/community" target="_blank">Community Forum</a>
                                </li>
                            </ul>
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

    <!-- External Scripts -->
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>
    <script src="/scripts/external/aos.js"></script>

    <!-- Major Scripts -->

    <!-- Minor Scripts -->
    <script src="/scripts/copyright-year.js"></script>
    <script src="/scripts/dynamic-buttons_sessiontoken.js"></script>
    <script src="/scripts/bootstrap-tooltip.js"></script>

    <!-- Internal Scripts -->
</body>
</html>