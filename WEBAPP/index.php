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

    $homepageHeroBg = $config_data['homepage-hero-bg-image'] ?? 'CONFIG/branding/default/index_hero_image.png';

    $adActiveUsers = $config_data['ad-active-users'] ?? '100K+';
    $adHealthcarePartners = $config_data['ad-healthcare-partners'] ?? '500+';
    $adUptime = $config_data['ad-uptime'] ?? '99.9%';
    $adSupport = $config_data['ad-support'] ?? '24/7';
    
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

    <title><?php echo htmlspecialchars($homepageTitle); ?></title>

    <link href="/styles/external/bootstrap.min.css" rel="stylesheet">
    <link href="/styles/external/aos.css" rel="stylesheet">
    <link href="/styles/global-styles/footer_globalstyles.css" rel="stylesheet">
    <link href="/styles/pages-styles/home_styles.css" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars($favicon); ?>">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <img src="<?php echo htmlspecialchars($navbarLogo); ?>" alt="MedicEdge Logo" class="img-fluid" draggable="false">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/pages/legal/about-us.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-2" href="/pages/auth/login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-2" href="/pages/auth/register_as_admin.php">Register as Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section d-flex align-items-center" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?php echo htmlspecialchars($homepageHeroBg); ?>') center/cover;">
        <div class="container text-center" data-aos="fade-up">
            <h1 class="display-3 fw-bold mb-4"><?php echo htmlspecialchars($heroTextTitle); ?></h1>
            <p class="lead mb-4"><?php echo htmlspecialchars($heroTextSubtitle); ?></p>
            <a href="/pages/auth/register_as_admin.php">
                <button class="btn btn-primary btn-lg">Get Started</button>
            </a>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="feature-card card h-100 shadow">
                        <img src="/img/bg/index/feature-card_bg_1.jpg" alt="Security" class="card-img-top">
                        <div class="card-body">
                            <h4>Secure Records</h4>
                            <p>Your medical data protected with enterprise-grade security.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card card h-100 shadow">
                        <img src="/img/bg/index/feature-card_bg_2.png" alt="Access" class="card-img-top">
                        <div class="card-body">
                            <h4>Easy Access</h4>
                            <p>View your medical history anytime, anywhere.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card card h-100 shadow">
                        <img src="/img/bg/index/feature-card_bg_3.png" alt="Integration" class="card-img-top">
                        <div class="card-body">
                            <h4>Seamless Integration</h4>
                            <p>Works with all major healthcare providers.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3" data-aos="fade-up">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo htmlspecialchars($adActiveUsers); ?></div>
                        <div>Active Users</div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo htmlspecialchars($adHealthcarePartners); ?></div>
                        <div>Healthcare Partners</div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo htmlspecialchars($adUptime); ?></div>
                        <div>Uptime</div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo htmlspecialchars($adSupport); ?></div>
                        <div>Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    <!-- Internal Scripts -->
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>