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

// Mostrar todos los clientes 
function getClientes() {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Datos recibidos:', response);
            
            $('#productos').hide();
            $('#nuevoProducto').hide();
            $('#carrito').hide();
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
                var row = $('<tr>').append(
                    $('<td>').text(cliente.id),
                    $('<td>').text(cliente.nombre),
                    $('<td>').text(cliente.apellidos),
                    $('<td>').text(cliente.email),
                    $('<td>').text(cliente.telefono)
                );
                var botonEliminar = $('<button>').addClass('btn btn-danger').text('Eliminar').click(function() {
                    deleteCliente(cliente.id);
                });
                row.append($('<td>').append(botonEliminar));

                var botonCliente = $('<button>').addClass('btn btn-primary btnCliente').text('Cliente').data('id', cliente.id);
                row.append($('<td>').append(botonCliente));

                cuerpo.append(row);
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

    console.log('Nombre del cliente:', cliente.nombre);
    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('Nombre:'),
        $('<input>').attr('type', 'text').attr('id', 'nombre').addClass('form-control').val(cliente.nombre)
    ));
    
    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('Apellidos:'),
        $('<input>').attr('type', 'text').attr('id', 'apellidosCliente').addClass('form-control').val(cliente.apellidos)
    ));
    
    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('Correo Electrónico:'),
        $('<input>').attr('type', 'email').attr('id', 'emailCliente').addClass('form-control').val(cliente.email)
    ));
    
    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('Teléfono:'),
        $('<input>').attr('type', 'tel').attr('id', 'telefonoCliente').addClass('form-control').val(cliente.telefono)
    ));
    

    var botonGuardar = $('<button>').addClass('btn btn-primary').text('Guardar Cambios').click(function() {
        // Pasar solo el ID del cliente a putCliente
        putCliente(cliente.id);
    });
    formulario.append($('<div>').addClass('form-group').append(botonGuardar));

    $('#clienteEditar').empty().append(formulario);
}

// Mandar datos nuevos del cliente
function putCliente(clienteId) {
    
    var nombre = $('#nombre').val();
    var apellidos = $('#apellidosCliente').val();
    var email = $('#emailCliente').val();
    var telefono = $('#telefonoCliente').val();

    console.log('Datos a enviar:', {
        nombreCliente: nombre,
        apellidos: apellidos,
        email: email,
        telefono: telefono
    });

    if (nombre === '' || apellidos === '' || email === '' || telefono === '') {
        alert('Por favor, completa todos los campos.');
        return;
    }

    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente/' + clienteId,
        type: 'PUT',
        dataType: 'json',
        data: {
            nombreCliente: nombre,
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
    var nombreCliente = $('#nombreCliente').val();
    var apellidos = $('#apellidos').val();
    var email = $('#email').val();
    var telefono = $('#telefono').val();

    if (nombreCliente === '' || apellidos === '' || email === '' || telefono === '') {
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
            nombreCliente: nombreCliente,
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
            alert('Error al eliminar cliente.');
        }
    });
}

//PRODUCTOS

// Conseguir/Mostrar porductos
function getProductos() {
    // Obtener lista de clientes
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente',
        type: 'GET',
        dataType: 'json',
        success: function(clientes) {
            console.log('Clientes obtenidos:', clientes);

            $('#clientes').hide();
            $('#nuevoCliente').hide();
            $('#clienteExtenso').hide();
            $('#clienteEditar').hide();
            $('#carrito').hide();
            $('#productos').empty();

            // Llenar el select con los clientes
            var selectClientes = $('<select>').addClass('form-control').attr('id', 'clienteSelect');
            $.each(clientes, function(index, cliente) {
                var option = $('<option>').val(cliente.id).text(cliente.nombre + ' ' + cliente.apellidos);
                selectClientes.append(option);
            });

            // Agregar título y el select al div de productos
            $('#productos').append($('<h2>').text('Productos'));
            $('#productos').append($('<h5>').text('Los mejores productos para tu amigo canino'));
            $('#productos').append($('<label>').text('Cliente:'));
            $('#productos').append(selectClientes);
            $('#productos').append('<br>');


            // Obtener lista de productos
            $.ajax({
                url: 'http://localhost/QuibixPC/conexiones/api.php/Producto',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    
                    // Crear tabla para mostrar los productos
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

                    // Llenar la tabla con los productos
                    $.each(response, function(index, producto) {
                        var row = $('<tr>');
                        row.append($('<td>').text(producto.nombre));
                        row.append($('<td>').text(producto.descripcion));
                        row.append($('<td>').text(producto.categoria));
                        row.append($('<td>').text(producto.stock));
                        row.append($('<td>').text(producto.precio));

                        // Input para la cantidad
                        var cantidadInput = $('<input>').attr('type', 'number').attr('min', 1).attr('max', producto.stock).addClass('form-control');
                        row.append($('<td>').append(cantidadInput));

                        // Botón para agregar al carrito
                        var botonAgregar = $('<button>').addClass('btn btn-primary').text('Agregar al carrito').click(function() {
                            var clienteID = $('#clienteSelect').val();
                            var cantidad = cantidadInput.val();
                            postAlCarrito(producto, clienteID, cantidad);
                        });
                        row.append($('<td>').append(botonAgregar));

                        var botonEliminar = $('<button>').addClass('btn btn-danger').text('Eliminar').click(function() {
                            deleteProducto(producto.id);
                        });
                        row.append($('<td>').append(botonEliminar));

                        table.append(row);
                    });

                    // Agregar la tabla al div de productos
                    $('#productos').append(table);

                    // Botón para mostrar el formulario de nuevo producto
                    var botonMostrarFormulario = $('<button>').attr('id', 'mostrarFormulario').addClass('btn btn-primary').text('Agregar Nuevo Producto');
                    $('#productos').append(botonMostrarFormulario);
                    botonMostrarFormulario.click(function() {
                        $('#productos').hide();
                        $('#nuevoProducto').show();
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener productos:', error);
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener clientes:', error);
        }
    });
}

// Crea un nuevo producto
function postProducto() {
    var sku = $('#sku').val();
    var nombreProducto = $('#nombreProducto').val();
    var descripcion = $('#descripcion').val();
    var stock = $('#stock').val();
    var precio = $('#precio').val();
    var categoriaID = $('#categoria').val();

    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Producto',
        type: 'POST',
        dataType: 'json',
        data: {
            sku: sku,
            nombreProducto: nombreProducto,
            descripcion: descripcion,
            stock: stock,
            precio: precio,
            categoriaID: categoriaID
        },
        success: function(response) {
            alert('Producto nuevo creado con éxito');
            console.log('Datos enviados:', response);
            location.reload();
        },
        error: function(xhr, status, error, response) {
            console.error('Error al crear producto:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            alert('Error al registrar un producto');
        }
    });
}

$(document).ready(function() {
    $('#btnAgregarProducto').click(function() {
        postProducto();
    });
});

//Borrar producto
function deleteProducto(id) {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Producto/' + id,
        type: 'DELETE',
        dataType: 'json',
        success: function(response) {
            alert('Producto eliminado correctamente');
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error al eliminar el producto:', error);
            alert('Error al eliminar el producto.');
        }
    });
}

