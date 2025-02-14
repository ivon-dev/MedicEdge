<?php 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/dashboard_onload_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MedicEdge Healthcare Provider Dashboard">
    <title>Healthcare Provider Dashboard - MedicEdge</title>

    <link href="/styles/external/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/styles/pages-styles/dashboard_styles.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
</head>
<body>
    <nav class="sidebar">
        <div class="profile-section">
            <img src="provider_profile.jpg" draggable="false">
            <h5 class="mt-2">Healthcare Provider</h5>
        </div>

        <div class="mt-4 nav-links">
            <a href="#" data-section="profile" class="active"><i class="fas fa-user me-2"></i> Your Profile</a>
            <a href="#" data-section="account"><i class="fas fa-key me-2"></i> Your Account</a>
            <a href="#" data-section="appointments"><i class="fas fa-calendar-check me-2"></i> Assigned Appointments</a>
            <a href="#" data-section="patients"><i class="fas fa-notes-medical me-2"></i> Patient Records</a>
            <a href="#" data-section="messaging"><i class="fas fa-envelope me-2"></i> Messaging</a>
            <a href="#" data-section="availability"><i class="fas fa-clock me-2"></i> Availability</a>
            <a href="/php/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>

        <!-- Logo Section -->
        <div class="logo-section">
            <img src="/img/dashboard/banner.png" alt="MedicEdge Logo">
            <p>MedicEdge</p>
        </div>
    </nav>

    <main class="content">
        <!-- Profile Section -->
        <section id="profile" class="content-section active">
            <h3 class="mb-4">Your Profile</h3>
            <form>
                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="tel" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Save Changes</button>
            </form>
        </section>

        <!-- Account Section -->
        <section id="account" class="content-section">
            <h3 class="mb-4">Your Account</h3>
            <button class="btn btn-primary">Change Password</button>
            <button class="btn btn-secondary">View Session History</button>
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
