DELIMITER $$

-- =========================
-- Agregar Usuario (incluye Persona)
-- =========================
CREATE PROCEDURE AgregarUsuario(
    IN pNombre VARCHAR(100),
    IN pPaterno VARCHAR(100),
    IN pMaterno VARCHAR(100),
    IN pTelefono VARCHAR(20),
    IN pEmail VARCHAR(100),
    IN pImagen VARCHAR(255),
    IN pEstatus ENUM('Activo','Inactivo'),
    IN pUsuario VARCHAR(50),
    IN pContrasena VARCHAR(255),
    IN pIdRol INT
)
BEGIN
    DECLARE nuevoIdPersona INT;

    -- Insertar en Personas
    INSERT INTO Personas (Nombre, Paterno, Materno, Telefono, Email, Imagen, Estatus)
    VALUES (pNombre, pPaterno, pMaterno, pTelefono, pEmail, pImagen, pEstatus);

    SET nuevoIdPersona = LAST_INSERT_ID();

    -- Insertar en Usuarios
    INSERT INTO Usuarios (Usuario, Contrasena, idPersona, idRol)
    VALUES (pUsuario, pContrasena, nuevoIdPersona, pIdRol);
END$$


-- =========================
-- Editar Usuario (incluye Persona)
-- =========================
CREATE PROCEDURE EditarUsuario(
    IN pIdUsuario INT,
    IN pNombre VARCHAR(100),
    IN pPaterno VARCHAR(100),
    IN pMaterno VARCHAR(100),
    IN pTelefono VARCHAR(20),
    IN pEmail VARCHAR(100),
    IN pImagen VARCHAR(255),
    IN pEstatus ENUM('Activo','Inactivo'),
    IN pUsuario VARCHAR(50),
    IN pIdRol INT
)
BEGIN
    DECLARE idPersonaUsuario INT;

    -- Obtener el idPersona del usuario
    SELECT idPersona INTO idPersonaUsuario
    FROM Usuarios
    WHERE idUsuario = pIdUsuario;

    -- Actualizar Persona
    UPDATE Personas
    SET Nombre = pNombre,
        Paterno = pPaterno,
        Materno = pMaterno,
        Telefono = pTelefono,
        Email = pEmail,
        Imagen = pImagen,
        Estatus = pEstatus
    WHERE idPersona = idPersonaUsuario;

    -- Actualizar Usuario
    UPDATE Usuarios
    SET Usuario = pUsuario,
        idRol = pIdRol
    WHERE idUsuario = pIdUsuario;
END$$


-- =========================
-- Eliminar Usuario (incluye Persona)
-- =========================
CREATE PROCEDURE EliminarUsuario(
    IN pIdUsuario INT
)
BEGIN
    DECLARE idPersonaUsuario INT;

    -- Obtener idPersona
    SELECT idPersona INTO idPersonaUsuario
    FROM Usuarios
    WHERE idUsuario = pIdUsuario;

    -- Eliminar Usuario
    DELETE FROM Usuarios
    WHERE idUsuario = pIdUsuario;

    -- Eliminar Persona asociada
    DELETE FROM Personas
    WHERE idPersona = idPersonaUsuario;
END$$


-- =========================
-- Agregar Cliente (incluye Persona)
-- =========================
CREATE PROCEDURE AgregarCliente(
    IN pNombre VARCHAR(100),
    IN pPaterno VARCHAR(100),
    IN pMaterno VARCHAR(100),
    IN pTelefono VARCHAR(20),
    IN pEmail VARCHAR(100),
    IN pImagen VARCHAR(255),
    IN pEstatus ENUM('Activo','Inactivo'),
    IN pCredito DECIMAL(10,2),
    IN pLimite DECIMAL(10,2)
)
BEGIN
    DECLARE nuevoIdPersona INT;

    -- Insertar en Personas
    INSERT INTO Personas (Nombre, Paterno, Materno, Telefono, Email, Imagen, Estatus)
    VALUES (pNombre, pPaterno, pMaterno, pTelefono, pEmail, pImagen, pEstatus);

    SET nuevoIdPersona = LAST_INSERT_ID();

    -- Insertar en Clientes
    INSERT INTO Clientes (idPersona, Credito, Limite)
    VALUES (nuevoIdPersona, pCredito, pLimite);
END$$


