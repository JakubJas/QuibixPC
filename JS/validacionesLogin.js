

$(document).ready(function () {
    
    $('#loginForm').on('submit', function (event) {
        event.preventDefault(); 

        const usuario = $('#usuario').val();
        const clave = $('#clave').val();

        if (!usuario || !clave) {
            alert('Por favor, completa todos los campos.');
            return; 
        }
    });
});
