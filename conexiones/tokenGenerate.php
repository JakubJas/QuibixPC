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
            $session_duration = 2*(60*60*24);
            $token = $this->generateToken();
            
            
            $expiracion_timestamp = time() + $session_duration;
            $token_expiracion = date('Y-m-d H:i:s', $expiracion_timestamp);
            
            $query = "INSERT INTO token (token, token_expiracion, usuarioID) VALUES (?, ?, ?)";
            $statement = $this->conexion->prepare($query);
            $statement->bind_param("ssi", $token, $token_expiracion, $usuarioID);
            $statement->execute();
        }
        
        public function verificarToken($usuarioID) {
            $current_time = date('Y-m-d H:i:s');
            $query = "SELECT * FROM token WHERE usuarioID = ? ORDER BY token_expiracion DESC LIMIT 1";
    
            $statement = $this->conexion->prepare($query);
            $statement->bind_param("i", $usuarioID);
            $statement->execute();
    
            $result = $statement->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $expiracion = $row['token_expiracion'];
    
                if ($expiracion > $current_time) {
                    return true;
                } else {
                    echo "El token ha expirado.";
                    return false;
                }
    
            } else {
                echo "No se encontraron tokens para este usuario.";
                return false;
            }
        }
    }
?>
