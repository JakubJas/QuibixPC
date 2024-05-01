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
        case 'compra':
            getCompra();
            break;
        case 'citas':
            getCitas();
            break;
        default:
            break;
    }
}

// CLIENTES············································································································································

// Función para mostrar a los clientes
function getClientes() {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Datos recibidos:', response);
            
            // Ocultamos las secciones que no hagan falta
            $('#productos').hide();
            $('#nuevoProducto').hide();
            $('#carrito').hide();
            $('#nuevoCliente').hide();
            $('#clienteExtenso').hide();
            $('#clienteEditar').hide();
            $('#compra').hide();
            $('#citas').hide();
            $('#clientes').empty();

            $('#clientes').append('<br>');
            $('#clientes').append($('<h2>').text('Clientes'));
            $('#clientes').append($('<h5>').text('Nuestros clientes'));
            
            // Campo de filtro por apellido
            var filtroApellido = $('<input>').attr('type', 'text').attr('id', 'apellidoFiltro').addClass('form-control').attr('placeholder', 'Filtrar por Apellido');

            $('#clientes').append($('<div>').addClass('form-group').append(filtroApellido));

            // Botón para aplicar filtro
            var botonFiltrar = $('<button>').addClass('btn btn-primary').text('Filtrar').click(function() {
                var apellidoFiltro = $('#apellidoFiltro').val();
                filtrarClientesPorApellido(apellidoFiltro);
            });
            $('#clientes').append(botonFiltrar);

            $('#clientes').append('<br><br>');

            // Crear tabla de clientes
            var tabla = $('<table>').addClass('table');
            var cabecera = $('<thead>').append(
                $('<tr>').append(
                    $('<th>').text('Nombre'),
                    $('<th>').text('Apellidos'),
                    $('<th>').text('Correo electrónico'),
                    $('<th>').text('Teléfono'),
                    $('<th>').text('Acciones')
                )
            );
            tabla.append(cabecera);

            var cuerpo = $('<tbody>');
            response.forEach(function(cliente) {
                var row = $('<tr>').append(
                    $('<td>').text(cliente.nombre),
                    $('<td>').text(cliente.apellidos),
                    $('<td>').text(cliente.email),
                    $('<td>').text(cliente.telefono)
                );

                // Botón eliminar cliente
                var botonEliminar = $('<button>').addClass('btn btn-danger').text('Eliminar').click(function() {
                    if (confirm("¿Estás seguro de que deseas eliminar este Cliente?")) {
                        if (clienteTieneProductosEnCarrito(cliente.id)) {
                            alert("No se puede eliminar el cliente mientras tenga productos en el carrito.");
                        } else {
                            deleteCliente(cliente.id);
                        }
                    }
                });
                row.append($('<td>').append(botonEliminar));

                // Botón ver cliente
                var botonCliente = $('<button>').addClass('btn btn-primary btnCliente').text('Ver').data('id', cliente.id).click(function() {
                });
                row.append($('<td>').append(botonCliente));

                cuerpo.append(row);
            });

            tabla.append(cuerpo);
            $('#clientes').append(tabla);
            
            // Botón mostrar formulario para agregar nuevo cliente
            var botonMostrarFormulario = $('<button>').attr('id', 'mostrarFormulario').addClass('btn btn-primary').text('Agregar Nuevo Cliente');
            $('#clientes').append(botonMostrarFormulario);
            botonMostrarFormulario.click(function() {
                $('#clientes').hide();
                $('#nuevoCliente').show();
            });
        },
        error: function(error) {
            console.error('Error al obtener clientes:', error);
        }
    });
}

// Función para el fitro de getClientes()
function filtrarClientesPorApellido(apellido) {
    var filas = $('#clientes tbody tr');
    filas.hide();
    filas.each(function() {
        var apellidoCliente = $(this).find('td:nth-child(3)').text();
        if (apellidoCliente.toLowerCase().includes(apellido.toLowerCase())) {
            $(this).show();
        }
    });
}

