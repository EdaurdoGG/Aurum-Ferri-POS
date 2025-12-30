<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PedidosAdministradorModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];


/* ================= INSTANCIA MODEL ================= */
$model = new PedidoModel($conn);

/* ================= NOTIFICACIONES NO LEÍDAS ================= */
$notificacionesNoLeidas = $model->contarNotificaciones();

/* ================= FILTROS ================= */
$fecha   = $_GET['fecha']   ?? '';
$estatus = $_GET['estatus'] ?? '';
$cliente = $_GET['cliente'] ?? '';

/* ================= DATOS DE PEDIDOS ================= */
$data = $model->obtenerPedidos($fecha, $estatus, $cliente);

$pedidos       = $data['pedidos'];
$clientes      = $data['clientes'];
$sinResultados = empty($pedidos);

/* ================= PETICIONES AJAX ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {

    header('Content-Type: application/json');

    try {
        $idPedido = (int)($_POST['idPedido'] ?? 0);
        $accion   = $_POST['accion'];

        if ($idPedido <= 0) {
            throw new Exception('Pedido inválido');
        }

        if ($accion === 'preparar') {

            $model->prepararPedido($idPedido, $idUsuario);

        } elseif ($accion === 'cancelar') {

            $model->cancelarPedido($idPedido, $idUsuario);

        } elseif ($accion === 'cobrar') {

            $model->cobrarPedido($idPedido);

        } else {
            throw new Exception('Acción no permitida');
        }

        echo json_encode([
            'ok'      => true,
            'mensaje' => 'Acción realizada correctamente'
        ]);

    } catch (Throwable $e) {

        echo json_encode([
            'ok'      => false,
            'mensaje' => $e->getMessage()
        ]);
    }

    exit;
}

/* ================= CARGAR VISTA ================= */
require_once __DIR__ . '/../views/PedidosAdministradorView.php';
