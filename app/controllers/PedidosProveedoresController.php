<?php
require_once __DIR__ . '/../config/session.php';
requireRole(3); // Solo proveedores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PedidosProveedoresModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Proveedor');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

$model = new PedidosProveedoresModel($conn);

/* ================= CONTADOR CARRITO ================= */
$contador = $model->contarCarrito($idUsuario);

/* ================= FILTROS ================= */
$fecha   = $_GET['fecha']   ?? '';
$estatus = $_GET['estatus'] ?? '';
$cliente = $_GET['cliente'] ?? '';

/* ================= ACCIONES AJAX ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {

    header('Content-Type: application/json');

    try {
        $idPedido = (int)($_POST['idPedido'] ?? 0);

        if ($idPedido <= 0) {
            throw new Exception('Pedido inválido');
        }

        if ($_POST['accion'] === 'cancelar') {
            $model->cancelarPedido($idPedido, $idUsuario);
            echo json_encode(['ok'=>true,'mensaje'=>'Pedido cancelado']);
        } else {
            throw new Exception('Acción no permitida');
        }

    } catch (Throwable $e) {
        echo json_encode(['ok'=>false,'mensaje'=>$e->getMessage()]);
    }

    exit;
}

/* ================= DATOS ================= */
$data     = $model->obtenerPedidos($idUsuario, $fecha, $estatus, $cliente);
$pedidos  = $data['pedidos'];
$clientes = $data['clientes'];

$sinResultados = empty($pedidos);

/* ================= VIEW ================= */
require_once __DIR__ . '/../views/PedidosProveedoresView.php';
