document.addEventListener('DOMContentLoaded', function() {
    // Load profile data on page load
    fetchProfileData();

    // Set up form submission handler
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            updateProfile();
        });
    }

    // Set up profile picture upload handler
    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            uploadProfilePicture(e.target.files[0]);
        });
    }

    // Set up gallery viewer button
    const galleryButton = document.querySelector('button[type="button"]');
    if (galleryButton) {
        galleryButton.addEventListener('click', showProfilePictureGallery);
    }
});

function fetchProfileData() {
    fetch('/php/dashboard/fetch-update_userinfo.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'fetch_profile' }),
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: 'include' // Important: Include cookies in the request
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            populateProfileForm(data.data);
        } else {
            console.error('Error:', data.message);
            alert('Failed to load profile data: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to load profile data. Please try again.');
    });
}

function populateProfileForm(data) {
    // Update both profile pictures
    const profilePicPath = '/' + data.picture;
    document.querySelector('.profile-section img').src = profilePicPath;
    document.getElementById('current-profile-picture').src = profilePicPath;
    
    document.querySelector('.profile-section h5').textContent = 
        `${data.firstname} ${data.lastname}`;

    // Populate form fields
    const fields = ['firstname', 'middlename', 'lastname', 'email', 'contact_num', 'address'];
    fields.forEach(field => {
        const input = document.querySelector(`input[name="${field}"]`);
        if (input) {
            input.value = data[field] || '';
        }
    });
}

function uploadProfilePicture(file) {
    const formData = new FormData();
    formData.append('action', 'upload_profile_picture');
    formData.append('profile_picture', file);

    fetch('/php/dashboard/fetch-update_userinfo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.querySelector('.profile-section img').src = '/' + data.filepath;
        }
    })
    .catch(error => console.error('Error:', error));
}

function showProfilePictureGallery() {
    fetch('/php/dashboard/fetch-update_userinfo.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'get_profile_pictures' }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            createGalleryModal(data.pictures);
        }
    })
    .catch(error => console.error('Error:', error));
}

function createGalleryModal(pictures) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        ${pictures.map(pic => `
                            <div class="col-md-4 mb-3">
                                <img src="/${pic}" class="img-fluid cursor-pointer" 
                                     onclick="selectProfilePicture('${pic}')">
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    new bootstrap.Modal(modal).show();

    modal.addEventListener('hidden.bs.modal', function() {
        modal.remove();
    });
}

function selectProfilePicture(picturePath) {
    const conn = getDBConnection();
    const stmt = conn.prepare("UPDATE users_table SET picture = ? WHERE user_id = ?");
    
    fetch('/php/dashboard/fetch-update_userinfo.php', {
        method: 'POST',
        body: JSON.stringify({
            action: 'update_profile',
            picture: picturePath
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.querySelector('.profile-section img').src = '/' + picturePath;
            bootstrap.Modal.getInstance(document.querySelector('.modal')).hide();
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateProfile() {
    const formData = new FormData(document.querySelector('form'));
    formData.append('action', 'update_profile');

    fetch('/php/dashboard/fetch-update_userinfo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Profile updated successfully');
            fetchProfileData(); // Refresh the display
        }
    })
    .catch(error => console.error('Error:', error));
}