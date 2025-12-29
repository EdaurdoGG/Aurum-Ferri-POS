<?php
session_start();
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';

/* ================= USUARIO ================= */
$idUsuario = $_SESSION['id']; 
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= VARIABLE PARA TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

/* ================= NOTIFICACIONES NO LEÍDAS ================= */
$consultaNoti = $conn->query("SELECT COUNT(*) AS total FROM Notificaciones WHERE Leida = 0");
$notificacionesNoLeidas = $consultaNoti->fetch_assoc()['total'] ?? 0;

/* ================= FILTROS ================= */
$fecha   = $_GET['fecha']   ?? '';
$estatus = $_GET['estatus'] ?? '';
$cliente = $_GET['cliente'] ?? '';

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

/* ================= ACCIONES POST ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    header('Content-Type: application/json');
    try {
        $idPedido = intval($_POST['idPedido']);

        if ($_POST['accion'] === 'preparar') {
            $stmt = $conn->prepare("CALL PrepararPedido(?, ?)");
            $stmt->bind_param("ii", $idPedido, $idUsuario);
            $stmt->execute();
            echo json_encode(['ok'=>true,'mensaje'=>'Pedido marcado como PARCIAL']);
            exit;
        }

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

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/PedidosAdministradorView.php';
