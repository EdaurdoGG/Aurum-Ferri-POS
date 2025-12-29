<?php
session_start();
require_once __DIR__ . '/../config/session.php';
requireRole(2); // Solo cajeros

require_once __DIR__ . '/../config/database.php';

/* ================= USUARIO ================= */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Cajero';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Cajero';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= VARIABLE PARA TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

/* ================= FILTROS ================= */
$fecha   = $_GET['fecha']   ?? '';
$estatus = $_GET['estatus'] ?? '';
$cliente = $_GET['cliente'] ?? '';

/* ================= ACCIONES POST ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    header('Content-Type: application/json');
    try {
        $idPedido = intval($_POST['idPedido']);

        if ($_POST['accion'] === 'cancelar') {
            $stmt = $conn->prepare("CALL CancelarPedido(?, ?)");
            $stmt->bind_param("ii", $idPedido, $idUsuario);
            $stmt->execute();
            echo json_encode(['ok'=>true,'mensaje'=>'Pedido cancelado']);
            exit;
        }

        if ($_POST['accion'] === 'cobrar') {
            $stmt = $conn->prepare("CALL ProcesarPedidoComoVenta(?)");
            $stmt->bind_param("i", $idPedido);
            $stmt->execute();
            echo json_encode(['ok'=>true,'mensaje'=>'Pedido procesado como venta']);
            exit;
        }

    } catch (Exception $e) {
        echo json_encode(['ok'=>false,'mensaje'=>$e->getMessage()]);
        exit;
    }
}

/* ================= CONSULTA ================= */
$sql = "SELECT * FROM VistaPedidos WHERE 1=1";
$params = [];
$tipos  = "";

if (!empty($fecha)) {
    $sql .= " AND DATE(Fecha) = ?";
    $params[] = $fecha;
    $tipos .= "s";
}

if (!empty($estatus)) {
    $sql .= " AND Estado = ?";
    $params[] = $estatus;
    $tipos .= "s";
}

if (!empty($cliente)) {
    $sql .= " AND Cliente = ?";
    $params[] = $cliente;
    $tipos .= "s";
}

$sql .= " ORDER BY Fecha DESC, idPedido DESC";

$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($tipos, ...$params);
$stmt->execute();
$result = $stmt->get_result();

/* ================= AGRUPAR PEDIDOS ================= */
$pedidos = [];
$clientes = [];

while ($row = $result->fetch_assoc()) {
    $clientes[$row['Cliente']] = $row['Cliente'];
    $id = $row['idPedido'];

    if (!isset($pedidos[$id])) {
        $pedidos[$id] = [
            'idPedido' => $id,
            'Fecha'    => $row['Fecha'],
            'Hora'     => $row['Hora'],
            'Estado'   => $row['Estado'],
            'Cliente'  => $row['Cliente'],
            'productos'=> []
        ];
    }

    $pedidos[$id]['productos'][] = [
        'Producto' => $row['Producto'],
        'Cantidad' => $row['Cantidad'],
        'Precio'   => $row['PrecioUnitario'],
        'Subtotal' => $row['Subtotal']
    ];
}

$sinResultados = count($pedidos) === 0;

/* ================= PASAR DATOS A LA VISTA ================= */
$viewData = [
    'fotoUsuario' => $fotoUsuario,
    'nombreUsuario' => $nombreUsuario,
    'rolUsuarioNombre' => $rolUsuarioNombre,
    'pedidos' => $pedidos,
    'clientes' => $clientes,
    'sinResultados' => $sinResultados,
    'fecha' => $fecha,
    'estatus' => $estatus,
    'cliente' => $cliente
];

// Convertimos keys en variables para la vista
extract($viewData);

// Cargamos la vista
require __DIR__ . '/../views/PedidosCajerosView.php';
$conn->close();
?>
