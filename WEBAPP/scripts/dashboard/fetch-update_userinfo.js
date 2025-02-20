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
    const formData = new FormData();
    formData.append('action', 'fetch_profile');

    fetch('/php/dashboard/fetch-update_userinfo.php', {
        method: 'POST',
        body: formData,
        credentials: 'include'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            populateProfileForm(data.data);
        } else {
            console.error('Error:', data.message);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load profile data: ' + data.message,
                confirmButtonColor: '#3085d6'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load profile data. Please try again.',
            confirmButtonColor: '#3085d6'
        });
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
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Profile picture updated successfully',
                timer: 1500,
                showConfirmButton: false
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to upload profile picture. Please try again.',
            confirmButtonColor: '#3085d6'
        });
    });
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
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Profile updated successfully',
                timer: 1500,
                showConfirmButton: false
            });
            fetchProfileData(); // Refresh the display
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update profile: ' + data.message,
                confirmButtonColor: '#3085d6'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to update profile. Please try again.',
            confirmButtonColor: '#3085d6'
        });
    });
}