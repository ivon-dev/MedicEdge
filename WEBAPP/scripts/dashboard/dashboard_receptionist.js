function loadUsers(userType, tableId) {
    let action = '';
    switch (userType) {
        case 'patient':
            action = 'getPatients';
            break;
        default:
            console.error('Invalid user type:', userType);
            return;
    }

    fetch(`/php/dashboard/dashboard_receptionist.php?action=${action}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            populateTable(data.users, tableId, userType);
        } else {
            console.error('Error fetching ' + userType + 's:', data.message);
        }
    });
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

function addPatient() {
    addUser('patient');
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

document.addEventListener('DOMContentLoaded', function() {
    loadUsers('patient', 'patientTableBody');
});
