<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/AbonoCreditoModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];


/* ================= MODEL ================= */
$model = new AbonoCreditoModel($conn);

/* ================= MENSAJES ================= */
$mensaje = $tipoMensaje = null;

/* ================= PROCESAR ABONO ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['abonar'])) {

    $idCliente = (int) $_POST['idCliente'];
    $monto     = (float) $_POST['monto'];

    if ($monto <= 0) {
        $mensaje = "El monto debe ser mayor a 0.";
        $tipoMensaje = "error";
    } elseif ($model->registrarAbono($idUsuario, $idCliente, $monto)) {
        $mensaje = "Abono registrado correctamente.";
        $tipoMensaje = "success";
    } else {
        $mensaje = "Error al registrar el abono.";
        $tipoMensaje = "error";
    }
}

/* ================= DATOS ================= */
$clientes = $model->obtenerClientesConCredito();

/* ================= CARGAR VISTA ================= */
require __DIR__ . '/../views/AbonoCreditoView.php';
