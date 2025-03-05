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

    <title>Error 403: Forbidden - <?php echo htmlspecialchars($orgName); ?></title>

    <link href="/styles/external/bootstrap.min.css" rel="stylesheet">
    <link href="/styles/external/aos.css" rel="stylesheet">
    <link href="/styles/global-styles/footer_globalstyles.css" rel="stylesheet">
    <link href="/styles/pages-styles/error-pages_styles.css" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="/<?php echo htmlspecialchars($favicon); ?>">
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
                    <li class="nav-item"><a class="nav-link" href="/pages/legal/about-us.php">About</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-2" href="/pages/auth/login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-2" href="/pages/auth/register_as_admin.php">Register as Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="main-content d-flex flex-column align-items-center justify-content-center text-center">
        <img class="img-fluid mb-4" src="/img/icon/error-pages/403.png" alt="Error 403 Image" draggable="false">
        <h3 class="fw-bold">Oops! Access Denied!</h3>
        <p class="text-muted">Your webapp administrator in your organization, <?php echo htmlspecialchars($orgName); ?> has restricted access in this resource. Please contact your webapp administrator for assistance.</p>
        <p class="text-secondary fw-semibold">HTTP Error 403: Forbidden</p>
        <a href="/" class="btn btn-primary mt-3">Back to Homepage &#8594;</a>
    </main>

    <footer>
        <div class="container">
            <div class="text-center">
                <p class="mb-0">&copy; <span id="currentYear"></span> <?php echo htmlspecialchars($orgName); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Major Scripts -->
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>
    <script src="/scripts/external/aos.js"></script>

    <!-- Minor Scripts -->
    <script src="/scripts/copyright-year.js"></script>
    <script src="/scripts/dynamic-buttons_sessiontoken.js"></script>

    <!-- Internal Scripts -->
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>