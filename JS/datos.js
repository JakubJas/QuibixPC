function showContenido(seccion) {

    $('#infoPelu').hide();
    $('#' + seccion).show();

    switch (seccion) {
        case 'productos':
            getProductos();
            break;
        case 'clientes':
            getClientes();
            break;
        case 'carrito':
            getCarrito();
            break;
        case 'citas':
            getCitas();
            break;
        default:
            break;
    }
}


function getProductos() {

    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Producto',
        type: 'GET',
        dataType: 'json',
        success: function(response) {

            console.log(response);
            
            $('#clientes').hide();
            $('#nuevoCliente').hide();
            $('#clienteExtenso').hide();
            $('#clienteEditar').hide();
            $('#productos').empty();

            $('#productos').append($('<h2>').text('Productos'));
            $('#productos').append($('<h5>').text('Los mejores productos para tu amigo canino'));

            var table = $('<table>').addClass('table'); 
            var headerRow = $('<tr>');
            headerRow.append($('<th>').text('Nombre'));
            headerRow.append($('<th>').text('Descripción'));
            headerRow.append($('<th>').text('Categoría'));
            headerRow.append($('<th>').text('Stock'));
            headerRow.append($('<th>').text('Precio'));
            headerRow.append($('<th>').text('Cantidad'));
            headerRow.append($('<th>').text('Acción'));
            table.append(headerRow);

            $.each(response, function(index, producto) {
                var row = $('<tr>');
                row.append($('<td>').text(producto.nombre));
                row.append($('<td>').text(producto.descripcion));
                row.append($('<td>').text(producto.categoria));
                row.append($('<td>').text(producto.stock));
                row.append($('<td>').text(producto.precio));

                var cantidadInput = $('<input>').attr('type', 'number').attr('min', 1).attr('max', producto.stock).val(1);
                row.append($('<td>').append(cantidadInput));
                
                var botonAgregar = $('<button>').addClass('btn btn-primary').text('Agregar al carrito').click(function() {
                    postAlCarrito(producto, cantidadInput.val());
                });
                row.append($('<td>').append(botonAgregar));
                
                table.append(row);
            });

            $('#productos').append(table);
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener productos:', error);
        }
    });
}

// Mostrar todos los clientes 
function getClientes() {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Datos recibidos:', response);
            
            $('#productos').hide();
            $('#nuevoCliente').hide();
            $('#clienteExtenso').hide();
            $('#clienteEditar').hide();
            $('#clientes').empty();

            $('#clientes').append($('<h2>').text('Clientes'));
            $('#clientes').append($('<h5>').text('Nuestros clientes'));
            
            var tabla = $('<table>').addClass('table');
            var cabecera = $('<thead>').append(
                $('<tr>').append(
                    $('<th>').text('ID'),
                    $('<th>').text('Nombre'),
                    $('<th>').text('Apellidos'),
                    $('<th>').text('Correo electrónico'),
                    $('<th>').text('Teléfono')
                )
            );
            tabla.append(cabecera);

            var cuerpo = $('<tbody>');
            response.forEach(function(cliente) {
                var fila = $('<tr>').append(
                    $('<td>').text(cliente.id),
                    $('<td>').text(cliente.nombre),
                    $('<td>').text(cliente.apellidos),
                    $('<td>').text(cliente.email),
                    $('<td>').text(cliente.telefono)
                );
                var botonEliminar = $('<button>').addClass('btn btn-danger').text('Eliminar').click(function() {
                    deleteCliente(cliente.id);
                });
                fila.append($('<td>').append(botonEliminar));

                var botonCliente = $('<button>').addClass('btn btn-primary btnCliente').text('Cliente').data('id', cliente.id);
                fila.append($('<td>').append(botonCliente));

                cuerpo.append(fila);
            });

            tabla.append(cuerpo);
            $('#clientes').append(tabla);
            
            var botonMostrarFormulario = $('<button>').attr('id', 'mostrarFormulario').addClass('btn btn-primary').text('Agregar Nuevo Cliente');
            $('#clientes').append(botonMostrarFormulario);
            botonMostrarFormulario.click(function() {
                $('#clientes').hide();
                $('#nuevoCliente').show();
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener clientes:', error);
        }
    });
}

// Recopila los datos de un cliente
function getCliente(clienteId) {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente/' + clienteId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Detalles del cliente:', response);
            showDetallesCliente(response);

        },
        error: function(xhr, status, error) {
            console.error('Error al obtener detalles del cliente:', error);
        }
    });
}

$(document).ready(function() {
    $('#clientes').on('click', '.btnCliente', function() {
        var clienteId = $(this).data('id');
        getCliente(clienteId);
    });
});

