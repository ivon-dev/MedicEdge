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
    <meta name="description" content="Admin Dashboard">
    <title>Admin Dashboard - <?php echo htmlspecialchars($orgName); ?></title>

    <link href="/styles/external/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" >
    <link href="/styles/pages-styles/dashboard_styles.css" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="/<?php echo htmlspecialchars($favicon); ?>">
</head>
<body>
    <nav class="sidebar" style="background-color: <?php echo htmlspecialchars($dashboardColor); ?>;">
        <div class="profile-section">
            <img id="current-profile-picture" draggable="false">
            <h5 class="mt-2">Admin Name</h5>
        </div>

        <div class="mt-4 nav-links">
            <a href="#" data-section="profile" class="active"><i class="fas fa-user me-2"></i> Your Profile</a>
            <a href="#" data-section="account"><i class="fas fa-key me-2"></i> Your Account</a>
            <a href="#" data-section="customization"><i class="fas fa-paint-brush me-2"></i> Webapp Customization</a>
            <a href="#" data-section="administrators"><i class="fas fa-users-cog me-2"></i> Administrators</a>
            <a href="#" data-section="appointments"><i class="fas fa-calendar-check me-2"></i> Appointments</a>
            <a href="#" data-section="patients"><i class="fas fa-user-injured me-2"></i> Patients</a>
            <a href="#" data-section="healthcare_providers"><i class="fas fa-user-md me-2"></i> Healthcare Providers</a>
            <a href="#" data-section="receptionists"><i class="fas fa-concierge-bell me-2"></i> Receptionists</a>
            <a href="#" data-section="activity_logs"><i class="fas fa-clipboard-list me-2"></i> User Activity Logs</a>
            <a href="#" data-section="system_settings"><i class="fas fa-cogs me-2"></i> System Settings</a>
            <a href="#" data-section="reports"><i class="fas fa-chart-bar me-2"></i> Reports & Analytics</a>
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
        <section id="profile" class="content-section active">
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
                    <label class="form-label">First Name</label>
                    <input type="text" name="firstname" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middlename" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lastname" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="tel" name="contact_num" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control">
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

        <!-- Account Section -->
        <section id="account" class="content-section">
            <h3 class="mb-4">Your Account</h3>
            <div class="row mb-4">
                <div class="col">
                    <h5>Account Actions</h5>
                    <form id="account-form">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" id="userUsername" name="userUsername" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="userEmail" name="userEmail" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Username</label>
                            <input type="text" class="form-control" id="newUsername" name="newUsername">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Email</label>
                            <input type="email" class="form-control" id="newEmail" name="newEmail">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Update Account</button>
                    </form>
                    <div class="btn-group-vertical w-100">
                        <button class="btn btn-primary mb-2">Change Email</button>
                        <button class="btn btn-primary mb-2">Change Password</button>
                        <button class="btn btn-secondary mb-2">View Session History</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Webapp Customization Section -->
        <section id="customization" class="content-section">
            <h3 class="mb-4">Webapp Customization</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3 border-0">
                        <div class="card-body">
                            <form id="website-settings-form">
                                <h5 class="card-title">Organization</h5>
                                <div class="mb-3">
                                    <label class="form-label">Name</label> <i class="fa fa-question-circle help-tooltip"
                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Your organization name. This will be reflected system-wide."></i>
                                    <input type="text" class="form-control" id="org-name" name="org-name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Slogan</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="Your organization slogan. This will be reflected system-wide."></i>
                                    <input type="text" class="form-control" id="slogan-footer" name="slogan-footer">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Logo</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="Your organization logo. Recommended to upload your org logo with it's logo name bundled in an image file. This will be reflected in some areas of the Webapp."></i>
                                    <input type="file" class="form-control" id="org-logo" name="org-logo" accept="image/*">
                                    <img id="org-logo-preview" src="" alt="Organization Logo Preview" style="max-width: 100px; margin-top: 10px; display: none;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Favicon</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="Your organization favicon. Favicons typically have a 32x32 or 64x64 .ico file, please upload on that size. This will be reflected system-wide in the browser's tab."></i>
                                    <input type="file" class="form-control" id="favicon" name="favicon" accept="image/x-icon">
                                    <img id="favicon-preview" src="" alt="Favicon Preview" style="max-width: 32px; margin-top: 10px; display: none;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Navbar Logo</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="Your organization logo. Recommended to upload your org logo only without the logo text. This will be reflected in some areas of the Webapp."></i>
                                    <input type="file" class="form-control" id="navbar-logo" name="navbar-logo" accept="image/*">
                                    <img id="navbar-logo-preview" src="" alt="Navbar Logo Preview" style="max-width: 100px; margin-top: 10px; display: none;">
                                </div>
                                <h5 class="card-title pt-3">Organization Contacts</h5>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="This will be reflected system-wide."></i>
                                    <input type="email" class="form-control" id="contact-email" name="contact-email">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="This will be reflected system-wide."></i>
                                    <input type="text" class="form-control" id="contact-num" name="contact-num">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="This will be reflected system-wide."></i>
                                    <input type="text" class="form-control" id="address" name="address">
                                </div>
                                <h5 class="card-title pt-3">Homepage</h5>
                                <div class="mb-3">
                                    <label class="form-label">Homepage &lt;Title&gt; Tag</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="HTML <title> tag of index.html in MedicEdge's Webapp."></i>
                                    <input type="text" class="form-control" id="homepage-title" name="homepage-title">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Homepage Hero Background Image</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="Modify the hero background image in the homepage. Recommended to upload a high resolution photo."></i>
                                    <input type="file" class="form-control" id="homepage-hero-bg-image" name="homepage-hero-bg-image" accept="image/*">
                                    <img id="homepage-hero-bg-image-preview" src="" alt="Homepage Hero Background Image Preview" style="max-width: 100px; margin-top: 10px; display: none;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hero Text Title</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="Modify the main hero text in the homepage."></i>
                                    <input type="text" class="form-control" id="hero-text-title" name="hero-text-title">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hero Text Subtitle</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="Modify the subtitle hero text in the homepage."></i>
                                    <input type="text" class="form-control" id="hero-text-subtitle" name="hero-text-subtitle">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Promotional - Active Users Count</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="Modify the Active Users fields under the feature card in index.html."></i>
                                    <input type="text" class="form-control" id="ad-active-users" name="ad-active-users">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Promotional - Healthcare Partners Count</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="Modify the Healthcare Partners fields under the feature card in index.html."></i>
                                    <input type="text" class="form-control" id="ad-healthcare-partners" name="ad-healthcare-partners">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Promotional - Uptime Percentage</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="Modify the Uptime Users fields under the feature card in index.html."></i>
                                    <input type="text" class="form-control" id="ad-uptime" name="ad-uptime">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Promotional - Support Availability</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="Modify the Support Users fields under the feature card in index.html."></i>
                                    <input type="text" class="form-control" id="ad-support" name="ad-support">
                                </div>
                                <h5 class="card-title pt-3">Login/Register Form</h5>
                                <div class="mb-3">
                                    <label class="form-label">Login/Register Banner Image</label>
                                    <i class="fa fa-question-circle help-tooltip"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title="Modify the banner image in the login/register form."></i>
                                    <input type="file" class="form-control" id="login-register-banner" name="login-register-banner" accept="image/*">
                                    <img id="login-register-banner-preview" src="" alt="Login/Register Banner Preview" style="max-width: 100px; margin-top: 10px; display: none;">
                                </div>
                                <h5 class="card-title pt-3">Dashboard</h5>
                                <div class="mb-3">
                                    <label class="form-label">Dashboard Color</label>
                                     <i class="fa fa-question-circle help-tooltip"
                                        data-bs-toggle="tooltip" data-bs-placement="right" title="Select a color palette in Dashboard pages."></i>
                                    <input type="color" class="form-control form-control-color" id="color-dashboard" name="color-dashboard" value="#0d6efd">
                                </div>
                                <button type="button" class="btn btn-warning" id="reset-to-default">Reset to Default</button>
                                <button type="submit" class="btn btn-success">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Administrators Section -->
        <section id="administrators" class="content-section">
            <h3 class="mb-4">Administrators Master List</h3>
            <div class="mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                    <i class="fas fa-plus"></i> Add Admin
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>A001</td>
                            <td>John Admin</td>
                            <td>john@medic.com</td>
                            <td>Super Admin</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>2024-02-12 09:30 AM</td>
                            <td>
                                <button class="btn btn-sm btn-info">Edit</button>
                                <button class="btn btn-sm btn-warning">Permissions</button>
                                <button class="btn btn-sm btn-danger">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Appointments Section -->
        <section id="appointments" class="content-section">
            <h3 class="mb-4">Appointments Master List</h3>
            
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Today's Appointments</h6>
                            <h3>24</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Confirmed</h6>
                            <h3>18</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6>Pending</h6>
                            <h3>5</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6>Cancelled</h6>
                            <h3>1</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-2">
                    <select class="form-select">
                        <option>All Departments</option>
                        <option>Cardiology</option>
                        <option>Neurology</option>
                        <option>Pediatrics</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option>All Doctors</option>
                        <option>Dr. Smith</option>
                        <option>Dr. Johnson</option>
                        <option>Dr. Williams</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option>All Status</option>
                        <option>Pending</option>
                        <option>Confirmed</option>
                        <option>Completed</option>
                        <option>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search appointments...">
                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mb-3">
                <button class="btn btn-info"><i class="fas fa-eye"></i> View</button>
                <button class="btn btn-success"><i class="fas fa-check"></i> Approve</button>
                <button class="btn btn-danger"><i class="fas fa-times"></i> Cancel</button>
                <button class="btn btn-secondary"><i class="fas fa-clock"></i> Reschedule</button>
                <button class="btn btn-primary"><i class="fas fa-envelope"></i> Send Email</button>
                <button class="btn btn-dark"><i class="fas fa-file-alt"></i> Generate Report</button>
                <button class="btn btn-primary float-end"><i class="fas fa-plus"></i> New Appointment</button>
            </div>

            <!-- Appointments Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input">
                            </th>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Department</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input">
                            </td>
                            <td>APT001</td>
                            <td>John Doe</td>
                            <td>Dr. Smith</td>
                            <td>Cardiology</td>
                            <td>2024-02-13</td>
                            <td>10:00 AM</td>
                            <td>Check-up</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">View Details</a></li>
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item" href="#">Cancel</a></li>
                                        <li><a class="dropdown-item" href="#">Send Reminder</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input">
                            </td>
                            <td>APT002</td>
                            <td>Jane Smith</td>
                            <td>Dr. Johnson</td>
                            <td>Neurology</td>
                            <td>2024-02-13</td>
                            <td>11:30 AM</td>
                            <td>Follow-up</td>
                            <td><span class="badge bg-success">Confirmed</span></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">View Details</a></li>
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item" href="#">Cancel</a></li>
                                        <li><a class="dropdown-item" href="#">Send Reminder</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Appointments pagination">
                <ul class="pagination justify-content-end">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </section>

        <!-- Patients Section -->
        <section id="patients" class="content-section">
            <h3 class="mb-4">Patients Master List</h3>
            <div class="row mb-3">
                <div class="col-md-9">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search patients...">
                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                        <i class="fas fa-plus"></i> Add Patient
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Contact</th>
                            <th>Last Visit</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>P001</td>
                            <td>Jane Doe</td>
                            <td>45</td>
                            <td>Female</td>
                            <td>555-0123</td>
                            <td>2024-01-15</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-primary">Edit</button>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Healthcare Providers Section -->
        <section id="healthcare_providers" class="content-section">
            <h3 class="mb-4">Healthcare Providers Master List</h3>
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Departments</option>
                        <option>Cardiology</option>
                        <option>Neurology</option>
                        <option>Pediatrics</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>On Leave</option>
                        <option>Inactive</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search providers...">
                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Specialization</th>
                            <th>Department</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>D001</td>
                            <td>Dr. Smith</td>
                            <td>Cardiologist</td>
                            <td>Cardiology</td>
                            <td>Mon-Fri, 9AM-5PM</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-primary">Schedule</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Receptionists Section -->
        <section id="receptionists" class="content-section">
            <h3 class="mb-4">Receptionists Master List</h3>
            <div class="mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReceptionistModal">
                    <i class="fas fa-plus"></i> Add Receptionist
                </button>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Shift</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>R001</td>
                            <td>Mary Johnson</td>
                            <td>555-0124</td>
                            <td>Morning</td>
                            <td>Main Desk</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                <button class="btn btn-sm btn-danger">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- User Activity Logs Section -->
        <section id="activity_logs" class="content-section">
            <h3 class="mb-4">User Activity Logs</h3>
            
            <!-- Stats Summary -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6>Total Actions Today</h6>
                            <h3>245</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6>Active Users</h6>
                            <h3>18</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6>Failed Logins</h6>
                            <h3>3</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6>System Events</h6>
                            <h3>12</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Advanced Filters</h5>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <select class="form-select">
                                <option>All Users</option>
                                <option>Administrators</option>
                                <option>Doctors</option>
                                <option>Receptionists</option>
                                <option>System</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select">
                                <option>All Actions</option>
                                <option>Login</option>
                                <option>Logout</option>
                                <option>Create</option>
                                <option>Update</option>
                                <option>Delete</option>
                                <option>View</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select">
                                <option>All Status</option>
                                <option>Success</option>
                                <option>Failed</option>
                                <option>Warning</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" placeholder="To Date">
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search logs...">
                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mb-3">
                <button class="btn btn-info"><i class="fas fa-eye"></i> View Selected</button>
                <button class="btn btn-primary"><i class="fas fa-download"></i> Export</button>
                <button class="btn btn-warning"><i class="fas fa-archive"></i> Archive</button>
                <button class="btn btn-danger"><i class="fas fa-trash"></i> Clear Logs</button>
                <div class="float-end">
                    <button class="btn btn-outline-secondary"><i class="fas fa-sync"></i> Refresh</button>
                </div>
            </div>

            <!-- Activity Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="form-check-input"></th>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>IP Address</th>
                            <th>Browser</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" class="form-check-input"></td>
                            <td>2024-02-12 09:30:15</td>
                            <td>admin@medic.com</td>
                            <td>Administrator</td>
                            <td><span class="badge bg-primary">Login</span></td>
                            <td>Auth</td>
                            <td>192.168.1.100</td>
                            <td>Chrome 121.0</td>
                            <td><span class="badge bg-success">Success</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#logModal">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="form-check-input"></td>
                            <td>2024-02-12 09:45:22</td>
                            <td>dr.smith@medic.com</td>
                            <td>Doctor</td>
                            <td><span class="badge bg-info">Update</span></td>
                            <td>Appointments</td>
                            <td>192.168.1.101</td>
                            <td>Firefox 123.0</td>
                            <td><span class="badge bg-success">Success</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#logModal">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="form-check-input"></td>
                            <td>2024-02-12 10:15:30</td>
                            <td>unknown</td>
                            <td>-</td>
                            <td><span class="badge bg-danger">Login Failed</span></td>
                            <td>Auth</td>
                            <td>192.168.1.150</td>
                            <td>Safari 17.0</td>
                            <td><span class="badge bg-danger">Failed</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#logModal">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Showing 1 to 10 of 245 entries
                </div>
                <nav aria-label="Activity logs pagination">
                    <ul class="pagination mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </section>

        <!-- System Settings Section -->
        <section id="system_settings" class="content-section">
            <h3 class="mb-4">System Settings</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">General Settings</h5>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">System Name</label>
                                    <input type="text" class="form-control" value="MedicEdge">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Timezone</label>
                                    <select class="form-select">
                                        <option>UTC</option>
                                        <option>PST</option>
                                        <option>EST</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date Format</label>
                                    <select class="form-select">
                                        <option>MM/DD/YYYY</option>
                                        <option>DD/MM/YYYY</option>
                                        <option>YYYY-MM-DD</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Language</label>
                                    <select class="form-select">
                                        <option>English</option>
                                        <option>Spanish</option>
                                        <option>French</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">System Maintenance</h5>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Maintenance Mode</label>
                                    <select class="form-select">
                                        <option>Disabled</option>
                                        <option>Enabled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Backup Schedule</label>
                                    <select class="form-select">
                                        <option>Daily</option>
                                        <option>Weekly</option>
                                        <option>Monthly</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Session Timeout (minutes)</label>
                                    <input type="number" class="form-control" value="30">
                                </div>
                                <button type="submit" class="btn btn-success">Save Settings</button>
                                <button type="button" class="btn btn-warning">Backup Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Report and Analytics Section -->
        <section id="reports" class="content-section">
            <h3 class="mb-4">Reports & Analytics</h3>
            <!-- Overview Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Total Revenue</h6>
                            <h3>$145,250</h3>
                            <small>+12% from last month</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Total Patients</h6>
                            <h3>1,234</h3>
                            <small>+8% from last month</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6>Appointments</h6>
                            <h3>450</h3>
                            <small>+15% from last month</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6>Average Rating</h6>
                            <h3>4.8/5.0</h3>
                            <small>Based on 350 reviews</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select class="form-select">
                                <option>All Departments</option>
                                <option>Cardiology</option>
                                <option>Neurology</option>
                                <option>Pediatrics</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select">
                                <option>This Month</option>
                                <option>Last Month</option>
                                <option>Last 3 Months</option>
                                <option>Last 6 Months</option>
                                <option>This Year</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" placeholder="From Date">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" placeholder="To Date">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Performance -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Department Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Revenue</th>
                                    <th>Patients</th>
                                    <th>Appointments</th>
                                    <th>Satisfaction</th>
                                    <th>Growth</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cardiology</td>
                                    <td>$45,250</td>
                                    <td>324</td>
                                    <td>180</td>
                                    <td>4.9/5.0</td>
                                    <td><span class="badge bg-success">+15%</span></td>
                                </tr>
                                <tr>
                                    <td>Neurology</td>
                                    <td>$38,450</td>
                                    <td>289</td>
                                    <td>145</td>
                                    <td>4.7/5.0</td>
                                    <td><span class="badge bg-success">+12%</span></td>
                                </tr>
                                <tr>
                                    <td>Pediatrics</td>
                                    <td>$35,800</td>
                                    <td>312</td>
                                    <td>165</td>
                                    <td>4.8/5.0</td>
                                    <td><span class="badge bg-warning">+5%</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="mb-4">
                <button class="btn btn-primary"><i class="fas fa-file-pdf"></i> Export PDF</button>
                <button class="btn btn-success"><i class="fas fa-file-excel"></i> Export Excel</button>
                <button class="btn btn-info"><i class="fas fa-print"></i> Print Report</button>
                <button class="btn btn-secondary"><i class="fas fa-envelope"></i> Email Report</button>
            </div>
    </main>
    
    <!-- External Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/scripts/dashboard/fetch-update_userinfo.js"></script>

    <!-- Major Scripts -->
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>
    <script src="/scripts/external/aos.js"></script>

    <!-- Internal Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Function to display image preview
            function displayImagePreview(input, previewId) {
                const preview = document.getElementById(previewId);

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }

                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.src = '';
                    preview.style.display = 'none';
                }
            }

            // Attach file input change listeners for image previews
            document.getElementById('org-logo').addEventListener('change', function() {
                displayImagePreview(this, 'org-logo-preview');
            });
            document.getElementById('favicon').addEventListener('change', function() {
                displayImagePreview(this, 'favicon-preview');
            });
            document.getElementById('navbar-logo').addEventListener('change', function() {
                displayImagePreview(this, 'navbar-logo-preview');
            });
            document.getElementById('homepage-hero-bg-image').addEventListener('change', function() {
                displayImagePreview(this, 'homepage-hero-bg-image-preview');
            });
             document.getElementById('login-register-banner').addEventListener('change', function() {
                displayImagePreview(this, 'login-register-banner-preview');
            });

            document.querySelectorAll('.sidebar a[data-section]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.querySelectorAll('.sidebar a').forEach(l => l.classList.remove('active'));
                    document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
                    link.classList.add('active');
                    document.getElementById(link.dataset.section).classList.add('active');
                });
            });

            // Fetch website configuration
            fetch('/php/website_config/fetch-update_websiteconfig.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error,
                    });
                } else {
                    // Populate form fields with configuration data
                    document.getElementById('org-name').value = data['org-name'] || '';
                    // Set image previews if paths exist
                    if (data['org-logo']) {
                        document.getElementById('org-logo-preview').src = '/' + data['org-logo'];
                        document.getElementById('org-logo-preview').style.display = 'block';
                    }
                    document.getElementById('homepage-title').value = data['homepage-title'] || '';
                    document.getElementById('hero-text-title').value = data['hero-text-title'] || '';
                    document.getElementById('hero-text-subtitle').value = data['hero-text-subtitle'] || '';
                    document.getElementById('contact-email').value = data['contact-email'] || '';
                    document.getElementById('contact-num').value = data['contact-num'] || '';
                    document.getElementById('address').value = data['address'] || '';
                    document.getElementById('ad-active-users').value = data['ad-active-users'] || '';
                    document.getElementById('ad-healthcare-partners').value = data['ad-healthcare-partners'] || '';
                    document.getElementById('ad-uptime').value = data['ad-uptime'] || '';
                    document.getElementById('ad-support').value = data['ad-support'] || '';
                    document.getElementById('slogan-footer').value = data['slogan-footer'] || '';
                    if (data['favicon']) {
                        document.getElementById('favicon-preview').src = '/' + data['favicon'];
                        document.getElementById('favicon-preview').style.display = 'block';
                    }
                    if (data['navbar-logo']) {
                        document.getElementById('navbar-logo-preview').src = '/' + data['navbar-logo'];
                        document.getElementById('navbar-logo-preview').style.display = 'block';
                    }
                    if (data['homepage-hero-bg-image']) {
                        document.getElementById('homepage-hero-bg-image-preview').src = '/' + data['homepage-hero-bg-image'];
                        document.getElementById('homepage-hero-bg-image-preview').style.display = 'block';
                    }
                    if (data['login-register-banner']) {
                        document.getElementById('login-register-banner-preview').src = '/' + data['login-register-banner'];
                        document.getElementById('login-register-banner-preview').style.display = 'block';
                    }
                    document.getElementById('color-dashboard').value = data['color-dashboard'] || '#0d6efd';
                }
            })
            .catch(error => {
                console.error('Error fetching configuration:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to fetch configuration. Please check the console.',
                });
            });

            // Handle form submission
            document.getElementById('website-settings-form').addEventListener('submit', async function(event) {
                event.preventDefault();

                const formData = new FormData(this);

                // Create a config object to hold the non-file data
                const config = {
                    'org-name': document.getElementById('org-name').value,
                    'homepage-title': document.getElementById('homepage-title').value,
                    'hero-text-title': document.getElementById('hero-text-title').value,
                    'hero-text-subtitle': document.getElementById('hero-text-subtitle').value,
                    'contact-email': document.getElementById('contact-email').value,
                    'contact-num': document.getElementById('contact-num').value,
                    'address': document.getElementById('address').value,
                    'ad-active-users': document.getElementById('ad-active-users').value,
                    'ad-healthcare-partners': document.getElementById('ad-healthcare-partners').value,
                    'ad-uptime': document.getElementById('ad-uptime').value,
                    'ad-support': document.getElementById('ad-support').value,
                    'slogan-footer': document.getElementById('slogan-footer').value,
                    'color-dashboard': document.getElementById('color-dashboard').value
                };

                // Append the config object as a JSON string
                formData.append('config', JSON.stringify(config));

                fetch('/php/website_config/fetch-update_websiteconfig.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.text(); // Get the raw response text
                })
                .then(data => {
                    try {
                        const jsonData = JSON.parse(data); // Try to parse the response as JSON
                        if (jsonData.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: jsonData.success,
                                showConfirmButton: false,
                                timer: 1850,
                                allowOutsideClick: false
                            }).then(() => {
                                location.reload(); // Refresh the page to reflect changes
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: jsonData.error,
                            });
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        console.log('Raw response data:', data); // Log the raw response for inspection
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to update configuration. Please check the console.',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error updating configuration:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update configuration. Please check the console.',
                    });
                });
            });

            // Handle reset to default
            document.getElementById('reset-to-default').addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will reset all website settings to default!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reset it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('/php/website_config/fetch-update_websiteconfig.php', {
                            method: 'POST',
                            body: new URLSearchParams({ reset: 'true' }) // Send reset as URL-encoded data
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: data.success,
                                    showConfirmButton: false,
                                    timer: 1850,
                                    allowOutsideClick: false
                                }).then(() => {
                                    location.reload(); // Refresh the page to reflect changes
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: data.error,
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error resetting configuration:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to reset configuration. Please check the console.',
                            });
                        });
                    }
                });
            });

            // Function to fetch account data
            function fetchAccountData() {
                fetch('/php/dashboard/fetch-update_useraccount.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=fetch'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('userUsername').textContent = data.data.username;
                        document.getElementById('userEmail').textContent = data.data.email;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.error,
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching account data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to fetch account data. Please check the console.',
                    });
                    console.log("Raw JSON data:", error);
                });
            }

            // Fetch account data on page load
            fetchAccountData();

            // Handle form submission for updating account
            document.getElementById('account-form').addEventListener('submit', function(event) {
                event.preventDefault();

                const newUsername = document.getElementById('newUsername').value;
                const newEmail = document.getElementById('newEmail').value;

                fetch('/php/dashboard/fetch-update_useraccount.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=update&username=${newUsername}&email=${newEmail}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            fetchAccountData(); // Refresh account data
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.error,
                        });
                    }
                })
                .catch(error => {
                    console.error('Error updating account:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update account. Please check the console.',
                    });
                });
            });

            document.querySelectorAll('.sidebar a[data-section]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.querySelectorAll('.sidebar a').forEach(l => l.classList.remove('active'));
                    document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
                    link.classList.add('active');
                    document.getElementById(link.dataset.section).classList.add('active');
                });
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
