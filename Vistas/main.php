<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link rel="stylesheet" href="../Public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Public/CSS/mainPref.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../JS/datos.js"></script>    

</head>
<body>
    <header class="bg_animate">
        <nav>
            <a href="main.php">
                <div class="logo"></div>
            </a>
            <input type="checkbox" id="menu">
            <label for="menu" class="ham"></label>
            <ul>
                <li><a href="#" onclick="showContenido('productos')">Productos</a></li>
                <li><a href="#" onclick="showContenido('')">Citas</a></li>
                <li><a href="#" onclick="showContenido('clientes')">Clientes</a></li>
                <li><a href="#" onclick="showContenido('carrito')">Carrito</a></li>
            </ul>
        </nav>
    </header>
    <div id="Principal" class="container">
        <div id="infoPelu" class="container">
            
            <h2>Peluqueria canina</h2>
            <h5>Nuestros clientes mas fieles</h5>
            
            <div id="imagen-container">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/Big.jpeg" class="img-fluid">
                            <p>Nico</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/Chiwi.jpeg" class="img-fluid">
                            <p>Pera</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/Pit.jpeg" class="img-fluid">
                            <p>Princesa</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/lab.jpeg" class="img-fluid">
                            <p>Pepe</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/Nor.jpeg" class="img-fluid">
                            <p>Linda</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/Husky.jpeg" class="img-fluid">
                            <p>Garchi</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3"></div>
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/Frances.jpeg" class="img-fluid">
                            <p>Boby</p>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
        
        <div id="clientes" class="container" style="display: none;">
        </div><br>

        <div id="nuevoCliente" class="container" style="display: none;">
            <h2>Agregar Nuevo Cliente</h2>
            <form id="formularioCliente">
                <div class="form-group">
                    <label for="nombreCliente">Nombre:</label>
                    <input type="text" class="form-control" id="nombreCliente" required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" class="form-control" id="apellidos" required>
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" class="form-control" id="telefono" required>
                </div>
                <button id="btnAgregarCliente" type="submit" class="btn btn-primary">Registrar Cliente</button>
            </form>
        </div>

        <div id="clienteExtenso" class="container" style="display: none;">
        </div><br>

        <div id="clienteEditar" class="container" style="display: none;">
        </div><br>

        <div id="productos" class="container" style="display: none;">
        </div><br>

        <div id="nuevoProducto" class="container" style="display: none;">
            <h2>Agregar Nuevo Producto</h2>
            <form id="formularioProducto">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sku">SKU:</label>
                            <input type="text" class="form-control" id="sku" required>
                        </div>
                        <div class="form-group">
                            <label for="nombreProducto">Nombre:</label>
                            <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <textarea id="descripcion" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stock">Stock:</label>
                            <input type="number" class="form-control" id="stock" required>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio:</label>
                            <input type="number" step="any" class="form-control" id="precio" required>
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoría:</label>
                            <select class="form-control" id="categoria" required>
                            </select>
                        </div>
                    </div>
                </div>
                <button id="btnAgregarProducto" type="submit" class="btn btn-primary">Crear Producto</button>
            </form>
        </div>

        <div id="carrito" class="container" style="display: none;">
        </div><br>


    </div>

    <footer>

    </footer>
</body>
</html>