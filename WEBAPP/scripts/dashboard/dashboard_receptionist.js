document.addEventListener('DOMContentLoaded', function() {
    // Initial load of patients with empty search term
    loadUsers('getPatients', 'patientTableBody', '');

    // Setup search input event listener
    const searchInput = document.getElementById('patientsSearch');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchInput.searchTimeout);
            searchInput.searchTimeout = setTimeout(() => {
                searchPatients();
            }, 300);
        });
    }
    
    loadMedicalRecords();
    loadAppointments();
});

function searchPatients() {
    const searchTerm = document.getElementById('patientsSearch').value;
    loadUsers('getPatients', 'patientTableBody', searchTerm);
}

function loadUsers(action, tableId, searchTerm) {
    let url = `/php/dashboard/dashboard_receptionist.php?action=${action}`;
    if (searchTerm !== '') {
        url += `&search=${encodeURIComponent(searchTerm)}`;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                populateTable(data.users, tableId);
            } else {
                console.error('Error loading users:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function populateTable(users, tableId) {
    const tableBody = document.getElementById(tableId);
    tableBody.innerHTML = ''; // Clear existing data

    users.forEach(user => {
        let row = tableBody.insertRow();
        row.insertCell(0).innerHTML = `<img src="/${user.picture}" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">`;
        row.insertCell(1).textContent = user.user_id;
        row.insertCell(2).textContent = user.fullname || user.username;
        row.insertCell(3).textContent = user.email;

        let actionsCell = row.insertCell(4);
        actionsCell.innerHTML = `
            <button class="btn btn-sm btn-info" onclick="editUser('${user.user_id}', 'patient')">Edit</button>
            <button class="btn btn-sm btn-danger" onclick="removeUser('${user.user_id}', 'patient')">Remove</button>
            <a class="btn btn-sm btn-info" href="mailto:${user.email}" target="_blank">Message</a>
        `;
    });
}

function addPatient() {
    Swal.fire({
        title: 'Add New Patient',
        html: `
            <input id="swal-input-username" class="swal2-input" placeholder="Username">
            <input id="swal-input-firstname" class="swal2-input" placeholder="First Name">
            <input id="swal-input-middlename" class="swal2-input" placeholder="Middle Name">
            <input id="swal-input-lastname" class="swal2-input" placeholder="Last Name">
            <input id="swal-input-password" class="swal2-input" placeholder="Password" type="password">
            <input id="swal-input-email" class="swal2-input" placeholder="Email" type="email">
            <input id="swal-input-contact" class="swal2-input" placeholder="Contact Number">
            <input id="swal-input-address" class="swal2-input" placeholder="Address">
        `,
        confirmButtonText: 'Add',
        focusConfirm: false,
        preConfirm: () => {
            return {
                username: document.getElementById('swal-input-username').value,
                firstname: document.getElementById('swal-input-firstname').value,
                middlename: document.getElementById('swal-input-middlename').value,
                lastname: document.getElementById('swal-input-lastname').value,
                password: document.getElementById('swal-input-password').value,
                email: document.getElementById('swal-input-email').value,
                contact_num: document.getElementById('swal-input-contact').value,
                address: document.getElementById('swal-input-address').value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const userData = result.value;
            fetch('/php/dashboard/dashboard_receptionist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=addPatient&username=${userData.username}&firstname=${userData.firstname}&middlename=${userData.middlename}&lastname=${userData.lastname}&password=${userData.password}&email=${userData.email}&contact_num=${userData.contact_num}&address=${userData.address}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added!',
                        text: 'New patient has been added.',
                        showConfirmButton: false,
                        timer: 1850,
                        allowOutsideClick: false
                    }).then(() => {
                        loadUsers('getPatients', 'patientTableBody', '');
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        'There was an error adding the patient.',
                        'error'
                    );
                }
            });
        }
    });
}

