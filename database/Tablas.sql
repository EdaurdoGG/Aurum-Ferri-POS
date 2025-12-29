CREATE DATABASE IF NOT EXISTS HerreriaUG;
USE HerreriaUG;

-- =========================
-- PERSONAS
-- =========================
CREATE TABLE Personas (
    idPersona INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Paterno VARCHAR(100),
    Materno VARCHAR(100),
    Telefono VARCHAR(20),
    Email VARCHAR(100),
    Imagen VARCHAR(255),
    Estatus ENUM('Activo','Inactivo') DEFAULT 'Activo'
);

-- =========================
-- ROLES
-- =========================
CREATE TABLE Roles (
    idRol INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL UNIQUE
);

-- =========================
-- USUARIOS
-- =========================
CREATE TABLE Usuarios (
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
    Usuario VARCHAR(50) NOT NULL UNIQUE,
    Contrasena VARCHAR(255) NOT NULL,
    idPersona INT NOT NULL,
    idRol INT NOT NULL,

    FOREIGN KEY (idPersona) REFERENCES Personas(idPersona)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (idRol) REFERENCES Roles(idRol)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =========================
-- CLIENTES
-- =========================
CREATE TABLE Clientes (
    idCliente INT AUTO_INCREMENT PRIMARY KEY,
    idPersona INT NOT NULL,
    Credito DECIMAL(10,2) DEFAULT 0,
    Limite DECIMAL(10,2) DEFAULT 0,

    FOREIGN KEY (idPersona) REFERENCES Personas(idPersona)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =========================
-- PROVEEDORES
-- =========================
CREATE TABLE Proveedores (
    idProveedor INT AUTO_INCREMENT PRIMARY KEY,
    idPersona INT NOT NULL,
    Estado ENUM('Activo','Inactivo') DEFAULT 'Activo',

    FOREIGN KEY (idPersona) REFERENCES Personas(idPersona)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =========================
-- CATEGORIAS
-- =========================
CREATE TABLE Categorias (
    idCategoria INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL UNIQUE
);

-- =========================
-- PRODUCTOS
-- =========================
CREATE TABLE Productos (
    idProducto INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    CodigoBarras VARCHAR(50) UNIQUE,
    PrecioCompra DECIMAL(10,2) NOT NULL,
    PrecioVenta DECIMAL(10,2) NOT NULL,
    Stock INT NOT NULL DEFAULT 0,
    StockMinimo INT NOT NULL DEFAULT 0,
    Imagen VARCHAR(255),
    idCategoria INT NOT NULL,
    idProveedor INT NOT NULL,

    FOREIGN KEY (idCategoria) REFERENCES Categorias(idCategoria)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (idProveedor) REFERENCES Proveedores(idProveedor)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =========================
-- CARRITO
-- =========================
CREATE TABLE Carrito (
    idCarrito INT AUTO_INCREMENT PRIMARY KEY,
    Fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    idUsuario INT NOT NULL, -- quien lo está registrando o atendiendo

    FOREIGN KEY (idUsuario) REFERENCES Usuarios(idUsuario)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =========================
-- DETALLE CARRITO
-- =========================
CREATE TABLE DetalleCarrito (
    idDetalleCarrito INT AUTO_INCREMENT PRIMARY KEY,
    idCarrito INT NOT NULL,
    idProducto INT NOT NULL,
    Cantidad INT NOT NULL,
    Precio DECIMAL(10,2) NOT NULL, -- precio por unidad al momento de agregar al carrito

    FOREIGN KEY (idCarrito) REFERENCES Carrito(idCarrito)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idProducto) REFERENCES Productos(idProducto)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =========================
-- VENTAS (SIN TOTALES)
-- =========================
CREATE TABLE Ventas (
    idVenta INT AUTO_INCREMENT PRIMARY KEY,
    Fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    idUsuario INT NOT NULL,
    idCliente INT NULL,

    FOREIGN KEY (idUsuario) REFERENCES Usuarios(idUsuario)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (idCliente) REFERENCES Clientes(idCliente)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- =========================
-- DETALLE VENTAS
-- =========================
CREATE TABLE DetalleVentas (
    idDetalleVenta INT AUTO_INCREMENT PRIMARY KEY,
    idVenta INT NOT NULL,
    idProducto INT NOT NULL,
    Cantidad INT NOT NULL,
    PrecioVenta DECIMAL(10,2) NOT NULL,
    Descuento DECIMAL(10,2) DEFAULT 0,

    FOREIGN KEY (idVenta) REFERENCES Ventas(idVenta)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idProducto) REFERENCES Productos(idProducto)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =========================
-- FINANZAS (SIN GANANCIAS)
-- =========================
CREATE TABLE Finanzas (
    idFinanza INT AUTO_INCREMENT PRIMARY KEY,
    idVenta INT NOT NULL,
    Invertido DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (idVenta) REFERENCES Ventas(idVenta)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- =========================
-- PEDIDOS (CLIENTES)
-- =========================
CREATE TABLE Pedidos (
    idPedido INT AUTO_INCREMENT PRIMARY KEY,
    Fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Estado ENUM('Pendiente','Parcial','Surtido','Cancelado') NOT NULL DEFAULT 'Pendiente',
    idCliente INT NOT NULL,
    idUsuario INT NOT NULL,

    FOREIGN KEY (idCliente) REFERENCES Clientes(idCliente)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (idUsuario) REFERENCES Usuarios(idUsuario)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =========================
-- DETALLE PEDIDOS
-- =========================
CREATE TABLE DetallePedidos (
    idDetallePedido INT AUTO_INCREMENT PRIMARY KEY,
    idPedido INT NOT NULL,
    idProducto INT NOT NULL,
    Cantidad INT NOT NULL,
    PrecioVenta DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (idPedido) REFERENCES Pedidos(idPedido)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idProducto) REFERENCES Productos(idProducto)
        ON DELETE RESTRICT ON UPDATE CASCADE
);


-- =========================
-- DEVOLUCIONES
-- =========================
CREATE TABLE Devoluciones (
    idDevolucion INT AUTO_INCREMENT PRIMARY KEY,
    Fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Motivo TEXT,
    idEmpleado INT NOT NULL,

    FOREIGN KEY (idEmpleado) REFERENCES Usuarios(idUsuario)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =========================
-- DETALLE DEVOLUCIONES
-- =========================
CREATE TABLE DetalleDevoluciones (
    idDetalleDevolucion INT AUTO_INCREMENT PRIMARY KEY,
    idDevolucion INT NOT NULL,
    idProducto INT NOT NULL,
    Cantidad INT NOT NULL,
    PrecioVenta DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (idDevolucion) REFERENCES Devoluciones(idDevolucion)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idProducto) REFERENCES Productos(idProducto)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =========================
-- HISTORIAL DE MODIFICACIONES
-- =========================
CREATE TABLE HistorialModificaciones (
    idHistorial INT AUTO_INCREMENT PRIMARY KEY,
    TablaAfectada VARCHAR(50) NOT NULL,
    Movimiento ENUM('INSERT','UPDATE','DELETE') NOT NULL,
    ColumnaAfectada VARCHAR(100) NULL,
    DatoAnterior TEXT NULL,
    DatoNuevo TEXT NULL,
    Fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    idUsuario INT NULL,

    FOREIGN KEY (idUsuario) REFERENCES Usuarios(idUsuario)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

-- =========================
-- NOTIFICACIONES
-- =========================
CREATE TABLE Notificaciones (
    idNotificacion INT AUTO_INCREMENT PRIMARY KEY,
    Tipo ENUM('StockBajo','PedidoPendiente') NOT NULL, -- tipo de notificación
    idProducto INT NULL,  -- si aplica (ej. StockBajo)
    idPedido INT NULL,    -- si aplica (ej. PedidoPendiente)
    Mensaje TEXT NOT NULL, -- descripción de la notificación
    Fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Leida BOOLEAN DEFAULT FALSE, -- para marcar si ya se revisó
    idUsuario INT NULL, -- opcional: usuario asignado a la notificación

    FOREIGN KEY (idProducto) REFERENCES Productos(idProducto)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (idPedido) REFERENCES Pedidos(idPedido)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (idUsuario) REFERENCES Usuarios(idUsuario)
        ON DELETE SET NULL ON UPDATE CASCADE
);