// Comprueba si el cliente contiene algo en el carrito por si quieren eliminarlo de la base de datos
function clienteTieneProductosEnCarrito(clienteId) {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/CarritoCliente',
        type: 'GET',
        data: { clienteId: clienteId },
        dataType: 'json',
        success: function(carrito) {
            if (carrito.length > 0) {
                return true;
            } else {
                return false;
            }
        },
        error: function(error) {
            console.error('Error al obtener el carrito del cliente:', error);
            return false; 
        }
    });
}

// Busca el ID del cliente que se quiere mostrar en detalle
function getCliente(clienteId) {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente/' + clienteId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Detalles del cliente:', response);
            showDetallesCliente(response);

        },
        error: function(error) {
            console.error('Error al obtener detalles del cliente:', error);
        }
    });
}

// Ejecuta la función
$(document).ready(function() {
    $('#clientes').on('click', '.btnCliente', function() {
        var clienteId = $(this).data('id');
        getCliente(clienteId);
    });
});

// Aqui mostramos en detalle los datos del cliente
function showDetallesCliente(cliente) {
    $('#clientes').hide();
    $('#productos').hide();
    $('#citas').hide();
    $('#carrito').hide();
    $('#clienteExtenso').show();

    $('#clienteExtenso').empty();
    $('#clienteExtenso').append('<br>');
    $('#clienteExtenso').append($('<h2>').text('Cliente'));
    $('#clienteExtenso').append($('<h5>').text('Nuestro cliente'));

    // Creamos un formulario para que los datos se muestren en los contenedores (bloqueados)
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

    // Creamos un boton que nos mande a la sección que nos permite cambiar los datos del usuario.
    var botonEditar = $('<button>').addClass('btn btn-primary').text('Editar').click(function() {
        updateCliente(cliente);
    });
    formulario.append($('<div>').addClass('form-group').append(botonEditar));

    $('#clienteExtenso').append(formulario);

}

// Función para poder editar los datos pero no se mandan
function updateCliente(cliente) {
    $('#clientes').hide();
    $('#productos').hide();
    $('#citas').hide();
    $('#carrito').hide();
    $('#clienteExtenso').hide();
    $('#clienteEditar').show();

    $('#clienteEditar').empty();
    $('#clienteEditar').append('<br>');
    $('#clienteEditar').append($('<h2>').text('Cliente'));
    $('#clienteEditar').append($('<h5>').text('Editar a ' + cliente.nombre));

    var formulario = $('<form>').addClass('form');

    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('ID:'),
        $('<input>').attr('type', 'text').addClass('form-control').val(cliente.id).prop('readonly', true)
    ));

    console.log('Nombre del cliente:', cliente.nombre);
    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('Nombre:'),
        $('<input>').attr('type', 'text').attr('id', 'nombreClientenuevo').addClass('form-control').val(cliente.nombre)
    ));
    
    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('Apellidos:'),
        $('<input>').attr('type', 'text').attr('id', 'apellidosClientenuevo').addClass('form-control').val(cliente.apellidos)
    ));
    
    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('Correo Electrónico:'),
        $('<input>').attr('type', 'email').attr('id', 'emailClientenuevo').addClass('form-control').val(cliente.email)
    ));
    
    formulario.append($('<div>').addClass('form-group').append(
        $('<label>').text('Teléfono:'),
        $('<input>').attr('type', 'tel').attr('id', 'telefonoClientenuevo').addClass('form-control').val(cliente.telefono)
    ));
    

    var botonGuardar = $('<button>').addClass('btn btn-primary').text('Guardar Cambios').click(function() {
        // Pasar solo el ID del cliente a putCliente
        putCliente(cliente.id);
    });
    formulario.append($('<div>').addClass('form-group').append(botonGuardar));

    $('#clienteEditar').append(formulario);
}

