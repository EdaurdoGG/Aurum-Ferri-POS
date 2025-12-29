DELIMITER $$

-- =========================
-- TRIGGERS PARA PERSONAS
-- =========================
CREATE TRIGGER trg_personas_insert
AFTER INSERT ON Personas
FOR EACH ROW
BEGIN
    INSERT INTO HistorialModificaciones
    (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES
    ('Personas','INSERT','Nombre',NEW.Nombre,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Personas','INSERT','Paterno',NEW.Paterno,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Personas','INSERT','Materno',NEW.Materno,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Personas','INSERT','Telefono',NEW.Telefono,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Personas','INSERT','Email',NEW.Email,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Personas','INSERT','Imagen',NEW.Imagen,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Personas','INSERT','Estatus',NEW.Estatus,@usuario_actual);
END$$

CREATE TRIGGER trg_personas_update
AFTER UPDATE ON Personas
FOR EACH ROW
BEGIN
    IF OLD.Nombre <> NEW.Nombre THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Personas','UPDATE','Nombre',OLD.Nombre,NEW.Nombre,@usuario_actual);
    END IF;
    IF OLD.Paterno <> NEW.Paterno THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Personas','UPDATE','Paterno',OLD.Paterno,NEW.Paterno,@usuario_actual);
    END IF;
    IF OLD.Materno <> NEW.Materno THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Personas','UPDATE','Materno',OLD.Materno,NEW.Materno,@usuario_actual);
    END IF;
    IF OLD.Telefono <> NEW.Telefono THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Personas','UPDATE','Telefono',OLD.Telefono,NEW.Telefono,@usuario_actual);
    END IF;
    IF OLD.Email <> NEW.Email THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Personas','UPDATE','Email',OLD.Email,NEW.Email,@usuario_actual);
    END IF;
    IF OLD.Imagen <> NEW.Imagen THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Personas','UPDATE','Imagen',OLD.Imagen,NEW.Imagen,@usuario_actual);
    END IF;
    IF OLD.Estatus <> NEW.Estatus THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Personas','UPDATE','Estatus',OLD.Estatus,NEW.Estatus,@usuario_actual);
    END IF;
END$$

CREATE TRIGGER trg_personas_delete
AFTER DELETE ON Personas
FOR EACH ROW
BEGIN
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Personas','DELETE','Nombre',OLD.Nombre,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Personas','DELETE','Paterno',OLD.Paterno,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Personas','DELETE','Materno',OLD.Materno,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Personas','DELETE','Telefono',OLD.Telefono,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Personas','DELETE','Email',OLD.Email,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Personas','DELETE','Imagen',OLD.Imagen,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Personas','DELETE','Estatus',OLD.Estatus,@usuario_actual);
END$$

-- =========================
-- TRIGGERS PARA USUARIOS
-- =========================
CREATE TRIGGER trg_usuarios_insert
AFTER INSERT ON Usuarios
FOR EACH ROW
BEGIN
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Usuarios','INSERT','Usuario',NEW.Usuario,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Usuarios','INSERT','Contrasena','***',@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Usuarios','INSERT','idPersona',NEW.idPersona,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Usuarios','INSERT','idRol',NEW.idRol,@usuario_actual);
END$$

CREATE TRIGGER trg_usuarios_update
AFTER UPDATE ON Usuarios
FOR EACH ROW
BEGIN
    IF OLD.Usuario <> NEW.Usuario THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Usuarios','UPDATE','Usuario',OLD.Usuario,NEW.Usuario,@usuario_actual);
    END IF;
    IF OLD.Contrasena <> NEW.Contrasena THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Usuarios','UPDATE','Contrasena','***','***',@usuario_actual);
    END IF;
    IF OLD.idPersona <> NEW.idPersona THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Usuarios','UPDATE','idPersona',OLD.idPersona,NEW.idPersona,@usuario_actual);
    END IF;
    IF OLD.idRol <> NEW.idRol THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Usuarios','UPDATE','idRol',OLD.idRol,NEW.idRol,@usuario_actual);
    END IF;
END$$

