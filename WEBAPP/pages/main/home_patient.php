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
    <title>Patient Dashboard — <?php echo htmlspecialchars($orgName); ?></title>

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
            <img src="" id="current-profile-picture" draggable="false">
            <h5 class="mt-2" id="patient-name">Loading...</h5>
        </div>

        <!-- Navigation Links -->
        <div class="mt-4 nav-links">
            <a href="#" data-section="account" class="active"><i class="fas fa-key me-2"></i> Your Account</a>
            <a href="#" data-section="appointments"><i class="fas fa-calendar-alt me-2"></i> Appointments</a>
            <a href="#" data-section="records"><i class="fas fa-file-medical me-2"></i> Medical Records</a>
            <a href="#" data-section="prescriptions"><i class="fas fa-pills me-2"></i> Prescriptions</a>
            <a href="/"><i class="fas fa-sign-out-alt me-2"></i> Back to Homepage</a>
            <a href="/php/credentials/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>

        <!-- Logo Section -->
        <div class="logo-section">
            <img src="/<?php echo htmlspecialchars($orgLogo); ?>" alt="<?php echo htmlspecialchars($orgName); ?> Logo">
            <p><?php echo htmlspecialchars($orgName); ?></p>
        </div>
    </nav>
    <button class="sidebar-toggle"><i class="fas fa-chevron-left"></i></button>

    <main class="content">
        <!-- Profile Section -->
        <section id="account" class="content-section active">
            <h3 class="mb-4">Your Account</h3>
            <form>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username">
                </div>
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="firstname" id="firstname">
                </div>
                <div class="mb-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control"  name="middlename" id="middlename">
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lastname" id="lastname">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="tel" class="form-control" name="contact_num" id="contact_num">
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" name="address" id="address">
                </div>
                <button type="submit" class="btn btn-success">Save Changes</button>
            </form>
        </section>

        <!-- Appointments -->
        <section id="appointments" class="content-section">
            <h3 class="mb-4">Your Appointments</h3>
            <ul class="list-group">
                <li class="list-group-item">Feb 15, 2025 - General Checkup with Dr. Smith</li>
                <li class="list-group-item">Mar 20, 2025 - Dental Cleaning with Dr. Brown</li>
                <li class="list-group-item text-muted">Jan 10, 2025 - Follow-up with Dr. Lee (Completed)</li>
            </ul>
        </section>

        <!-- Medical Records -->
        <section id="records" class="content-section">
            <h3 class="mb-4">Your Medical Records</h3>
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
            <h3 class="mb-4">Your Prescriptions</h3>
            <ul class="list-group">
                <li class="list-group-item">Lisinopril - 10mg (Daily) - Expires: June 2025</li>
                <li class="list-group-item">Ibuprofen - 200mg (As needed) - Expires: Dec 2025</li>
                <li class="list-group-item text-muted">Amoxicillin - 500mg (Completed) - Expired: Jan 2025</li>
            </ul>
        </section>

        <!-- Patient Medical Records Section -->
        <section id="medical-records" class="content-section">
            <h3 class="mb-4">Patient Medical Records</h3>
            
            <div class="card mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" id="medicalRecordsSearch" class="form-control" placeholder="Search for medical records...">
                        <button class="btn btn-primary" onclick="searchMedicalRecords()"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Condition</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody id="medicalRecordsTableBody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </section>
        
        <!-- Patients Section -->
        <section id="patients" class="content-section">
            <h3 class="mb-4">Your Profile</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" id="patientsSearch" class="form-control" placeholder="Search for your records...">
                        <button class="btn btn-primary" onclick="searchPatients()"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <!-- Rest of patient profile content -->
        </section>
    </main>
    
    <!-- External Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>
    <script src="/scripts/external/aos.js"></script>

    <!-- Major Scripts -->
    <script src="/scripts/dashboard/dashboard_all.js"></script>
    <script src="/scripts/dashboard/fetch-update_userinfo.js"></script>

    <!-- Minor Scripts -->
    <script src="/scripts/bootstrap-tooltip.js"></script>

    <!-- Internal Scripts -->
    <script> // Collapse/Expand Sidebar
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const icon = sidebarToggle.querySelector('i');

            function collapseSidebar() {
                sidebar.classList.add('collapsed');
                content.classList.add('sidebar-collapsed');
                sidebarToggle.classList.add('collapsed');
                icon.classList.replace('fa-chevron-left', 'fa-chevron-right');
                localStorage.setItem('sidebarCollapsed', 'true');
            }

            function expandSidebar() {
                sidebar.classList.remove('collapsed');
                content.classList.remove('sidebar-collapsed');
                sidebarToggle.classList.remove('collapsed');
                icon.classList.replace('fa-chevron-right', 'fa-chevron-left');
                localStorage.setItem('sidebarCollapsed', 'false');
            }

            // Check window width and localStorage for initial state
            function initializeSidebar() {
                if (window.innerWidth <= 675) {
                    collapseSidebar();
                } else {
                    // Check localStorage only for larger screens
                    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                    if (isCollapsed) {
                        collapseSidebar();
                    }
                }
            }

            // Initialize on load
            initializeSidebar();

            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth <= 675) {
                    collapseSidebar();
                }
            });

            // Toggle handler
            sidebarToggle.addEventListener('click', () => {
                const willCollapse = !sidebar.classList.contains('collapsed');
                if (willCollapse) {
                    collapseSidebar();
                } else {
                    expandSidebar();
                }
            });
        });
    </script>
</body>
</html>
