$(document).ready(function () {
    $('#registerForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: 'auth/register_process.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                    window.location.reload(); // Ou rediriger vers le login
                } else {
                    alert(response.message);
                }
            }
        });
    });
});