// Muestra un cliente
function showDetallesCliente(cliente) {
    $('#clientes').hide();
    $('#productos').hide();
    $('#citas').hide();
    $('#carrito').hide();
    $('#clienteExtenso').show();

    var formulario = $('<form>').addClass('form');

    formulario.append($('<div>').addClass('form-group').attr('disabled', true).append(
        $('<label>').text('ID:').attr('disabled', true),
        $('<input>').attr('type', 'text').attr('readonly', true).attr('disabled', true).addClass('form-control').val(cliente.id)
    ));

    formulario.append($('<div>').addClass('form-group').attr('disabled', true).append(
        $('<label>').text('Nombre:').attr('disabled', true),
        $('<input>').attr('type', 'text').attr('readonly', true).attr('disabled', true).addClass('form-control').val(cliente.nombre)
    ));

    formulario.append($('<div>').addClass('form-group').attr('disabled', true).append(
        $('<label>').text('Apellidos:').attr('disabled', true),
        $('<input>').attr('type', 'text').attr('readonly', true).attr('disabled', true).addClass('form-control').val(cliente.apellidos)
    ));

    formulario.append($('<div>').addClass('form-group').attr('disabled', true).append(
        $('<label>').text('Correo Electrónico:').attr('disabled', true),
        $('<input>').attr('type', 'email').attr('readonly', true).attr('disabled', true).addClass('form-control').val(cliente.email)
    ));

    formulario.append($('<div>').addClass('form-group').attr('disabled', true).append(
        $('<label>').text('Teléfono:').attr('disabled', true),
        $('<input>').attr('type', 'tel').attr('readonly', true).attr('disabled', true).addClass('form-control').val(cliente.telefono)
    ));

    var botonEditar = $('<button>').addClass('btn btn-primary').text('Editar').click(function() {
        editarCliente(cliente);
    });
    formulario.append($('<div>').addClass('form-group').append(botonEditar));

    $('#clienteExtenso').empty();
    $('#clienteExtenso').append(formulario);

}

// Formulario editar cliente
function editarCliente(cliente) {
    $('#clientes').hide();
    $('#productos').hide();
    $('#citas').hide();
    $('#carrito').hide();
    $('#clienteExtenso').hide();
    $('#clienteEditar').show();

    var formulario = $('<form>').addClass('form');

    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('ID:'),
        $('<input>').attr('type', 'text').addClass('form-control').val(cliente.id).prop('readonly', true)
    ));

    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('Nombre:'),
        $('<input>').attr('type', 'text').addClass('form-control').val(cliente.nombre)
    ));

    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('Apellidos:'),
        $('<input>').attr('type', 'text').addClass('form-control').val(cliente.apellidos)
    ));

    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('Correo Electrónico:'),
        $('<input>').attr('type', 'email').addClass('form-control').val(cliente.email)
    ));

    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('Teléfono:'),
        $('<input>').attr('type', 'tel').addClass('form-control').val(cliente.telefono)
    ));

    var botonGuardar = $('<button>').addClass('btn btn-primary').text('Guardar Cambios').click(function() {
        putCliente(cliente);
    });
    formulario.append($('<div>').addClass('form-group').append(botonGuardar));

    $('#clienteEditar').empty().append(formulario);
}

// Mandar datos nuevos del cliente
function putCliente(clienteId) {
    
    var nombre = $('#clienteEditar #nombre').val();
    var apellidos = $('#clienteEditar #apellidos').val();
    var email = $('#clienteEditar #email').val();
    var telefono = $('#clienteEditar #telefono').val();

    if (nombre === '' || apellidos === '' || email === '' || telefono === '') {
        alert('Por favor, completa todos los campos.');
        return;
    }

    if (!isValidEmail(email)) {
        alert('Por favor, introduce un correo electrónico válido.');
        return;
    }

    if (!isValidPhoneNumber(telefono)) {
        alert('Por favor, introduce un número de teléfono válido.');
        return; 
    }

    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente/' + clienteId,
        type: 'PUT',
        dataType: 'json',
        data: {
            nombre: nombre,
            apellidos: apellidos,
            email: email,
            telefono: telefono
        },
        success: function(response) {
            alert('Cliente editado con éxito');
            console.log('Datos enviados:', response);
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error al editar cliente:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            alert('Error al editar cliente');
        }
    });
}


// Crea un nuevo registro de cliente
function postCliente() {
    var nombre = $('#nombre').val();
    var apellidos = $('#apellidos').val();
    var email = $('#email').val();
    var telefono = $('#telefono').val();

    if (nombre === '' || apellidos === '' || email === '' || telefono === '') {
        alert('Por favor, completa todos los campos.');
        return;
    }

    if (!isValidEmail(email)) {
        alert('Por favor, introduce un correo electrónico válido.');
        return;
    }

    if (!isValidPhoneNumber(telefono)) {
        alert('Por favor, introduce un número de teléfono válido.');
        return; 
    }

    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente',
        type: 'POST',
        dataType: 'json',
        data: {
            nombre: nombre,
            apellidos: apellidos,
            email: email,
            telefono: telefono
        },
        success: function(response) {
            alert('Cliente registrado con éxito');
            console.log('Datos enviados:', response);
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error al registrar cliente:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            alert('Error al registrar cliente');
        }
    });
}

// Validación del Email
function isValidEmail(email) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Validacion numero TLF
function isValidPhoneNumber(telefono) {
    var phoneRegex = /^\d{9}$/;
    return phoneRegex.test(telefono);
}

$(document).ready(function() {
    $('#btnAgregarCliente').click(function() {
        postCliente();
    });
});

// Borra al cliente del registro de BD
function deleteCliente(id) {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente/' + id,
        type: 'DELETE',
        dataType: 'json',
        success: function(response) {
            alert('Cliente eliminado correctamente');
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error al eliminar cliente:', error);
            alert('Error al eliminar cliente. Mira la consola para más detalles sobre el error.');
        }
    });
}
