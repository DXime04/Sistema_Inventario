CREATE DATABASE MelyPaperTrack_Data
GO

USE MelyPaperTrack_Data
GO

CREATE TABLE categorias (
    id_categoria INT IDENTITY(1,1) PRIMARY KEY,
    nombre_categoria NVARCHAR(255) NOT NULL
);
GO

CREATE TABLE proveedores (
    id_proveedor INT IDENTITY(1,1) PRIMARY KEY,
    nombre_proveedor NVARCHAR(255) NOT NULL,
    contacto_proveedor NVARCHAR(255),
    telefono_proveedor NVARCHAR(20),
    direccion_proveedor NVARCHAR(255)
);
GO
CREATE TABLE productos (
    id_producto INT IDENTITY(1,1) PRIMARY KEY,
    nombre_producto NVARCHAR(255) NOT NULL,
    descripcion NVARCHAR(MAX),
    cantidad_stock INT NOT NULL,
    precio_compra DECIMAL(10, 2),
    precio_venta DECIMAL(10, 2),
    id_categoria INT,
    id_proveedor INT,
    fecha_registro DATETIME DEFAULT GETDATE(),
    CONSTRAINT FK_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
    CONSTRAINT FK_proveedor FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor)
);
GO
CREATE TABLE entradas (
    id_entrada INT IDENTITY(1,1) PRIMARY KEY,
    id_producto INT,
    cantidad_entrada INT NOT NULL,
    fecha_entrada DATETIME DEFAULT GETDATE(),
    CONSTRAINT FK_producto_entrada FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);
GO

CREATE TABLE salidas (
    id_salida INT IDENTITY(1,1) PRIMARY KEY,
    id_producto INT,
    cantidad_salida INT NOT NULL,
    fecha_salida DATETIME DEFAULT GETDATE(),
    CONSTRAINT FK_producto_salida FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);
GO

CREATE TABLE roles_usuarios (
    id_rol INT IDENTITY(1,1) PRIMARY KEY,
    nombre_rol NVARCHAR(50) NOT NULL UNIQUE
);
GO

CREATE TABLE usuarios (
    id_usuario INT IDENTITY(1,1) PRIMARY KEY,
    nombre_usuario NVARCHAR(255) NOT NULL,
    email_usuario NVARCHAR(255) UNIQUE NOT NULL,
    password_usuario NVARCHAR(255) NOT NULL,
    id_rol INT,
    CONSTRAINT FK_rol_usuario FOREIGN KEY (id_rol) REFERENCES roles_usuarios(id_rol)
);
GO

CREATE TABLE estados_reservas (
    id_estado INT IDENTITY(1,1) PRIMARY KEY,
    nombre_estado NVARCHAR(50) NOT NULL UNIQUE
);
GO

CREATE TABLE clientes (
    id_cliente INT IDENTITY(1,1) PRIMARY KEY,
    nombre_cliente NVARCHAR(255) NOT NULL,
    contacto_cliente NVARCHAR(255) NOT NULL,
    email_cliente NVARCHAR(255) NULL,
    telefono_cliente NVARCHAR(20) NULL
);
GO

CREATE TABLE reservas (
    id_reserva INT IDENTITY(1,1) PRIMARY KEY,
    id_producto INT,
    id_cliente INT,
    cantidad_reservada INT NOT NULL,
    fecha_reserva DATETIME DEFAULT GETDATE(),
    fecha_limite_reserva DATETIME NOT NULL,
    id_estado INT,
    CONSTRAINT FK_producto_reserva FOREIGN KEY (id_producto) REFERENCES productos(id_producto),
    CONSTRAINT FK_cliente_reserva FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    CONSTRAINT FK_estado_reserva FOREIGN KEY (id_estado) REFERENCES estados_reservas(id_estado)
);
GO

-- acciones
select *from categorias
select *from clientes
select *from entradas
select *from estados_reservas
select *from productos
select *from proveedores
select *from reservas
select *from roles_usuarios
select *from salidas
select *from usuarios