// Función que recoge los datos del formulario de edición y los manda a la base de datos
function putCliente(clienteId) {

    var nombreClienteNuevo = $('#nombreClientenuevo').val();
    var apellidosNuevo = $('#apellidosClientenuevo').val();
    var emailNuevo = $('#emailClientenuevo').val();
    var telefonoNuevo = $('#telefonoClientenuevo').val();

    // Validaciones y comporbaciónes
    detectSqlInjection(nombreClienteNuevo, apellidosNuevo, emailNuevo, telefonoNuevo);

    if (nombreClienteNuevo === '' || apellidosNuevo === '' || emailNuevo === '' || telefonoNuevo === '') {
        alert('Por favor, completa todos los campos.');
        return;
    } else if (!isValidNombre(nombreClienteNuevo || apellidosNuevo)) {
        alert('Por favor, introduce el nombre o apellido válido.')
    } else if (!isValidEmail(emailNuevo)) {
        alert('Por favor, introduce un correo electrónico válido.');
        return;
    } else if (!isValidNumeroTlf(telefonoNuevo)) {
        alert('Por favor, introduce un número de teléfono válido.');
        return; 
    } else {
        $.ajax({
            url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente/' + clienteId,
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify({
                nombre: nombreClienteNuevo,
                apellidos: apellidosNuevo,
                email: emailNuevo,
                telefono: telefonoNuevo
            }),
            success: function(response) {
                console.log('Cliente actualizado correctamente:', response);
                alert("Cliete actualizado ccorrectamente");
            },
            error: function(error) {
                console.error('Error al actualizar cliente:', error);
            }
        });
    }
}

// Función para crear clientes nuevos
function postCliente() {
    var nombreCliente = $('#nombreCliente').val();
    var apellidos = $('#apellidos').val();
    var email = $('#email').val();
    var telefono = $('#telefono').val();

    // verificaciones y comprobaciones
    detectSqlInjection(nombreCliente, apellidos, email, telefono);

    if (detectSqlInjection(nombreCliente, apellidos, email, telefono)) {
        console.log("Posible intento de inyección SQL");
    } else {
        console.log("La cadena es segura");
    }

    if (nombreCliente === '' || apellidos === '' || email === '' || telefono === '') {
        alert('Por favor, completa todos los campos.');
        return;
    } else if (!isValidNombre(nombreCliente, apellidos)) {
        alert('Por favor, introduce el nombre o apellido válido.')
    } else if (!isValidEmail(email)) {
        alert('Por favor, introduce un correo electrónico válido.');
        return;
    } else if (!isValidNumeroTlf(telefono)) {
        alert('Por favor, introduce un número de teléfono válido.');
        return; 
    } else {
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
            },
            error: function(xhr, status, error) {
                console.error('Error al registrar cliente:', error);
                console.log('Respuesta del servidor:', xhr.responseText);
                alert('Error al registrar cliente');
            }
        });
    }
}

// Llamada a la función
$(document).ready(function() {
    $('#btnAgregarCliente').click(function() {
        postCliente();
    });
});

// Función de borrar al cliente dependiendo de su id
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
            alert('El cliente tiene citas o contiene artuculos en el carrito');
        }
    });
}

//PRODUCTOS·············································································································································

