function validaFormulario() {
    var usuario = document.getElementById("usuario").value;
    var clave = document.getElementById("clave").value;

    // Verifica si el campo de usuario está vacío
    if (usuario.trim() === "") {
        alert("Por favor, ingresa un usuario.");
        return false;
    }

    // Verifica si el campo de clave está vacío
    if (clave.trim() === "") {
        alert("Por favor, ingresa una contraseña.");
        return false;
    }

    // Si ambos campos no están vacíos, devuelve true para permitir el envío del formulario
    return true;
}