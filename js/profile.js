$(document).ready(function() {
    const token = localStorage.getItem('session_token');

    // **Page Protection**
    // If no token exists, redirect to login page immediately
    if (!token) {
        window.location.href = 'login.html';
        return; // Stop further execution
    }

    // Function to fetch and display profile data
    function fetchProfile() {
        $.ajax({
            type: 'POST', // Using POST to send token in body
            url: 'php/profile.php',
            data: {
                action: 'get',
                token: token
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#age').val(response.data.age);
                    $('#dob').val(response.data.dob);
                    $('#contact').val(response.data.contact);
                } else {
                    // If token is invalid, clear it and redirect to login
                    alert(response.message);
                    localStorage.removeItem('session_token');
                    window.location.href = 'login.html';
                }
            },
            error: function() {
                alert('An error occurred while fetching profile data.');
                localStorage.removeItem('session_token');
                window.location.href = 'login.html';
            }
        });
    }

    // Fetch profile on page load
    fetchProfile();

    // Handle profile update form submission
    $('#profile-form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'php/profile.php',
            data: {
                action: 'update',
                token: token,
                age: $('#age').val(),
                dob: $('#dob').val(),
                contact: $('#contact').val()
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#message').html('<div class="alert alert-success">' + response.message + '</div>');
                } else {
                    $('#message').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#message').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
            }
        });
    });

    // Handle logout
    $('#logout-btn').on('click', function() {
        localStorage.removeItem('session_token');
        window.location.href = 'login.html';
    });
});