-- =========================
-- Editar Cliente (incluye Persona)
-- =========================
CREATE PROCEDURE EditarCliente(
    IN pIdCliente INT,
    IN pNombre VARCHAR(100),
    IN pPaterno VARCHAR(100),
    IN pMaterno VARCHAR(100),
    IN pTelefono VARCHAR(20),
    IN pEmail VARCHAR(100),
    IN pImagen VARCHAR(255),
    IN pEstatus ENUM('Activo','Inactivo'),
    IN pCredito DECIMAL(10,2),
    IN pLimite DECIMAL(10,2)
)
BEGIN
    DECLARE idPersonaCliente INT;

    -- Obtener el idPersona del cliente
    SELECT idPersona INTO idPersonaCliente
    FROM Clientes
    WHERE idCliente = pIdCliente;

    -- Actualizar Persona
    UPDATE Personas
    SET Nombre = pNombre,
        Paterno = pPaterno,
        Materno = pMaterno,
        Telefono = pTelefono,
        Email = pEmail,
        Imagen = pImagen,
        Estatus = pEstatus
    WHERE idPersona = idPersonaCliente;

    -- Actualizar Cliente
    UPDATE Clientes
    SET Credito = pCredito,
        Limite = pLimite
    WHERE idCliente = pIdCliente;
END$$


-- =========================
-- Eliminar Cliente (incluye Persona)
-- =========================
CREATE PROCEDURE EliminarCliente(
    IN pIdCliente INT
)
BEGIN
    DECLARE idPersonaCliente INT;

    -- Obtener idPersona
    SELECT idPersona INTO idPersonaCliente
    FROM Clientes
    WHERE idCliente = pIdCliente;

    -- Eliminar Cliente
    DELETE FROM Clientes
    WHERE idCliente = pIdCliente;

    -- Eliminar Persona asociada
    DELETE FROM Personas
    WHERE idPersona = idPersonaCliente;
END$$


-- =========================
-- Agregar Proveedor (incluye Persona)
-- =========================
CREATE PROCEDURE AgregarProveedor(
    IN pNombre VARCHAR(100),
    IN pPaterno VARCHAR(100),
    IN pMaterno VARCHAR(100),
    IN pTelefono VARCHAR(20),
    IN pEmail VARCHAR(100),
    IN pImagen VARCHAR(255),
    IN pEstatusPersona ENUM('Activo','Inactivo'),
    IN pEstadoProveedor ENUM('Activo','Inactivo')
)
BEGIN
    DECLARE nuevoIdPersona INT;

    -- Insertar en Personas
    INSERT INTO Personas (Nombre, Paterno, Materno, Telefono, Email, Imagen, Estatus)
    VALUES (pNombre, pPaterno, pMaterno, pTelefono, pEmail, pImagen, pEstatusPersona);

    SET nuevoIdPersona = LAST_INSERT_ID();

    -- Insertar en Proveedores
    INSERT INTO Proveedores (idPersona, Estado)
    VALUES (nuevoIdPersona, pEstadoProveedor);
END$$


-- =========================
-- Editar Proveedor (incluye Persona)
-- =========================
CREATE PROCEDURE EditarProveedor(
    IN pIdProveedor INT,
    IN pNombre VARCHAR(100),
    IN pPaterno VARCHAR(100),
    IN pMaterno VARCHAR(100),
    IN pTelefono VARCHAR(20),
    IN pEmail VARCHAR(100),
    IN pImagen VARCHAR(255),
    IN pEstatusPersona ENUM('Activo','Inactivo'),
    IN pEstadoProveedor ENUM('Activo','Inactivo')
)
BEGIN
    DECLARE idPersonaProveedor INT;

    -- Obtener el idPersona del proveedor
    SELECT idPersona INTO idPersonaProveedor
    FROM Proveedores
    WHERE idProveedor = pIdProveedor;

    -- Actualizar Persona
    UPDATE Personas
    SET Nombre = pNombre,
        Paterno = pPaterno,
        Materno = pMaterno,
        Telefono = pTelefono,
        Email = pEmail,
        Imagen = pImagen,
        Estatus = pEstatusPersona
    WHERE idPersona = idPersonaProveedor;

    -- Actualizar Proveedor
    UPDATE Proveedores
    SET Estado = pEstadoProveedor
    WHERE idProveedor = pIdProveedor;
END$$


-- =========================
-- Eliminar Proveedor (incluye Persona)
-- =========================
CREATE PROCEDURE EliminarProveedor(
    IN pIdProveedor INT
)
BEGIN
    DECLARE idPersonaProveedor INT;

    -- Obtener idPersona
    SELECT idPersona INTO idPersonaProveedor
    FROM Proveedores
    WHERE idProveedor = pIdProveedor;

    -- Eliminar Proveedor
    DELETE FROM Proveedores
    WHERE idProveedor = pIdProveedor;

    -- Eliminar Persona asociada
    DELETE FROM Personas
    WHERE idPersona = idPersonaProveedor;
END$$


-- =========================
-- Agregar Categoria
-- =========================
CREATE PROCEDURE AgregarCategoria(
    IN p_Nombre VARCHAR(100)
)
BEGIN
    -- Validar si ya existe
    IF EXISTS (SELECT 1 FROM Categorias WHERE Nombre = p_Nombre) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La categoría ya existe';
    ELSE
        INSERT INTO Categorias (Nombre)
        VALUES (p_Nombre);
    END IF;
END$$