function removeUser(userId, userType) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action will move the patient's data to archive. You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/php/dashboard/dashboard_receptionist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=removeUser&user_id=${userId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'User has been deleted and data has been archived.',
                        showConfirmButton: false,
                        timer: 1850,
                        allowOutsideClick: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        data.message || 'There was an error deleting the user.',
                        'error'
                    );
                }
            });
        }
    });
}

function addUser(userType) {
    let action = '';
    switch (userType) {
        case 'patient':
            action = 'addPatient';
            break;
        default:
            console.error('Invalid user type:', userType);
            return;
    }

    Swal.fire({
        title: 'Add New ' + userType.charAt(0).toUpperCase() + userType.slice(1),
        html: `
            <input id="swal-input-username" class="swal2-input" placeholder="Username">
            <input id="swal-input-firstname" class="swal2-input" placeholder="First Name">
            <input id="swal-input-middlename" class="swal2-input" placeholder="Middle Name">
            <input id="swal-input-lastname" class="swal2-input" placeholder="Last Name">
            <input id="swal-input-password" class="swal2-input" placeholder="Password" type="password">
            <input id="swal-input-email" class="swal2-input" placeholder="Email" type="email">
            <input id="swal-input-contact" class="swal2-input" placeholder="Contact Number">
            <input id="swal-input-address" class="swal2-input" placeholder="Address">
        `,
        confirmButtonText: 'Add',
        focusConfirm: false,
        preConfirm: () => {
            return {
                username: document.getElementById('swal-input-username').value,
                firstname: document.getElementById('swal-input-firstname').value,
                middlename: document.getElementById('swal-input-middlename').value,
                lastname: document.getElementById('swal-input-lastname').value,
                password: document.getElementById('swal-input-password').value,
                email: document.getElementById('swal-input-email').value,
                contact_num: document.getElementById('swal-input-contact').value,
                address: document.getElementById('swal-input-address').value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const userData = result.value;
            fetch('/php/dashboard/dashboard_receptionist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=${action}&username=${userData.username}&firstname=${userData.firstname}&middlename=${userData.middlename}&lastname=${userData.lastname}&password=${userData.password}&email=${userData.email}&contact_num=${userData.contact_num}&address=${userData.address}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({ // Added properties
                        icon: 'success',
                        title: 'Added!',
                        text: 'New ' + userType + ' has been added.',
                        showConfirmButton: false,
                        timer: 1850,
                        allowOutsideClick: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        'There was an error adding the ' + userType + '.',
                        'error'
                    );
                }
            });
        }
    })
}

function editUser(userId, userType) {
    fetch('/php/dashboard/dashboard_receptionist.php?action=getUser&user_id=' + userId)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const user = data.user;
            Swal.fire({
                title: 'Edit ' + userType.charAt(0).toUpperCase() + userType.slice(1),
                html: `
                    <input id="swal-input-username" class="swal2-input" placeholder="Username" value="${user.username}">
                    <input id="swal-input-firstname" class="swal2-input" placeholder="First Name" value="${user.firstname}">
                    <input id="swal-input-middlename" class="swal2-input" placeholder="Middle Name" value="${user.middlename}">
                    <input id="swal-input-lastname" class="swal2-input" placeholder="Last Name" value="${user.lastname}">
                    <input id="swal-input-email" class="swal2-input" placeholder="Email" type="email" value="${user.email}">
                    <input id="swal-input-contact" class="swal2-input" placeholder="Contact Number" value="${user.contact_num}">
                    <input id="swal-input-address" class="swal2-input" placeholder="Address" value="${user.address}">
                `,
                confirmButtonText: 'Update',
                focusConfirm: false,
                preConfirm: () => {
                    return {
                        username: document.getElementById('swal-input-username').value,
                        firstname: document.getElementById('swal-input-firstname').value,
                        middlename: document.getElementById('swal-input-middlename').value,
                        lastname: document.getElementById('swal-input-lastname').value,
                        email: document.getElementById('swal-input-email').value,
                        contact_num: document.getElementById('swal-input-contact').value,
                        address: document.getElementById('swal-input-address').value
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const userData = result.value;
                    // TODO: Implement update functionality here
                    console.log('Update user:', userId, userData);
                    Swal.fire(
                        'Updated!',
                        'User information has been updated.',
                        'success'
                    ).then(() => {
                        loadUsers(userType, userType + 'TableBody');
                    });
                }
            });
        } else {
            Swal.fire(
                'Error!',
                'There was an error fetching user data.',
                'error'
            );
        }
    });
}

