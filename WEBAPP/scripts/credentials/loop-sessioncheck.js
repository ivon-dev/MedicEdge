function checkSession() {
    fetch('/php/api/userinfo.php', {
        method: 'GET',
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        if (data === "empty" || (data.session_expired === true)) {
            document.body.innerHTML = '';
            Swal.fire({
                title: 'Access Denied',
                text: 'You have been remotely logged out. Please login again.',
                icon: 'error',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then(() => {
                // Clear session token cookie with correct attributes
                document.cookie = "session_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; SameSite=Lax";
                window.location.href = '/pages/auth/login.php';
            });
        }
    })
    .catch(error => {
        console.error('Session check failed:', error);
    });
}

// Initial check
checkSession();

// Set up the interval for checking every 8 seconds
setInterval(checkSession, 8000);
