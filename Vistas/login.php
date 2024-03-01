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
    <title>Login</title>
    <link rel="stylesheet" href="../Public/CSS/login.css">
    <link rel="stylesheet" href="../Public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Public/CSS/validaciones.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../JS/validacionesLogin.js"></script>

</head>
<body>
    <div id="container">
        <div id="login_cont">
            <h2>Login</h2>

            <form action="" method="POST" class="login-form">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input autofocus id="usuario" name="usuario" type="text" class="obligatorio form-control">
                </div>
                <div>
                    <label for="clave" class="form-label">Contraseña</label>
                    <input id="clave" name="clave" type="password" class="form-control">
                </div>
                <button type="submit" name="loginBtn" id="loginBtn" class="btn btn-primary btn-lg btn-block">Login</button>
            </form>
        </div>
    </div>
</body>
</html>