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

    $legalRevisionDate = $config_data['legal-pages-revision-date'] ?? 'NULL';

    $contactEmail = $config_data['contact-email'] ?? 'Unavailable';
    $contactNumber = $config_data['contact-num'] ?? 'Unavailable';
    $address = $config_data['address'] ?? 'Unavailable';
?>

<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php/legal/markdown_handler.php';

    $markdownFile = $_SERVER['DOCUMENT_ROOT'] . '/CONFIG/legal/privacy.md';
    $markdown = file_get_contents($markdownFile);
    $htmlContent = markdownToHtml($markdown);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Privacy Policy - <?php echo htmlspecialchars($orgName); ?></title>

    <link href="/styles/external/bootstrap.min.css" rel="stylesheet">
    <link href="/styles/external/aos.css" rel="stylesheet">
    <link href="/styles/global-styles/footer_globalstyles.css" rel="stylesheet">
    <link href="/styles/pages-styles/legal_styles.css" rel="stylesheet">

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
                    <li class="nav-item"><a class="nav-link" href="/pages/legal/terms.php">Terms of Service</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-2" href="/pages/auth/login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-2" href="/pages/auth/register_as_admin.php">Register as Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main -->
    <div class="container py-5 mt-5">
        <header class="text-center mb-5">
            <h1 class="display-4">Privacy Policy</h1>
            <p class="lead">Updated on <?php echo htmlspecialchars($legalRevisionDate); ?></p>
        </header>

        <main>
            <?php echo $htmlContent; ?>
        </main>
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
                        <li><a href="/pages/legal/about-us.php">About Us</a></li>
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

    <!-- Minor Scripts -->
    <script src="/scripts/copyright-year.js"></script>
    <script src="/scripts/bootstrap-tooltip.js"></script>
</body>
</html>