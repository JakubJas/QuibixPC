<?php
require 'basedatosconexion.php';
/** 
* Clase Cliente, aqui se tratan todas las gestíones/operaciones que 
* tienen que ver con el cliente/s en la base de datos.
*/
class Cliente {
    private $conn;

    /**
    *  Constructor de la clase Cliente.
    *  Inicializa la conexión a la base de datos.
    */
    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    /**
    * Método para obtener todos los clientes.
    * Devuelve un JSON con la información de todos los clientes.
    */
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

     /**
     * Método para registrar un nuevo cliente.
     * 
     * @param string $nombreCliente Nombre del cliente.
     * @param string $apellidos Apellidos del cliente.
     * @param string $email Email del cliente.
     * @param string $telefono Teléfono del cliente.
    */
    public function postCliente($nombreCliente, $apellidos, $email, $telefono) {
        
        //Otra manera de si el nombre y apellido no cintienen caracteres especiales ni numero
        if (!preg_match("/^[a-zA-Z]+$/", $nombreCliente) || !preg_match("/^[a-zA-Z]+$/", $apellidos)) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'El nombre y apellidos deben contener solo letras.'));
            return;
        }
        
        // Otra manera si el email es válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'El email no es válido.'));
            return;
        }
    
        // Otra manera de verificar del teléfono
        if (!preg_match("/^\d{9}$/", $telefono)) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'El teléfono debe contener exactamente 9 cifras.'));
            return;
        }

        $sql = "INSERT INTO cliente (nombre, apellidos, email, telefono) VALUES (?, ?, ?, ?)";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("ssss", $nombreCliente, $apellidos, $email, $telefono);
        
        if ($statement->execute()) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'Cliente registrado correctamente'));
        } else {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'Error al registrar cliente: ' . $statement->error));
        }
    }

    /**
    * Método para eliminar un cliente de la base de datos.
    *
    * @param int $id ID del cliente a eliminar.
    */
    public function deleteCliente($id) {
        $sqlCarrito = "SELECT COUNT(*) AS cantidad FROM Carrito WHERE clienteID = ?";
        $statementCarrito = $this->conn->prepare($sqlCarrito);
        $statementCarrito->bind_param("i", $id);
        $statementCarrito->execute();
        $resultadoCarrito = $statementCarrito->get_result();
        $filaCarrito = $resultadoCarrito->fetch_assoc();
        $cantidadCarrito = intval($filaCarrito['cantidad']);
    
        $sqlCitas = "SELECT COUNT(*) AS cantidad FROM Cita WHERE clienteID = ?";
        $statementCitas = $this->conn->prepare($sqlCitas);
        $statementCitas->bind_param("i", $id);
        $statementCitas->execute();
        $resultadoCitas = $statementCitas->get_result();
        $filaCitas = $resultadoCitas->fetch_assoc();
        $cantidadCitas = intval($filaCitas['cantidad']);
    
        if ($cantidadCarrito > 0 || $cantidadCitas > 0) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'El cliente tiene elementos en el carrito o citas asociadas. No se puede eliminar.'));
        } else {
            $sqlDelete = "DELETE FROM cliente WHERE id = ?";
            $statementDelete = $this->conn->prepare($sqlDelete);
            $statementDelete->bind_param("i", $id);
    
            if ($statementDelete->execute()) {
                header('Content-Type: application/json', true, 200);
                echo json_encode(array('mensaje' => 'Cliente eliminado correctamente'));
            } else {
                header('Content-Type: application/json', true, 500);
                echo json_encode(array('mensaje' => 'Error al eliminar cliente: ' . $statementDelete->error));
            }
        }
    }

    /**
    * Método para obtener un cliente específico por su ID.
    * Devuelve un JSON con la información del cliente.
    *
    * @param int $clienteId ID del cliente a obtener.
    */
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

    /**
    * Método para actualizar la información de un cliente existente.
    *
    * @param int $id ID del cliente a actualizar.
    * @param string $nombreClienteNuevo Nuevo nombre del cliente.
    * @param string $apellidosNuevo Nuevos apellidos del cliente.
    * @param string $emailNuevo Nuevo email del cliente.
    * @param string $telefonoNuevo Nuevo teléfono del cliente.
    */
    public function putCliente($id, $nombreCliente, $apellidos, $email, $telefono) {
        
        //Otra manera de si el nombre y apellido no cintienen caracteres especiales ni numero
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u", $nombreCliente) || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u", $apellidos)) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'El nombre y apellidos deben contener solo letras.'));
            return;
        }
        
        // Otra manera si el email es válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'El email no es válido.'));
            return;
        }
    
        // Otra manera de verificar del teléfono
        if (!preg_match("/^\d{9}$/", $telefono)) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'El teléfono debe contener exactamente 9 cifras.'));
            return;
        }
        
        $sql = "UPDATE cliente SET nombre = ?, apellidos = ?, email = ?, telefono = ? WHERE id = ?";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("ssssi", $nombreCliente, $apellidos, $email, $telefono, $id);
        
        if ($statement->execute()) {
            header('Content-Type: application/json', true, 200);
            echo json_encode(array('mensaje' => 'Cliente actualizado correctamente'));
        } else {
            header('Content-Type: application/json', true, 500);
            echo json_encode(array('mensaje' => 'Error al actualizar cliente: ' . $statement->error));
        }
    }

    /**
    * Método para obtener los productos en el carrito de un cliente específico.
    * Devuelve un JSON con la información de los productos en el carrito del cliente.
    *
    * @param int $clienteId ID del cliente del que se desean obtener los productos en el carrito.
    */
    public function getCarritoCliente($clienteId) {
        $sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, c.cantidad 
                FROM Carrito c 
                INNER JOIN Producto p ON c.producto_id = p.id 
                WHERE c.cliente_id = $clienteId";
    
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $carrito = array();
    
            while ($fila = $resultado->fetch_assoc()) {
                $carrito[] = $fila;
            }
    
            echo json_encode($carrito);
        } else {
            echo json_encode(array('mensaje' => 'El cliente no tiene productos en su carrito'));
        }
    }
}

