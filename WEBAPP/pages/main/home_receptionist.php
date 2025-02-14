<?php 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/dashboard_onload_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MedicEdge Receptionist Dashboard">
    <title>Receptionist Dashboard - MedicEdge</title>

    <link href="/styles/external/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/styles/pages-styles/dashboard_styles.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
</head>
<body>
    <nav class="sidebar">
        <div class="profile-section">
            <img src="receptionist_profile.jpg" draggable="false">
            <h5 class="mt-2">Receptionist Name</h5>
        </div>

        <div class="mt-4 nav-links">
            <a href="#" data-section="profile" class="active"><i class="fas fa-user me-2"></i> Your Profile</a>
            <a href="#" data-section="account"><i class="fas fa-key me-2"></i> Your Account</a>
            <a href="#" data-section="appointments"><i class="fas fa-calendar-check me-2"></i> Appointment Management</a>
            <a href="#" data-section="checkin"><i class="fas fa-clipboard-check me-2"></i> Patient Check-In</a>
            <a href="#" data-section="records"><i class="fas fa-folder-open me-2"></i> Patient Records</a>
            <a href="#" data-section="messaging"><i class="fas fa-envelope me-2"></i> Messaging</a>
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

        <!-- Appointment Management Section -->
        <section id="appointments" class="content-section">
            <h3 class="mb-4">Appointment Management</h3>
            
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
                            <h6>Checked In</h6>
                            <h3>12</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6>Waiting</h6>
                            <h3>8</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6>Upcoming</h6>
                            <h3>4</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <button class="btn btn-primary"><i class="fas fa-plus"></i> New Appointment</button>
                    <button class="btn btn-warning"><i class="fas fa-clock"></i> Reschedule</button>
                    <button class="btn btn-danger"><i class="fas fa-times"></i> Cancel</button>
                    <button class="btn btn-success"><i class="fas fa-check"></i> Confirm</button>
                    <button class="btn btn-info"><i class="fas fa-envelope"></i> Send Reminder</button>
                </div>
            </div>

            <!-- Appointment List -->
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
                    <tbody>
                        <tr>
                            <td>09:00 AM</td>
                            <td>John Doe</td>
                            <td>Dr. Smith</td>
                            <td>Check-up</td>
                            <td><span class="badge bg-success">Confirmed</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">Check-in</button>
                                <button class="btn btn-sm btn-warning">Reschedule</button>
                                <button class="btn btn-sm btn-danger">Cancel</button>
                            </td>
                        </tr>
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

        <!-- Messaging Section -->
        <section id="messaging" class="content-section">
            <h3 class="mb-4">Messaging</h3>
            
            <!-- New Message -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">New Message</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">To</label>
                            <select class="form-select">
                                <option>Select Recipient</option>
                                <optgroup label="Patients">
                                    <option>John Doe</option>
                                    <option>Jane Smith</option>
                                </optgroup>
                                <optgroup label="Healthcare Providers">
                                    <option>Dr. Smith</option>
                                    <option>Dr. Johnson</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>

            <!-- Message History -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recent Messages</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>From/To</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2024-02-13</td>
                                    <td>Dr. Smith</td>
                                    <td>Schedule Change Request</td>
                                    <td><span class="badge bg-success">Read</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View</button>
                                        <button class="btn btn-sm btn-primary">Reply</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

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
