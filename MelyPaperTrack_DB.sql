CREATE DATABASE MelyPaperTrack_Data;

USE MelyPaperTrack_Data;

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(255) NOT NULL
);

CREATE TABLE proveedores (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre_proveedor VARCHAR(255) NOT NULL,
    contacto_proveedor VARCHAR(255),
    telefono_proveedor VARCHAR(20),
    direccion_proveedor VARCHAR(255)
);

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(255) NOT NULL,
    descripcion VARCHAR(255),
    cantidad_stock INT NOT NULL,
    precio_compra DECIMAL(10, 2),
    precio_venta DECIMAL(10, 2),
    id_categoria INT,
    id_proveedor INT,
    fecha_registro DATETIME DEFAULT NOW(),
    CONSTRAINT FK_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
    CONSTRAINT FK_proveedor FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor)
);

CREATE TABLE entradas (
    id_entrada INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT,
    cantidad_entrada INT NOT NULL,
    fecha_entrada DATETIME DEFAULT NOW(),
    CONSTRAINT FK_producto_entrada FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE salidas (
    id_salida INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT,
    cantidad_salida INT NOT NULL,
    fecha_salida DATETIME DEFAULT NOW(),
    CONSTRAINT FK_producto_salida FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE roles_usuarios (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(255) NOT NULL,
    email_usuario VARCHAR(255) UNIQUE NOT NULL,
    password_usuario VARCHAR(255) NOT NULL,
    id_rol INT,
    CONSTRAINT FK_rol_usuario FOREIGN KEY (id_rol) REFERENCES roles_usuarios(id_rol)
);

CREATE TABLE estados_reservas (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre_estado VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cliente VARCHAR(255) NOT NULL,
    contacto_cliente VARCHAR(255) NOT NULL,
    email_cliente VARCHAR(255),
    telefono_cliente VARCHAR(20)
);

CREATE TABLE reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT,
    id_cliente INT,
    cantidad_reservada INT NOT NULL,
    fecha_reserva DATETIME DEFAULT NOW(),
    fecha_limite_reserva DATETIME NOT NULL,
    id_estado INT,
    CONSTRAINT FK_producto_reserva FOREIGN KEY (id_producto) REFERENCES productos(id_producto),
    CONSTRAINT FK_cliente_reserva FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    CONSTRAINT FK_estado_reserva FOREIGN KEY (id_estado) REFERENCES estados_reservas(id_estado)
);