// Función que consigue los productos de la bd
function getProductos() {

    $('#clientes').hide();
    $('#nuevoCliente').hide();
    $('#clienteExtenso').hide();
    $('#clienteEditar').hide();
    $('#carrito').hide();
    $('#compra').hide();
    $('#nuevoProducto').hide();
    $('#citas').hide();

    // Obtener lista de productos
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Producto',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response);

            var productsPerPage = 10;
            var totalPages = Math.ceil(response.length / productsPerPage);

            function mostrarProductosEnPagina(page, categoriaFiltro, nombreFiltro) {

                $('#productos').empty();
                $('#productos').append('<br>');
                $('#productos').append($('<h2>').text('Productos'));
                $('#productos').append($('<h5>').text('Los mejores productos para tu amigo canino'));
                $('#productos').append('<br>');

                // Agregar campos de filtro por categoría y por nombre
                var filtroCategoria = $('<input>').attr('type', 'text').attr('id', 'categoriaFiltro').addClass('form-control').attr('placeholder', 'Filtrar por categoría');
                var filtroNombre = $('<input>').attr('type', 'text').attr('id', 'nombreFiltro').addClass('form-control').attr('placeholder', 'Filtrar por nombre');

                $('#productos').append($('<div>').addClass('form-group').append(filtroNombre));
                $('#productos').append($('<div>').addClass('form-group').append(filtroCategoria));

                // Botón para aplicar filtros
                var botonFiltrar = $('<button>').addClass('btn btn-primary').text('Filtrar').click(function() {
                    var categoriaFiltro = $('#categoriaFiltro').val();
                    var nombreFiltro = $('#nombreFiltro').val();
                    mostrarProductosEnPagina(1, categoriaFiltro, nombreFiltro);

                    var botonMostrarFormulario = $('<button>').attr('id', 'mostrarFormulario').addClass('btn btn-primary').text('Agregar Nuevo Producto');
                            $('#productos').append(botonMostrarFormulario);
                            botonMostrarFormulario.click(function() {
                                $('#productos').hide();
                                $('#nuevoProducto').show();
                            });
                });
                $('#productos').append(botonFiltrar);

                $('#productos').append('<br>');
                $('#productos').append('<br>');

                var startIndex = (page - 1) * productsPerPage;
                var endIndex = startIndex + productsPerPage;
                var productosFiltrados = response;

                // Aplicar filtro por categoría si se proporciona
                if (categoriaFiltro) {
                    productosFiltrados = productosFiltrados.filter(function(producto) {
                        return producto.categoria.toLowerCase() === categoriaFiltro.toLowerCase();
                    });
                }

                // Aplicar filtro por nombre si se proporciona
                if (nombreFiltro) {
                    productosFiltrados = productosFiltrados.filter(function(producto) {
                        return producto.nombre.toLowerCase().includes(nombreFiltro.toLowerCase());
                    });
                }

                var productsToShow = productosFiltrados.slice(startIndex, endIndex);

                var table = $('<table>').addClass('table');
                var headerRow = $('<tr>');
                headerRow.append($('<th>').text('Nombre'));
                headerRow.append($('<th>').text('Descripción'));
                headerRow.append($('<th>').text('Categoría'));
                headerRow.append($('<th>').text('Stock'));
                headerRow.append($('<th>').text('Precio'));
                headerRow.append($('<th>').text('Acción'));
                table.append(headerRow);

                $.each(productsToShow, function(index, producto) {
                    var row = $('<tr>');
                    row.append($('<td>').text(producto.nombre));
                    row.append($('<td>').text(producto.descripcion));
                    row.append($('<td>').text(producto.categoria));
                    row.append($('<td>').text(producto.stock));
                    row.append($('<td>').text(producto.precio));

                    var botonEliminar = $('<button>').addClass('btn btn-danger').text('Eliminar').click(function() {
                        if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
                            deleteProducto(producto.id);
                        } else {
                            return false;
                        }
                    });
                    row.append($('<td>').append(botonEliminar));

                    table.append(row);
                });

                $('#productos').append(table);
                mostrarPaginacion(); 
            }

            // Mostrar paginación
            function mostrarPaginacion() {
                var pagination = $('<div>').addClass('pagination');
                for (var i = 1; i <= totalPages; i++) {
                    var button = $('<button>').addClass('pagination-button').text(i).click((function(page) {
                        return function() {
                            mostrarProductosEnPagina(page, $('#categoriaFiltro').val(), $('#nombreFiltro').val());

                            var botonMostrarFormulario = $('<button>').attr('id', 'mostrarFormulario').addClass('btn btn-primary').text('Agregar Nuevo Producto');
                            $('#productos').append(botonMostrarFormulario);
                            botonMostrarFormulario.click(function() {
                                $('#productos').hide();
                                $('#nuevoProducto').show();
                            });
                        };
                    })(i));
                    pagination.append(button);
                    
                }
                $('#productos').append(pagination);

                $('#productos').append('<br>');
            }

            // Mostrar la primera página con los filtros por defecto vacíos
            mostrarProductosEnPagina(1, '', '');

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
}

// Función que crea porductos nuevos
function postProducto() {
    var sku = $('#sku').val();
    var nombreProducto = $('#nombreProducto').val();
    var descripcion = $('#descripcion').val();
    var stock = $('#stock').val();
    var precio = $('#precio').val();
    var categoriaID = $('#categoria').val();

    detectSqlInjection(sku, nombreProducto, descripcion, stock, precio, categoriaID);

    if (detectSqlInjection(sku, nombreProducto, descripcion, stock, precio, categoriaID)) {
        console.log("Posible intento de inyección SQL");
    } else {
        console.log("La cadena es segura");
    }

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
        },
        error: function(xhr, status, error) {
            console.error('Error al crear producto:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            alert('Error al registrar un producto');
        }
    });
}