/** 
* Clase Producto, en está clase haremos las gestiones de producto
* que tiene que ver en la base de datos.
*/
class Producto {
    private $conn;

    /**
    *  Constructor de la clase Producto.
    *  Inicializa la conexión a la base de datos.
    */
    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    /**
    * Método para obtener todos los productos.
    * Devuelve un JSON con la información de todos los productos.
    */
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

    /**
     * Método para registrar un nuevo producto.
     * 
     * @param int $sku El sku del cliente.
     * @param string $nombreProducto Nombre del producto.
     * @param string $descripcion Descripción del producto.
     * @param int $categoriaID El id de la categoria al que se quiere añadir el producto.
     * @param int $stock Stock del producto.
     * @param float $precio Precio del producto.
    */      
    public function postProducto($sku, $nombreProducto, $descripcion, $categoriaID, $stock, $precio) {
        
        // Verificaciones de las variables, si se han introducido todas y en el formato correcto
        $categoriaExistente = $this->verificarCategoriaExistente($categoriaID);
        $skuExistente = $this->verificarSkuExistente($sku);

        // Aqui comporbamos si la categoría que se introudce existe en la bd o no
        if (!$categoriaExistente) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'La categoría especificada no existe'));
            return;
        }
        
        // Aqui hacemos lo mismo que con la categoría
        if ($skuExistente) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'El sku ya existe'));
            return;
        }

        if (!$this->verificarNumeroValido($sku, $stock)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'El SKU debe ser un número positivo'));
            return;
        }

        if (!$this->verificarLetrasValido($nombreProducto)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'El nombre del producto solo puede contener letras y espacios'));
            return;
        }

        if (!$this->verificarPrecioValido($precio)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'El precio debe ser un número positivo con hasta dos decimales'));
            return;
        }

        // Si todo a sido correcto introducimos el dato en la bd
        $sql = "INSERT INTO producto (sku, nombre, descripcion, categoriaID, stock, precio) VALUES (?, ?, ?, ?, ?, ?)";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("issiii", $sku, $nombreProducto, $descripcion, $categoriaID, $stock, $precio);
        
        if ($statement->execute()) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'Producto registrado correctamente'));
        } else {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Error al registrar producto: ' . $statement->error));
        }
    }
    
    /**
    * Verifica si una categoría existe en la base de datos.
    *
    * @param int $categoriaID El ID de la categoría a verificar. 
    *
    * @return bool Devuelve true si la categoría existe, de lo contrario devuelve false.
    *
    */
    private function verificarCategoriaExistente($categoriaID) {
        $sql = "SELECT id FROM categoria WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $categoriaID);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows > 0;
    }

    /**
    * Verifica si un SKU ya está en uso en la base de datos.
    *
    * @param int $sku El SKU a verificar. 
    *
    * @return bool Devuelve true si el SKU ya está en uso, de lo contrario devuelve false.
    *
    */
    private function verificarSkuExistente($sku) {
        $sql = "SELECT id FROM producto WHERE sku = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("s", $sku);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows > 0;
    }    

    /**
    * Verifica si un número (SKU o stock) es válido y positivo.
    * 
    * @param int $sku El SKU a verificar.
    * @param int $stock El stock a verificar.
    * 
    * @return bool Devuelve true si el SKU y el stock son números positivos, de lo contrario devuelve false.
    */
    private function verificarNumeroValido($sku, $stock) {

        if (!preg_match('/^\d+$/', $sku, $stock)) {
            return false;
        }
        return $sku > 0;
    }

    /**
    * Verifica si un nombre de producto contiene solo letras y espacios.
    * 
    * @param string $nombreProducto El nombre del producto a verificar.
    * 
    * @return bool Devuelve true si el nombre del producto contiene solo letras y espacios, de lo contrario devuelve false.
    */
    private function verificarLetrasValido($nombreProducto) {
        return preg_match('/^[a-zA-Z\s]+$/', $nombreProducto);
    }

    /**
    * Verifica si un precio es válido, siendo un número positivo con hasta dos decimales.
    * 
    * @param float $precio El precio a verificar.
    * 
    * @return bool Devuelve true si el precio es un número positivo con hasta dos decimales, de lo contrario devuelve false.
    */
    public function verificarPrecioValido($precio) {
        return preg_match('/^\d+(\.\d{1,2})?$/', $precio) && $precio > 0;
    }

    /**
    * Método para eliminar un producto de la base de datos.
    * @param int $id ID del producto a eliminar.
    */
    public function deleteProducto($id) {
        $sqlCarrito = "SELECT COUNT(*) AS cantidad FROM Carrito WHERE productoID = ?";
        $statementCarrito = $this->conn->prepare($sqlCarrito);
        $statementCarrito->bind_param("i", $id);
        $statementCarrito->execute();
        $resultadoCarrito = $statementCarrito->get_result();
        $filaCarrito = $resultadoCarrito->fetch_assoc();
        $cantidadCarrito = intval($filaCarrito['cantidad']);
        
        if ($cantidadCarrito > 0) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'El producto se encuentra en el carrito'));
        } else {
            $sql = "DELETE FROM producto WHERE id = ?";
            
            $statement = $this->conn->prepare($sql);
            $statement->bind_param("i", $id);
            
            if ($statement->execute()) {
                header('Content-Type: application/json', true, 200);
                echo json_encode(array('mensaje' => 'Producto eliminado correctamente'));
            }
        }
    }

    /**
    * Metodo para actualizar el stock de a base de datos.
    *
    * @param int $productoID Es el id que se busca para cambiarle es stock.
    * @param int $nuevoStock Es el nuevo stock que va a integrarse a la bd.
    */
    public function putStockProducto($productoID, $nuevoStock) {
        // Preparar la consulta SQL para actualizar el stock del producto
        $sqlCarrito = "SELECT COUNT(*) AS cantidad FROM Carrito WHERE productoID = ?";
        $statementCitas = $this->conn->prepare($sqlCarrito);
        $statementCitas->bind_param("i", $id);
        $statementCitas->execute();
        $resultadoCitas = $statementCitas->get_result();
        $filaCitas = $resultadoCitas->fetch_assoc();
        $cantidadCarrito = intval($filaCitas['cantidad']);
    
        if ($cantidadCarrito > 0) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'El producto tiene elementos en el carrito. No se puede eliminar.'));
        }else{
            $sql = "UPDATE producto SET stock = ? WHERE id = ?";
            
            // Preparar la declaración y enlazar los parámetros
            $statement = $this->conn->prepare($sql);
            $statement->bind_param("ii", $nuevoStock, $productoID);
        }
        // Ejecutar la consulta
        if ($statement->execute()) {
            // Devolver una respuesta JSON de éxito
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'Stock del producto actualizado correctamente'));
        } else {
            // Devolver una respuesta JSON de error
            header('Content-Type: application/json', true, 500);
            echo json_encode(array('error' => 'Error al actualizar el stock del producto: ' . $statement->error));
        }
    }
    
}

