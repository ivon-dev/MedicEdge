<?php 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/dashboard/dashboard_onload_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MedicEdge patient dashboard">
    <title>Patient Dashboard - MedicEdge</title>

    <link href="/styles/external/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/styles/pages-styles/dashboard_styles.css" rel="stylesheet">
    
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
</head>
<body>
    <nav class="sidebar">
        <!-- Profile Section -->
        <div class="profile-section">
            <img src="profile.jpg" draggable="false">
            <h5 class="mt-2">John Doe</h5>
        </div>

        <!-- Navigation Links -->
        <div class="mt-4 nav-links">
            <a href="#" data-section="dashboard" class="active"><i class="fas fa-home me-2"></i> Dashboard</a>
            <a href="#" data-section="appointments"><i class="fas fa-calendar-alt me-2"></i> Appointments</a>
            <a href="#" data-section="records"><i class="fas fa-file-medical me-2"></i> Medical Records</a>
            <a href="#" data-section="prescriptions"><i class="fas fa-pills me-2"></i> Prescriptions</a>
            <a href="#" data-section="profile"><i class="fas fa-user me-2"></i> Profile</a>
            <a href="/php/credentials/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>

        <!-- Logo Section -->
        <div class="logo-section">
            <img src="/img/dashboard/banner.png" alt="MedicEdge Logo">
            <p>MedicEdge</p>
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

    <!-- Internal Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
