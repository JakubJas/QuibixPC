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
            getClientes();
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
                
                var botonAgregar = $('<button>').text('Agregar al carrito').click(function() {
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

function getClientes() {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Datos recibidos:', response);
            
            $('#productos').hide();
            $('#nuevoCliente').hide();
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

$(document).ready(function() {
    $('#mostrarFormulario').click(function() {
        $('#productos').hide();
        $('#citas').hide();
        $('#clientes').hide();
        $('#carrito').hide();
        $('#nuevoCliente').show();
    });
});

function postCliente() {

        var nombre = $('#nombre').val();
        var apellido = $('#apellido').val();
        var email = $('#email').val();
        var telefono = $('#telefono').val();

        $.ajax({
            url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente',
            type: 'POST',
            dataType: 'json',
            data: {
                nombre: nombre,
                apellido: apellido,
                email: email,
                telefono: telefono
            },
            success: function(response) {
                alert('Cliente registrado con éxito');
                console.log('Datos eviados:', response)

            },
            error: function(xhr, status, error) {
                console.error('Error al registrar cliente:', error);
                alert('Error al registrar cliente. Mira la consola para más detalles sobre el error.');
            }
        });
}

$(document).ready(function() {
    // Agrega un controlador de eventos al botón btnAgregarCliente
    $('#btnAgregarCliente').click(function() {
        // Llama a la función postCliente() cuando se hace clic en el botón
        postCliente();
    });
});