/**
 * Clase Categoria, esta clase la usaremos para hacer llamadas en otras funciones y clases.
 * La aprovecharemos para algunos filtros y inputs tipo select para creación de datos nuevos.
 */
class Categoria{
    private $conn;

    /**
    *  Constructor de la clase Categoria.
    *  Inicializa la conexión a la base de datos.
    */
    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    /**
    * Método para obtener todos las categorias.
    * Devuelve un JSON con la información de todas las categorias.
    */
    public function getCategorias() {
        $sql = "SELECT c.id, c.nombre FROM Categoria c";
    
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $categorias = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $categorias[] = $columna;
            }
            echo json_encode($categorias);
        } else {
            echo json_encode(array('mensaje' => 'No se encontraron categorias'));
        }
    }
}

/**
 * Clase Peluquero, esta clase la usaremos para hacer llamadas en otras funciones y clases.
 * La aprovecharemos para algunos filtros y inputs tipo select para creación de datos nuevos.
 */
class Peluquero{
    private $conn;

    /**
    *  Constructor de la clase Peluquero.
    *  Inicializa la conexión a la base de datos.
    */
    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    /**
    * Método para obtener todos los peluqueros.
    * Devuelve un JSON con la información de todos los peluqueros.
    */
    public function getPeluquero() {
        $sql = "SELECT p.id, p.nombre, p.apellidos, p.telefono FROM Peluquero p";
    
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $peluqueros = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $peluqueros[] = $columna;
            }
            echo json_encode($peluqueros);
        } else {
            echo json_encode(array('mensaje' => 'No se encontraron peluqueros'));
        }
    }
}