CREATE TRIGGER trg_usuarios_delete
AFTER DELETE ON Usuarios
FOR EACH ROW
BEGIN
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Usuarios','DELETE','Usuario',OLD.Usuario,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Usuarios','DELETE','Contrasena','***',@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Usuarios','DELETE','idPersona',OLD.idPersona,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Usuarios','DELETE','idRol',OLD.idRol,@usuario_actual);
END$$

-- =========================
-- TRIGGERS PARA CLIENTES
-- =========================
CREATE TRIGGER trg_clientes_insert
AFTER INSERT ON Clientes
FOR EACH ROW
BEGIN
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Clientes','INSERT','idPersona',NEW.idPersona,@usuario_actual);
    
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Clientes','INSERT','Credito',NEW.Credito,@usuario_actual);
    
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Clientes','INSERT','Limite',NEW.Limite,@usuario_actual);
END$$

CREATE TRIGGER trg_clientes_update
AFTER UPDATE ON Clientes
FOR EACH ROW
BEGIN
    IF OLD.Credito <> NEW.Credito THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Clientes','UPDATE','Credito',OLD.Credito,NEW.Credito,@usuario_actual);
    END IF;
    IF OLD.Limite <> NEW.Limite THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Clientes','UPDATE','Limite',OLD.Limite,NEW.Limite,@usuario_actual);
    END IF;
END$$

CREATE TRIGGER trg_clientes_delete
AFTER DELETE ON Clientes
FOR EACH ROW
BEGIN
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Clientes','DELETE','idPersona',OLD.idPersona,@usuario_actual);
    
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Clientes','DELETE','Credito',OLD.Credito,@usuario_actual);
    
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Clientes','DELETE','Limite',OLD.Limite,@usuario_actual);
END$$

-- =========================
-- TRIGGERS PARA PROVEEDORES
-- =========================
CREATE TRIGGER trg_proveedores_insert
AFTER INSERT ON Proveedores
FOR EACH ROW
BEGIN
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Proveedores','INSERT','idPersona',NEW.idPersona,@usuario_actual);
    
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Proveedores','INSERT','Estado',NEW.Estado,@usuario_actual);
END$$

CREATE TRIGGER trg_proveedores_update
AFTER UPDATE ON Proveedores
FOR EACH ROW
BEGIN
    IF OLD.Estado <> NEW.Estado THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Proveedores','UPDATE','Estado',OLD.Estado,NEW.Estado,@usuario_actual);
    END IF;
END$$

CREATE TRIGGER trg_proveedores_delete
AFTER DELETE ON Proveedores
FOR EACH ROW
BEGIN
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Proveedores','DELETE','idPersona',OLD.idPersona,@usuario_actual);
    
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Proveedores','DELETE','Estado',OLD.Estado,@usuario_actual);
END$$

-- =========================
-- TRIGGERS PARA PRODUCTOS
-- =========================
CREATE TRIGGER trg_productos_insert
AFTER INSERT ON Productos
FOR EACH ROW
BEGIN
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Productos','INSERT','Nombre',NEW.Nombre,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Productos','INSERT','CodigoBarras',NEW.CodigoBarras,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Productos','INSERT','PrecioCompra',NEW.PrecioCompra,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Productos','INSERT','PrecioVenta',NEW.PrecioVenta,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Productos','INSERT','Stock',NEW.Stock,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Productos','INSERT','Imagen',NEW.Imagen,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Productos','INSERT','idCategoria',NEW.idCategoria,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Productos','INSERT','idProveedor',NEW.idProveedor,@usuario_actual);
END$$

