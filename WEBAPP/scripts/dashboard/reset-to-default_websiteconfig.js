document.getElementById('reset-to-default').addEventListener('click', function() {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will reset all website settings to default!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, reset it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/php/website_config/fetch-update_websiteconfig.php', {
                method: 'POST',
                body: new URLSearchParams({ reset: 'true' }) // Send reset as URL-encoded data
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.success,
                        showConfirmButton: false,
                        timer: 1850,
                        allowOutsideClick: false
                    }).then(() => {
                        location.reload(); // Refresh the page to reflect changes
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error,
                    });
                }
            })
            .catch(error => {
                console.error('Error resetting configuration:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to reset configuration. Please check the console.',
                });
            });
        }
    });
});