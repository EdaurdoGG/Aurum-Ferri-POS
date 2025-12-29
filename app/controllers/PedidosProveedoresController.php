<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

// ================= VERIFICAR SESIÓN =================
requireRole(3); // Solo proveedores

/* ================= USUARIO ================= */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Proveedor';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Proveedor';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= VARIABLE PARA TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

/* ================= CONTADOR CARRITO ================= */
$resCount = $conn->query("
    SELECT COUNT(DISTINCT dc.idProducto) total
    FROM DetalleCarrito dc
    INNER JOIN Carrito c ON dc.idCarrito = c.idCarrito
    WHERE c.idUsuario = $idUsuario
");
$contador = (int)$resCount->fetch_assoc()['total'];

/* ================= FILTROS ================= */
$fecha   = $_GET['fecha']   ?? '';
$estatus = $_GET['estatus'] ?? '';
$cliente = $_GET['cliente'] ?? '';

/* ================= CONSULTA ================= */
$sql = "SELECT * FROM VistaPedidos WHERE idUsuario = ?";
$params = [$idUsuario];
$tipos  = "i";

if ($fecha !== '') {
    $sql .= " AND DATE(Fecha) = ?";
    $params[] = $fecha;
    $tipos .= "s";
}
if ($estatus !== '') {
    $sql .= " AND Estado = ?";
    $params[] = $estatus;
    $tipos .= "s";
}
if ($cliente !== '') {
    $sql .= " AND Cliente = ?";
    $params[] = $cliente;
    $tipos .= "s";
}

$sql .= " ORDER BY Fecha DESC, idPedido DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) die("Error en preparación: " . $conn->error);

$stmt->bind_param($tipos, ...$params);
$stmt->execute();
$result = $stmt->get_result();
if (!$result) die("Error en ejecución: " . $stmt->error);

/* ================= AGRUPAR PEDIDOS ================= */
$pedidos = [];
$clientes = [];

while ($row = $result->fetch_assoc()) {
    $clientes[$row['Cliente']] = $row['Cliente'];
    $id = $row['idPedido'];

    if (!isset($pedidos[$id])) {
        $pedidos[$id] = [
            'idPedido' => $id,
            'Fecha' => $row['Fecha'],
            'Hora' => $row['Hora'],
            'Estado' => $row['Estado'],
            'Cliente' => $row['Cliente'],
            'productos' => []
        ];
    }

    $pedidos[$id]['productos'][] = [
        'Producto' => $row['Producto'],
        'Cantidad' => $row['Cantidad'],
        'Precio' => $row['PrecioUnitario'],
        'Subtotal' => $row['Subtotal']
    ];
}

$sinResultados = empty($pedidos);

/* ================= ACCIONES POST ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    header('Content-Type: application/json');
    try {
        if ($_POST['accion'] === 'cancelar') {
            $idPedido = intval($_POST['idPedido']);
            $stmt = $conn->prepare("CALL CancelarPedido(?, ?)");
            $stmt->bind_param("ii", $idPedido, $idUsuario);
            $stmt->execute();
            echo json_encode(['ok'=>true,'mensaje'=>'Pedido cancelado']);
            exit;
        }
    } catch (Exception $e) {
        echo json_encode(['ok'=>false,'mensaje'=>$e->getMessage()]);
        exit;
    }
}

?>