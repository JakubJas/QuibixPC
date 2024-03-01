class LoginValidator {
    constructor() {
        this.formulario = document.querySelector('.login-form');
        this.usuarioInput = document.getElementById('usuario');
        this.claveInput = document.getElementById('clave');
        this.submitButton = document.getElementById('loginBtn');

        this.formulario.addEventListener('submit', this.validarLogin.bind(this));
    }

    validarLogin(event) {
        event.preventDefault();

        const usuario = this.usuarioInput.value.trim();
        const clave = this.claveInput.value.trim();

        // Verifica si el campo de usuario está vacío
        if (usuario === "") {
            alert("Por favor, ingresa un usuario.");
            return false;
        }

        // Verifica si el campo de clave está vacío
        if (clave === "") {
            alert("Por favor, ingresa una contraseña.");
            return false;
        }

        // Envía la solicitud AJAX para verificar las credenciales
        $.ajax({
            url: '../controller/verificarCredenciales.php', 
            type: 'POST',
            data: {
                usuario: usuario,
                clave: clave
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert("Inicio de sesión exitoso");
                    // Redirige a la página principal u otra página
                    window.location.href = "main.html";
                } else {
                    alert("Usuario o contraseña incorrectos");
                }
            },
            error: function(error) {
                console.error('Error al verificar credenciales:', error);
            }
        });

        return false;
    }
}

// Crea una instancia de LoginValidator cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    new LoginValidator();
});