// Categoria
function cargarCategorias() {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Categoria',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#categoria').empty();
            response.forEach(function(categoria) {
                $('#categoria').append($('<option>', {
                    value: categoria.id,
                    text: categoria.nombre
                }));
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar categorías:', error);
        }
    });
}

$(document).ready(function() {
    cargarCategorias();
});

function getCarrito() {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Carrito',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            
            $('#clientes').hide();
            $('#nuevoCliente').hide();
            $('#clienteExtenso').hide();
            $('#clienteEditar').hide();
            $('#productos').hide();
            $('#carrito').empty();
    
            if (response.length > 0) {

            $('#carrito').append($('<h2>').text('Carrito de Compras'));
            $('#carrito').append($('<h5>').text('Aquí están los productos en tu carrito'));
    
            var table = $('<table>').addClass('table'); 
            var headerRow = $('<tr>');
            headerRow.append($('<th>').text('ID'));
            headerRow.append($('<th>').text('Cliente'));
            headerRow.append($('<th>').text('Producto'));
            headerRow.append($('<th>').text('Estado'));
            headerRow.append($('<th>').text('Cantidad'));
            headerRow.append($('<th>').text('Precio Total'));
            headerRow.append($('<th>').text('Acciones'));
            table.append(headerRow);
    
            $.each(response, function(index, carrito) {
                var row = $('<tr>');
                row.append($('<td>').text(carrito.id));
                row.append($('<td>').text(carrito.cliente));
                row.append($('<td>').text(carrito.producto));
                row.append($('<td>').text(carrito.estado));
                row.append($('<td>').text(carrito.cantidad));
                row.append($('<td>').text(carrito.precio_total));

                var botonEliminar = $('<button>').addClass('btn btn-danger').text('Eliminar').click(function() {
                    deleteCarrito(carrito.id);
                });
                row.append($('<td>').append(botonEliminar));
                table.append(row);
            });
    
            $('#carrito').append(table);
            } else {
                $('#carrito').text('El carrito está vacío');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener el carrito:', error);
        }
    });    
}

function postAlCarrito(producto, clienteID, cantidad) {
    // Obtener el precio total multiplicando la cantidad por el precio del producto
    var precioTotal = cantidad * producto.precio;

    // Datos del producto a enviar al servidor
    var datosProducto = {
        clienteID: clienteID,
        productoID: producto.id,
        cantidad: cantidad,
        precioTotal: precioTotal
    };

    var _this = this; // Guardar una referencia al objeto actual

    // Realizar la solicitud POST al servidor para agregar el producto al carrito
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Carrito',
        type: 'POST',
        dataType: 'json',
        data: datosProducto,
        success: function(response) {
            // Mostrar mensaje de éxito en la consola
            console.log('Producto agregado al carrito:', response.mensaje);

            // Actualizar el stock del producto
            var nuevoStock = producto.stock - cantidad;
            _this.putStockProducto(producto.id, nuevoStock); // Usar _this en lugar de this
        },
        error: function(xhr, status, error) {
            // Mostrar mensaje de error en la consola
            console.error('Error al agregar producto al carrito:', error);
        }
    });
}


function putStockProducto(productoID, nuevoStock) {
    // Datos del producto a enviar al servidor para actualizar el stock
    var datosStock = {
        productoID: productoID,
        nuevoStock: nuevoStock
    };

    // Realizar la solicitud PUT al servidor para actualizar el stock del producto
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Producto/' + productoID,
        type: 'PUT',
        dataType: 'json',
        data: datosStock,
        success: function(response) {
            // Mostrar mensaje de éxito en la consola
            console.log('Stock actualizado:', response.mensaje);
        },
        error: function(xhr, status, error) {
            // Mostrar mensaje de error en la consola
            console.error('Error al actualizar stock del producto:', error);
        }
    });
}

function deleteCarrito(id) {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Carrito/' + id,
        type: 'DELETE',
        dataType: 'json',
        success: function(response) {
            alert('Producto eliminado del carrito correctamente');
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error al eliminar del carrito:', error);
            alert('Error al eliminar producto del carrito.');
        }
    });
}