// Llamada a la función
$(document).ready(function() {
    $('#btnAgregarProducto').click(function() {
        postProducto();
    });
});

// Función de borrar producto dependiendo de su id
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
            alert('El producto se encuentra en el carrito no se puede eliminar');
        }
    });
}

// CARRITO················································································································································

// Función que crea un tabla con los productos y clientes que se encuentren en el carrito
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
            $('#compra').hide();
            $('#citas').hide();
            $('#carrito').empty();
            
            if (response.length > 0) {
                $('#carrito').append($('<h2>').text('Carrito de Compras'));
                $('#carrito').append($('<h5>').text('Selecciona al cliente:'));
                
                // Un select con los nombre he apellidos de los clientes.
                var selectApellido = $('<select>').addClass('form-control').append($('<option>').attr('value', '').text('Seleccionar apellido'));

                var apellidos = response.map(function(item) {
                    return item.nombre + " " + item.apellido;
                });
                apellidos = apellidos.filter(function(value, index, self) {
                    return self.indexOf(value) === index;
                });


                apellidos.forEach(function(apellido) {
                    selectApellido.append($('<option>').attr('value', apellido).text(apellido));
                });

                selectApellido.change(function() {
                    var apellidoSeleccionado = $(this).val();
                    filtrarCarritoPorApellido(apellidoSeleccionado);
                });

                $('#carrito').append(selectApellido);

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
                
                response.forEach(function(carrito) {
                    var row = $('<tr>');
                    row.append($('<td>').text(carrito.id));
                    // Te manda a la seccion de mostrar cliente al detalle
                    var botonCliente = $('<button>').addClass('btn btn-link cliente-btn').text(carrito.nombre + " " + carrito.apellido).data('id', carrito.clienteID).click(function() {
                        var clienteId = $(this).data('id');
                        getCliente(clienteId);
                    });
                    row.append($('<td>').append(botonCliente));                    
                    row.append($('<td>').text(carrito.producto));
                    row.append($('<td>').text(carrito.estado));
                    row.append($('<td>').text(carrito.cantidad));
                    row.append($('<td>').text(carrito.precio_total));

                    // Boton eliminar del carrito
                    var botonEliminar = $('<button>').addClass('btn btn-danger').text('Eliminar').click(function() {
                        if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
                            deleteCarrito(carrito.id);
                        } else {
                            return false;
                        }
                    });
                    row.append($('<td>').append(botonEliminar));
                    table.append(row);
                });
                
                $('#carrito').append(table);
            } else {
                // Si el carrito esta vació se muestra el siguiente mensaje
                $('#carrito').text('El carrito está vacío');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener el carrito:', error);
        }
    });
}


// Un filtro para filtrar los pedidos por apellidos y nombre
function filtrarCarritoPorApellido(apellido) {
    if (apellido === '') {
        $('#carrito table tr').show();
    } else {
        $('#carrito table tr').hide();
        $('#carrito table tr:has(td:nth-child(2):contains("' + apellido + '"))').show();
    }
}

// Subida de productos y clientes al carrito 
function postAlCarrito(producto, clienteID, cantidad) {
    // Se calcula el precio total del producto
    var precioTotal = cantidad * producto.precio;

    var datosProducto = {
        clienteID: clienteID,
        productoID: producto.id,
        cantidad: cantidad,
        precioTotal: precioTotal
    };

    var _this = this;

    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Carrito',
        type: 'POST',
        dataType: 'json',
        data: datosProducto,
        success: function(response) {
            console.log('Producto agregado al carrito:', response.mensaje);
            alert("El producto se ha añadido correctamente")

            var nuevoStock = producto.stock - cantidad;
            _this.putStockProducto(producto.id, nuevoStock);
        },
        error: function(xhr, status, error) {
            console.error('Error al agregar producto al carrito:', error);
        }
    });
}

// Se borra del varrito el producto y al cliente
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

// Hace un update en el stock del producto
function putStockProducto(productoID, nuevoStock) {

    var datosStock = {
        productoID: productoID,
        nuevoStock: nuevoStock
    };

    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Producto/' + productoID,
        type: 'PUT',
        dataType: 'json',
        data: datosStock,
        success: function(response) {
            console.log('Stock actualizado:', response.mensaje);
        },
        error: function(xhr, status, error) {
            console.error('Error al actualizar stock del producto:', error);
        }
    });
}
 
