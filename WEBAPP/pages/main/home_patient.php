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
            <a href="#" data-section="dashboard" class="active"><i class="fas fa-home me-2"></i> Dashboard</a>
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
        <!-- Dashboard -->
        <section id="dashboard" class="content-section active">
            <h3 class="mb-4">Dashboard</h3>
            <div class="dashboard-grid">
                <div class="dashboard-item">
                    <i class="fas fa-calendar-check fa-2x mb-3 text-success"></i>
                    <h3 class="h5">Next Appointment</h3>
                    <p>Feb 15, 2025</p>
                </div>
                <div class="dashboard-item">
                    <i class="fas fa-pills fa-2x mb-3 text-success"></i>
                    <h3 class="h5">Active Prescriptions</h3>
                    <p>3 medications</p>
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
    <script src="/scripts/external/bootstrap.bundle.min.js"></script>

    <!-- External Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/scripts/dashboard/fetch-update_userinfo.js"></script>

    <!-- Internal Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
</body>
</html>
