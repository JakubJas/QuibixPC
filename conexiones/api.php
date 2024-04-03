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
            echo json_encode(array('mensaje' => 'Cliente registrado correctamente'));
        } else {
            echo json_encode(array('mensaje' => 'Error al registrar cliente: ' . $statement->error));
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_SERVER['REQUEST_URI'] === '/ProyectoQuibix/QuibixPC/conexiones/api.php/Usuario') {
        $usuario = new Usuario();
        $usuario->getUsuarios();
    } elseif ($_SERVER['REQUEST_URI'] === '/ProyectoQuibix/QuibixPC/conexiones/api.php/Producto') {
        $producto = new Producto();
        $producto->getProductos();
    } elseif ($_SERVER['REQUEST_URI'] === '/ProyectoQuibix/QuibixPC/conexiones/api.php/Cliente') {
        $cliente = new Cliente();
        $cliente->getClientes();
    }
}else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['apellidos'], $_POST['email'], $_POST['telefono'])) {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $cliente = new Cliente(); 
    $cliente->postCliente($nombre, $apellidos, $email, $telefono);
}