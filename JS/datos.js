function showContenido(seccion) {

    if (seccion === 'Principal') {
        window.location.href = '../view/main.php';
    } else {
        $('.container').hide();
        $('#' + seccion).show();

        switch (seccion) {
            case 'productos':
                getProductos();
                break;
            default:
                break;
        }
    }
}


function getProductos() {

    $.ajax({
        url: 'http://localhost/proyectoquibix/QuibixPC/conexiones/api.php/Producto',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#productos').empty();

            var table = $('<table>').addClass('table').css('background-color', '#E3E2E2'); 
            var headerRow = $('<tr>');
            headerRow.append($('<th>').text('ID Producto'));
            headerRow.append($('<th>').text('ID Marca'));
            headerRow.append($('<th>').text('Nombre Marca'));
            headerRow.append($('<th>').text('ID Modelo'));
            headerRow.append($('<th>').text('Nombre Modelo'));
            headerRow.append($('<th>').text('Stock'));
            headerRow.append($('<th>').text('Precio Unidad'));
            headerRow.append($('<th>').text('Tallas'));
            headerRow.append($('<th>').text('Cantidad'));
            headerRow.append($('<th>').text('Acción'));
            table.append(headerRow);

            $.each(response, function(index, producto) {
                var row = $('<tr>');
                row.append($('<td>').text(producto.id));
                row.append($('<td>').text(producto.nombre));
                row.append($('<td>').text(producto.descripcion));
                row.append($('<td>').text(producto.categoriaID));
                row.append($('<td>').text(producto.stock));
                row.append($('<td>').text(producto.precio));
                
                
                table.append(row);
            });

            $('#productos').append(table);
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener productos:', error);
        }
    });
}