/**
 * Clase Servicio, esta clase la usaremos para hacer llamadas en otras funciones y clases.
 * La aprovecharemos para algunos filtros y inputs tipo select para creación de datos nuevos.
 */
class Servicio{
    private $conn;

    /**
    *  Constructor de la clase Servicio.
    *  Inicializa la conexión a la base de datos.
    */
    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    /**
    * Método para obtener todos los servicios.
    * Devuelve un JSON con la información de todos los servicios.
    */
    public function getServicio() {
        $sql = "SELECT s.id, s.nombre_servicio, s.precio FROM Servicio s";
    
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $servicios = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $servicios[] = $columna;
            }
            echo json_encode($servicios);
        } else {
            echo json_encode(array('mensaje' => 'No se encontraron categorias'));
        }
    }
}

/**
 * Calse Carrito, en esta clase gestionaremos las operaciones que tiene el carrito.
 * Aquí nos encontraremos valores/parametros tanto del propio carrito como de clientes y productos
 */
class Carrito{
    private $conn;

    /**
    *  Constructor de la clase Carrito.
    *  Inicializa la conexión a la base de datos.
    */
    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    /**
    * Método para obtener todos los clientes y productos que se han añadido al carrito.
    * Devuelve un JSON con la información de todos los clientes y productos que se han añadido al carrito.
    */
    public function getCarrito() {
        $sql = "SELECT c.id, cl.id AS clienteID, cl.nombre AS nombre, cl.apellidos AS apellido, p.nombre AS producto, e.estado AS estado, c.cantidad, c.precio_total
                FROM Carrito c
                INNER JOIN Cliente cl ON c.clienteID = cl.id
                INNER JOIN Producto p ON c.productoID = p.id
                INNER JOIN Estado e ON c.estadoID = e.id";
    
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $carrito = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $carrito[] = $columna;
            }
            echo json_encode($carrito);
        } else {
            echo json_encode(array('mensaje' => 'Carrito está vacío'));
        }
    }

    /**
    * Método para añadir un producto al carrito.
    * 
    * @param int $clienteID El ID del cliente.
    * @param int $productoID El ID del producto a añadir.
    * @param int $estadoID El ID del estado del producto en el carrito.
    * @param int $cantidad La cantidad del producto a añadir.
    * @param float $precioTotal El precio total del producto en el carrito.
    */
    public function postCarrito($clienteID, $productoID, $estadoID, $cantidad, $precioTotal) {
        // Preparar la consulta SQL para insertar el producto en el carrito
        $clienteExistente = $this->verificarClienteExistente($clienteID);
        if (!$clienteExistente) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'La categoría especificada no existe'));
            return;
        }

        $estadoExistente = $this->verificarEstadoExistente($estadoID);
        if (!$estadoExistente) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'La categoría especificada no existe'));
            return;
        }

        $productoExistente = $this->verificarProductoExistente($productoID);
        if (!$productoExistente) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'La categoría especificada no existe'));
            return;
        }

        $sql = "INSERT INTO carrito (clienteID, productoID, estadoID, cantidad, precio_total) 
                VALUES (?, ?, ?, ?, ?)";
        
        $statement = $this->conn->prepare($sql);

        $statement->bind_param("iiidd", $clienteID, $productoID, $estadoID, $cantidad, $precioTotal);

        if ($statement->execute()) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'Producto registrado correctamente'));
        } else {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Error al registrar producto: ' . $statement->error));
        }
    }

    /**
    * Método privado para verificar si un cliente existe en la base de datos.
    * 
    * @param int $clienteID El ID del cliente a verificar.
    * 
    * @return bool Devuelve true si el cliente existe, de lo contrario devuelve false.
    */
    private function verificarClienteExistente($clienteID) {
        $sql = "SELECT id FROM cliente WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $clienteID);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows > 0;
    }
    
    /**
    * Método privado para verificar si un producto existe en la base de datos.
    * 
    * @param int $productoID El ID del producto a verificar.
    * 
    * @return bool Devuelve true si el producto existe, de lo contrario devuelve false.
    */
    private function verificarProductoExistente($productoID) {
        $sql = "SELECT id FROM producto WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $productoID);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows > 0;
    }
    
     /**
    * Método privado para verificar si un estado existe en la base de datos.
    * 
    * @param int $estadoID El ID del estado a verificar.
    * 
    * @return bool Devuelve true si el estado existe, de lo contrario devuelve false.
    */
    private function verificarEstadoExistente($estadoID) {
        $sql = "SELECT id FROM estado WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $estadoID);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows > 0;
    }

    /**
    * Método para eliminar un elemento del carrito por su ID, y revertir la cantidad eliminada al stock del producto asociado.
    * 
    * @param int $id El ID del elemento del carrito a eliminar.
    */
    public function deleteCarrito($id) {
        // Obtener la cantidad eliminada del carrito y el ID del producto asociado
        $sql = "SELECT productoID, cantidad FROM carrito WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->bind_result($productoID, $cantidadEliminada);
        $statement->fetch();
        $statement->close();
        
        // Eliminar el elemento del carrito
        $sql = "DELETE FROM carrito WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $id);
        
        if ($statement->execute()) {
            // Revertir la cantidad eliminada del carrito al stock del producto
            $sql = "UPDATE producto SET stock = stock + ? WHERE id = ?";
            $statement = $this->conn->prepare($sql);
            $statement->bind_param("ii", $cantidadEliminada, $productoID);
            $statement->execute();
    
            header('Content-Type: application/json', true, 200);
            echo json_encode(array('mensaje' => 'Carrito eliminado correctamente'));
        } else {
            header('Content-Type: application/json', true, 500);
            echo json_encode(array('mensaje' => 'Error al eliminar carrito: ' . $statement->error));
        }
    }
    
}

