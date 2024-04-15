<?php
require_once '../conexiones/basedatosconexion.php';
require_once '../conexiones/tokenGenerate.php';

class login{ 

    public function processLogin(){
        
        $conn = connection::dbConnection();


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginBtn'])) {

            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];
        
            $query = "SELECT id, email FROM usuario WHERE email = ? AND clave = ?";
            $statement = $conn->prepare($query);
            $statement->bind_param("ss", $usuario, $clave);
            $statement->execute();
            $result = $statement->get_result();
        
            if ($result->num_rows > 0) {

                $row = $result->fetch_assoc();
                $user_id = $row['id'];
            
                $token = new Token();
                $token->insertarToken($user_id);
            

                header("Location: ../Vistas/main.php");
                exit();
            }
        }

        header("Location: ../Vistas/login.php");
        exit();
    }

    public function cerrarSesion() {
        session_start();
        session_unset();
        session_destroy();
        
        // Devolver una respuesta JSON
        header('Content-Type: application/json');
        echo json_encode(array('mensaje' => 'Sesión cerrada correctamente'));
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/logout') {
    $login = new login();
    $login->cerrarSesion();
}
?>
