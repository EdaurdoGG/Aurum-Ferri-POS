<?php
session_start();
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';

// ================= VERIFICAR SESIÓN =================
requireRole(3); // Solo proveedores

$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Proveedor';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Proveedor';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= CLIENTE SELECCIONADO ================= */
$idCliente     = $_SESSION['cliente_seleccionado'] ?? null;
$nombreCliente = $_SESSION['cliente_nombre'] ?? null;

/* ================= RUTAS IMÁGENES ================= */
$rutaServidor = __DIR__ . '/../../public/Imagenes/Productos/';
$rutaWeb = 'Imagenes/Productos/';

/* ================= MENSAJES ================= */
$mensaje = $_GET['pedido_ok'] ?? '';
$error   = '';

/* ================= CONTADOR CARRITO ================= */
$resCount = $conn->query("
    SELECT COUNT(DISTINCT dc.idProducto) total
    FROM DetalleCarrito dc
    INNER JOIN Carrito c ON dc.idCarrito = c.idCarrito
    WHERE c.idUsuario = $idUsuario
");
$contador = (int)$resCount->fetch_assoc()['total'];

/* ================= FUNCIONES ================= */
function limpiarResultados($conn) {
    while ($conn->more_results()) $conn->next_result();
}

/* ================= OBTENER CARRITO ================= */
$carrito   = [];
$idCarrito = null;

limpiarResultados($conn);
$stmt = $conn->prepare("CALL ObtenerCarritoUsuario(?)");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $carrito[] = $row;
    $idCarrito = $row['idCarrito'];
}
$stmt->close();
limpiarResultados($conn);

/* ================= IMÁGENES ================= */
$imagenesProductos = [];
$resImg = $conn->query("SELECT idProducto, Imagen FROM VistaProductos");
while ($img = $resImg->fetch_assoc()) {
    $imagenesProductos[$img['idProducto']] = $img['Imagen'];
}

/* ================= ACCIONES ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!$idCarrito) throw new Exception("No existe un carrito activo.");

        if (isset($_POST['sumar'])) {
            limpiarResultados($conn);
            $stmt = $conn->prepare("CALL SumarCantidadCarrito(?,?)");
            $stmt->bind_param("ii", $idCarrito, $_POST['idProducto']);
            $stmt->execute();
            $stmt->close();
            limpiarResultados($conn);
        }

        if (isset($_POST['restar'])) {
            limpiarResultados($conn);
            $stmt = $conn->prepare("CALL RestarCantidadCarrito(?,?)");
            $stmt->bind_param("ii", $idCarrito, $_POST['idProducto']);
            $stmt->execute();
            $stmt->close();
            limpiarResultados($conn);
        }

        if (isset($_POST['actualizar'])) {
            limpiarResultados($conn);
            $stmt = $conn->prepare("CALL ActualizarCantidadCarrito(?,?,?)");
            $stmt->bind_param("iii", $idCarrito, $_POST['idProducto'], $_POST['cantidad']);
            $stmt->execute();
            $stmt->close();
            limpiarResultados($conn);
        }

        if (isset($_POST['pedido'])) {
            if (empty($idCliente)) throw new Exception("Debes seleccionar un cliente antes de generar el pedido.");
            if (empty($carrito)) throw new Exception("El carrito está vacío.");

            $productos = [];
            foreach ($carrito as $c) {
                $productos[] = [
                    "idProducto"     => (int)$c['idProducto'],
                    "Cantidad"       => (int)$c['Cantidad'],
                    "PrecioProducto" => (float)$c['Precio']
                ];
            }
            $json = json_encode($productos, JSON_UNESCAPED_UNICODE);

            limpiarResultados($conn);
            $stmt = $conn->prepare("CALL AgregarPedido(?,?,?)");
            $stmt->bind_param("iis", $idCliente, $idUsuario, $json);
            $stmt->execute();
            $stmt->close();
            limpiarResultados($conn);

            unset($_SESSION['cliente_seleccionado'], $_SESSION['cliente_nombre']);
            header("Location: CarritoProveedores.php?pedido_ok=1");
            exit();
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

/* ================= TOTALES ================= */
$subtotal = array_sum(array_column($carrito, 'Total'));
