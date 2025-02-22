document.addEventListener('DOMContentLoaded', function() {
    // Function to check if a cookie exists
    function cookieExists(name) {
        return document.cookie.split('; ').some((cookie) => cookie.trim().startsWith(name + '='));
    }

    if (cookieExists('session_token')) {
        let heroLink = document.querySelector('.hero-section a');
        if (heroLink) {
            heroLink.href = '/pages/main/redirect.php';
            heroLink.querySelector('button').textContent = 'Go to your Dashboard';
        }

        let navbarLink = document.querySelector('.navbar-nav a[href="/pages/auth/register_as_admin.php"]');
        if (navbarLink) {
            navbarLink.href = '/pages/main/redirect.php';
            navbarLink.textContent = 'Go to your Dashboard';
        }

        let loginLinkItem = document.querySelector('.navbar-nav a[href="/pages/auth/login.php"]').closest('.nav-item');
        if (loginLinkItem) {
            loginLinkItem.style.display = 'none';
        }
    }
});