// CITAS····················································································································································

// Función de conseguir las citas de la BD
function getCitas() {

    $('#clientes').hide();
    $('#nuevoCliente').hide();
    $('#clienteExtenso').hide();
    $('#clienteEditar').hide();
    $('#productos').hide();
    $('#carrito').hide();
    $('#compra').hide();
    $('#nuevaCita').hide();

    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cita',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response);

            if(response.length > 0){

            var citasPerPage = 10;
            var totalPages = Math.ceil(response.length / citasPerPage);

            function mostrarCitasEnPagina(page) {

                $('#citas').empty();
                $('#citas').append('<br>');
                $('#citas').append($('<h2>').text('Citas'));
                $('#citas').append($('<h5>').text('Todas nuestras citas'));
                $('#citas').append('<br>');

                var startIndex = (page - 1) * citasPerPage;
                var endIndex = startIndex + citasPerPage;
                var citasToShow = response.slice(startIndex, endIndex);

                var table = $('<table>').addClass('table');
                var headerRow = $('<tr>');
                headerRow.append($('<th>').text('Cita'));
                headerRow.append($('<th>').text('Cliente'));
                headerRow.append($('<th>').text('Servicio'));
                headerRow.append($('<th>').text('Trabajador'));
                table.append(headerRow);

                $.each(citasToShow, function(index, cita) {
                    var row = $('<tr>');
                    row.append($('<td>').text(cita.horario));
                    // Te lleva a la parte de cliente en detalle
                    var botonCliente = $('<button>').addClass('btn btn-link cliente-btn').text(cita.nombre + " " + cita.apellido).data('id', cita.clienteID).click(function() {
                        var clienteId = $(this).data('id');
                        getCliente(clienteId);
                    });
                    row.append($('<td>').append(botonCliente));                    
                    row.append($('<td>').text(cita.servicio));
                    row.append($('<td>').text(cita.peluquero));

                    // Boton de eliminar cita
                    var botonEliminar = $('<button>').addClass('btn btn-danger').text('Eliminar').click(function() {
                        if (confirm("¿Estás seguro de que deseas eliminar esta cita?")) {
                            deleteCita(cita.id);
                        } else {
                            return false;
                        }
                    });
                    row.append($('<td>').append(botonEliminar));

                    table.append(row);
                });

                $('#citas').append(table);
                mostrarPaginacion(); 
            }

            // Mostrar paginación
            function mostrarPaginacion() {
                var pagination = $('<div>').addClass('pagination');
                for (var i = 1; i <= totalPages; i++) {
                    var button = $('<button>').addClass('pagination-button').text(i).click((function(page) {
                        return function() {
                            mostrarCitasEnPagina(page);

                            var botonMostrarFormulario = $('<button>').attr('id', 'mostrarFormulario').addClass('btn btn-primary').text('Agregar Nueva Cita');
                            $('#citas').append(botonMostrarFormulario);
                            botonMostrarFormulario.click(function() {
                                $('#citas').hide();
                                $('#nuevaCita').show();
                            });
                        };
                    })(i));
                    pagination.append(button);
                    
                }
                $('#citas').append(pagination);

                $('#citas').append('<br>');
            }
            
            mostrarCitasEnPagina(1);

            // Boton para crear citas nuevas
            var botonMostrarFormulario = $('<button>').attr('id', 'mostrarFormulario').addClass('btn btn-primary').text('Agregar Nueva Cita');
            $('#citas').append(botonMostrarFormulario);
            botonMostrarFormulario.click(function() {
                $('#citas').hide();
                $('#nuevaCita').show();
            });
            
        } else {
            // Se muestra cuando no hay citas
            $('#citas').append($('<h2>').text('Citas'));
            $('#citas').text('No hay citas');
            $('#citas').append('<br><br>');
            
            var botonMostrarFormulario = $('<button>').attr('id', 'mostrarFormulario').addClass('btn btn-primary').text('Agregar Nueva Cita');
            $('#citas').append(botonMostrarFormulario);
            botonMostrarFormulario.click(function() {
                $('#citas').hide();
                $('#nuevaCita').show();
            });
        }
            

        },
        error: function(xhr, status, error) {
            console.error('Error al obtener citas:', error);
        }
    });
}

