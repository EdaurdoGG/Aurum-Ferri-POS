<?php
session_start();
if (!isset($_SESSION['id'])) {
    die("Acceso denegado. Debes iniciar sesión.");
}

$empleadoId = intval($_SESSION['id']);
$mysqli = new mysqli('db', 'root', 'clave', 'HerreriaUG');
if ($mysqli->connect_errno) {
    die("Error de conexión a la base de datos: " . $mysqli->connect_error);
}

if (!$mysqli->query("SET @id_empleado_sesion := $empleadoId")) {
    die("Error al establecer variable de sesión en MySQL: " . $mysqli->error);
}

$mensaje = '';
$productosVenta = [];
$ventaSeleccionada = intval($_POST['venta_id'] ?? 0);
$fechaSeleccionada = $_POST['fecha'] ?? '';
$tipoDevolucion = $_POST['tipo_devolucion'] ?? '';
$productoSeleccionado = intval($_POST['producto_id'] ?? 0);
$cantidadDevolver = intval($_POST['cantidad'] ?? 0);
$motivoDevolucion = trim($_POST['motivo'] ?? '');

function obtenerVentas($mysqli, $empleadoId, $fecha) {
    $ventas = [];
    $sql = "SELECT v.idVenta, GROUP_CONCAT(p.Nombre SEPARATOR ', ') AS NombreProductos
            FROM Ventas v
            JOIN DetalleVenta dv ON v.idVenta = dv.idVenta
            JOIN Productos p ON dv.idProducto = p.idProducto
            WHERE DATE(v.Fecha) = ? AND v.idEmpleado = ? AND v.Estatus != 'Cancelada'
            GROUP BY v.idVenta
            ORDER BY v.idVenta";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('si', $fecha, $empleadoId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $ventas[] = $row;
    }
    $stmt->close();
    return $ventas;
}

function obtenerProductosVenta($mysqli, $idVenta) {
    $productos = [];
    $sql = "SELECT dv.idProducto, p.Nombre AS NombreProducto, dv.Cantidad
            FROM DetalleVenta dv
            JOIN Productos p ON dv.idProducto = p.idProducto
            WHERE dv.idVenta = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $idVenta);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $productos[] = $row;
    }
    $stmt->close();
    return $productos;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['devolver'])) {
    if (!$fechaSeleccionada) {
        $mensaje = "Seleccione una fecha válida.";
    } elseif (!$ventaSeleccionada) {
        $mensaje = "Seleccione una venta válida.";
    } elseif (!in_array($tipoDevolucion, ['pieza', 'venta'])) {
        $mensaje = "Seleccione un tipo de devolución válido.";
    } else {
        try {
            if ($tipoDevolucion === 'pieza') {
                if (!$productoSeleccionado || $cantidadDevolver <= 0) {
                    $mensaje = "Seleccione un producto y una cantidad válida para devolver.";
                } elseif (empty($motivoDevolucion)) {
                    $mensaje = "Debe proporcionar un motivo para la devolución.";
                } else {
                    $stmt = $mysqli->prepare("CALL DevolverProductoIndividual(?, ?, ?, ?, ?)");
                    if (!$stmt) {
                        throw new Exception("Error en prepare(): " . $mysqli->error);
                    }
                    $stmt->bind_param('iiiis', $ventaSeleccionada, $productoSeleccionado, $cantidadDevolver, $empleadoId, $motivoDevolucion);
                    if (!$stmt->execute()) {
                        throw new Exception("Error en execute(): " . $stmt->error);
                    }
                    $stmt->close();
                    while ($mysqli->more_results() && $mysqli->next_result()) {
                        $res = $mysqli->use_result();
                        if ($res instanceof mysqli_result) {
                            $res->free();
                        }
                    }
                    $mensaje = "Producto devuelto correctamente.";
                }
            } else {
                $stmt = $mysqli->prepare("CALL DevolverVentaCompleta(?, ?, ?)");
                if (!$stmt) {
                    throw new Exception("Error en prepare(): " . $mysqli->error);
                }
                $stmt->bind_param('iis', $ventaSeleccionada, $empleadoId, $motivoDevolucion);
                if (!$stmt->execute()) {
                    throw new Exception("Error en execute(): " . $stmt->error);
                }
                $stmt->close();
                while ($mysqli->more_results() && $mysqli->next_result()) {
                    $res = $mysqli->use_result();
                    if ($res instanceof mysqli_result) {
                        $res->free();
                    }
                }
                $mensaje = "Venta completa devuelta correctamente.";
            }
        } catch (Exception $e) {
            $mensaje = "Error al procesar la devolución: " . $e->getMessage();
        }
    }
}

if ($fechaSeleccionada && $ventaSeleccionada && $tipoDevolucion === 'pieza') {
    $productosVenta = obtenerProductosVenta($mysqli, $ventaSeleccionada);
}

$ventas = [];
if ($fechaSeleccionada) {
    $ventas = obtenerVentas($mysqli, $empleadoId, $fechaSeleccionada);
}

