<?php
// Requiere la conexión a la base de datos
require_once 'basedatosconexion.php';

/**
 * Clase Token
 * Maneja la generación y verificación de tokens de autenticación de usuario
 */
class Token {
    private $conexion;

    /**
     * Constructor de la clase Token
     * Inicializa la conexión a la base de datos
     */
    public function __construct() {
        $this->conexion = connection::dbConnection();
    }

    /**
     * Método privado para generar un token aleatorio
     * @return string El token generado en formato hexadecimal
     */
    private function generateToken() {
        return bin2hex(random_bytes(32)); 
    }

    /**
     * Método para generar y registrar un token en la base de datos para un usuario específico
     * @param int $usuarioID El ID del usuario para el cual se genera el token
     */
    public function postToken($usuarioID) {
        // Duración de la sesión en segundos (2 días)
        $session_duration = 2 * (60 * 60 * 24);
        $token = $this->generateToken();
        
        // Calcular la fecha y hora de expiración del token
        $expiracion_timestamp = time() + $session_duration;
        $token_expiracion = date('Y-m-d H:i:s', $expiracion_timestamp);
        
        // Preparar la consulta SQL para insertar el token en la base de datos
        $query = "INSERT INTO token (token, token_expiracion, usuarioID) VALUES (?, ?, ?)";
        $statement = $this->conexion->prepare($query);
        $statement->bind_param("ssi", $token, $token_expiracion, $usuarioID);
        $statement->execute();
    }
    
    /**
     * Método para verificar si un token de usuario aún es válido
     * @param int $usuarioID El ID del usuario cuyo token se va a verificar
     * @return bool true si el token es válido, false si ha expirado o no existe
     */
    public function verificarToken($usuarioID) {
        // Obtener la fecha y hora actual
        $current_time = date('Y-m-d H:i:s');
        
        // Consulta SQL para obtener el último token registrado para el usuario
        $query = "SELECT * FROM token WHERE usuarioID = ? ORDER BY token_expiracion DESC LIMIT 1";

        // Preparar y ejecutar la consulta SQL
        $statement = $this->conexion->prepare($query);
        $statement->bind_param("i", $usuarioID);
        $statement->execute();

        // Obtener el resultado de la consulta
        $result = $statement->get_result();
        
        // Verificar si se encontraron resultados
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $expiracion = $row['token_expiracion'];

            // Verificar si el token aún no ha expirado
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
