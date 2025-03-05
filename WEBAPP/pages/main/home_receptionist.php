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
    <meta name="description" content="Receptionist Dashboard">
    <title>Receptionist Dashboard - <?php echo htmlspecialchars($orgName); ?></title>

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
            <h5 class="mt-2">Receptionist Name</h5>
        </div>

        <div class="mt-4 nav-links">
            <a href="#" data-section="account" class="active"><i class="fas fa-key me-2"></i> Your Account</a>
            <a href="#" data-section="patients"><i class="fas fa-user-injured me-2"></i> Patients</a>
            <a href="#" data-section="medical-records"><i class="fas fa-file-medical me-2"></i> Patient Medical Records</a>
            <a href="#" data-section="appointments"><i class="fas fa-calendar-check me-2"></i> Appointments</a>
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

        <!-- Patients Section -->
        <section id="patients" class="content-section">
            <h3 class="mb-4">Patients Master List</h3>
            <div class="mb-3">
                <button class="btn btn-primary" onclick="addPatient()">
                    <i class="fas fa-plus"></i> Add Patient
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="patientTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Patient Medical Records Section -->
        <section id="medical-records" class="content-section">
            <h3 class="mb-4">Patient Medical Records</h3>
            
            <div class="card mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" id="medicalRecordsSearch" class="form-control" placeholder="Search patients...">
                        <button class="btn btn-primary" onclick="searchMedicalRecords()"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="medicalRecordsTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Appointment Management Section -->
        <section id="appointments" class="content-section">
            <h3 class="mb-4">Appointments</h3>
            
            <div class="card mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" id="appointmentsSearch" class="form-control" placeholder="Search appointments...">
                        <button class="btn btn-primary" onclick="searchAppointments()"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button class="btn btn-primary" onclick="addAppointment()">
                    <i class="fas fa-plus"></i> Add Appointment
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="appointmentsTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Patient Check-In Section -->
        <section id="checkin" class="content-section">
            <h3 class="mb-4">Patient Check-In</h3>
            
            <!-- Search Patient -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search patient by name or appointment ID...">
                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

            <!-- Check-In Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Patient Check-In Form</h5>
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Appointment ID</label>
                                <input type="text" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Arrival Time</label>
                                <input type="time" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Insurance Verification</label>
                            <select class="form-select">
                                <option>Verified</option>
                                <option>Pending</option>
                                <option>Issues Found</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Complete Check-In</button>
                        <button type="button" class="btn btn-danger">Mark as No-Show</button>
                    </form>
                </div>
            </div>

            <!-- Today's Check-Ins -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Appointment</th>
                            <th>Status</th>
                            <th>Wait Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>09:15 AM</td>
                            <td>Jane Smith</td>
                            <td>Regular Check-up</td>
                            <td><span class="badge bg-success">Checked In</span></td>
                            <td>10 mins</td>
                            <td>
                                <button class="btn btn-sm btn-info">View Details</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Patient Records Section -->
        <section id="records" class="content-section">
            <h3 class="mb-4">Patient Records</h3>
            
            <!-- Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search patient records...">
                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

            <!-- Patient Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Patient Information</h5>
                    <form>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Insurance Provider</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Policy Number</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Update Information</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- External Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>
    <script src="/scripts/external/aos.js"></script>

    <!-- Major Scripts -->
    <script src="/scripts/dashboard/dashboard_all.js"></script>
    <script src="/scripts/dashboard/dashboard_receptionist.js"></script>
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
