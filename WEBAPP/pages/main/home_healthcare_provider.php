<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/dashboard/dashboard_onload_check.php';

    $webapp_config_file = $_SERVER['DOCUMENT_ROOT'] . '/CONFIG/medicedge_webapp_config.json';
    if (file_exists($webapp_config_file)) {
        $config_data = json_decode(file_get_contents($webapp_config_file), true);
    } else {
        $config_data = [];
    }

    $orgName = $config_data['org-name'] ?? 'MedicEdge';
    $orgLogo = $config_data['org-logo'] ?? 'CONFIG/branding/default/org_logo.png';
    $favicon = $config_data['favicon'] ?? 'CONFIG/branding/default/favicon.ico';
    $navbarLogo = $config_data['navbar-logo'] ?? 'CONFIG/branding/default/navbar_logo.png';

    $dashboardColor = $config_data['color-dashboard'] ?? '#ce0000';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Healthcare Provider Dashboard">
    <title>Healthcare Provider Dashboard - <?php echo htmlspecialchars($orgName); ?></title>

    <link href="/styles/external/bootstrap.min.css" rel="stylesheet">
    <link href="/styles/external/aos.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/styles/pages-styles/dashboard_styles.css" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="/<?php echo htmlspecialchars($favicon); ?>">
</head>
<body>
    <nav class="sidebar" style="background-color: <?php echo htmlspecialchars($dashboardColor); ?>;">
        <div class="profile-section">
            <img id="current-profile-picture" draggable="false">
            <h5 class="mt-2">Healthcare Provider</h5>
        </div>

        <div class="mt-4 nav-links">
            <a href="#" data-section="account" class="active"><i class="fas fa-key me-2"></i> Your Account</a>
            <a href="#" data-section="appointments"><i class="fas fa-calendar-check me-2"></i> Assigned Appointments</a>
            <a href="#" data-section="patients"><i class="fas fa-notes-medical me-2"></i> Patient Records</a>
            <a href="#" data-section="messaging"><i class="fas fa-envelope me-2"></i> Messaging</a>
            <a href="#" data-section="availability"><i class="fas fa-clock me-2"></i> Availability</a>
            <a href="/"><i class="fas fa-sign-out-alt me-2"></i> Back to Homepage</a>
            <a href="/php/credentials/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>

        <!-- Logo Section -->
        <div class="logo-section">
            <img src="/<?php echo htmlspecialchars($orgLogo); ?>" alt="<?php echo htmlspecialchars($orgName); ?> Logo">
            <p><?php echo htmlspecialchars($orgName); ?></p>
        </div>
    </nav>

    <main class="content">
        <!-- Profile Section -->
        <section id="account" class="content-section active">
            <h3 class="mb-4">Your Profile</h3>
            <form>
                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" accept="image/jpeg, image/png, image/jpg">
                </div>
                <button type="button" class="btn btn-primary mt-0 mb-3" onclick="showProfilePictureGallery()">
                    Select Profile Picture
                </button>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username">
                </div>
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="firstname" class="form-control" id="firstname">
                </div>
                <div class="mb-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middlename" class="form-control" id="middlename">
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lastname" class="form-control" id="lastname">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="tel" name="contact_num" class="form-control" id="contact_num">
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" id="address">
                </div>
                <button type="submit" class="btn btn-success">Save Changes</button>
            </form>
            <div id="gallery-modal" class="fixed inset-0 bg-black bg-opacity-50 d-none items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg max-w-3xl w-full mx-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-xl font-bold">Select a Picture</h4>
                        <button onclick="closeGallery()" class="text-gray-600 hover:text-gray-800">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div id="gallery-grid" class="grid grid-cols-3 gap-4">
                        <!-- Images will be loaded here -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Assigned Appointments Section -->
        <section id="appointments" class="content-section">
            <h3 class="mb-4">Assigned Appointments</h3>
            <button class="btn btn-info">View</button>
            <button class="btn btn-warning">Cancel</button>
            <button class="btn btn-primary">Reschedule</button>
            <button class="btn btn-success">Mark as Completed</button>
            <button class="btn btn-secondary">Add Notes</button>
        </section>

        <!-- Patient Records Section -->
        <section id="patients" class="content-section">
            <h3 class="mb-4">Patient Records</h3>
            <button class="btn btn-info">View Patient History</button>
            <button class="btn btn-success">Add Prescriptions</button>
            <button class="btn btn-secondary">Upload Test Results</button>
        </section>

        <!-- Messaging Section -->
        <section id="messaging" class="content-section">
            <h3 class="mb-4">Messaging</h3>
            <button class="btn btn-primary">Send Message to Patient</button>
            <button class="btn btn-secondary">Send Message to Receptionist</button>
        </section>

        <!-- Availability Section -->
        <section id="availability" class="content-section">
            <h3 class="mb-4">Availability</h3>
            <button class="btn btn-primary">Set Working Hours</button>
            <button class="btn btn-secondary">Block Off Unavailable Times</button>
        </section>
    </main>

    <!-- External Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>
    <script src="/scripts/external/aos.js"></script>

    <!-- Major Scripts -->
    <script src="/scripts/dashboard/dashboard_all.js"></script>
    <script src="/scripts/dashboard/fetch-update_userinfo.js"></script>

    <!-- Internal Scripts -->
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
