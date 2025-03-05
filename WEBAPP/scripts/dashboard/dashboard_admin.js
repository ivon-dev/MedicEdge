document.addEventListener('DOMContentLoaded', function() {
    // Modified these calls to use the new loadUsers function with correct actions
    loadUsers('getAdmins', 'adminTableBody', '');         // Empty string for no search filter
    loadUsers('getHealthcareProviders', 'healthcareProviderTableBody', '');
    loadUsers('getReceptionists', 'receptionistTableBody', '');
    loadUsers('getPatients', 'patientTableBody', '');

    // Handle both forms
    ['website-settings-form', 'website-customization-form'].forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', async function(event) {
                event.preventDefault();

                console.log(`Form ID: ${formId} submitted`);

                const formData = new FormData(this);

                // Determine which form is being submitted
                const isCustomizationForm = formId === 'website-customization-form';

                // Selectors for the fields
                const orgNameSelector = isCustomizationForm ? 'org-name' : 'org-name';
                const homepageTitleSelector = isCustomizationForm ? 'homepage-title' : 'homepage-title';
                const heroTextTitleSelector = isCustomizationForm ? 'hero-text-title' : 'hero-text-title';
                const heroTextSubtitleSelector = isCustomizationForm ? 'hero-text-subtitle' : 'hero-text-subtitle';
                const contactEmailSelector = isCustomizationForm ? 'contact-email' : 'contact-email';
                const contactNumSelector = isCustomizationForm ? 'contact-num' : 'contact-num';
                const addressSelector = isCustomizationForm ? 'org-address' : 'org-address';
                const adActiveUsersSelector = isCustomizationForm ? 'ad-active-users' : 'ad-active-users';
                const adHealthcarePartnersSelector = isCustomizationForm ? 'ad-healthcare-partners' : 'ad-healthcare-partners';
                const adUptimeSelector = isCustomizationForm ? 'ad-uptime' : 'ad-uptime';
                const adSupportSelector = isCustomizationForm ? 'ad-support' : 'ad-support';
                const sloganFooterSelector = isCustomizationForm ? 'slogan-footer' : 'slogan-footer';
                const colorDashboardSelector = isCustomizationForm ? 'color-dashboard' : 'color-dashboard';
                const legalPagesRevisionDateSelector = isCustomizationForm ? 'legal-pages-revision-date' : 'legal-pages-revision-date';

                // Create a config object to hold the non-file data
                const config = {
                    'org-name': document.getElementById(orgNameSelector)?.value || '',
                    'homepage-title': document.getElementById(homepageTitleSelector)?.value || '',
                    'hero-text-title': document.getElementById(heroTextTitleSelector)?.value || '',
                    'hero-text-subtitle': document.getElementById(heroTextSubtitleSelector)?.value || '',
                    'contact-email': document.getElementById(contactEmailSelector)?.value || '',
                    'contact-num': document.getElementById(contactNumSelector)?.value || '',
                    'org-address': document.getElementById(addressSelector)?.value || '', // Changed from 'address' to 'org-address'
                    'ad-active-users': document.getElementById(adActiveUsersSelector)?.value || '',
                    'ad-healthcare-partners': document.getElementById(adHealthcarePartnersSelector)?.value || '',
                    'ad-uptime': document.getElementById(adUptimeSelector)?.value || '',
                    'ad-support': document.getElementById(adSupportSelector)?.value || '',
                    'slogan-footer': document.getElementById(sloganFooterSelector)?.value || '',
                    'color-dashboard': document.getElementById(colorDashboardSelector)?.value || '',
                    'legal-pages-revision-date': document.getElementById(legalPagesRevisionDateSelector)?.value || ''
                };

                console.log('Config being sent:', config);
                formData.append('config', JSON.stringify(config));

                fetch('/php/website_config/fetch-update_websiteconfig.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(data => {
                    try {
                        const jsonData = JSON.parse(data);
                        if (jsonData.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: jsonData.success,
                                showConfirmButton: false,
                                timer: 1850,
                                allowOutsideClick: false
                            }).then(() => {
                                location.reload();
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
                        console.log('Raw response data:', data);
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
        } else {
            console.warn(`Form with ID ${formId} not found.`);
        }
    });

    const searchInputs = {
        'adminSearch': searchAdmins,
        'providersSearch': searchProviders,
        'receptionistsSearch': searchReceptionists,
        'patientsSearch': searchPatients
    };

    for (const [inputId, searchFunction] of Object.entries(searchInputs)) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', () => {
                clearTimeout(input.searchTimeout);
                input.searchTimeout = setTimeout(searchFunction, 300);
            });
        }
    }
});

