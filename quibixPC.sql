CREATE DATABASE IF NOT EXISTS QuibixPC;

USE quibixpc;

CREATE TABLE IF NOT EXISTS usuario(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) NOT NULL,
    clave VARCHAR(25) NOT NULL,
    email VARCHAR(255),
	telefono INT(20)
);
CREATE TABLE IF NOT EXISTS token(
	id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(255),
    token_expiracion DATE,
    usuarioID INT
);
CREATE TABLE IF NOT EXISTS cliente(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    telefono VARCHAR(20)
);
CREATE TABLE IF NOT EXISTS servicio(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_servicio VARCHAR(255),
    precio FLOAT
);
CREATE TABLE IF NOT EXISTS peluquero(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    apellidos VARCHAR(255),
    telefono INT(10)
);
CREATE TABLE IF NOT EXISTS cita(
	horario DATETIME,
    clienteID INT,
    servicioID INT,
    peluqueroID INT,
    primary key(horario, clienteID, servicioID, peluqueroID)
);
CREATE TABLE IF NOT EXISTS categoria(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255)
);
CREATE TABLE IF NOT EXISTS producto(
	id INT AUTO_INCREMENT PRIMARY KEY,
    sku INT(40),
    nombre VARCHAR(25),
    descripcion VARCHAR(255),
    categoriaID INT,
    stock INT(255),
    precio FLOAT,
    imagen LONGBLOB
);
CREATE TABLE IF NOT EXISTS estado(
	id INT AUTO_INCREMENT PRIMARY KEY,
    estado VARCHAR(50)
);
CREATE TABLE IF NOT EXISTS carrito(
	id INT AUTO_INCREMENT PRIMARY KEY,
    productoID INT,
    estadoID INT,
    cantidad INT(20),
    precio_total FLOAT
);

ALTER TABLE usuario
	ADD UNIQUE(email);
INSERT IGNORE INTO usuario(nombre, apellidos, clave, email, telefono) VALUES(
	'Jakub', 'Jasinski', '1234', 'jakubj23@mail.com', 664234999);
    
ALTER TABLE cliente
	ADD UNIQUE(apellidos, email, telefono);
INSERT IGNORE INTO cliente(nombre, apellidos, email, telefono) VALUES(
	'Juan', 'Gonzales', 'JG@mail,com', 663438899),(
    'Pedro', 'Gorro', 'PG@mail,com', 663438899);    
    
ALTER TABLE servicio
	ADD UNIQUE(nombre_servicio);
INSERT IGNORE INTO servicio(nombre_servicio, precio) VALUES(
	'Corte', 19.99),(
    'Lavado', 10.99),(
    'Corte y lavado', 27.50);
    
ALTER TABLE estado
	ADD UNIQUE(estado);
INSERT IGNORE INTO estado(estado) VALUES(
	'Pendiente de pago'),(
    'Pagado'),(
    'Anulado'),(
    'Preparando para el envio'),(
    'Entregado'),(
    'Enviado');
    
ALTER TABLE peluquero
	ADD UNIQUE(apellidos, telefono);
INSERT IGNORE INTO peluquero(nombre, apellidos, telefono) VALUES(
	'Ruperto', 'Martel', 772889002),(
    'Luisa', 'Peña', 665322889);    
    
ALTER TABLE categoria
	ADD UNIQUE(nombre);
INSERT IGNORE INTO categoria(nombre) VALUES(
	'Cuidados para animales'),(
    'Juguetes'),(
    'Comida');
    
ALTER TABLE producto
	ADD UNIQUE(sku);
INSERT IGNORE INTO producto(sku, nombre, descripcion, categoriaID, stock, precio, imagen) VALUES (
    321, 'Peine Perro', 'Un peine con dos caras: una de púas metálicas y separadas entre sí, ideal para desenredar con cuidado y otra de cerdas naturales o sintéticas, para mantener el pelo suave y brillante.',1 ,200, 45.95, load_file('C:/xampp/htdocs/Goals/QuibicPC/public/Imagenes/peineperro.jpg')),
    (234, 'Champu Oster Coco', 'Champu para perros con extracto natural de coco para un toque de pelo más natural y brillante', 1, 400, 22.25, load_file('C:/xampp/htdocs/Goals/QuibicPC/public/Imagenes/champuperro.jpg'));


ALTER TABLE token
	ADD CONSTRAINT FK_usuario_token FOREIGN KEY (usuarioID) REFERENCES usuario(ID) ON DELETE CASCADE;
    
ALTER TABLE producto
	ADD CONSTRAINT FK_producto_categoria FOREIGN KEY (categoriaID) REFERENCES categoria(ID) ON DELETE CASCADE;
    
ALTER TABLE cita
	ADD CONSTRAINT FK_cita_cliente FOREIGN KEY (clienteID) REFERENCES cliente(ID) ON DELETE CASCADE,
	ADD CONSTRAINT FK_cita_servicio FOREIGN KEY (servicioID) REFERENCES servicio(ID) ON DELETE CASCADE,
    ADD CONSTRAINT FK_cita_peluquero FOREIGN KEY (peluqueroID) REFERENCES peluquero(ID) ON DELETE CASCADE;

ALTER TABLE carrito
	ADD CONSTRAINT FK_carrito_producto FOREIGN KEY (productoID) REFERENCES producto(ID) ON DELETE CASCADE,
    ADD CONSTRAINT FK_carrito_estado FOREIGN KEY (estadoID) REFERENCES estado(ID) ON DELETE CASCADE;
    
-- drop database quibixpc;