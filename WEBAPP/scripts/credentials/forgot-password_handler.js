document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!this.checkValidity()) {
        e.stopPropagation();
        this.classList.add('was-validated');
        return;
    }

    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const inputs = this.querySelectorAll('input');
    
    submitButton.disabled = true;
    inputs.forEach(input => input.disabled = true);

    fetch('/php/credentials/forgot-password_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error('Invalid server response. Please contact administrator for help.');
            }
        });
    })
    .then(data => {
        if (data.success) {
            // Show code input popup
            Swal.fire({
                title: 'Enter Reset Code',
                input: 'text',
                inputLabel: 'Check your email for the reset code',
                inputPlaceholder: 'Enter 8-character code',
                showCancelButton: true,
                confirmButtonText: 'Verify Code',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Please enter the reset code!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Verify the code
                    return fetch('/php/credentials/forgot-password_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=verify_code&code=${result.value}`
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text().then(text => {
                            try {
                                return JSON.parse(text);
                            } catch (e) {
                                throw new Error('Invalid server response. Please contact administrator for help.');
                            }
                        });
                    });
                }
            }).then(verifyResult => {
                if (verifyResult && verifyResult.success) {
                    // Show password reset popup
                    return Swal.fire({
                        title: 'Set New Password',
                        html: `
                            <input type="password" id="password" class="swal2-input" placeholder="New Password">
                            <input type="password" id="confirmPassword" class="swal2-input" placeholder="Confirm Password">
                        `,
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: 'Reset Password',
                        preConfirm: () => {
                            const password = document.getElementById('password').value;
                            const confirmPassword = document.getElementById('confirmPassword').value;
                            if (!password || !confirmPassword) {
                                Swal.showValidationMessage('Please fill in all fields');
                                return false;
                            }
                            if (password !== confirmPassword) {
                                Swal.showValidationMessage('Passwords do not match');
                                return false;
                            }
                            return { password: password };
                        }
                    });
                } else {
                    throw new Error(verifyResult.message || 'Invalid code');
                }
            }).then(result => {
                if (result.isConfirmed) {
                    // Update password
                    return fetch('/php/credentials/forgot-password_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=update_password&password=${result.value.password}`
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text().then(text => {
                            try {
                                return JSON.parse(text);
                            } catch (e) {
                                throw new Error('Invalid server response. Please contact administrator for help.');
                            }
                        });
                    });
                }
            }).then(updateResult => {
                if (updateResult && updateResult.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Updated!',
                        text: 'You will be redirected to login page.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/pages/auth/login.php';
                    });
                }
            }).catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'An error occurred'
                });
            });
        } else {
            throw new Error(data.message || 'Email not found');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'An unexpected error occurred'
        });
    })
    .finally(() => {
        submitButton.disabled = false;
        inputs.forEach(input => input.disabled = false);
    });
});