// Crear citas nuevas
function postCita(){
    var horario = $('#horario').val();
    var clienteID = $('#clienteID').val();
    var servicioID = $('#servicioID').val();
    var peluqueroID = $('#peluqueroID').val();

    detectSqlInjection(horario, clienteID, servicioID, peluqueroID);

    if (detectSqlInjection(horario, clienteID, servicioID, peluqueroID)) {
        console.log("Posible intento de inyección SQL");
    } else {
        console.log("La cadena es segura");
    }

    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cita',
        type: 'POST',
        dataType: 'json',
        data: {
            horario: horario,
            clienteID: clienteID,
            servicioID: servicioID,
            peluqueroID: peluqueroID,
            },
        success: function(response) {
            alert('Cita nueva creada con éxito');
            console.log('Datos enviados:', response);
        },
        error: function(xhr, status, error) {
            console.error('Error al crear cita:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            if(xhr.responseText.includes('peluquero ya tiene una cita programada')) {
                alert('El peluquero ya tiene una cita programada para esta hora');
            } else {
                alert('Error al registrar la cita');
            }
        }
    });
}

// Llamada a la función de crear citas
$(document).ready(function() {
    
    $("#horario").flatpickr({
        enableTime: true,
        minTime: "9:00",
        maxTime: "21:00",
        dateFormat: "Y-m-d H:i",
        minDate: "today", 
        maxDate: new Date().fp_incr(365), 
        time_24hr: true, 
        minuteIncrement: 30 
    });

    $('#btnAgregarCita').click(function() {
            postCita();
    });
});

// Borrar cita 
function deleteCita(id) {
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cita/' + id,
        type: 'DELETE',
        dataType: 'json',
        success: function(response) {
            alert('Cita eliminada correctamente');
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error al eliminar la cita:', error);
            alert('Error al eliminar la cita.');
        }
    });
}

// COMPRA···················································································································································

// Mostrar los productos pero con el boton de agregar al carrito, la función se encuntra en la
// parte de carrito
function getCompra() {
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
            $('#productos').hide();
            $('#nuevoProducto').hide();

            // Select con los clientes
            var selectClientes = $('<select>').addClass('form-control').attr('id', 'clienteSelect');
            $.each(clientes, function(index, cliente) {
                var option = $('<option>').val(cliente.id).text(cliente.nombre + ' ' + cliente.apellidos);
                selectClientes.append(option);
            });

            // Obtener lista de productos
            $.ajax({
                url: 'http://localhost/QuibixPC/conexiones/api.php/Producto',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    var productsPerPage = 10;
                    var totalPages = Math.ceil(response.length / productsPerPage);

                    function mostrarProductosEnPagina(page) {
                        $('#compra').empty();
                        $('#compra').append('<br>');
                        $('#compra').append($('<h2>').text('Compra'));
                        $('#compra').append($('<h5>').text('Compra lo mejor para tu amigo canino'));
                        $('#compra').append($('<label>').text('Cliente:'));
                        $('#compra').append(selectClientes);
                        $('#compra').append('<br>');
                        $('#compra').append($('<label>').text('Productos:'));
                        $('#compra').append($('<br><br>'));

                        var startIndex = (page - 1) * productsPerPage;
                        var endIndex = startIndex + productsPerPage;
                        var productsToShow = response.slice(startIndex, endIndex);

                        var table = $('<table>').addClass('table');
                        var headerRow = $('<tr>');
                        headerRow.append($('<th>').text('Nombre'));
                        headerRow.append($('<th>').text('Descripción'));
                        headerRow.append($('<th>').text('Categoría'));
                        headerRow.append($('<th>').text('Stock'));
                        headerRow.append($('<th>').text('Precio'));
                        headerRow.append($('<th>').text('Cantidad'));
                        table.append(headerRow);

                        $.each(productsToShow, function(index, producto) {
                            var row = $('<tr>');
                            row.append($('<td>').text(producto.nombre));
                            row.append($('<td>').text(producto.descripcion));
                            row.append($('<td>').text(producto.categoria));
                            row.append($('<td>').text(producto.stock));
                            row.append($('<td>').text(producto.precio));

                            // Input para la cantidad
                            var cantidadInput = $('<input>').attr('type', 'number').attr('min', 0).attr('max', producto.stock).addClass('form-control');
                            row.append($('<td>').append(cantidadInput));

                            if (producto.stock <= 0) {
                                cantidadInput.hide();
                            }

                            table.append(row);
                        });

                        $('#compra').append(table);

                        // Botón para agregar todos los productos al carrito
                        var botonAgregar = $('<button>').addClass('btn btn-primary').text('Agregar al carrito').click(function() {
                            var clienteID = $('#clienteSelect').val();
                            productsToShow.forEach(function(producto, index) {
                                var cantidad = parseInt($('table').find('tr').eq(index + 1).find('input[type="number"]').val());
                                if (cantidad > 0) {
                                    postAlCarrito(producto, clienteID, cantidad);
                                }
                            });
                        });

                        $('#compra').append($('<br>')).append(botonAgregar);
                        mostrarPaginacion();
                    }

                    // Mostrar paginación
                    function mostrarPaginacion() {
                        var pagination = $('<div>').addClass('pagination');
                        for (var i = 1; i <= totalPages; i++) {
                            var button = $('<button>').addClass('pagination-button').text(i).click((function(page) {
                                return function() {
                                    mostrarProductosEnPagina(page);
                                };
                            })(i));
                            pagination.append(button);
                        }
                        $('#compra').append(pagination);
                        $('#compra').append('<br>');
                    }

                    // Mostrar la primera página
                    mostrarProductosEnPagina(1);

                },
                error: function(error) {
                    console.error('Error al obtener productos:', error);
                }
            });
        },
        error: function(error) {
            console.error('Error al obtener clientes:', error);
        }
    });
}

