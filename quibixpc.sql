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
    token_expiracion DATETIME,
    usuarioID INT
);
CREATE TABLE IF NOT EXISTS cliente(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefono VARCHAR(20) NOT NULL
);
CREATE TABLE IF NOT EXISTS servicio(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_servicio VARCHAR(255) NOT NULL,
    precio FLOAT NOT NULL
);
CREATE TABLE IF NOT EXISTS peluquero(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) NOT NULL,
    telefono INT(10) NOT NULL
);
CREATE TABLE IF NOT EXISTS cita(
    id INT AUTO_INCREMENT PRIMARY KEY,
    horario DATETIME NOT NULL,
    clienteID INT,
    servicioID INT,
    peluqueroID INT
);
CREATE TABLE IF NOT EXISTS categoria(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS producto(
	id INT AUTO_INCREMENT PRIMARY KEY,
    sku INT(40) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    categoriaID INT NOT NULL,
    stock INT(255) NOT NULL,
    precio FLOAT NOT NULL
);
CREATE TABLE IF NOT EXISTS estado(
	id INT AUTO_INCREMENT PRIMARY KEY,
    estado VARCHAR(50) NOT NULL
);
CREATE TABLE IF NOT EXISTS carrito(
	id INT AUTO_INCREMENT PRIMARY KEY,
    clienteID INT,
    productoID INT,
    estadoID INT,
    cantidad INT(20) NOT NULL,
    precio_total FLOAT
);

ALTER TABLE usuario
	ADD UNIQUE(email);
INSERT IGNORE INTO usuario(nombre, apellidos, clave, email, telefono) VALUES
	('Jakub', 'Jasinski', '1234', 'jakubj23@mail.com', 664234999);
    
ALTER TABLE cliente
	ADD UNIQUE(apellidos, email, telefono);
INSERT IGNORE INTO cliente(nombre, apellidos, email, telefono) VALUES
	('Juan', 'Gonzales', 'JG@mail.com', 663438899),
    ('Pedro', 'Gorro', 'PG@mail.com', 663438899),
    ('María', 'López', 'ML@mail.com', 665550123),
	('Ana', 'Martínez', 'AM@mail.com', 667788990),
	('Carlos', 'Sánchez', 'CS@mail.com', 661122334),
	('Sara', 'Fernández', 'SF@mail.com', 664433221),
	('Luis', 'Ramírez', 'LR@mail.com', 669988776),
	('Elena', 'García', 'EG@mail.com', 661199887),
	('Miguel', 'Díaz', 'MD@mail.com', 667755443),
	('Laura', 'Hernández', 'LH@mail.com', 662211009);    
    
ALTER TABLE servicio
	ADD UNIQUE(nombre_servicio);
INSERT IGNORE INTO servicio(nombre_servicio, precio) VALUES
	('Corte', 19.99),
    ('Lavado', 10.99),
	('Corte y lavado', 27.50);
    
ALTER TABLE estado
	ADD UNIQUE(estado);
INSERT IGNORE INTO estado(estado) VALUES
	('Pendiente de pago'),
    ('Pagado'),
    ('Anulado'),
    ('Preparando para el envio'),
    ('Entregado'),
    ('Enviado');
    
ALTER TABLE peluquero
	ADD UNIQUE(apellidos, telefono);
INSERT IGNORE INTO peluquero(nombre, apellidos, telefono) VALUES(
	'Ruperto', 'Martel', 772889002),(
    'Luisa', 'Peña', 665322889);    
    
ALTER TABLE categoria
	ADD UNIQUE(nombre);
INSERT IGNORE INTO categoria(nombre) VALUES
	('Cuidados para animales'),
	('Juguetes'),
    ('Comida'),
    ('Otros');
    
ALTER TABLE producto
	ADD UNIQUE(sku);
INSERT IGNORE INTO producto(sku, nombre, descripcion, categoriaID, stock, precio) VALUES 
	(321, 'Peine Perro', 'Un peine con dos caras: una de púas metálicas y separadas entre sí, ideal para desenredar con cuidado y otra de cerdas naturales o sintéticas, para mantener el pelo suave y brillante.',1 ,200, 45.95),
    (234, 'Champu Oster Coco', 'Champu para perros con extracto natural de coco para un toque de pelo más natural y brillante', 1, 400, 22.25 ),
	(101, 'Juguete Mordedor Perro', 'Juguete mordedor para perros hecho de goma resistente. Ideal para promover la salud dental y satisfacer el instinto de morder.', 2, 150, 12.99),
	(102, 'Pienso Premium Perros Adultos', 'Alimento balanceado para perros adultos, elaborado con ingredientes naturales y ricos en nutrientes esenciales para una salud óptima.', 2, 300, 49.99),
	(103, 'Collar Adiestramiento Perro', 'Collar de adiestramiento para perros con control remoto. Ayuda a corregir comportamientos no deseados y enseñar órdenes básicas.', 4, 100, 89.99),
	(104, 'Cepillo Gato', 'Cepillo suave y ergonómico para cepillar el pelaje de tu gato. Elimina el pelo suelto y previene la formación de bolas de pelo.', 1, 200, 19.99),
	(105, 'Transportín Gato', 'Transportín seguro y cómodo para gatos de todos los tamaños. Con puerta de acceso y ventilación para garantizar un viaje confortable.', 4, 80, 39.99),
	(106, 'Comedero Antideslizante Gato', 'Comedero antideslizante para gatos con diseño ergonómico. Evita derrames y facilita la alimentación de tu felino.', 4, 120, 14.99),
	(107, 'Arenero Grande Gato', 'Arenero espacioso para gatos con borde alto para contener la arena. Fácil de limpiar y mantener.', 4, 100, 29.99),
	(108, 'Snack Natural Gato', 'Snack natural y saludable para gatos. Elaborado con ingredientes de alta calidad y sin aditivos artificiales.', 3, 150, 8.99),
	(109, 'Rascador Poste Gato', 'Rascador para gatos con poste de sisal resistente y plataforma para descansar. Ayuda a satisfacer el instinto de rascar de tu gato.', 2, 80, 34.99),
	(110, 'Bolso Transporte Gato', 'Bolso de transporte práctico y ligero para gatos. Ideal para viajes al veterinario o paseos cortos.', 4, 60, 29.99),
	(111, 'Snack Dental Perro', 'Snack dental para perros que ayuda a reducir la acumulación de placa y sarro. Delicioso sabor a pollo que tu perro adorará.', 3, 200, 12.99),
	(112, 'Pelota Goma Resistente Perro', 'Pelota de goma resistente para perros. Ideal para juegos de lanzar y recoger, y para mantener entretenido a tu perro.', 2, 100, 9.99),
	(113, 'Arnés Reflectante Perro', 'Arnés para perros con bandas reflectantes para mayor visibilidad durante paseos nocturnos. Ajustable y seguro.', 4, 150, 49.99),
	(114, 'Champú Suave Gato', 'Champú suave y delicado para gatos. Fórmula hipoalergénica que limpia y nutre el pelaje sin irritar la piel.', 1, 100, 16.99),
	(115, 'Cama Ovalada Gato', 'Cama ovalada y acogedora para gatos. Fabricada con materiales suaves y duraderos para garantizar el máximo confort de tu felino.', 4, 80, 29.99),
	(116, 'Juguete Peluche Rellenable Gato', 'Juguete de peluche rellenable para gatos. Con compartimento para añadir hierba gatera u otros premios para mayor diversión.', 2, 120, 11.99),
	(117, 'Collar Luz LED Perro', 'Collar con luz LED para perros. Ajustable y recargable mediante USB. Perfecto para paseos nocturnos con visibilidad mejorada.', 4, 80, 19.99),
	(118, 'Comedero Elevado Perro', 'Comedero elevado para perros de tamaño grande. Diseño ergonómico que promueve una postura más cómoda y saludable durante la alimentación.', 4, 70, 49.99);


ALTER TABLE token
	ADD CONSTRAINT FK_usuario_token FOREIGN KEY (usuarioID) REFERENCES usuario(ID) ON DELETE CASCADE;
    
ALTER TABLE producto
	ADD CONSTRAINT FK_producto_categoria FOREIGN KEY (categoriaID) REFERENCES categoria(ID) ON DELETE CASCADE;
    
ALTER TABLE cita
	ADD CONSTRAINT FK_cita_cliente FOREIGN KEY (clienteID) REFERENCES cliente(ID) ON DELETE CASCADE,
	ADD CONSTRAINT FK_cita_servicio FOREIGN KEY (servicioID) REFERENCES servicio(ID) ON DELETE CASCADE,
    ADD CONSTRAINT FK_cita_peluquero FOREIGN KEY (peluqueroID) REFERENCES peluquero(ID) ON DELETE CASCADE;

ALTER TABLE carrito
	ADD CONSTRAINT FK_carrito_cliente FOREIGN KEY (clienteID) REFERENCES cliente(ID) ON DELETE CASCADE,
	ADD CONSTRAINT FK_carrito_producto FOREIGN KEY (productoID) REFERENCES producto(ID) ON DELETE CASCADE,
    ADD CONSTRAINT FK_carrito_estado FOREIGN KEY (estadoID) REFERENCES estado(ID) ON DELETE CASCADE;
    
-- drop database quibixpc;