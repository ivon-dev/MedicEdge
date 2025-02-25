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
    <meta name="description" content="Patient dashboard">
    <title>Patient Dashboard - <?php echo htmlspecialchars($orgName); ?></title>

    <link href="/styles/external/bootstrap.min.css" rel="stylesheet">
    <link href="/styles/external/aos.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/styles/pages-styles/dashboard_styles.css" rel="stylesheet">
    
    <link rel="icon" type="image/x-icon" href="/<?php echo htmlspecialchars($favicon); ?>">
</head>
<body>
    <nav class="sidebar" style="background-color: <?php echo htmlspecialchars($dashboardColor); ?>;">
        <!-- Profile Section -->
        <div class="profile-section">
            <img src="profile.jpg" draggable="false">
            <h5 class="mt-2">John Doe</h5>
        </div>

        <!-- Navigation Links -->
        <div class="mt-4 nav-links">
            <a href="#" data-section="account" class="active"><i class="fas fa-home me-2"></i> Your Account</a>
            <a href="#" data-section="appointments"><i class="fas fa-calendar-alt me-2"></i> Appointments</a>
            <a href="#" data-section="records"><i class="fas fa-file-medical me-2"></i> Medical Records</a>
            <a href="#" data-section="prescriptions"><i class="fas fa-pills me-2"></i> Prescriptions</a>
            <a href="#" data-section="profile"><i class="fas fa-user me-2"></i> Profile</a>
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
                    <label class="form-label">First Name</label>
                    <input type="text" name="firstname" class="form-control" id="firstname" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middlename" class="form-control" id="middlename" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lastname" class="form-control" id="lastname" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="tel" name="contact_num" class="form-control" id="contact_num" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" id="address" readonly>
                </div>
            </form>
        </section>

        <!-- Appointments -->
        <section id="appointments" class="content-section">
            <h3 class="mb-4">Appointments</h3>
            <ul class="list-group">
                <li class="list-group-item">Feb 15, 2025 - General Checkup with Dr. Smith</li>
                <li class="list-group-item">Mar 20, 2025 - Dental Cleaning with Dr. Brown</li>
                <li class="list-group-item text-muted">Jan 10, 2025 - Follow-up with Dr. Lee (Completed)</li>
            </ul>
        </section>

        <!-- Medical Records -->
        <section id="records" class="content-section">
            <h3 class="mb-4">Medical Records</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Condition</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Jan 10, 2025</td>
                        <td>Dr. Lee</td>
                        <td>Hypertension</td>
                        <td>Monitor blood pressure</td>
                    </tr>
                    <tr>
                        <td>Nov 22, 2024</td>
                        <td>Dr. Smith</td>
                        <td>Cold & Flu</td>
                        <td>Prescribed rest and hydration</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Prescriptions -->
        <section id="prescriptions" class="content-section">
            <h3 class="mb-4">Prescriptions</h3>
            <ul class="list-group">
                <li class="list-group-item">Lisinopril - 10mg (Daily) - Expires: June 2025</li>
                <li class="list-group-item">Ibuprofen - 200mg (As needed) - Expires: Dec 2025</li>
                <li class="list-group-item text-muted">Amoxicillin - 500mg (Completed) - Expired: Jan 2025</li>
            </ul>
        </section>

        <!-- Profile -->
        <section id="profile" class="content-section">
            <h3 class="mb-4">Profile</h3>
            <form>
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" value="John Doe">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="john.doe@email.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" value="+1 234 567 890">
                </div>
                <button type="submit" class="btn btn-success">Save Changes</button>
            </form>
        </section>
    </main>
    
    <!-- Major Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>
    <script src="/scripts/external/aos.js"></script>

    <!-- External Scripts -->
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
