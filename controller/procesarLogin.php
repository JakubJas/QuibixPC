<?php
require_once '../conexiones/basedatosconexion.php';
require_once '../conexiones/tokenGenerate.php';

class login{ 

    public function processLogin(){
        session_start();
        $conn = connection::dbConnection();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginBtn'])) {
            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];
    
            $query = "SELECT id, email, clave FROM usuario WHERE email = ?";
            $statement = $conn->prepare($query);
            $statement->bind_param("s", $usuario);
            $statement->execute();
            $result = $statement->get_result();
    
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row['id'];
                $stored_password = $row['clave'];
                
                // Comparar la contraseña ingresada con la contraseña almacenada
                if ($clave === $stored_password) {
                    $token = new Token();
                    $token->insertarToken($user_id);

                    session_start();
                    $_SESSION['usuario_id'] = $user_id;
    
                    header("Location: ../Vistas/main.php");
                    exit();
                }
            }
        }
    
        // Si las credenciales son inválidas o no se proporcionaron, redirigir al usuario a la página de inicio de sesión
        header("Location: ../Vistas/login.php");
        exit();
    }
    
}
?>