// Search functions modified to handle empty search
function searchAdmins() {
    const searchTerm = document.getElementById('adminSearch').value;
    loadUsers('getAdmins', 'adminTableBody', searchTerm);
}

function searchProviders() {
    const searchTerm = document.getElementById('providersSearch').value;
    loadUsers('getHealthcareProviders', 'healthcareProviderTableBody', searchTerm);
}

function searchReceptionists() {
    const searchTerm = document.getElementById('receptionistsSearch').value;
    loadUsers('getReceptionists', 'receptionistTableBody', searchTerm);
}

function searchPatients() {
    const searchTerm = document.getElementById('patientsSearch').value;
    loadUsers('getPatients', 'patientTableBody', searchTerm);
}

// Modified loadUsers function to properly handle empty search terms
function loadUsers(action, tableId, searchTerm) {
    let url = `/php/dashboard/dashboard_admin.php?action=${action}`;
    if (searchTerm !== '') {
        url += `&search=${encodeURIComponent(searchTerm)}`;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                populateTable(data.users, tableId, action);
            } else {
                console.error('Error loading users:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function populateTable(users, tableId, userType) {
    const tableBody = document.getElementById(tableId);
    tableBody.innerHTML = ''; // Clear existing data

    // Get the user_id from the session token (cookie)
    const sessionToken = document.cookie.replace(/(?:(?:^|.*;\s*)session_token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
    let loggedInUserId = null;
    if (sessionToken) {
        try {
            const decodedToken = JSON.parse(atob(sessionToken.split('.')[1]));
            loggedInUserId = decodedToken.user_id;
        } catch (e) {
            console.error("Error decoding session token:", e);
        }
    }

    users.forEach(user => {
        let row = tableBody.insertRow();
        row.insertCell(0).innerHTML = `<img src="/${user.picture}" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">`;
        row.insertCell(1).textContent = user.user_id;
        row.insertCell(2).textContent = user.fullname || user.username;
        row.insertCell(3).textContent = user.email;

        let actionsCell = row.insertCell(4);
        let removeButton = `<button class="btn btn-sm btn-danger" onclick="removeUser('${user.user_id}', '${userType}')">Remove</button>`;
        
        if (user.user_id === loggedInUserId) {
            removeButton = `<button class="btn btn-sm btn-secondary" disabled>Remove</button>`;
        }

        actionsCell.innerHTML = `
            <button class="btn btn-sm btn-info" onclick="editUser('${user.user_id}', '${userType}')">Edit</button>
            ${removeButton}
            <a class="btn btn-sm btn-info" href="mailto:${user.email}" target="_blank">Message</a>
        `;
    });
}

function removeUser(userId, userType) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/php/dashboard/dashboard_admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=removeUser&user_id=${userId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({ // Added properties
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'User has been deleted.',
                        showConfirmButton: false,
                        timer: 1850,
                        allowOutsideClick: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        'There was an error deleting the user.',
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
        case 'administrator':
            action = 'addAdmin';
            break;
        case 'healthcare_provider':
            action = 'addHealthcareProvider';
            break;
        case 'receptionist':
            action = 'addReceptionist';
            break;
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
            fetch('/php/dashboard/dashboard_admin.php', {
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

function addAdmin() {
    addUser('administrator');
}

function addHealthcareProvider() {
    addUser('healthcare_provider');
}

function addReceptionist() {
    addUser('receptionist');
}

function addPatient() {
    addUser('patient');
}

function editUser(userId, userType) {
    fetch('/php/dashboard/dashboard_admin.php?action=getUser&user_id=' + userId)
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
