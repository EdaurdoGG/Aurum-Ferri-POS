CREATE OR REPLACE VIEW VistaVentas AS
SELECT
    v.idVenta  AS NumeroVenta,
    DATE(v.Fecha)                               AS Fecha,
    TIME(v.Fecha)                               AS Hora,

    -- Usuario / Empleado
    u.idUsuario,
    CONCAT(perEmp.Nombre, ' ', perEmp.Paterno, ' ', perEmp.Materno) 
                                                AS Empleado,

    -- Cliente
    c.idCliente,
    CONCAT(perCli.Nombre, ' ', perCli.Paterno, ' ', perCli.Materno) 
                                                AS Cliente,

    -- Totales calculados
    SUM(dv.Cantidad * p.PrecioCompra)           AS TotalInvertido,
    SUM(dv.Cantidad * dv.PrecioVenta)           AS TotalVenta,
    SUM((dv.Cantidad * dv.PrecioVenta) 
        - (dv.Cantidad * p.PrecioCompra)
        - dv.Descuento)                          AS Ganancia

FROM Ventas v
INNER JOIN Usuarios u           ON v.idUsuario = u.idUsuario
INNER JOIN Personas perEmp      ON u.idPersona = perEmp.idPersona

LEFT JOIN Clientes c            ON v.idCliente = c.idCliente
LEFT JOIN Personas perCli       ON c.idPersona = perCli.idPersona

INNER JOIN DetalleVentas dv     ON v.idVenta = dv.idVenta
INNER JOIN Productos p          ON dv.idProducto = p.idProducto

GROUP BY 
    v.idVenta,
    v.Fecha,
    u.idUsuario,
    perEmp.Nombre,
    perEmp.Paterno,
    perEmp.Materno,
    c.idCliente,
    perCli.Nombre,
    perCli.Paterno,
    perCli.Materno;


CREATE OR REPLACE VIEW VistaUsuarios AS
SELECT
    u.idUsuario,
    u.Usuario,
    r.Nombre AS Rol,
    p.idPersona,
    p.Nombre,
    p.Paterno,
    p.Materno,
    p.Telefono,
    p.Email,
    p.Imagen,
    p.Estatus
FROM Usuarios u
JOIN Personas p ON u.idPersona = p.idPersona
JOIN Roles r    ON u.idRol = r.idRol;


CREATE OR REPLACE VIEW VistaProductos AS
SELECT
    p.idProducto,
    p.Nombre AS Producto,
    c.Nombre AS Categoria,
    pr.idProveedor,
    CONCAT(perProv.Nombre, ' ', perProv.Paterno, ' ', perProv.Materno) AS Proveedor,
    perProv.Estatus AS EstatusProveedor,
    p.Stock,
    p.PrecioCompra,
    p.PrecioVenta,
    p.Imagen
FROM Productos p
JOIN Categorias c       ON p.idCategoria = c.idCategoria
JOIN Proveedores pr     ON p.idProveedor = pr.idProveedor
JOIN Personas perProv   ON pr.idPersona = perProv.idPersona;



CREATE OR REPLACE VIEW VistaClientes AS
SELECT
    c.idCliente,
    
    p.idPersona,
    p.Nombre,
    p.Paterno,
    p.Materno,
    p.Telefono,
    p.Email,
    p.Imagen,
    p.Estatus, -- permite filtrar por Activo/Inactivo
    
    c.Credito,
    c.Limite

FROM Clientes c
JOIN Personas p ON c.idPersona = p.idPersona;



CREATE OR REPLACE VIEW VistaPedidos AS
SELECT
    p.idPedido,
    DATE(p.Fecha) AS Fecha,
    TIME(p.Fecha) AS Hora,
    p.Estado,

    -- Cliente
    c.idCliente,
    CONCAT(perCli.Nombre, ' ', perCli.Paterno, ' ', perCli.Materno) AS Cliente,

    -- Usuario / Empleado
    u.idUsuario,
    CONCAT(perUsr.Nombre, ' ', perUsr.Paterno, ' ', perUsr.Materno) AS Empleado,

    -- Detalle pedido
    dp.idDetallePedido,
    pr.idProducto,
    pr.Nombre AS Producto,
    dp.Cantidad,
    dp.PrecioVenta AS PrecioUnitario,
    (dp.Cantidad * dp.PrecioVenta) AS Subtotal

