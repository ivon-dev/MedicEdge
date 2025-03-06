document.addEventListener('DOMContentLoaded', function() {
    // Clear request_id cookie
    document.cookie = "request_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; SameSite=Lax;";
    // Clear resetpwd_id cookie
    document.cookie = "resetpwd_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; SameSite=Lax;";
});