$(document).ready(function() {
    $('#login-form').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        const email = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            type: 'POST',
            url: 'php/login.php',
            data: {
                email: email,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Store the session token in localStorage
                    localStorage.setItem('session_token', response.token);
                    $('#message').html('<div class="alert alert-success">' + response.message + '</div>');
                    
                    // Redirect to profile page
                    setTimeout(function() {
                        window.location.href = 'profile.html';
                    }, 1500);
                } else {
                    $('#message').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#message').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
            }
        });
    });
});