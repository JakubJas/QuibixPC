<?php
require 'basedatosconexion.php';

class Usuario {
    private $conn;
    
    public function __construct()
    {
       $this->conn = connection::dbConnection();    
    }

    public function getUsuarios() {
        $sql = "SELECT * FROM usuario";
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $usuarios = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $usuarios[] = $columna;
            }
            echo json_encode($usuarios);
        } else {
            echo json_encode(array('mensaje' => 'No se encontraron usuarios'));
        }
    }
}

class Producto {
    private $conn;

    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    public function getProductos() {
        $sql = "SELECT p.id, p.nombre, p.descripcion, c.nombre AS categoria, p.stock, p.precio 
                FROM Producto p
                INNER JOIN Categoria c ON p.categoriaID = c.id";
    
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $productos = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $productos[] = $columna;
            }
            echo json_encode($productos);
        } else {
            echo json_encode(array('mensaje' => 'No se encontraron productos'));
        }
    }
    
}

class Cliente {
    private $conn;

    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    public function getClientes() {
        $sql = "SELECT c.id, c.nombre, c.apellidos, c.email, c.telefono FROM Cliente c";
    
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $clientes = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $clientes[] = $columna;
            }
            echo json_encode($clientes);
        } else {
            echo json_encode(array('mensaje' => 'No se encontraron clientes'));
        }
    }
    
    public function postCliente($nombre, $apellidos, $email, $telefono) {
        $sql = "INSERT INTO cliente (nombre, apellidos, email, telefono) VALUES (?, ?, ?, ?)";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("ssss", $nombre, $apellidos, $email, $telefono);
        
        if ($statement->execute()) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'Cliente registrado correctamente'));
        } else {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'Error al registrar cliente: ' . $statement->error));
        }
    }

    public function deleteCliente($id) {
        $sql = "DELETE FROM cliente WHERE id = ?";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $id);
        
        if ($statement->execute()) {
            header('Content-Type: application/json', true, 200);
            echo json_encode(array('mensaje' => 'Cliente eliminado correctamente'));
        } else {
            header('Content-Type: application/json', true, 500);
            echo json_encode(array('mensaje' => 'Error al eliminar cliente: ' . $statement->error));
        }
    }

    public function getClientePorId($clienteId) {
        $sql = "SELECT id, nombre, apellidos, email, telefono FROM cliente WHERE id = ?";
    
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $clienteId);
        $statement->execute();
        
        $resultado = $statement->get_result();
        
        if ($resultado->num_rows > 0) {
            $cliente = $resultado->fetch_assoc();
            header('Content-Type: application/json');
            echo json_encode($cliente);
        } else {
            header('Content-Type: application/json', true, 404);
            echo json_encode(array('mensaje' => 'Cliente no encontrado'));
        }
    }

    public function putCliente($id, $nombre, $apellidos, $email, $telefono) {
        $sql = "UPDATE cliente SET nombre = ?, apellidos = ?, email = ?, telefono = ? WHERE id = ?";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("ssssi", $nombre, $apellidos, $email, $telefono, $id);
        
        if ($statement->execute()) {
            header('Content-Type: application/json', true, 200);
            echo json_encode(array('mensaje' => 'Cliente actualizado correctamente'));
        } else {
            header('Content-Type: application/json', true, 500);
            echo json_encode(array('mensaje' => 'Error al actualizar cliente: ' . $statement->error));
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Usuario') {
        $usuario = new Usuario();
        $usuario->getUsuarios();
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Producto') {
        $producto = new Producto();
        $producto->getProductos();
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Cliente') {
        $cliente = new Cliente();
        $cliente->getClientes();
    } else {
        // Verifica si la URI contiene /Cliente/ID
        $uriSegments = explode('/', $_SERVER['REQUEST_URI']);
        $clienteId = end($uriSegments);
        
        // Si el último segmento de la URI es un número (ID de cliente)
        if (is_numeric($clienteId)) {
            $cliente = new Cliente();
            // Llama a una función para obtener los detalles del cliente por su ID
            $cliente->getClientePorId($clienteId);
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['apellidos'], $_POST['email'], $_POST['telefono'])) {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $cliente = new Cliente(); 
    $cliente->postCliente($nombre, $apellidos, $email, $telefono);
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $uriSegments = explode('/', $_SERVER['REQUEST_URI']);
    $clienteId = end($uriSegments);

    if (is_numeric($clienteId)) {
        $cliente = new Cliente();
        $cliente->deleteCliente($clienteId);
    } else {
        header('Content-Type: application/json', true, 400);
        echo json_encode(array('mensaje' => 'ID de cliente no válido'));
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $uriSegments = explode('/', $_SERVER['REQUEST_URI']);
    $clienteId = end($uriSegments);

    if (is_numeric($clienteId)) {
        $datosCliente = json_decode(file_get_contents("php://input"), true);
        
        if (isset($datosCliente['nombre'], $datosCliente['apellidos'], $datosCliente['email'], $datosCliente['telefono'])) {

            $nombre = $datosCliente['nombre'];
            $apellidos = $datosCliente['apellidos'];
            $email = $datosCliente['email'];
            $telefono = $datosCliente['telefono'];
            
            $cliente = new Cliente();
            $cliente->putCliente($clienteId, $nombre, $apellidos, $email, $telefono);
        } else {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'Datos incompletos en la solicitud'));
        }
    } else {
        header('Content-Type: application/json', true, 400);
        echo json_encode(array('mensaje' => 'ID de cliente no válido'));
    }
}