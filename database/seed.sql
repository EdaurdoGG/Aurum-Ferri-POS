-- Para que ek sistema funcione despues de hacer la inseracios 
-- de los triggers, procedimintoa lamacenados y las vistas se 
-- nesecita hacer estios inserat para que el sistema fucnione de manera adecuando

-- =========================
-- Agregamos los roles que se usaran en
-- el sistema
-- =========================
INSERT INTO Roles (Nombre) VALUES
('Administrador'),
('Cajero'),
('Proveedor');

-- =========================
-- Primero se agrega un clinte generico para poder hacer las 
-- ventas nomales sin nesecidada de un clinte. 
-- =========================
CALL AgregarCliente(
    'Cliente',              -- Nombre
    'Generico',             -- Apellido paterno
    '.',                    -- Apellido materno
    '0000000000',           -- Teléfono
    'cliente.generico@demo.com', -- Email
    'default.png',          -- Imagen
    'Activo',               -- Estatus
    0.00,                   -- Crédito actual
    0.00                -- Límite de crédito
);

-- =========================
-- Se agrega el primer provvedor para 
-- que funcione el registro del producto generico 
-- =========================
CALL AgregarProveedor(
    'Juan',
    'Pérez',
    'García',
    '5551234567',
    'juan.perez@proveedores.com',
    'proveedor1.png',
    'Activo',
    'Activo'
);

-- =========================
-- Se registra la primer categoria que sirve 
-- para el registro del producto generico 
-- =========================
CALL AgregarCategoria('Herramientas Manuales');

-- =========================
-- Registro del producto generico para hacer 
-- los pagos de los creditos de los clintes 
-- =========================
CALL AgregarProducto(
    'AbonosCreditos',   -- Nombre
    '12345678',         -- CodigoBarras
    0.00,               -- PrecioCompra
    0.00,               -- PrecioVenta
    0,                  -- Stock
    0,                  -- StockMinimo
    'abonos.png',       -- Imagen (puede ser cualquier nombre)
    1,                  -- idCategoria
    1                   -- idProveedor
);

-- =========================
-- Codigo sql para aregar un administrador para 
-- poder acceder al sistema como adminsitrador 
-- =========================
INSERT INTO Personas (
    Nombre,
    Paterno,
    Materno,
    Telefono,
    Email,
    Imagen,
    Estatus
) VALUES (
    'Administrador',
    'General',
    'Sistema',
    '0000000000',
    'admin@herreriaug.com',
    'default.png',
    'Activo'
);

INSERT INTO Usuarios (
    Usuario,
    Contrasena,
    idPersona,
    idRol
) VALUES (
    'admin',
    'Admin123',
    @idPersonaAdmin,
    1
);
