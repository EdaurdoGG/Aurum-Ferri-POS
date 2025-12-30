<?php
require_once __DIR__ . '/../config/session.php';
requireRole(2); // solo cajero
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PedidosCajerosModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Cajero');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

$model = new PedidosCajerosModel($conn);

/* ================= FILTROS ================= */
$fecha   = $_GET['fecha']   ?? '';
$estatus = $_GET['estatus'] ?? '';
$cliente = $_GET['cliente'] ?? '';

/* ================= ACCIONES AJAX ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {

    header('Content-Type: application/json');

    try {
        $idPedido = (int)($_POST['idPedido'] ?? 0);
        $accion   = $_POST['accion'];

        if ($idPedido <= 0) {
            throw new Exception('Pedido inválido');
        }

        if ($accion === 'cancelar') {
            $model->cancelarPedido($idPedido, $idUsuario);

        } elseif ($accion === 'cobrar') {
            $model->cobrarPedido($idPedido);

        } else {
            throw new Exception('Acción no permitida');
        }

        echo json_encode(['ok'=>true,'mensaje'=>'Acción realizada correctamente']);

    } catch (Throwable $e) {
        echo json_encode(['ok'=>false,'mensaje'=>$e->getMessage()]);
    }

    exit;
}

/* ================= DATOS ================= */
$data      = $model->obtenerPedidos($fecha, $estatus, $cliente);
$pedidos   = $data['pedidos'];
$clientes  = $data['clientes'];

$sinResultados = empty($pedidos);

/* ================= VIEW ================= */
require_once __DIR__ . '/../views/PedidosCajerosView.php';