$enlaceInicio = 'Inicio.php';
if (isset($_SESSION['rol'])) {
    switch ($_SESSION['rol']) {
        case 'Administrador':
            $enlaceInicio = 'InicioAdministradores.php';
            break;
        case 'Cajero':
            $enlaceInicio = 'InicioTrabajadores.php';
            break;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Aurum Ferri</title>
    <link rel="stylesheet" href="assets/css/Devolucion.css" />
    <script>
        function mostrarProductos() {
            const tipo = document.getElementById('tipoDevolucion').value;
            const productosDiv = document.getElementById('productosDiv');
            const productoSelect = document.getElementById('producto_id');
            const cantidadInput = document.getElementById('cantidad');
            if (tipo === 'pieza') {
                productosDiv.style.display = 'block';
                productoSelect.required = true;
                cantidadInput.required = true;
            } else {
                productosDiv.style.display = 'none';
                productoSelect.required = false;
                cantidadInput.required = false;
                productoSelect.selectedIndex = 0;
                cantidadInput.value = '';
            }
        }
        window.onload = mostrarProductos;
    </script>
</head>
<body>
<main>
    <section class="izquierda"></section>
    <section class="centro">
        <label for="menu-toggle" class="btn-atras-contenedor">
            <img src="Imagenes/Menu.png" alt="Menú" class="boton-atras" id="boton-menu" />
        </label>

        <nav class="menu-lateral">
            <div class="menu-header">
                <img src="Imagenes/Logo.png" alt="Logo" class="menu-logo" />
                <h2 class="menu-titulo">Aurum Ferri</h2>
            </div>
            <ul class="menu-list">
                <li><a href="InicioAdministradores.php"><img src="Imagenes/Casa.png" alt="Inicio" /> Inicio</a></li>
                <li><a href="Almacen.php"><img src="Imagenes/Almacen.png" alt="Almacén" /> Almacén</a></li>
                <li><a href="Empleados.php"><img src="Imagenes/Empleado.png" alt="Empleados" /> Empleados</a></li>
                <li><a href="Provedores.php"><img src="Imagenes/Proveedor.png" alt="Proveedores" /> Proveedores</a></li>
                <li><a href="Clientes.php"><img src="Imagenes/Cliente.png" alt="Clientes" /> Clientes</a></li>
                <li><a href="Devolucion.php"><img src="Imagenes/Devolucion.png" alt="Devoluciones" /> Devoluciones</a></li>
                <li><a href="Ventas.php"><img src="Imagenes/Ventas.png" alt="Ventas" /> Ventas</a></li>
                <li><a href="PedidosPendientes.php"><img src="Imagenes/Pedido.png" alt="Pedidos" /> Pedidos</a></li>
                <li><a href="Auditoria.php"><img src="Imagenes/Pasos.png" alt="Auditoría" /> Cambios</a></li>
                <li><a href="Login.php"><img src="Imagenes/Salir.png" alt="Cerrar sesión" /> Cerrar Sesión</a></li>
            </ul>
        </nav>

        <div class="log">
            <div class="login">
                <div class="titulo">
                    <h2>Devoluciones</h2>
                </div>
                <?php if (!empty($mensaje)): ?>
                    <p class="mensaje-error"><?= htmlspecialchars($mensaje) ?></p>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="input-group">
                        <input type="date" id="fecha" name="fecha" required value="<?= htmlspecialchars($fechaSeleccionada ?? '') ?>" onchange="this.form.submit()" />
                        <label for="fecha">Fecha:</label>
                    </div>

                    <div class="input-group">
                        <select id="venta_id" name="venta_id" required onchange="this.form.submit()">
                            <option value="" disabled <?= !isset($ventaSeleccionada) ? 'selected' : '' ?>>Seleccione la venta</option>
                            <?php foreach ($ventas ?? [] as $v): ?>
                                <option value="<?= htmlspecialchars($v['idVenta']) ?>" <?= (isset($ventaSeleccionada) && $v['idVenta'] == $ventaSeleccionada) ? 'selected' : '' ?>>
                                    Venta #<?= htmlspecialchars($v['idVenta']) ?> - <?= htmlspecialchars($v['NombreProductos']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="venta_id">Ventas del día:</label>
                    </div>

                    <div class="input-group">
                        <select id="tipoDevolucion" name="tipo_devolucion" required onchange="mostrarProductos(); this.form.submit();">
                            <option value="" disabled <?= empty($tipoDevolucion) ? 'selected' : '' ?>>Seleccione tipo de devolución</option>
                            <option value="pieza" <?= ($tipoDevolucion === 'pieza') ? 'selected' : '' ?>>Por pieza</option>
                            <option value="venta" <?= ($tipoDevolucion === 'venta') ? 'selected' : '' ?>>Venta completa</option>
                        </select>
                        <label for="tipoDevolucion">Tipo de devolución:</label>
                    </div>

                    <div class="input-group" id="productosDiv" style="display:none;">
                        <select id="producto_id" name="producto_id">
                            <option value="" disabled selected>Seleccione un producto</option>
                            <?php foreach ($productosVenta ?? [] as $prod): ?>
                                <option value="<?= htmlspecialchars($prod['idProducto']) ?>">
                                    <?= htmlspecialchars($prod['NombreProducto']) ?> (Cantidad: <?= htmlspecialchars($prod['Cantidad']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="producto_id">Producto a devolver:</label>

                        <input type="number" name="cantidad" id="cantidad" min="1" placeholder="Cantidad a devolver" />
                    </div>

                    <div class="input-group">
                        <input type="text" id="motivo" name="motivo" required value="<?= htmlspecialchars($motivo ?? '') ?>" />
                        <label for="motivo">Motivo de la devolución:</label>
                    </div>

                    <button type="submit" name="devolver">Procesar devolución</button>
                    <button type="button" onclick="location.href='HistorialDevoluciones.php'" class="btn-estilo">Ver devoluciones</button>
                </form>
            </div>
        </div>
    </section>
    <section class="derecha"> </section>
</main>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>
</body>
</html>
