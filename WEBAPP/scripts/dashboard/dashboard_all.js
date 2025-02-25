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
            document.getElementById('address').value = data['address'] || ''; // Add this line
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
                const addressSelector = isCustomizationForm ? 'address' : 'address';
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
                    'address': document.getElementById(addressSelector)?.value || '',
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
});

// SIDEBAR
document.querySelectorAll('.sidebar a[data-section]').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        document.querySelectorAll('.sidebar a').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
        link.classList.add('active');
        document.getElementById(link.dataset.section).classList.add('active');
    });
});

// BOOTSTRAP TOOLTIP
document.addEventListener("DOMContentLoaded", function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});