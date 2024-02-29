<?php
require_once 'basedatosconexion.php';

class Token {
    private $conexion;

    public function __construct() {
        $this->conexion = connection::dbConnection();
    }

    private function generateToken() {
        return bin2hex(random_bytes(32)); 
    }

    public function insertarToken($user_id) {

        $session_duration = (3600*60*24)*2;

        $token = $this->generateToken();

        $token_expiracion = date('Y-m-d H:i:s', time() + $session_duration);

        $query = "INSERT INTO token (token, token_expiracion, usuarioID) VALUES (?, ?, ?)";
        $statement = $this->conexion->prepare($query);
        $statement->bind_param("sis", $token, $token_expiracion, $usuarioID);
        $statement->execute();
    }

    public function verificarToken($token, $user_id) {
        $current_time = date('Y-m-d H:i:s');
        $query = "SELECT * FROM tokens WHERE token = ? AND user_id = ? AND expires_at > ?";
        $statement = $this->conexion->prepare($query);
        $statement->bind_param("sis", $token, $user_id, $current_time);
        $statement->execute();
        $result = $statement->get_result();
        return $result->num_rows > 0;
    }

    public function eliminarToken($token) {
        $query = "DELETE FROM tokens WHERE token = ?";
        $statement = $this->conexion->prepare($query);
        $statement->bind_param("s", $token);
        $statement->execute();
    }
}
?>