CREATE TRIGGER trg_productos_update
AFTER UPDATE ON Productos
FOR EACH ROW
BEGIN
    IF OLD.Nombre <> NEW.Nombre THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Productos','UPDATE','Nombre',OLD.Nombre,NEW.Nombre,@usuario_actual);
    END IF;
    IF OLD.CodigoBarras <> NEW.CodigoBarras THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Productos','UPDATE','CodigoBarras',OLD.CodigoBarras,NEW.CodigoBarras,@usuario_actual);
    END IF;
    IF OLD.PrecioCompra <> NEW.PrecioCompra THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Productos','UPDATE','PrecioCompra',OLD.PrecioCompra,NEW.PrecioCompra,@usuario_actual);
    END IF;
    IF OLD.PrecioVenta <> NEW.PrecioVenta THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Productos','UPDATE','PrecioVenta',OLD.PrecioVenta,NEW.PrecioVenta,@usuario_actual);
    END IF;
    IF OLD.Stock <> NEW.Stock THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Productos','UPDATE','Stock',OLD.Stock,NEW.Stock,@usuario_actual);
    END IF;
    IF OLD.Imagen <> NEW.Imagen THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Productos','UPDATE','Imagen',OLD.Imagen,NEW.Imagen,@usuario_actual);
    END IF;
    IF OLD.idCategoria <> NEW.idCategoria THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Productos','UPDATE','idCategoria',OLD.idCategoria,NEW.idCategoria,@usuario_actual);
    END IF;
    IF OLD.idProveedor <> NEW.idProveedor THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Productos','UPDATE','idProveedor',OLD.idProveedor,NEW.idProveedor,@usuario_actual);
    END IF;
END$$

CREATE TRIGGER trg_productos_delete
AFTER DELETE ON Productos
FOR EACH ROW
BEGIN
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Productos','DELETE','Nombre',OLD.Nombre,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Productos','DELETE','CodigoBarras',OLD.CodigoBarras,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Productos','DELETE','PrecioCompra',OLD.PrecioCompra,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Productos','DELETE','PrecioVenta',OLD.PrecioVenta,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Productos','DELETE','Stock',OLD.Stock,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Productos','DELETE','Imagen',OLD.Imagen,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Productos','DELETE','idCategoria',OLD.idCategoria,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Productos','DELETE','idProveedor',OLD.idProveedor,@usuario_actual);
END$$

-- =========================
-- TRIGGERS PARA PEDIDOS
-- =========================
CREATE TRIGGER trg_pedidos_insert
AFTER INSERT ON Pedidos
FOR EACH ROW
BEGIN
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Pedidos','INSERT','Fecha',NEW.Fecha,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Pedidos','INSERT','Estado',NEW.Estado,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Pedidos','INSERT','idCliente',NEW.idCliente,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoNuevo, idUsuario)
    VALUES ('Pedidos','INSERT','idUsuario',NEW.idUsuario,@usuario_actual);
END$$

CREATE TRIGGER trg_pedidos_update
AFTER UPDATE ON Pedidos
FOR EACH ROW
BEGIN
    IF OLD.Estado <> NEW.Estado THEN
        INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, DatoNuevo, idUsuario)
        VALUES ('Pedidos','UPDATE','Estado',OLD.Estado,NEW.Estado,@usuario_actual);
    END IF;
END$$

CREATE TRIGGER trg_pedidos_delete
AFTER DELETE ON Pedidos
FOR EACH ROW
BEGIN
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Pedidos','DELETE','Fecha',OLD.Fecha,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Pedidos','DELETE','Estado',OLD.Estado,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Pedidos','DELETE','idCliente',OLD.idCliente,@usuario_actual);
    INSERT INTO HistorialModificaciones (TablaAfectada, Movimiento, ColumnaAfectada, DatoAnterior, idUsuario)
    VALUES ('Pedidos','DELETE','idUsuario',OLD.idUsuario,@usuario_actual);
END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER trg_notificacion_stock_bajo
AFTER UPDATE ON Productos
FOR EACH ROW
BEGIN

    IF NEW.Stock <= NEW.StockMinimo 
       AND OLD.Stock > OLD.StockMinimo THEN

        INSERT INTO Notificaciones
        (Tipo, idProducto, Mensaje)
        VALUES
        (
            'StockBajo',
            NEW.idProducto,
            CONCAT(
                'El producto "', 
                NEW.Nombre, 
                '" tiene stock bajo (', 
                NEW.Stock, 
                ' unidades, mínimo permitido: ',
                NEW.StockMinimo,
                ').'
            )
        );
    END IF;
END$$

CREATE TRIGGER trg_notificacion_nuevo_pedido
AFTER INSERT ON Pedidos
FOR EACH ROW
BEGIN
    INSERT INTO Notificaciones
    (Tipo, idPedido, Mensaje)
    VALUES
    (
        'PedidoPendiente',
        NEW.idPedido,
        CONCAT('Nuevo pedido registrado con ID #', NEW.idPedido, '. Estado: ', NEW.Estado)
    );
END$$

DELIMITER ;