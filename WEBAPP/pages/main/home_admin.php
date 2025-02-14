<?php include('../../php/dashboard_onload_check.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MedicEdge Admin Dashboard">
    <title>Admin Dashboard - MedicEdge</title>

    <link href="/styles/external/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" >
    <link href="/styles/pages-styles/dashboard_styles.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
</head>
<body>
    <nav class="sidebar">
        <div class="profile-section">
            <img src="admin_profile.jpg" draggable="false">
            <h5 class="mt-2">Admin Name</h5>
        </div>

        <div class="mt-4 nav-links">
            <a href="#" data-section="profile" class="active"><i class="fas fa-user me-2"></i> Your Profile</a>
            <a href="#" data-section="account"><i class="fas fa-key me-2"></i> Your Account</a>
            <a href="#" data-section="personalization"><i class="fas fa-paint-brush me-2"></i> Personalization</a>
            <a href="#" data-section="administrators"><i class="fas fa-users-cog me-2"></i> Administrators</a>
            <a href="#" data-section="appointments"><i class="fas fa-calendar-check me-2"></i> Appointments</a>
            <a href="#" data-section="patients"><i class="fas fa-user-injured me-2"></i> Patients</a>
            <a href="#" data-section="healthcare_providers"><i class="fas fa-user-md me-2"></i> Healthcare Providers</a>
            <a href="#" data-section="receptionists"><i class="fas fa-concierge-bell me-2"></i> Receptionists</a>
            <a href="#" data-section="activity_logs"><i class="fas fa-clipboard-list me-2"></i> User Activity Logs</a>
            <a href="#" data-section="system_settings"><i class="fas fa-cogs me-2"></i> System Settings</a>
            <a href="#" data-section="reports"><i class="fas fa-chart-bar me-2"></i> Reports & Analytics</a>
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

        <!-- Personalization Section -->
        <section id="personalization" class="content-section">
            <h3 class="mb-4">Personalization</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Brand Settings</h5>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Upload Logo</label>
                                    <input type="file" class="form-control">
                                    <img src="current_logo.png" class="mt-2" style="height: 50px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hero Text</label>
                                    <textarea class="form-control" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Banner Picture</label>
                                    <input type="file" class="form-control">
                                    <img src="current_banner.jpg" class="mt-2 img-fluid">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Theme Customization</h5>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Primary Color</label>
                                    <input type="color" class="form-control form-control-color" value="#0d6efd">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Secondary Color</label>
                                    <input type="color" class="form-control form-control-color" value="#6c757d">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Font Family</label>
                                    <select class="form-select">
                                        <option>Arial</option>
                                        <option>Roboto</option>
                                        <option>Open Sans</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success">Apply Theme</button>
                                <button type="button" class="btn btn-secondary">Reset to Default</button>
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
                            <h3>5</h3>
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
