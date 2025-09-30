$(document).ready(function() {
    $('#register-form').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        const email = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            type: 'POST',
            url: 'php/register.php',
            data: {
                email: email,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#message').html('<div class="alert alert-success">' + response.message + '</div>');
                    // Redirect to login page after a short delay
                    setTimeout(function() {
                        window.location.href = 'login.html';
                    }, 2000);
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