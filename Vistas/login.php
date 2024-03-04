<?php
require_once '../controller/procesarLogin.php';

$login = new login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginBtn'])) {
    $login->processLogin();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../JS/validacionesLogin.js"></script>
    <title>Login</title>
    <link rel="stylesheet" href="../Public/CSS/login.css">
    <link rel="stylesheet" href="../Public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Public/CSS/validaciones.css">

</head>
<body>
    <header class="bg_animate">

        <div class="burbujas">
            <div class="burbuja"></div>
            <div class="burbuja"></div>
            <div class="burbuja"></div>
            <div class="burbuja"></div>
            <div class="burbuja"></div>
            <div class="burbuja"></div>
            <div class="burbuja"></div>
        </div>

        <div id="container">
            <div id="login_cont">
                <h2 class="title">Login</h2>
                <hr>

                <form action="" method="POST" class="login-form" onsubmit="return validaFormulario()">
                    <div class="mb-3">
                        <label for="usuario" class="subTitle">Usuario</label>
                        <input autofocus id="usuario" name="usuario" type="text" class="obligatorio recuadros">
                    </div>
                    <div>
                        <label for="clave" class="subTitle">Contraseña</label>
                        <input id="clave" name="clave" type="password" class="recuadros">
                    </div>
                    <hr>
                    <button type="submit" name="loginBtn" id="loginBtn" class="loginBtn">Login</button>
                </form>
            </div>
        </div>
    </header>
</body>
</html>