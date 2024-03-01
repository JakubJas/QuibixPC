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

        public function insertarToken($usuarioID) {
            $session_duration = 3600;
        
            $token = $this->generateToken();
        
            // Calcular el tiempo de expiración
            $expiracion_timestamp = time() + $session_duration;
            $token_expiracion = date('Y-m-d H:i:s', $expiracion_timestamp);
        
            // Preparar la consulta SQL
            $query = "INSERT INTO token (token, token_expiracion, usuarioID) VALUES (?, ?, ?)";
            $statement = $this->conexion->prepare($query);
            $statement->bind_param("ssi", $token, $token_expiracion, $usuarioID);
            $statement->execute();
        }
        

        public function verificarToken($token, $usuarioID) {
            $current_time = date('Y-m-d H:i:s');
            $query = "SELECT * FROM token WHERE token = ? AND usuarioID = ? AND expires_at > ?";
            $statement = $this->conexion->prepare($query);
            $statement->bind_param("sis", $token, $usuarioID, $current_time);
            $statement->execute();
            $result = $statement->get_result();
            return $result->num_rows > 0;
        }

        public function eliminarToken($token) {
            $query = "DELETE FROM token WHERE token = ?";
            $statement = $this->conexion->prepare($query);
            $statement->bind_param("s", $token);
            $statement->execute();
        }
    }
    ?>
