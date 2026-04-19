$(document).ready(function () {
    $('#loginForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: 'auth/login_process.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.status === "success") {
                    window.location.href = response.role + '/dashboard.php';
                } else {
                    $('#message').html('<p style="color:red">' + response.message + '</p>');
                }
            }
        });
    });
});