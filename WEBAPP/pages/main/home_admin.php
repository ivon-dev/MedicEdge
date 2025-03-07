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
    <title>Administrator Dashboard — <?php echo htmlspecialchars($orgName); ?></title>

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
            <h5 class="mt-2">Admin Name</h5>
        </div>

        <div class="mt-4 nav-links">
            <a href="#account" data-section="account" class="active"><i class="fas fa-key me-2"></i> Your Account</a>
            <a href="#webapp_settings" data-section="webapp_settings"><i class="fas fa-cogs me-2"></i> Webapp Settings</a>
            <a href="#webapp_customization" data-section="webapp_customization"><i class="fas fa-paint-brush me-2"></i> Webapp Customization</a>
            <a href="#administrators" data-section="administrators"><i class="fas fa-users-cog me-2"></i> Administrators</a>
            <a href="#healthcare_providers" data-section="healthcare_providers"><i class="fas fa-user-md me-2"></i> Healthcare Providers</a>
            <a href="#receptionists" data-section="receptionists"><i class="fas fa-concierge-bell me-2"></i> Receptionists</a>
            <a href="#patients" data-section="patients"><i class="fas fa-user-injured me-2"></i> Patients</a>
            <a href="#appointments" data-section="appointments"><i class="fas fa-calendar-check me-2"></i> Appointments</a>
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
            <h5 class="card-title mb-2">Your User Info</h5>
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
            <h5 class="card-title mt-4">Security</h5>
            <div class="btn-group-vertical w-100 mt-2">
                <button class="btn btn-primary mb-2 w-100" onclick="showSessionHistory()">Session History</button>
            </div>
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

        <!-- Webapp Settings Section -->
        <section id="webapp_settings" class="content-section">
            <h3 class="mb-4">Webapp Settings</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3 border-0">
                        <div class="card-body">
                            <form id="website-settings-form">
                                <h5 class="card-title">Organization</h5>
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <i class="fa fa-question-circle help-tooltip" data-bs-toggle="tooltip" data-bs-placement="right" title="Your organization name. This will be reflected system-wide."></i>
                                    <input type="text" class="form-control" id="org-name" name="org-name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Slogan</label>
                                    <i class="fa fa-question-circle help-tooltip" data-bs-toggle="tooltip" data-bs-placement="right" title="Your organization slogan. This will be reflected system-wide."></i>
                                    <input type="text" class="form-control" id="slogan-footer" name="slogan-footer">
                                </div>
                                <h5 class="card-title">Legal</h5>
                                <div class="btn-group-vertical w-100">
                                    <a href="/pages/util/text_editor.php?file=about_us" class="w-100">
                                        <button class="btn btn-primary mb-2 w-100" onclick="window.open('/pages/util/text_editor.php?file=about_us', '_blank'); return false;">Edit About Us Page</button>
                                    </a>
                                    <a href="/pages/util/text_editor.php?file=terms_of_service" class="w-100">
                                        <button class="btn btn-primary mb-2 w-100" onclick="window.open('/pages/util/text_editor.php?file=terms_of_service', '_blank'); return false;">Edit Terms of Service Statement</button>
                                    </a>
                                    <a href="/pages/util/text_editor.php?file=privacy_policy" class="w-100">
                                        <button class="btn btn-primary mb-2 w-100" onclick="window.open('/pages/util/text_editor.php?file=privacy_policy', '_blank'); return false;">Edit Privacy Policy Statement</button>
                                    </a>
                                    <div class="mb-3 w-100">
                                        <label class="form-label">Terms/Privacy Revision Date</label>
                                        <i class="fa fa-question-circle help-tooltip" data-bs-toggle="tooltip" data-bs-placement="right" title="Terms of Service and Privacy Policy Revision Date. This will be encoded as is when you submit it. (Example, you've written '12-06-2024', it will appear as '12-06-2024' on legal pages.)"></i>
                                        <input type="text" class="form-control" id="legal-pages-revision-date" name="legal-pages-revision-date">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-warning" id="reset-to-default">Reset to Default</button>
                                <button type="submit" class="btn btn-success">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Webapp Customization Section -->
        <section id="webapp_customization" class="content-section">
            <h3 class="mb-4">Webapp Customization</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3 border-0">
                        <div class="card-body">
                            <form id="website-customization-form">
                                <h5 class="card-title">Organization</h5>
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
                                    <input type="text" class="form-control" id="org-address" name="address">
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
                                        data-bs-toggle="tooltip" data-bs-placement="right" title="Select a color in Dashboard pages' sidebar."></i>
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
            <div class="card mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" id="adminSearch" class="form-control" placeholder="Search for an Administrator's name...">
                        <button class="btn btn-primary" onclick="searchAdmins()"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <button class="btn btn-primary" onclick="addAdmin()">
                    <i class="fas fa-plus"></i> Add Admin
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="adminTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Healthcare Providers Section -->
        <section id="healthcare_providers" class="content-section">
            <h3 class="mb-4">Healthcare Providers Master List</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" id="providersSearch" class="form-control" placeholder="Search for a Healthcare Provider's name...">
                        <button class="btn btn-primary" onclick="searchProviders()"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <button class="btn btn-primary" onclick="addHealthcareProvider()">
                    <i class="fas fa-plus"></i> Add Healthcare Provider
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="healthcareProviderTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Receptionists Section -->
        <section id="receptionists" class="content-section">
            <h3 class="mb-4">Receptionists Master List</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" id="receptionistsSearch" class="form-control" placeholder="Search for a Receptionist's name...">
                        <button class="btn btn-primary" onclick="searchReceptionists()"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <button class="btn btn-primary" onclick="addReceptionist()">
                    <i class="fas fa-plus"></i> Add Receptionist
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="receptionistTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Patients Section -->
        <section id="patients" class="content-section">
            <h3 class="mb-4">Patients Master List</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" id="patientsSearch" class="form-control" placeholder="Search for a Patient's name...">
                        <button class="btn btn-primary" onclick="searchPatients()"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
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
                            <th>ID</th>
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

        <!-- Appointments Section -->
        <section id="appointments" class="content-section">
            <h3 class="mb-4">Appointments Master List</h3>
            
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

            <div class="mb-3">
                <button class="btn btn-info"><i class="fas fa-eye"></i> View</button>
                <button class="btn btn-success"><i class="fas fa-check"></i> Approve</button>
                <button class="btn btn-danger"><i class="fas fa-times"></i> Cancel</button>
                <button class="btn btn-secondary"><i class="fas fa-clock"></i> Reschedule</button>
                <button class="btn btn-primary"><i class="fas fa-envelope"></i> Send Email</button>
                <button class="btn btn-dark"><i class="fas fa-file-alt"></i> Generate Report</button>
                <button class="btn btn-primary float-end"><i class="fas fa-plus"></i> New Appointment</button>
            </div>

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
    </main>
    
    <!-- External Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>
    <script src="/scripts/external/aos.js"></script>

    <!-- Major Scripts -->
    <script src="/scripts/dashboard/dashboard_all.js"></script>
    <script src="/scripts/dashboard/dashboard_admin.js"></script>
    <script src="/scripts/dashboard/fetch-update_userinfo.js"></script>
    <script src="/scripts/dashboard/reset-to-default_websiteconfig.js"></script>

    <!-- Minor Scripts -->
    <script src="/scripts/bootstrap-tooltip.js"></script>
    <script src="/scripts/credentials/auth-clearcookies.js"></script>

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
