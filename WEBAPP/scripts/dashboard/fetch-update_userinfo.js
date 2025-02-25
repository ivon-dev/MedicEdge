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
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'An unknown error occurred',
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
    const fields = ['username', 'firstname', 'middlename', 'lastname', 'email', 'contact_num', 'address'];
    fields.forEach(field => {
        const input = document.querySelector(`input[name="${field}"]`);
        if (input) {
            input.value = data[field] || '';
        }
        const element = document.getElementById(`${field}`);
            if (element) {
                element.value = data[field] || '';
            }
    });
}

function uploadProfilePicture(file) {
    const formData = new FormData();
    formData.append('action', 'upload_profile_picture');
    formData.append('profile_picture', file);

    fetch('/php/dashboard/fetch-update_userinfo.php', {
        method: 'POST',
        body: formData,
        credentials: 'include'
    })
    .then(response => response.text()) // Get raw response as text first
    .then(text => {
        console.log("Raw response:", text); // Log raw response

        try {
            let data = JSON.parse(text); // Attempt to parse JSON
            console.log("Parsed JSON:", data); // Log parsed JSON

            if (data.status === 'success') {
                document.querySelector('.profile-section img').src = '/' + data.filepath;
                document.getElementById('current-profile-picture').src = '/' + data.filepath; // Update the second profile picture
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Profile picture updated successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message || 'Failed to upload profile picture');
            }
        } catch (error) {
            console.error("JSON parsing error:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Invalid server response. Please try again.',
                confirmButtonColor: '#3085d6'
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

function showProfilePictureGallery() {
    const modal = document.getElementById('gallery-modal');
    const grid = document.getElementById('gallery-grid');
    
    // Show loading
    grid.innerHTML = '<div class="col-span-3 text-center py-8">Loading...</div>';
    modal.classList.remove('d-none');
    modal.classList.add('flex');

    // Fetch gallery pictures
    const formData = new FormData();
    formData.append('action', 'get_profile_pictures');

    fetch('/php/dashboard/fetch-update_userinfo.php', {
        method: 'POST',
        body: formData,
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            grid.innerHTML = data.pictures.map(picPath => `
                <div class="relative group cursor-pointer aspect-square">
                    <img 
                        src="/${picPath}" 
                        alt="Profile picture" 
                        width="100px" 
                        height="100px" 
                        draggable="false" 
                        class="w-full h-full border border-2 border-dark object-cover rounded-lg transition-all duration-200 hover:opacity-75"
                        onclick="selectProfilePicture('${picPath}')"
                    >
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="bg-black bg-opacity-50 text-white px-3 py-1 rounded">Select</div>
                    </div>
                    <button class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded" onclick="deleteProfilePicture('${picPath}')">Delete</button>
                </div>
            `).join('');
        } else {
            grid.innerHTML = '<div class="col-span-3 text-center py-8 text-red-500">Failed to load images</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        grid.innerHTML = '<div class="col-span-3 text-center py-8 text-red-500">Failed to load images</div>';
    });
}

function closeGallery() {
    const modal = document.getElementById('gallery-modal');
    modal.classList.add('d-none');
    modal.classList.remove('flex');
}

function selectProfilePicture(picturePath) {
    const formData = new FormData();
    formData.append('action', 'update_profile_picture');
    formData.append('picture', picturePath);

    fetch('/php/dashboard/fetch-update_userinfo.php', {
        method: 'POST',
        body: formData,
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Update both profile pictures on the page
            const profilePics = document.querySelectorAll('.profile-section img');
            profilePics.forEach(pic => pic.src = '/' + picturePath);
            document.getElementById('current-profile-picture').src = '/' + picturePath;
            
            // Close gallery
            closeGallery();
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Profile picture updated successfully',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'An unknown error occurred',
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to update profile picture. Please try again.',
            confirmButtonColor: '#3085d6'
        });
    });
}

function deleteProfilePicture(picturePath) {
    const formData = new FormData();
    formData.append('action', 'delete_profile_picture');
    formData.append('picture', picturePath);

    fetch('/php/dashboard/fetch-update_userinfo.php', {
        method: 'POST',
        body: formData,
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showProfilePictureGallery(); // Refresh the gallery
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Picture deleted successfully',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to delete picture: ' + data.message,
                confirmButtonColor: '#3085d6'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to delete picture. Please try again.',
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
                text: data.message || 'Profile updated successfully',
                timer: 1500,
                showConfirmButton: false
            });
            fetchProfileData(); // Refresh the display
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'An unknown error occurred',
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