//··························································································································································

//SELECT :

// Estas funciónes han sido creadas para complementar otras para algunos selects de algunos filtros
// o comporbación de la existencia de los datos al crear uno nuvo y validarlo (Productos)
function getServicios(){
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Servicio',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#servicioID').empty();
            response.forEach(function(servicio) {
                $('#servicioID').append($('<option>', {
                    value: servicio.id,
                    text: servicio.nombre_servicio + " " + servicio.precio
                }));
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar servicio:', error);
        }
    });
}

$(document).ready(function() {
    getServicios();
});

function getClienteCita(){
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Cliente',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#clienteID').empty();
            response.forEach(function(cliente) {
                $('#clienteID').append($('<option>', {
                    value: cliente.id,
                    text: cliente.nombre + " " + cliente.apellidos
                }));
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar clientes:', error);
        }
    });
}

$(document).ready(function() {
    getClienteCita();
});

function getPeluqueros(){
    $.ajax({
        url: 'http://localhost/QuibixPC/conexiones/api.php/Peluquero',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#peluqueroID').empty();
            response.forEach(function(peluquero) {
                $('#peluqueroID').append($('<option>', {
                    value: peluquero.id,
                    text: peluquero.nombre + " " + peluquero.apellidos 
                }));
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar clientes:', error);
        }
    });
}

$(document).ready(function() {
    getPeluqueros();
});

function getCategorias() {
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
    getCategorias();
});

// VALIDACIONES :

// Validación del Email
function isValidEmail(email) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Validacion numero TLF
function isValidNumeroTlf(telefono) {
    var phoneRegex = /^\d{9}$/;
    return phoneRegex.test(telefono);
}

function isValidNombre(nombreCliente, apellidos) {
    var nameRegex = /^[A-Za-zÁáÉéÍíÓóÚúÑñÜü\s]+$/;
    return nameRegex.test(nombreCliente, apellidos);
}

//Detección de SQL Injection
function detectSqlInjection(string) {
    var sql_injection = false;
    var forbidden = [
        'select * ',
        'select ',
        'union all ',
        'union ',
        'insert',
        'drop',
        'where'
    ];

    string = decodeURIComponent(string.toLowerCase());

    forbidden.forEach(function(operator) {
        if (string.includes(operator) || new RegExp(operator, 'i').test(string)) {
            sql_injection = true;
        }
    });

    return sql_injection;
}
