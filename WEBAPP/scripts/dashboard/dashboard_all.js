document.addEventListener('DOMContentLoaded', () => {
    // MAIN DASHBOARD CONTENT
    function displayImagePreview(input, previewId) {
        const preview = document.getElementById(previewId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }

    document.getElementById('org-logo').addEventListener('change', function() {
        displayImagePreview(this, 'org-logo-preview');
    });
    document.getElementById('favicon').addEventListener('change', function() {
        displayImagePreview(this, 'favicon-preview');
    });
    document.getElementById('navbar-logo').addEventListener('change', function() {
        displayImagePreview(this, 'navbar-logo-preview');
    });
    document.getElementById('homepage-hero-bg-image').addEventListener('change', function() {
        displayImagePreview(this, 'homepage-hero-bg-image-preview');
    });
    document.getElementById('login-register-banner').addEventListener('change', function() {
        displayImagePreview(this, 'login-register-banner-preview');
    });

    fetch('/php/website_config/fetch-update_websiteconfig.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.error,
            });
        } else {
            document.getElementById('org-name').value = data['org-name'] || '';
            document.getElementById('homepage-title').value = data['homepage-title'] || '';
            document.getElementById('hero-text-title').value = data['hero-text-title'] || '';
            document.getElementById('hero-text-subtitle').value = data['hero-text-subtitle'] || '';
            document.getElementById('contact-email').value = data['contact-email'] || '';
            document.getElementById('contact-num').value = data['contact-num'] || '';
            document.getElementById('address').value = data['address'] || '';
            document.getElementById('ad-active-users').value = data['ad-active-users'] || '';
            document.getElementById('ad-healthcare-partners').value = data['ad-healthcare-partners'] || '';
            document.getElementById('ad-uptime').value = data['ad-uptime'] || '';
            document.getElementById('ad-support').value = data['ad-support'] || '';
            document.getElementById('slogan-footer').value = data['slogan-footer'] || '';
            if (data['favicon']) {
                document.getElementById('favicon-preview').src = '/' + data['favicon'];
                document.getElementById('favicon-preview').style.display = 'block';
            }
            if (data['navbar-logo']) {
                document.getElementById('navbar-logo-preview').src = '/' + data['navbar-logo'];
                document.getElementById('navbar-logo-preview').style.display = 'block';
            }
            if (data['homepage-hero-bg-image']) {
                document.getElementById('homepage-hero-bg-image-preview').src = '/' + data['homepage-hero-bg-image'];
                document.getElementById('homepage-hero-bg-image-preview').style.display = 'block';
            }
            if (data['login-register-banner']) {
                document.getElementById('login-register-banner-preview').src = '/' + data['login-register-banner'];
                document.getElementById('login-register-banner-preview').style.display = 'block';
            }
            document.getElementById('color-dashboard').value = data['color-dashboard'] || '#0d6efd';
            document.getElementById('legal-pages-revision-date').value = data['legal-pages-revision-date'] || '';
        }
    })
    .catch(error => {
        console.error('Error fetching configuration:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Failed to fetch configuration. Please check the console.',
        });
    });
});

// PAGE SECTIONS IN SIDEBAR NAVBAR
document.querySelectorAll('.sidebar a[data-section]').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        document.querySelectorAll('.sidebar a').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
        link.classList.add('active');
        document.getElementById(link.dataset.section).classList.add('active');
    });
});