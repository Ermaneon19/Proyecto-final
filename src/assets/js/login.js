$(document).ready(function() {
    $('#loginForm').on('submit', function(event) {
        event.preventDefault(); // Evitar el envío del formulario

        var username = $('#username').val();
        var password = $('#password').val();

        $.ajax({
            url: 'authentication-login.php',
            type: 'POST',
            dataType: 'json',
            data: {
                username: username,
                password: password,
                ajax: true // Indicar que es una solicitud AJAX
            },
            success: function(response) {
                var data = (response); // directamente ya es un array .
                if (data.success) {
                    window.location.href = 'index.php';
                } else {
                    $('#errorMessage').text(data.message).show();
                    $('#username').val(data.username); // Mantener el usuario en el campo
                }
            }
        });
    });
});