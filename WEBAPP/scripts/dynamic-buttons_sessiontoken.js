document.addEventListener('DOMContentLoaded', function() {
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    const sessionToken = getCookie('session_token');

    if (sessionToken) {
        // Hero section modification
        const heroLink = document.querySelector('.hero-section a');
        if (heroLink) {
            heroLink.href = '/pages/main/redirect.php';
            const heroButton = heroLink.querySelector('button');
            if (heroButton) {
                heroButton.textContent = 'Go to your Dashboard';
            }
        }

        // Navbar register link modification
        const navbarLink = document.querySelector('a[href="/pages/auth/register_as_admin.php"]');
        if (navbarLink) {
            navbarLink.href = '/pages/main/redirect.php';
            navbarLink.textContent = 'Go to your Dashboard';
        }

        // Hide login link
        const loginLink = document.querySelector('a[href="/pages/auth/login.php"]');
        if (loginLink) {
            const loginLinkItem = loginLink.closest('.nav-item');
            if (loginLinkItem) {
                loginLinkItem.style.display = 'none';
            }
        }
    }
});
