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

class Producto {
    private $conn;

    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    public function getProductos() {
        $sql = "SELECT p.id, p.sku, p.nombre, p.descripcion, c.nombre AS categoria, p.stock, p.precio 
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

    public function postProducto($sku, $nombre, $descripcion, $categoriaID, $stock, $precio) {
        $sql = "INSERT INTO producto (sku, nombre, descripcion, categoriaID, stock, precio) VALUES (?, ?, ?, ?, ?, ?)";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("issiii", $sku, $nombre, $descripcion, $categoriaID, $stock, $precio);
        
        if ($statement->execute()) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'Producto registrado correctamente'));
        } else {
            if ($statement->errno == 1062) { // Error de duplicado de clave
                header('Content-Type: application/json');
                echo json_encode(array('error' => 'El SKU proporcionado ya está en uso. Por favor, elija otro.'));
            } else {
                header('Content-Type: application/json');
                echo json_encode(array('error' => 'Error al registrar producto: ' . $statement->error));
            }
        }
    }
    
    public function putProducto($id, $sku, $nombre, $descripcion, $categoriaID, $stock, $precio) {
        // Verificar si la categoría existe
        $categoriaExistente = $this->verificarCategoriaExistente($categoriaID);
        if (!$categoriaExistente) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'La categoría especificada no existe'));
            return;
        }
    
        // Continuar con la actualización del producto
        $sql = "UPDATE producto SET sku = ?, nombre = ?, descripcion = ?, categoriaID = ?, stock = ?, precio = ? WHERE id = ?";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("issiiii", $sku, $nombre, $descripcion, $categoriaID, $stock, $precio, $id);
        
        if ($statement->execute()) {
            header('Content-Type: application/json', true, 200);
            echo json_encode(array('mensaje' => 'Producto actualizado correctamente'));
        } else {
            header('Content-Type: application/json', true, 500);
            echo json_encode(array('mensaje' => 'Error al actualizar producto: ' . $statement->error));
        }
    }
    
    private function verificarCategoriaExistente($categoriaID) {
        $sql = "SELECT id FROM categoria WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $categoriaID);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows > 0;
    }

    public function deleteProducto($id) {
        $sql = "DELETE FROM producto WHERE id = ?";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $id);
        
        if ($statement->execute()) {
            header('Content-Type: application/json', true, 200);
            echo json_encode(array('mensaje' => 'Producto eliminado correctamente'));
        } else {
            header('Content-Type: application/json', true, 500);
            echo json_encode(array('mensaje' => 'Error al eliminar producto: ' . $statement->error));
        }
    }
    
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Usuario') {
        $usuario = new Usuario();
        $usuario->getUsuarios();
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Cliente') {
        $cliente = new Cliente();
        $cliente->getClientes();
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Producto') {
        $producto = new Producto();
        $producto->getProductos();
    } else {
        // Verificar si la URI contiene /Cliente/ID
        $uriSegments = explode('/', $_SERVER['REQUEST_URI']);
        $clienteId = end($uriSegments);
        
        // Verificar si la URI contiene /Producto/ID
        $productoId = end($uriSegments);
        
        // Si el último segmento de la URI es un número (ID de cliente)
        if (is_numeric($clienteId)) {
            $cliente = new Cliente();
            // Llama a una función para obtener los detalles del cliente por su ID
            $cliente->getClientePorId($clienteId);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Cliente') {
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $cliente = new Cliente(); 
        $cliente->postCliente($nombre, $apellidos, $email, $telefono);
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Producto') {
        $sku = $_POST['sku'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $categoriaID = $_POST['categoriaID'];
        $stock = $_POST['stock'];
        $precio = $_POST['precio'];
        $producto = new Producto(); 
        $producto->postProducto($sku, $nombre, $descripcion, $categoriaID, $stock, $precio);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $uriSegments = explode('/', $_SERVER['REQUEST_URI']);
    $clienteId = end($uriSegments);
    $productoId = end($uriSegments);

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
    } elseif (is_numeric($productoId)) {
        $datosProducto = json_decode(file_get_contents("php://input"), true);
        
        if (isset($datosProducto['sku'], $datosProducto['nombre'], $datosProducto['descripcion'], $datosProducto['categoriaID'], $datosProducto['stock'], $datosProducto['precio'])) {

            $sku = $datosProducto['sku'];
            $nombre = $datosProducto['nombre'];
            $descripcion = $datosProducto['descripcion'];
            $categoriaID = $datosProducto['categoriaID'];
            $stock = $datosProducto['stock'];
            $precio = $datosProducto['precio'];
            
            $producto = new Producto();
            $producto->putProducto($productoId, $sku, $nombre, $descripcion, $categoriaID, $stock, $precio);
        } else {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'Datos incompletos en la solicitud'));
        }
    } else {
        header('Content-Type: application/json', true, 400);
        echo json_encode(array('mensaje' => 'ID de cliente o producto no válido'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $uriSegments = explode('/', $_SERVER['REQUEST_URI']);
    $clienteId = end($uriSegments);
    $productoId = end($uriSegments);

    if (is_numeric($clienteId)) {
        $cliente = new Cliente();
        $cliente->deleteCliente($clienteId);
    } elseif (is_numeric($productoId)) {
        $producto = new Producto();
        $producto->deleteProducto($productoId);
    } else {
        header('Content-Type: application/json', true, 400);
        echo json_encode(array('mensaje' => 'ID de cliente o producto no válido'));
    }
}
