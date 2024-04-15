function cerrarSesion() {
    $.ajax({
        url: '../conexiones/api.php/logout',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response && response.mensaje === 'Sesión cerrada correctamente') {
                window.location.href = '../Vistas/login.php';
            } else {
                console.error('Respuesta de cierre de sesión no válida:', response);
            }
        },
        error: function(xhr, status, error) {
            // Manejar el error aquí
            console.error('Error al cerrar sesión:', error);
            // Opcional: redirigir a la página de inicio de sesión en caso de error
            window.location.href = '../Vistas/login.php';
        }
    });
}