-- =========================
-- Editar Categoria
-- =========================
CREATE PROCEDURE EditarCategoria(
    IN p_idCategoria INT,
    IN p_NuevoNombre VARCHAR(100)
)
BEGIN
    -- Validar que exista la categoría
    IF NOT EXISTS (SELECT 1 FROM Categorias WHERE idCategoria = p_idCategoria) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La categoría no existe';
    
    -- Validar que el nuevo nombre no esté repetido
    ELSEIF EXISTS (
        SELECT 1 
        FROM Categorias 
        WHERE Nombre = p_NuevoNombre 
        AND idCategoria <> p_idCategoria
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Ya existe otra categoría con ese nombre';
    
    ELSE
        UPDATE Categorias
        SET Nombre = p_NuevoNombre
        WHERE idCategoria = p_idCategoria;
    END IF;
END$$

-- =========================
-- Eliminar Categoria
-- =========================
CREATE PROCEDURE EliminarCategoria(
    IN pIdCategoria INT
)
BEGIN
    DELETE FROM Categorias
    WHERE idCategoria = pIdCategoria;
END$$


-- =========================
-- Agregar Producto
-- =========================
CREATE PROCEDURE AgregarProducto(
    IN pNombre VARCHAR(100),
    IN pCodigoBarras VARCHAR(50),
    IN pPrecioCompra DECIMAL(10,2),
    IN pPrecioVenta DECIMAL(10,2),
    IN pStock INT,
    IN pStockMinimo INT,
    IN pImagen VARCHAR(255),
    IN pIdCategoria INT,
    IN pIdProveedor INT
)
BEGIN
    INSERT INTO Productos 
    (Nombre, CodigoBarras, PrecioCompra, PrecioVenta, Stock, StockMinimo, Imagen, idCategoria, idProveedor)
    VALUES 
    (pNombre, pCodigoBarras, pPrecioCompra, pPrecioVenta, pStock, pStockMinimo, pImagen, pIdCategoria, pIdProveedor);
END$$

-- =========================
-- Editar Producto
-- =========================
CREATE PROCEDURE EditarProducto(
    IN pIdProducto INT,
    IN pNombre VARCHAR(100),
    IN pCodigoBarras VARCHAR(50),
    IN pPrecioCompra DECIMAL(10,2),
    IN pPrecioVenta DECIMAL(10,2),
    IN pStock INT,
    IN pStockMinimo INT,
    IN pImagen VARCHAR(255),
    IN pIdCategoria INT,
    IN pIdProveedor INT
)
BEGIN
    UPDATE Productos
    SET 
        Nombre = pNombre,
        CodigoBarras = pCodigoBarras,
        PrecioCompra = pPrecioCompra,
        PrecioVenta = pPrecioVenta,
        Stock = pStock,
        StockMinimo = pStockMinimo,
        Imagen = pImagen,
        idCategoria = pIdCategoria,
        idProveedor = pIdProveedor
    WHERE idProducto = pIdProducto;
END$$

-- =========================
-- Eliminar Producto
-- =========================
CREATE PROCEDURE EliminarProducto(
    IN pIdProducto INT
)
BEGIN
    DELETE FROM Productos
    WHERE idProducto = pIdProducto;
END$$

DELIMITER $$

-- =========================
-- Buscar producto por código de barras
-- =========================
CREATE PROCEDURE BuscarProductoPorCodigoBarra(IN p_CodigoBarra VARCHAR(50))
BEGIN
    SELECT 
        p.idProducto,
        p.Nombre AS Producto,
        p.CodigoBarras,
        c.Nombre AS Categoria,
        p.PrecioVenta,
        p.PrecioCompra,
        p.Stock AS Existencia,
        p.Imagen
    FROM Productos p
    INNER JOIN Categorias c ON p.idCategoria = c.idCategoria
    WHERE p.CodigoBarras = p_CodigoBarra;
END $$

-- =========================
-- Buscar producto por nombre
-- =========================
CREATE PROCEDURE BuscarProductoPorNombre(IN p_Nombre VARCHAR(100))
BEGIN
    SELECT 
        p.idProducto,
        p.Nombre AS Producto,
        p.CodigoBarras,
        c.Nombre AS Categoria,
        p.PrecioVenta,
        p.PrecioCompra,
        p.Stock AS Existencia,
        p.Imagen
    FROM Productos p
    INNER JOIN Categorias c ON p.idCategoria = c.idCategoria
    WHERE p.Nombre LIKE CONCAT('%', p_Nombre, '%');
END $$


-- =========================
-- Agregar producto al carrito
-- =========================
CREATE PROCEDURE AgregarAlCarrito(
    IN p_idCarrito INT,
    IN p_idProducto INT,
    IN p_Cantidad INT
)
BEGIN
    DECLARE v_stock INT;
    DECLARE v_cantidad_actual INT DEFAULT 0;
    DECLARE v_cantidad_final INT;
    DECLARE v_precio DECIMAL(10,2);

    -- Obtener stock y precio del producto
    SELECT Stock, PrecioVenta INTO v_stock, v_precio
    FROM Productos
    WHERE idProducto = p_idProducto;

    IF v_stock IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El producto no existe.';
    END IF;

    IF p_Cantidad <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La cantidad debe ser mayor a 0.';
    END IF;

    -- Verificar si el producto ya está en el carrito
    IF EXISTS (SELECT 1 FROM DetalleCarrito WHERE idCarrito = p_idCarrito AND idProducto = p_idProducto) THEN
        SELECT Cantidad INTO v_cantidad_actual
        FROM DetalleCarrito
        WHERE idCarrito = p_idCarrito AND idProducto = p_idProducto;

        SET v_cantidad_final = LEAST(v_cantidad_actual + p_Cantidad, v_stock);

        UPDATE DetalleCarrito
        SET Cantidad = v_cantidad_final,
            Precio = v_precio
        WHERE idCarrito = p_idCarrito AND idProducto = p_idProducto;
    ELSE
        SET v_cantidad_final = LEAST(p_Cantidad, v_stock);

        INSERT INTO DetalleCarrito (idCarrito, idProducto, Cantidad, Precio)
        VALUES (p_idCarrito, p_idProducto, v_cantidad_final, v_precio);
    END IF;
END $$

-- =========================
-- Restar 1 unidad del carrito
-- =========================
CREATE PROCEDURE RestarCantidadCarrito(
    IN p_idCarrito INT,
    IN p_idProducto INT
)
BEGIN
    DECLARE v_cantidad_actual INT;

    SELECT Cantidad INTO v_cantidad_actual
    FROM DetalleCarrito
    WHERE idCarrito = p_idCarrito AND idProducto = p_idProducto;

    IF v_cantidad_actual IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El producto no está en el carrito.';
    END IF;

    IF v_cantidad_actual <= 1 THEN
        DELETE FROM DetalleCarrito WHERE idCarrito = p_idCarrito AND idProducto = p_idProducto;
    ELSE
        UPDATE DetalleCarrito
        SET Cantidad = v_cantidad_actual - 1
        WHERE idCarrito = p_idCarrito AND idProducto = p_idProducto;
    END IF;
END $$

-- =========================
-- Sumar 1 unidad al carrito
-- =========================
CREATE PROCEDURE SumarCantidadCarrito(
    IN p_idCarrito INT,
    IN p_idProducto INT
)
BEGIN
    DECLARE v_cantidad_actual INT;
    DECLARE v_stock INT;

    SELECT Cantidad INTO v_cantidad_actual
    FROM DetalleCarrito
    WHERE idCarrito = p_idCarrito AND idProducto = p_idProducto;

    IF v_cantidad_actual IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El producto no está en el carrito.';
    END IF;

    SELECT Stock INTO v_stock
    FROM Productos
    WHERE idProducto = p_idProducto;

    IF v_cantidad_actual >= v_stock THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No se puede superar la cantidad disponible en inventario.';
    END IF;

    UPDATE DetalleCarrito
    SET Cantidad = v_cantidad_actual + 1
    WHERE idCarrito = p_idCarrito AND idProducto = p_idProducto;
END $$

-- =========================
-- Actualizar cantidad específica del carrito
-- =========================
CREATE PROCEDURE ActualizarCantidadCarrito(
    IN p_idCarrito INT,
    IN p_idProducto INT,
    IN p_nuevaCantidad INT
)
BEGIN
    DECLARE v_stock INT;
    DECLARE v_mensaje VARCHAR(255);

    -- Validar cantidad negativa
    IF p_nuevaCantidad < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La cantidad no puede ser negativa.';
    END IF;

    -- Obtener stock actual
    SELECT Stock INTO v_stock FROM Productos WHERE idProducto = p_idProducto;

    -- Validar que no supere stock
    IF p_nuevaCantidad > v_stock THEN
        SET v_mensaje = CONCAT('No hay suficiente stock. Máximo disponible: ', v_stock);
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_mensaje;
    END IF;

    -- Si la cantidad es cero, eliminar del carrito
    IF p_nuevaCantidad = 0 THEN
        DELETE FROM DetalleCarrito 
        WHERE idCarrito = p_idCarrito AND idProducto = p_idProducto;
    ELSE
        UPDATE DetalleCarrito
        SET Cantidad = p_nuevaCantidad
        WHERE idCarrito = p_idCarrito AND idProducto = p_idProducto;
    END IF;

END $$

-- =========================
-- Obtener carrito de un usuario
-- =========================
CREATE PROCEDURE ObtenerCarritoUsuario(
    IN p_idUsuario INT
)
BEGIN
    SELECT 
        c.idCarrito,
        c.Fecha,
        dc.idDetalleCarrito,
        dc.idProducto,
        p.Nombre AS NombreProducto,
        p.CodigoBarras,
        dc.Cantidad,
        dc.Precio,
        dc.Cantidad * dc.Precio AS Total
    FROM Carrito c
    INNER JOIN DetalleCarrito dc ON c.idCarrito = dc.idCarrito
    INNER JOIN Productos p ON dc.idProducto = p.idProducto
    WHERE c.idUsuario = p_idUsuario
    ORDER BY c.Fecha ASC, dc.idDetalleCarrito ASC;
END $$


-- =========================
-- Procesar venta desde carrito (ajustado)
-- =========================
CREATE PROCEDURE ProcesarVentaDesdeCarrito (
    IN p_idUsuario INT,
    IN p_idCliente INT,
    IN p_ventaCredito TINYINT
)
BEGIN
    DECLARE v_idCarrito INT;
    DECLARE v_idVenta INT;
    DECLARE v_total DECIMAL(10,2);
    DECLARE v_creditoActual DECIMAL(10,2);
    DECLARE v_limiteCredito DECIMAL(10,2);

    /* ===== MANEJO DE ERRORES ===== */
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error al procesar la operación.';
    END;

    START TRANSACTION;

    /* ===== OBTENER CARRITO ACTIVO ===== */
    SELECT idCarrito
    INTO v_idCarrito
    FROM Carrito
    WHERE idUsuario = p_idUsuario
    ORDER BY Fecha DESC
    LIMIT 1
    FOR UPDATE;

    IF v_idCarrito IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No hay carrito activo.';
    END IF;

    /* ===== CALCULAR TOTAL ===== */
    SELECT SUM(Cantidad * Precio)
    INTO v_total
    FROM DetalleCarrito
    WHERE idCarrito = v_idCarrito;

    IF v_total IS NULL OR v_total <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El carrito está vacío.';
    END IF;

    /* ==================================================
       ========== VENTA A CRÉDITO ========================
       ================================================== */
    IF p_ventaCredito = 1 AND p_idCliente <> 1 THEN

        /* Bloquear cliente */
        SELECT Credito, Limite
        INTO v_creditoActual, v_limiteCredito
        FROM Clientes
        WHERE idCliente = p_idCliente
        FOR UPDATE;

        /* Validar límite */
        IF (v_creditoActual + v_total) > v_limiteCredito THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Límite de crédito excedido.';
        END IF;

        /* Descontar stock */
        UPDATE Productos p
        JOIN DetalleCarrito dc ON p.idProducto = dc.idProducto
        SET p.Stock = p.Stock - dc.Cantidad
        WHERE dc.idCarrito = v_idCarrito;

        /* Sumar crédito */
        UPDATE Clientes
        SET Credito = Credito + v_total
        WHERE idCliente = p_idCliente;

    /* ==================================================
       ========== VENTA DE CONTADO =======================
       ================================================== */
    ELSE

        /* Registrar venta */
        INSERT INTO Ventas (idUsuario, idCliente)
        VALUES (p_idUsuario, p_idCliente);

        SET v_idVenta = LAST_INSERT_ID();

        /* Registrar detalle */
        INSERT INTO DetalleVentas (idVenta, idProducto, Cantidad, PrecioVenta)
        SELECT v_idVenta, idProducto, Cantidad, Precio
        FROM DetalleCarrito
        WHERE idCarrito = v_idCarrito;

        /* Descontar stock */
        UPDATE Productos p
        JOIN DetalleCarrito dc ON p.idProducto = dc.idProducto
        SET p.Stock = p.Stock - dc.Cantidad
        WHERE dc.idCarrito = v_idCarrito;

    END IF;

    /* ===== LIMPIAR CARRITO ===== */
    DELETE FROM DetalleCarrito WHERE idCarrito = v_idCarrito;
    DELETE FROM Carrito WHERE idCarrito = v_idCarrito;

    COMMIT;
END $$


-- =========================
-- Agregar abono a crédito
-- =========================
CREATE PROCEDURE RegistrarAbonoCredito (
    IN p_idUsuario INT,
    IN p_idCliente INT,
    IN p_montoAbono DECIMAL(10,2)
)
BEGIN
    DECLARE v_creditoActual DECIMAL(10,2);
    DECLARE v_idVenta INT;
    DECLARE v_idProductoAbono INT;

    /* ================= MANEJO DE ERRORES ================= */
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error al registrar el abono al crédito.';
    END;

    START TRANSACTION;

    /* ================= VALIDAR MONTO ================= */
    IF p_montoAbono <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El monto del abono debe ser mayor a 0.';
    END IF;

    /* ================= OBTENER PRODUCTO ABONO ================= */
    SELECT idProducto
    INTO v_idProductoAbono
    FROM Productos
    WHERE Nombre = 'AbonosCreditos'
    LIMIT 1;

    IF v_idProductoAbono IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El producto AbonosCreditos no existe.';
    END IF;

    /* ================= OBTENER CRÉDITO ACTUAL ================= */
    SELECT Credito
    INTO v_creditoActual
    FROM Clientes
    WHERE idCliente = p_idCliente
    FOR UPDATE;

    IF v_creditoActual IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cliente no encontrado.';
    END IF;

    /* ================= VALIDAR ABONO ================= */
    IF p_montoAbono > v_creditoActual THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El abono no puede ser mayor al crédito pendiente.';
    END IF;

    /* ================= ACTUALIZAR CRÉDITO ================= */
    UPDATE Clientes
    SET Credito = Credito - p_montoAbono
    WHERE idCliente = p_idCliente;

    /* ================= REGISTRAR VENTA ================= */
    INSERT INTO Ventas (idUsuario, idCliente)
    VALUES (p_idUsuario, p_idCliente);

    SET v_idVenta = LAST_INSERT_ID();

    /* ================= DETALLE DE VENTA ================= */
    INSERT INTO DetalleVentas (
        idVenta,
        idProducto,
        Cantidad,
        PrecioVenta,
        Descuento
    )
    VALUES (
        v_idVenta,
        v_idProductoAbono,
        1,
        p_montoAbono,
        0
    );

    COMMIT;
END $$


-- =========================
-- Agregar pedido desde JSON
-- =========================
CREATE PROCEDURE AgregarPedido(
    IN p_idCliente INT, 
    IN p_idUsuario INT, 
    IN p_ProductosJSON JSON
)
BEGIN
    DECLARE v_idPedido INT;
    DECLARE v_i INT DEFAULT 0;
    DECLARE v_len INT;
    DECLARE v_idProducto INT;
    DECLARE v_Cantidad INT;
    DECLARE v_Precio DECIMAL(10,2);
    DECLARE v_StockActual INT;
    DECLARE v_idCarrito INT;
    DECLARE v_ErrorMsg VARCHAR(255);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    /* ================= INSERTAR PEDIDO ================= */
    INSERT INTO Pedidos (idCliente, idUsuario, Estado, Fecha)
    VALUES (p_idCliente, p_idUsuario, 'Pendiente', NOW());

    SET v_idPedido = LAST_INSERT_ID();
    SET v_len = JSON_LENGTH(p_ProductosJSON);

    /* ================= RECORRER PRODUCTOS ================= */
    WHILE v_i < v_len DO

        SET v_idProducto = CAST(
            JSON_UNQUOTE(JSON_EXTRACT(p_ProductosJSON, CONCAT('$[',v_i,'].idProducto')))
            AS UNSIGNED
        );

        SET v_Cantidad = CAST(
            JSON_UNQUOTE(JSON_EXTRACT(p_ProductosJSON, CONCAT('$[',v_i,'].Cantidad')))
            AS UNSIGNED
        );

        SET v_Precio = CAST(
            JSON_UNQUOTE(JSON_EXTRACT(p_ProductosJSON, CONCAT('$[',v_i,'].PrecioProducto')))
            AS DECIMAL(10,2)
        );

        /* ========= VALIDACIONES ========= */
        IF v_Cantidad <= 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'La cantidad debe ser mayor a 0';
        END IF;

        SELECT Stock
        INTO v_StockActual
        FROM Productos
        WHERE idProducto = v_idProducto
        FOR UPDATE;

        IF v_StockActual < v_Cantidad THEN
            SET v_ErrorMsg = CONCAT('Stock insuficiente para producto ID ', v_idProducto);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_ErrorMsg;
        END IF;

        /* ========= INSERTAR DETALLE ========= */
        INSERT INTO DetallePedidos (idPedido, idProducto, Cantidad, PrecioVenta)
        VALUES (v_idPedido, v_idProducto, v_Cantidad, v_Precio);

        /* ========= DESCONTAR STOCK ========= */
        UPDATE Productos
        SET Stock = Stock - v_Cantidad
        WHERE idProducto = v_idProducto;

        SET v_i = v_i + 1;

    END WHILE;

    /* ================= LIMPIAR CARRITO ================= */

    -- Obtener carrito del usuario
    SELECT idCarrito
    INTO v_idCarrito
    FROM Carrito
    WHERE idUsuario = p_idUsuario
    ORDER BY Fecha DESC
    LIMIT 1;

    IF v_idCarrito IS NOT NULL THEN
        -- Eliminar detalles (ON DELETE CASCADE también funcionaría)
        DELETE FROM DetalleCarrito WHERE idCarrito = v_idCarrito;

    END IF;

    COMMIT;

END$$


-- =========================
-- Editar pedido pendiente
-- =========================
CREATE PROCEDURE EditarPedido (
    IN p_idPedido INT,
    IN p_idProducto INT,
    IN p_cantidad INT
)
BEGIN
    DECLARE v_estado VARCHAR(20);
    DECLARE v_existeProducto INT;
    DECLARE v_totalProductos INT;
    DECLARE v_precio DECIMAL(10,2);

    /* ===============================
       1. VALIDAR ESTADO DEL PEDIDO
    =============================== */
    SELECT Estado
    INTO v_estado
    FROM Pedidos
    WHERE idPedido = p_idPedido;

    IF v_estado IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El pedido no existe';
    END IF;

    IF v_estado <> 'Pendiente' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Solo se pueden editar pedidos en estado Pendiente';
    END IF;

    /* ===============================
       2. VALIDAR PRODUCTO
    =============================== */
    SELECT PrecioVenta
    INTO v_precio
    FROM Productos
    WHERE idProducto = p_idProducto;

    IF v_precio IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El producto no existe';
    END IF;

    /* ===============================
       3. VERIFICAR SI EL PRODUCTO YA ESTÁ EN EL PEDIDO
    =============================== */
    SELECT COUNT(*)
    INTO v_existeProducto
    FROM DetallePedidos
    WHERE idPedido = p_idPedido
      AND idProducto = p_idProducto;

    /* ===============================
       4. INSERTAR / ACTUALIZAR / ELIMINAR
    =============================== */
    IF p_cantidad > 0 THEN

        IF v_existeProducto > 0 THEN
            UPDATE DetallePedidos
            SET Cantidad = p_cantidad
            WHERE idPedido = p_idPedido
              AND idProducto = p_idProducto;
        ELSE
            INSERT INTO DetallePedidos (idPedido, idProducto, Cantidad, PrecioVenta)
            VALUES (p_idPedido, p_idProducto, p_cantidad, v_precio);
        END IF;

    ELSE
        DELETE FROM DetallePedidos
        WHERE idPedido = p_idPedido
          AND idProducto = p_idProducto;
    END IF;

    /* ===============================
       5. VALIDAR SI EL PEDIDO QUEDÓ VACÍO
    =============================== */
    SELECT COUNT(*)
    INTO v_totalProductos
    FROM DetallePedidos
    WHERE idPedido = p_idPedido;

    IF v_totalProductos = 0 THEN
        UPDATE Pedidos
        SET Estado = 'Cancelado'
        WHERE idPedido = p_idPedido;
    END IF;

END $$

-- =========================
-- Procesar pedido como venta (ajustado)
-- =========================
CREATE PROCEDURE ProcesarPedidoComoVenta (
    IN p_idPedido INT
)
BEGIN
    DECLARE v_idVenta INT;
    DECLARE v_idCliente INT;
    DECLARE v_idUsuario INT;
    DECLARE v_EstadoActual VARCHAR(20);

    START TRANSACTION;

    /* ===== Validar pedido ===== */
    SELECT idCliente, idUsuario, Estado
    INTO v_idCliente, v_idUsuario, v_EstadoActual
    FROM Pedidos
    WHERE idPedido = p_idPedido
    LIMIT 1;

    IF v_idCliente IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Pedido no encontrado.';
    END IF;

    IF v_EstadoActual <> 'Parcial' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Solo pedidos PREPARADOS pueden convertirse en venta.';
    END IF;

    /* ===== Insertar venta ===== */
    INSERT INTO Ventas (idCliente, idUsuario)
    VALUES (v_idCliente, v_idUsuario);

    SET v_idVenta = LAST_INSERT_ID();

    /* ===== Copiar detalle del pedido a la venta ===== */
    INSERT INTO DetalleVentas (idVenta, idProducto, Cantidad, PrecioVenta)
    SELECT v_idVenta, idProducto, Cantidad, PrecioVenta
    FROM DetallePedidos
    WHERE idPedido = p_idPedido;

    /* ===== Marcar pedido como surtido ===== */
    UPDATE Pedidos
    SET Estado = 'Surtido'
    WHERE idPedido = p_idPedido;

    COMMIT;
END $$


-- =========================
-- Cancelar pedido (ajustado)
-- =========================
CREATE PROCEDURE CancelarPedido (
    IN p_idPedido INT,
    IN p_idUsuario INT
)
BEGIN
    DECLARE v_EstadoActual VARCHAR(20);
    DECLARE v_Descripcion TEXT;

    SELECT Estado INTO v_EstadoActual
    FROM Pedidos
    WHERE idPedido = p_idPedido
    LIMIT 1;

    IF v_EstadoActual IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El pedido no existe.';
    END IF;

    IF v_EstadoActual IN ('Cancelado','Surtido') THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El pedido no puede cancelarse.';
    END IF;

    -- Devolver stock
    UPDATE Productos p
    JOIN DetallePedidos dp ON p.idProducto = dp.idProducto
    SET p.Stock = p.Stock + dp.Cantidad
    WHERE dp.idPedido = p_idPedido;

    -- Cambiar estado
    UPDATE Pedidos SET Estado = 'Cancelado' WHERE idPedido = p_idPedido;

    -- Registrar en historial de modificaciones
    SET v_Descripcion = CONCAT('Pedido ', p_idPedido, ' cancelado por usuario ', p_idUsuario);
    INSERT INTO HistorialModificaciones (Tabla, idRegistro, Accion, idUsuario, Fecha)
    VALUES ('Pedidos', p_idPedido, 'UPDATE', p_idUsuario, NOW());
END $$


-- =========================
-- Preparar pedido
-- =========================
CREATE PROCEDURE PrepararPedido (
    IN p_idPedido INT,
    IN p_idUsuario INT
)
BEGIN
    DECLARE v_EstadoActual VARCHAR(20);
    DECLARE v_Descripcion TEXT;

    SELECT Estado INTO v_EstadoActual
    FROM Pedidos
    WHERE idPedido = p_idPedido
    LIMIT 1;

    IF v_EstadoActual IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El pedido no existe.';
    END IF;

    IF v_EstadoActual <> 'Pendiente' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Solo se pueden preparar pedidos pendientes.';
    END IF;

    UPDATE Pedidos SET Estado = 'Parcial' WHERE idPedido = p_idPedido;

    SET v_Descripcion = CONCAT('Pedido ', p_idPedido, ' marcado como PREPARADO por usuario ', p_idUsuario);
    INSERT INTO Auditoria (TablaAfectada, Operacion, idRegistroAfectado, idUsuario, Descripcion)
    VALUES ('Pedidos', 'UPDATE', p_idPedido, p_idUsuario, v_Descripcion);
END $$


-- =====================================
-- Devolver un producto individual
-- =====================================
CREATE PROCEDURE DevolverProductoIndividual(
    IN p_idVenta INT,
    IN p_idDetalleVenta INT,
    IN p_CantidadDevuelta INT,
    IN p_Motivo VARCHAR(200),
    IN p_idEmpleado INT
)
BEGIN
    DECLARE v_CantidadActual INT;
    DECLARE v_Precio DECIMAL(10,2);
    DECLARE v_idProducto INT;

    START TRANSACTION;

    -- Bloquear registro del detalle de venta
    SELECT idProducto, Cantidad, PrecioVenta
    INTO v_idProducto, v_CantidadActual, v_Precio
    FROM DetalleVentas
    WHERE idDetalleVenta = p_idDetalleVenta AND idVenta = p_idVenta
    FOR UPDATE;

    IF p_CantidadDevuelta > v_CantidadActual THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La cantidad a devolver excede la cantidad vendida.';
    END IF;

    -- Insertar devolución
    INSERT INTO Devoluciones (Motivo, idEmpleado)
    VALUES (p_Motivo, p_idEmpleado);

    INSERT INTO DetalleDevoluciones (idDevolucion, idProducto, Cantidad, PrecioVenta)
    VALUES (LAST_INSERT_ID(), v_idProducto, p_CantidadDevuelta, v_Precio);

    -- Actualizar inventario
    UPDATE Productos
    SET Stock = Stock + p_CantidadDevuelta
    WHERE idProducto = v_idProducto;

    -- Actualizar o eliminar detalle de venta
    IF v_CantidadActual - p_CantidadDevuelta > 0 THEN
        UPDATE DetalleVentas
        SET Cantidad = v_CantidadActual - p_CantidadDevuelta
        WHERE idDetalleVenta = p_idDetalleVenta;
    ELSE
        DELETE FROM DetalleVentas WHERE idDetalleVenta = p_idDetalleVenta;
    END IF;

    COMMIT;
END $$


-- =========================
-- Devolver toda la venta (ajustado)
-- =========================
CREATE PROCEDURE DevolverVentaCompleta(
    IN p_idVenta INT,
    IN p_Motivo VARCHAR(200),
    IN p_idEmpleado INT
)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_idDetalle INT;
    DECLARE v_idProducto INT;
    DECLARE v_Cantidad INT;
    DECLARE v_Precio DECIMAL(10,2);
    DECLARE v_idDevolucion INT;
    DECLARE v_StockActual INT;

    DECLARE cur CURSOR FOR
        SELECT idDetalleVenta, idProducto, Cantidad, PrecioVenta
        FROM DetalleVentas
        WHERE idVenta = p_idVenta;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    START TRANSACTION;

    INSERT INTO Devoluciones (Motivo, idEmpleado)
    VALUES (p_Motivo, p_idEmpleado);

    SET v_idDevolucion = LAST_INSERT_ID();

    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO v_idDetalle, v_idProducto, v_Cantidad, v_Precio;
        IF done THEN
            LEAVE read_loop;
        END IF;

        SELECT Stock INTO v_StockActual
        FROM Productos
        WHERE idProducto = v_idProducto
        FOR UPDATE;

        INSERT INTO DetalleDevoluciones (idDevolucion, idProducto, Cantidad, PrecioVenta)
        VALUES (v_idDevolucion, v_idProducto, v_Cantidad, v_Precio);

        UPDATE Productos
        SET Stock = v_StockActual + v_Cantidad
        WHERE idProducto = v_idProducto;

        -- Marcar detalle de venta como 0
        UPDATE DetalleVentas
        SET Cantidad = 0, PrecioVenta = 0
        WHERE idDetalleVenta = v_idDetalle;
    END LOOP;

    CLOSE cur;

    COMMIT;
END $$

DELIMITER ;