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
document.addEventListener('DOMContentLoaded', () => {
    // Handle initial load based on URL hash
    const hash = window.location.hash || '#account';
    const sectionId = hash.substring(1);
    activateSection(sectionId);

    // Update navigation when hash changes
    window.addEventListener('hashchange', () => {
        const hash = window.location.hash || '#account';
        const sectionId = hash.substring(1);
        activateSection(sectionId);
    });
});

function activateSection(sectionId) {
    // Remove active class from all links and sections
    document.querySelectorAll('.sidebar a').forEach(link => link.classList.remove('active'));
    document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));
    
    // Add active class to current link and section
    const currentLink = document.querySelector(`.sidebar a[href="#${sectionId}"]`);
    const currentSection = document.getElementById(sectionId);
    
    if (currentLink && currentSection) {
        currentLink.classList.add('active');
        currentSection.classList.add('active');
        
        // Scroll section into view smoothly
        currentSection.scrollIntoView({ behavior: 'smooth' });
    } else {
        // Fallback to account section if the requested section doesn't exist
        const accountLink = document.querySelector('.sidebar a[href="#account"]');
        const accountSection = document.getElementById('account');
        if (accountLink && accountSection) {
            accountLink.classList.add('active');
            accountSection.classList.add('active');
        }
    }
}

// Remove the duplicate event listeners
document.querySelectorAll('.sidebar a[href^="#"]').forEach(link => {
    link.addEventListener('click', (e) => {
        // Let the default behavior handle the navigation
        const sectionId = link.getAttribute('href').substring(1);
        activateSection(sectionId);
    });
});

// Function to get user-friendly browser/OS info
function parseUserAgent(userAgent) {
    let browserName = "Unknown";
    let osName = "Unknown";

    // Detect OS
    if (userAgent.includes("Windows")) osName = "Windows";
    else if (userAgent.includes("Mac")) osName = "MacOS";
    else if (userAgent.includes("Linux")) osName = "Linux";
    else if (userAgent.includes("Android")) osName = "Android";
    else if (userAgent.includes("iOS")) osName = "iOS";

    // Detect Browser
    if (userAgent.includes("Firefox/")) browserName = "Firefox";
    else if (userAgent.includes("Chrome/")) browserName = "Chrome";
    else if (userAgent.includes("Safari/")) browserName = "Safari";
    else if (userAgent.includes("Edge/")) browserName = "Edge";
    else if (userAgent.includes("Opera/")) browserName = "Opera";

    return `${browserName} on ${osName}`;
}

function formatDate(dateString) {
    // Convert UTC date string to local timezone and format
    const date = new Date(dateString + 'Z'); // Append 'Z' to treat the input as UTC
    return new Intl.DateTimeFormat(undefined, {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
        timeZoneName: 'short'
    }).format(date);
}

// Handle session history button click
document.addEventListener('DOMContentLoaded', () => {
    const sessionHistoryBtn = document.querySelector('button:contains("Session History")');
    if (sessionHistoryBtn) {
        sessionHistoryBtn.addEventListener('click', showSessionHistory);
    }
});

async function showSessionHistory() {
    const sessionToken = document.cookie.split('; ')
        .find(row => row.startsWith('session_token='))
        ?.split('=')[1];

    if (!sessionToken) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No active session found'
        });
        return;
    }

    try {
        const response = await fetch('/php/dashboard/dashboard_all.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                type: 'session',  // Add this line
                action: 'get_history',
                session_token: sessionToken
            })
        });

        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        let tableHtml = `
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Status</th>
                            <th>IP Address</th>
                            <th>Client Info</th>
                            <th>Raw User Agent</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        data.sessions.forEach(session => {
            const now = new Date();
            const expireTime = new Date(session.expire_time + 'Z'); // Add Z to treat as UTC
            const status = now > expireTime ? 'Expired' : 'Active';
            const statusClass = status === 'Active' ? 'text-success' : 'text-danger';
            const isCurrentSession = session.is_current;

            tableHtml += `
                <tr ${isCurrentSession ? 'class="table-primary"' : ''}>
                    <td>${formatDate(session.login_time)}</td>
                    <td class="${statusClass}">${status}${isCurrentSession ? ' (Current)' : ''}</td>
                    <td>${session.ip_address}</td>
                    <td>${parseUserAgent(session.user_agent)}</td>
                    <td><small>${session.user_agent}</small></td>
                    <td>
                        ${isCurrentSession ? 
                            `<button class="btn btn-danger btn-sm" onclick="window.location.href='/php/credentials/logout.php'">Logout</button>` 
                            : 
                            `${status === 'Active' ? 
                                `<button class="btn btn-warning btn-sm" onclick="revokeSession('${session.id}')">Revoke</button> ` 
                                : ''
                            }
                            <button class="btn btn-danger btn-sm" onclick="deleteSession('${session.id}')">Delete</button>`
                        }
                    </td>
                </tr>
            `;
        });

        tableHtml += `
                    </tbody>
                </table>
            </div>
        `;

        Swal.fire({
            title: 'Session History',
            html: tableHtml,
            width: '80%',
            customClass: {
                popup: 'swal2-wide',
                content: 'p-0'
            }
        });

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to fetch session history'
        });
    }
}

async function revokeSession(sessionId) {
    try {
        const response = await fetch('/php/dashboard/dashboard_all.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                type: 'session',  // Add this line
                action: 'revoke_session',
                session_id: sessionId
            })
        });

        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        Swal.fire({
            icon: 'success',
            title: 'Session Revoked',
            text: 'The session has been revoked successfully'
        }).then(() => {
            showSessionHistory();
        });

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to revoke session'
        });
    }
}

async function deleteSession(sessionId) {
    try {
        const response = await fetch('/php/dashboard/dashboard_all.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                type: 'session',  // Add this line
                action: 'delete_session',
                session_id: sessionId
            })
        });

        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        Swal.fire({
            icon: 'success',
            title: 'Session Deleted',
            text: 'The session has been deleted successfully'
        }).then(() => {
            showSessionHistory();
        });

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to delete session'
        });
    }
}