/**
 * Clase Cita, esta clase gestiona las operaciones relacionadas con las citas de los clientes.
 */
class Cita{
    private $conn;

    /**
    * Constructor de la clase Cita.
    * Inicializa la conexión a la base de datos.
    */
    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    /**
    * Método para obtener todas las citas de la base de datos y devolverlas en formato JSON.
    * 
    * @return string Devuelve un JSON con la información de todas las citas.
    */
    public function getCitas() {
        $sql = "SELECT c.id, c.horario, cl.id AS clienteID, cl.nombre AS nombre, cl.apellidos AS apellido, s.nombre_servicio AS servicio, p.nombre AS peluquero
                FROM Cita c
                INNER JOIN Cliente cl ON c.clienteID = cl.id
                INNER JOIN Servicio s ON c.servicioID = s.id
                INNER JOIN Peluquero p ON c.peluqueroID = p.id";
    
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $citas = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $citas[] = $columna;
            }
            echo json_encode($citas);
        } else {
            echo json_encode(array('mensaje' => 'No hay citas'));
        }
    }

    /**
    * Método para registrar una nueva cita.
    * 
    * @param string $horario El horario de la cita.
    * @param int $clienteID El ID del cliente.
    * @param int $servicioID El ID del servicio de la cita.
    * @param int $peluqueroID El ID del peluquero de la cita.
    */
    public function postCita($horario, $clienteID, $servicioID, $peluqueroID) {

        $clienteExistente = $this->verificarClienteExistente($clienteID);
        if (!$clienteExistente) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'El cliente especificado no existe'));
            return;
        }
    
        $servicioExistente = $this->verificarServicioExistente($servicioID);
        if (!$servicioExistente) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'El servicio especificado no existe'));
            return;
        }
    
        $peluqueroExistente = $this->verificarPeluqueroExistente($peluqueroID);
        if (!$peluqueroExistente) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'El peluquero especificado no existe'));
            return;
        }
    
        $peluqueroDisponible = $this->verificarDisponibilidadPeluquero($horario, $peluqueroID);
        if (!$peluqueroDisponible) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'El peluquero ya tiene una cita programada para esta hora'));
            return;
        }
        
        // Preparar la consulta SQL para insertar la cita
        $sql = "INSERT INTO cita (horario, clienteID, servicioID, peluqueroID) 
                VALUES (?, ?, ?, ?)";
        
        $statement = $this->conn->prepare($sql);
    
        $statement->bind_param("siii", $horario, $clienteID, $servicioID, $peluqueroID);
    
        if ($statement->execute()) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'Cita registrada correctamente'));
        } else {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Error al registrar la cita: ' . $statement->error));
        }
    }

     /**
    * Método privado para verificar si un cliente existe en la base de datos.
    * 
    * @param int $clienteID El ID del cliente a verificar.
    * 
    * @return bool Devuelve true si el cliente existe, de lo contrario devuelve false.
    */
    private function verificarClienteExistente($clienteID) {
        $sql = "SELECT id FROM cliente WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $clienteID);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows > 0;
    }
    
    /**
    * Método privado para verificar si un servicio existe en la base de datos.
    * 
    * @param int $servicioID El ID del servicio a verificar.
    * 
    * @return bool Devuelve true si el servicio existe, de lo contrario devuelve false.
    */
    private function verificarServicioExistente($servicioID) {
        $sql = "SELECT id FROM servicio WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $servicioID);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows > 0;
    }
    
    /**
    * Método privado para verificar si un peluquero existe en la base de datos.
    * 
    * @param int $peluqueroID El ID del peluquero a verificar.
    * 
    * @return bool Devuelve true si el peluquero existe, de lo contrario devuelve false.
    */
    private function verificarPeluqueroExistente($peluqueroID) {
        $sql = "SELECT id FROM peluquero WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $peluqueroID);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows > 0;
    }

     /**
    * Método privado para verificar la disponibilidad de un peluquero en un horario específico.
    * 
    * @param string $horario El horario de la cita.
    * @param int $peluqueroID El ID del peluquero.
    * 
    * @return bool Devuelve true si el peluquero está disponible, de lo contrario devuelve false.
    */
    private function verificarDisponibilidadPeluquero($horario, $peluqueroID) {
     
        $sql = "SELECT COUNT(*) as total FROM cita WHERE horario = ? AND peluqueroID = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("si", $horario, $peluqueroID);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] == 0;
    }

    /**
    * Método para eliminar una cita por su ID.
    * 
    * @param int $id El ID de la cita a eliminar.
    */
    public function deleteCita($id) {

        $sql = "DELETE FROM cita WHERE id = ?";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $id);
        
        if ($statement->execute()) {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'Cita eliminada correctamente'));
        } else {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Error al eliminar la cita: ' . $statement->error));
        }
    }
    
} 

// GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Cliente') {
        $cliente = new Cliente();
        $cliente->getClientes();
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/CarritoCliente') {
        $cliente = new Cliente();
        $cliente->getCarritoCliente($clienteID);
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Producto') {
        $producto = new Producto();
        $producto->getProductos();
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Categoria') {
        $categoria = new Categoria();
        $categoria->getCategorias();
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Carrito') {
        $categoria = new Carrito();
        $categoria->getCarrito();
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Cita') {
        $categoria = new Cita();
        $categoria->getCitas();
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Peluquero') {
        $categoria = new Peluquero();
        $categoria->getPeluquero();
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Servicio') {
        $categoria = new Servicio();
        $categoria->getServicio();
    } else {
        $uriSegments = explode('/', $_SERVER['REQUEST_URI']);
        $clienteId = end($uriSegments);
                
        if (is_numeric($clienteId)) {
            $cliente = new Cliente();
            $cliente->getClientePorId($clienteId);
        }
    }
} 

// POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Cliente') {
        $nombreCliente = $_POST['nombreCliente'];
        $apellidos = $_POST['apellidos'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];

        $cliente = new Cliente(); 
        $cliente->postCliente($nombreCliente, $apellidos, $email, $telefono);
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Producto') {
        $sku = $_POST['sku'];
        $nombreProducto = $_POST['nombreProducto'];
        $descripcion = $_POST['descripcion'];
        $categoriaID = $_POST['categoriaID'];
        $stock = $_POST['stock'];
        $precio = $_POST['precio'];

        $producto = new Producto(); 
        $producto->postProducto($sku, $nombreProducto, $descripcion, $categoriaID, $stock, $precio);
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Carrito') {
        $clienteID = $_POST['clienteID'];
        $productoID = $_POST['productoID'];
        $estadoID = 1; 
        $cantidad = $_POST['cantidad'];
        $precioTotal = $_POST['precioTotal'];
    
        $carrito = new Carrito();
        $carrito->postCarrito($clienteID, $productoID, $estadoID, $cantidad, $precioTotal);
    } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Cita') {
        $horario = $_POST['horario'];
        $clienteID = $_POST['clienteID'];
        $servicioID = $_POST['servicioID'];
        $peluqueroID = $_POST['peluqueroID'];
    
        $cita = new Cita();
        $cita-> postCita($horario, $clienteID, $servicioID, $peluqueroID);
    }    
} 

// PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $uriSegments = explode('/', $_SERVER['REQUEST_URI']);
    $lastSegment = end($uriSegments);

    if (is_numeric($lastSegment)) {
        if (strpos($_SERVER['REQUEST_URI'], '/QuibixPC/conexiones/api.php/Cliente') !== false) {
            // Procesar la actualización del cliente
            $datosCliente = json_decode(file_get_contents("php://input"), true);
            
            if (isset($datosCliente['nombre'], $datosCliente['apellidos'], $datosCliente['email'], $datosCliente['telefono'])) {
                $nombre = $datosCliente['nombre'];
                $apellidos = $datosCliente['apellidos'];
                $email = $datosCliente['email'];
                $telefono = $datosCliente['telefono'];
                
                $cliente = new Cliente();
                $cliente->putCliente($lastSegment, $nombre, $apellidos, $email, $telefono);
            } else {
                // Devolver un mensaje de error si faltan datos en la solicitud
                header('Content-Type: application/json', true, 400);
                echo json_encode(array('mensaje' => 'Datos incompletos en la solicitud'));
            }
        } elseif (strpos($_SERVER['REQUEST_URI'], '/QuibixPC/conexiones/api.php/Producto') !== false) {
                parse_str(file_get_contents("php://input"), $putData);
                $nuevoStock = $putData['nuevoStock'];
    
                $producto = new Producto();
                $producto->putStockProducto($lastSegment, $nuevoStock);
            
        } else {
            // Devolver un mensaje de error si la URI no es reconocida
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'URI no válida para solicitud PUT'));
        }
    } else {
        // Devolver un mensaje de error si el último segmento de la URI no es numérico
        header('Content-Type: application/json', true, 400);
        echo json_encode(array('mensaje' => 'ID no válido en la URI'));
    }
} 

// DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $uriSegments = explode('/', $_SERVER['REQUEST_URI']);

    $lastSegment = end($uriSegments);

    if (is_numeric($lastSegment)) {
        if ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Cliente/' . $lastSegment) {
            $cliente = new Cliente();
            $cliente->deleteCliente($lastSegment);
        } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Producto/' . $lastSegment) {
            $producto = new Producto();
            $producto->deleteProducto($lastSegment);
        } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Carrito/' . $lastSegment) {
            $productoCarrito = new Carrito();
            $productoCarrito->deleteCarrito($lastSegment);
        } elseif ($_SERVER['REQUEST_URI'] === '/QuibixPC/conexiones/api.php/Cita/' . $lastSegment) {
            $cita = new Cita();
            $cita->deleteCita($lastSegment);
        } else {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('mensaje' => 'ID no valido'));
        }
    } else {
        header('Content-Type: application/json', true, 400);
        echo json_encode(array('mensaje' => 'ID no valido'));
    }
}

