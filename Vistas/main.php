<?php
require_once '../conexiones/tokenGenerate.php';

    session_start();
    
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: login.php");
        exit();
    }
    $token = new Token();

    if ($token->verificarToken($_SESSION['usuario_id'])) {
    } else {
        header("Location: ../Vistas/login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link rel="stylesheet" href="../Public/CSS/mainPref.css">
    <link rel="stylesheet" href="../Public/bootstrap/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../JS/datos.js"></script>
 

</head>
<body>
    <header class="bg_animate">
        <nav>
            <a href="main.php">
                <div class="logo"></div>
            </a>
            <div class="listCenter">
                <ul class="headMenu">
                    <li><a href="#" onclick="showContenido('productos')">Productos</a></li>
                    <li><a href="#" onclick="showContenido('citas')">Citas</a></li>
                    <li><a href="#" onclick="showContenido('clientes')">Clientes</a></li>
                    <li><a href="#" onclick="showContenido('compra')">Compra</a></li>
                    <li><a href="#" onclick="showContenido('carrito')">Carrito</a></li>
                    <li><a href="../controller/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <div id="Principal" class="container">
        <div id="infoPelu" class="container">
            <br>
            <h2>Peluqueria canina</h2>
            <h5>Nuestros clientes mas fieles</h5>
            
            <div id="imagen-container">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/Big.jpeg" class="bor-img img-fluid">
                            <p>Nico</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/Chiwi.jpeg" class="bor-img img-fluid">
                            <p>Pera</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/Pit.jpeg" class="bor-img img-fluid">
                            <p>Princesa</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/lab.jpeg" class="bor-img img-fluid">
                            <p>Pepe</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/Nor.jpeg" class="bor-img img-fluid">
                            <p>Linda</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/Husky.jpeg" class="bor-img img-fluid">
                            <p>Garchi</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3"></div>
                    <div class="col-md-4 mb-3">
                        <div class="img">
                            <img src="../Public/IMG_Web/Frances.jpeg" class="bor-img img-fluid">
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
                            <input type="number" class="form-control" id="sku" required>
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

        <div id="compra" class="container" style="display: none;">
        </div><br>

        <div id="citas" class="container" style="display: none;">
        </div><br>

        <div id="nuevaCita" class="container" style="display: none;">
            <h2>Agregar Nueva Cita</h2>
            <form id="formularioCita">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="horario">Hora/Día:</label>
                            <input type="text" class="form-control" id="horario" name="horario" required>
                        </div>
                        <div class="form-group">
                            <label for="clienteID">Cliente:</label>
                            <select class="form-control" id="clienteID" name="clienteID" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                            <label for="servicioID">Servicio:</label>
                            <select class="form-control" id="servicioID" name="servicioID" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="peluqueroID">Peluquero:</label>
                            <select class="form-control" id="peluqueroID" name="peluqueroID" required>
                            </select>
                        </div>
                    </div>
                </div>
                <button id="btnAgregarCita" type="submit" class="btn btn-primary">Crear Cita</button>
            </form>
        </div>

    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4>Contacto</h4>
                    <hr>
                    <p>Teléfono: +123456789</p>
                    <p>Email: jakubj@gmail.com</p>
                    <p>Dirección: C/ Secundino Delgado 9, San Bartolome de Tirajana, Las Palmas</p>
                </div>
                <div class="col-md-4">
                    <div class="logo2"></div>
                </div>
                <div class="col-md-4">
                    <h4>Redes Sociales</h4>
                    <hr>
                    <ul class="list-inline footerMenu">
                        <li class="list-inline-item"><a href="#">Facebook</a></li>
                        <li class="list-inline-item"><a href="#">Twitter</a></li>
                        <li class="list-inline-item"><a href="#">Instagram</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>