function searchMedicalRecords() {
    const searchTerm = document.getElementById('medicalRecordsSearch').value.toLowerCase();
    loadMedicalRecords(searchTerm);
}

function loadMedicalRecords(searchTerm = '') {
    fetch(`/php/dashboard/dashboard_receptionist.php?action=getMedicalRecords&search=${searchTerm}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            populateMedicalRecordsTable(data.patients);
        } else {
            console.error('Error fetching medical records:', data.message);
        }
    });
}

function populateMedicalRecordsTable(patients) {
    const tableBody = document.getElementById('medicalRecordsTableBody');
    tableBody.innerHTML = '';

    patients.forEach(patient => {
        let row = tableBody.insertRow();
        row.insertCell(0).innerHTML = `<img src="/${patient.picture}" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">`;
        row.insertCell(1).textContent = patient.username;
        row.insertCell(2).textContent = `${patient.firstname} ${patient.middlename} ${patient.lastname}`;
        
        let actionsCell = row.insertCell(3);
        actionsCell.innerHTML = `
            <button class="btn btn-sm btn-primary" onclick="editMedicalRecord('${patient.user_id}')">
                Edit Medical Record
            </button>
        `;
    });
}

function editMedicalRecord(userId) {
    fetch(`/php/dashboard/dashboard_receptionist.php?action=getMedicalRecord&user_id=${userId}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showMedicalRecordEditor(userId, data.medicalRecord);
        } else {
            Swal.fire('Error', 'Failed to load medical record', 'error');
        }
    });
}

function showMedicalRecordEditor(userId, medicalRecord) {
    Swal.fire({
        title: 'Edit Medical Record',
        html: `
            <div class="medical-record-editor">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" id="dateOfBirth" class="form-control" value="${medicalRecord['Date of Birth'] || ''}">
                    </div>
                    <div class="col">
                        <label class="form-label">Gender</label>
                        <select id="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="Male" ${medicalRecord.Gender === 'Male' ? 'selected' : ''}>Male</option>
                            <option value="Female" ${medicalRecord.Gender === 'Female' ? 'selected' : ''}>Female</option>
                            <option value="Other" ${medicalRecord.Gender === 'Other' ? 'selected' : ''}>Other</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Blood Type</label>
                        <select id="bloodType" class="form-control">
                            <option value="">Select Blood Type</option>
                            <option value="A+" ${medicalRecord['Blood Type'] === 'A+' ? 'selected' : ''}>A+</option>
                            <option value="A-" ${medicalRecord['Blood Type'] === 'A-' ? 'selected' : ''}>A-</option>
                            <option value="B+" ${medicalRecord['Blood Type'] === 'B+' ? 'selected' : ''}>B+</option>
                            <option value="B-" ${medicalRecord['Blood Type'] === 'B-' ? 'selected' : ''}>B-</option>
                            <option value="AB+" ${medicalRecord['Blood Type'] === 'AB+' ? 'selected' : ''}>AB+</option>
                            <option value="AB-" ${medicalRecord['Blood Type'] === 'AB-' ? 'selected' : ''}>AB-</option>
                            <option value="O+" ${medicalRecord['Blood Type'] === 'O+' ? 'selected' : ''}>O+</option>
                            <option value="O-" ${medicalRecord['Blood Type'] === 'O-' ? 'selected' : ''}>O-</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Height (cm)</label>
                        <input type="number" id="height" class="form-control" value="${medicalRecord.Height || ''}">
                    </div>
                    <div class="col">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" id="weight" class="form-control" value="${medicalRecord.Weight || ''}">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Allergies</label>
                    <textarea id="allergies" class="form-control" rows="2">${medicalRecord.Allergies || ''}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Chronic Conditions</label>
                    <textarea id="chronicConditions" class="form-control" rows="2">${medicalRecord['Chronic Conditions'] || ''}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Current Medications</label>
                    <textarea id="currentMedications" class="form-control" rows="2">${medicalRecord['Current Medications'] || ''}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Medical History</label>
                    <textarea id="medicalHistory" class="form-control" rows="3">${medicalRecord['Medical History'] || ''}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Surgical History</label>
                    <textarea id="surgicalHistory" class="form-control" rows="2">${medicalRecord['Surgical History'] || ''}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Family Medical History</label>
                    <textarea id="familyMedicalHistory" class="form-control" rows="2">${medicalRecord['Family Medical History'] || ''}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Emergency Contact</label>
                    <textarea id="emergencyContact" class="form-control" rows="2">${medicalRecord['Emergency Contact'] || ''}</textarea>
                </div>
            </div>
        `,
        width: '800px',
        showCancelButton: true,
        confirmButtonText: 'Save Changes',
        focusConfirm: false,
        preConfirm: () => {
            return {
                'Date of Birth': document.getElementById('dateOfBirth').value,
                'Gender': document.getElementById('gender').value,
                'Blood Type': document.getElementById('bloodType').value,
                'Height': document.getElementById('height').value,
                'Weight': document.getElementById('weight').value,
                'Allergies': document.getElementById('allergies').value,
                'Chronic Conditions': document.getElementById('chronicConditions').value,
                'Current Medications': document.getElementById('currentMedications').value,
                'Medical History': document.getElementById('medicalHistory').value,
                'Surgical History': document.getElementById('surgicalHistory').value,
                'Family Medical History': document.getElementById('familyMedicalHistory').value,
                'Emergency Contact': document.getElementById('emergencyContact').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            updateMedicalRecord(userId, result.value);
        }
    });
}

function updateMedicalRecord(userId, medicalRecord) {
    fetch('/php/dashboard/dashboard_receptionist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=updateMedicalRecord&user_id=${userId}&data=${JSON.stringify(medicalRecord)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire('Success', 'Medical record updated successfully', 'success');
        } else {
            Swal.fire('Error', 'Failed to update medical record', 'error');
        }
    });
}

function addAppointment() {
    // Fetch patients and healthcare providers first
    Promise.all([
        fetch('/php/dashboard/dashboard_receptionist.php?action=getPatientsList'),
        fetch('/php/dashboard/dashboard_receptionist.php?action=getHealthcareProvidersList')
    ])
    .then(responses => Promise.all(responses.map(r => r.json())))
    .then(([patientsData, providersData]) => {
        const patients = patientsData.users;
        const providers = providersData.users;

        const predefinedTypes = [
            'General Consultation',
            'Dental Check-up',
            'Follow-up Appointment',
            'Pediatric Consultation',
            'Vaccination Appointment',
            'Specialist Consultation',
            'Physical Therapy Session',
            'Mental Health Counseling',
            'Lab Test Appointment',
            'Maternity & Prenatal Check-up'
        ];

        Swal.fire({
            title: 'New Appointment',
            html: `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Select Patient</label>
                        <select id="patient-select" class="form-control">
                            <option value="">Choose a patient...</option>
                            ${patients.map(p => `<option value="${p.user_id}">${p.firstname} ${p.lastname}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Select Healthcare Provider</label>
                        <select id="provider-select" class="form-control">
                            <option value="">Choose a provider...</option>
                            ${providers.map(p => `<option value="${p.user_id}">${p.firstname} ${p.lastname}</option>`).join('')}
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Appointment Type</label>
                    <input list="type-options" id="type-input" class="form-control" placeholder="Enter or select type">
                    <datalist id="type-options">
                        ${predefinedTypes.map(type => `<option value="${type}">`).join('')}
                    </datalist>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Start Time</label>
                        <input type="datetime-local" id="time-start" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Time</label>
                        <input type="datetime-local" id="time-end" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Symptoms</label>
                    <textarea id="symptoms" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Diagnosis</label>
                    <textarea id="diagnosis" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Remarks</label>
                    <textarea id="remarks" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select id="status" class="form-control">
                        <option value="upcoming" selected>Upcoming</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Create Appointment',
            preConfirm: () => {
                const patient = document.getElementById('patient-select').value;
                const provider = document.getElementById('provider-select').value;
                const type = document.getElementById('type-input').value;
                const timeStart = document.getElementById('time-start').value;
                const timeEnd = document.getElementById('time-end').value;
                const symptoms = document.getElementById('symptoms').value;
                const diagnosis = document.getElementById('diagnosis').value;
                const remarks = document.getElementById('remarks').value;
                const status = document.getElementById('status').value;

                if (!patient || !provider || !type || !timeStart || !timeEnd) {
                    Swal.showValidationMessage('Please fill in all required fields');
                    return false;
                }

                return {
                    patient,
                    provider,
                    type,
                    timeStart,
                    timeEnd,
                    symptoms,
                    diagnosis,
                    remarks,
                    status
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new URLSearchParams(result.value);
                formData.append('action', 'addAppointment');

                fetch('/php/dashboard/dashboard_receptionist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Success', 'Appointment created successfully', 'success');
                        // Reload appointments list if needed
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    });
}

function loadAppointments(searchTerm = '') {
    fetch(`/php/dashboard/dashboard_receptionist.php?action=getAppointments&search=${searchTerm}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            populateAppointmentsTable(data.appointments);
        } else {
            console.error('Error fetching appointments:', data.message);
        }
    });
}

