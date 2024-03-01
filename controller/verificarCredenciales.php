<?php
require_once '../conexiones/basedatosconexion.php';

class Auth {
    private $db;

    public function __construct() {
        $this->db = connection::dbConnection();
    }

    public function validarCredenciales($usuario, $clave) {
        $query = "SELECT * FROM usuarios WHERE usuario = ? AND clave = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("ss", $usuario, $clave);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            return true; // Credenciales válidas
        } else {
            return false; // Credenciales inválidas
        }

        $statement->close();
        $this->db->close();
    }
}

function validarCredenciales($usuario, $clave) {
    $auth = new Auth();
    $valido = $auth->validarCredenciales($usuario, $clave);
    return $valido;
}
?>