FROM Pedidos p
JOIN Clientes c        ON p.idCliente = c.idCliente
JOIN Personas perCli   ON c.idPersona = perCli.idPersona

JOIN Usuarios u        ON p.idUsuario = u.idUsuario
JOIN Personas perUsr   ON u.idPersona = perUsr.idPersona

JOIN DetallePedidos dp ON p.idPedido = dp.idPedido
JOIN Productos pr      ON dp.idProducto = pr.idProducto;


CREATE OR REPLACE VIEW VistaDevoluciones AS
SELECT
    d.idDevolucion,
    DATE(d.Fecha) AS Fecha,
    TIME(d.Fecha) AS Hora,
    d.Motivo,

    -- Usuario / Empleado
    u.idUsuario,
    CONCAT(perUsr.Nombre, ' ', perUsr.Paterno, ' ', perUsr.Materno) AS Empleado,

    -- Detalle devolución
    dd.idDetalleDevolucion,
    pr.idProducto,
    pr.Nombre AS Producto,
    dd.Cantidad,
    dd.PrecioVenta,
    (dd.Cantidad * dd.PrecioVenta) AS TotalDevuelto

FROM Devoluciones d
JOIN Usuarios u        ON d.idEmpleado = u.idUsuario
JOIN Personas perUsr  ON u.idPersona = perUsr.idPersona

JOIN DetalleDevoluciones dd ON d.idDevolucion = dd.idDevolucion
JOIN Productos pr           ON dd.idProducto = pr.idProducto;


-- Vista básica de proveedores con datos personales
CREATE OR REPLACE VIEW VistaProveedores AS
SELECT 
    prov.idProveedor,   -- corregido: alias y nombre de columna
    per.Nombre,
    per.Paterno,
    per.Materno,
    per.Telefono,
    per.Email,
    per.Imagen,
    prov.Estado
FROM Proveedores prov
INNER JOIN Personas per ON prov.idPersona = per.idPersona;


CREATE OR REPLACE VIEW VistaAuditoria AS
SELECT 
    h.idHistorial,
    h.TablaAfectada AS Tabla,
    h.Movimiento AS Accion,
    h.ColumnaAfectada,
    h.DatoAnterior,
    h.DatoNuevo,
    h.Fecha,
    h.idUsuario,
    u.Usuario AS NombreUsuario,
    CONCAT(p.Nombre, ' ', p.Paterno, ' ', p.Materno) AS NombreCompletoUsuario
FROM HistorialModificaciones h
LEFT JOIN Usuarios u ON h.idUsuario = u.idUsuario
LEFT JOIN Personas p ON u.idPersona = p.idPersona;


CREATE OR REPLACE VIEW VistaCategorias AS
SELECT 
    idCategoria,
    Nombre
FROM Categorias
ORDER BY Nombre;


CREATE OR REPLACE VIEW VistaNotificaciones AS
SELECT
    n.idNotificacion,
    n.Tipo,
    n.Mensaje,
    n.Fecha,
    n.Leida,

    -- Producto (si aplica)
    n.idProducto,
    p.Nombre AS NombreProducto,
    p.Stock AS StockActual,

    -- Pedido (si aplica)
    n.idPedido,
    ped.Estado AS EstadoPedido,
    ped.Fecha AS FechaPedido,

    -- Usuario asignado (si aplica)
    n.idUsuario,
    u.Usuario AS UsuarioAsignado,
    CONCAT(per.Nombre, ' ', per.Paterno, ' ', per.Materno) AS NombreCompletoUsuario

FROM Notificaciones n

LEFT JOIN Productos p 
    ON n.idProducto = p.idProducto

LEFT JOIN Pedidos ped
    ON n.idPedido = ped.idPedido

LEFT JOIN Usuarios u
    ON n.idUsuario = u.idUsuario

LEFT JOIN Personas per
    ON u.idPersona = per.idPersona;