function populateAppointmentsTable(appointments) {
    const tableBody = document.getElementById('appointmentsTableBody');
    tableBody.innerHTML = '';

    appointments.forEach(appointment => {
        let row = tableBody.insertRow();
        
        // Format datetime for display without seconds
        const startTime = new Date(appointment.time_start).toLocaleString([], { 
            year: 'numeric',
            month: 'numeric',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        const endTime = new Date(appointment.time_end).toLocaleString([], {
            year: 'numeric',
            month: 'numeric',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        row.insertCell(0).textContent = `${startTime} - ${endTime}`;
        row.insertCell(1).textContent = appointment.patient_name;
        row.insertCell(2).textContent = appointment.provider_name;
        row.insertCell(3).textContent = appointment.type;

        let statusCell = row.insertCell(4);
        let statusClass = '';
        switch(appointment.status) {
            case 'upcoming':
                statusClass = 'bg-primary';
                break;
            case 'completed':
                statusClass = 'bg-success';
                break;
            case 'cancelled':
                statusClass = 'bg-danger';
                break;
        }
        statusCell.innerHTML = `<span class="badge ${statusClass}">${appointment.status}</span>`;

        let actionsCell = row.insertCell(5);
        actionsCell.innerHTML = `
            <button class="btn btn-sm btn-info" onclick="editAppointment('${appointment.id}')">Edit</button>
            <button class="btn btn-sm btn-danger" onclick="deleteAppointment('${appointment.id}')">Delete</button>
        `;
    });
}

function editAppointment(appointmentId) {
    // First fetch the appointment details
    fetch(`/php/dashboard/dashboard_receptionist.php?action=getAppointment&id=${appointmentId}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const appointment = data.appointment;
            
            // Fetch patients and providers for the dropdowns
            Promise.all([
                fetch('/php/dashboard/dashboard_receptionist.php?action=getPatientsList'),
                fetch('/php/dashboard/dashboard_receptionist.php?action=getHealthcareProvidersList')
            ])
            .then(responses => Promise.all(responses.map(r => r.json())))
            .then(([patientsData, providersData]) => {
                const patients = patientsData.users;
                const providers = providersData.users;

                const predefinedTypes = [
                    'General Consultation',
                    'Dental Check-up',
                    'Follow-up Appointment',
                    'Pediatric Consultation',
                    'Vaccination Appointment',
                    'Specialist Consultation',
                    'Physical Therapy Session',
                    'Mental Health Counseling',
                    'Lab Test Appointment',
                    'Maternity & Prenatal Check-up'
                ];

                // Format datetime-local value
                const startTime = new Date(appointment.time_start).toISOString().slice(0,16);
                const endTime = new Date(appointment.time_end).toISOString().slice(0,16);

                Swal.fire({
                    title: 'Edit Appointment',
                    html: `
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Patient</label>
                                <select id="patient-select" class="form-control">
                                    <option value="">Choose a patient...</option>
                                    ${patients.map(p => `<option value="${p.user_id}" ${p.user_id === appointment.patient ? 'selected' : ''}>${p.firstname} ${p.lastname}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Select Healthcare Provider</label>
                                <select id="provider-select" class="form-control">
                                    <option value="">Choose a provider...</option>
                                    ${providers.map(p => `<option value="${p.user_id}" ${p.user_id === appointment.healthcare_provider ? 'selected' : ''}>${p.firstname} ${p.lastname}</option>`).join('')}
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Appointment Type</label>
                            <input list="type-options" id="type-input" class="form-control" value="${appointment.type}" placeholder="Enter or select type">
                            <datalist id="type-options">
                                ${predefinedTypes.map(type => `<option value="${type}">`).join('')}
                            </datalist>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Start Time</label>
                                <input type="datetime-local" id="time-start" class="form-control" value="${startTime}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Time</label>
                                <input type="datetime-local" id="time-end" class="form-control" value="${endTime}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Symptoms</label>
                            <textarea id="symptoms" class="form-control" rows="2">${appointment.symptoms}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Diagnosis</label>
                            <textarea id="diagnosis" class="form-control" rows="2">${appointment.diagnosis}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea id="remarks" class="form-control" rows="2">${appointment.remarks}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select id="status" class="form-control">
                                <option value="upcoming" ${appointment.status === 'upcoming' ? 'selected' : ''}>Upcoming</option>
                                <option value="completed" ${appointment.status === 'completed' ? 'selected' : ''}>Completed</option>
                                <option value="cancelled" ${appointment.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                            </select>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Update Appointment',
                    preConfirm: () => {
                        const formData = {
                            id: appointmentId,
                            patient: document.getElementById('patient-select').value,
                            provider: document.getElementById('provider-select').value,
                            type: document.getElementById('type-input').value,
                            timeStart: document.getElementById('time-start').value,
                            timeEnd: document.getElementById('time-end').value,
                            symptoms: document.getElementById('symptoms').value,
                            diagnosis: document.getElementById('diagnosis').value,
                            remarks: document.getElementById('remarks').value,
                            status: document.getElementById('status').value
                        };

                        if (!formData.patient || !formData.provider || !formData.type || !formData.timeStart || !formData.timeEnd) {
                            Swal.showValidationMessage('Please fill in all required fields');
                            return false;
                        }

                        return formData;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new URLSearchParams(result.value);
                        formData.append('action', 'updateAppointment');

                        fetch('/php/dashboard/dashboard_receptionist.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire('Success', 'Appointment updated successfully', 'success');
                                loadAppointments();
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        });
                    }
                });
            });
        }
    });
}

function deleteAppointment(appointmentId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete this appointment?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/php/dashboard/dashboard_receptionist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=deleteAppointment&id=${appointmentId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Deleted!', 'Appointment has been deleted.', 'success');
                    loadAppointments();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }
    });
}

function searchAppointments() {
    const searchTerm = document.getElementById('appointmentsSearch').value.toLowerCase();
    loadAppointments(searchTerm);
}
