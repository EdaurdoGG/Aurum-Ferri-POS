<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/NotificacionesModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];


/* ================= MODEL ================= */
$model = new NotificacionesModel($conn);

/* ================= ACCIÓN: MARCAR LEÍDA ================= */
if (isset($_GET['leer'])) {
    $id = (int)$_GET['leer'];
    $model->marcarComoLeida($id);
    header("Location: Notificaciones.php");
    exit();
}

/* ================= DATOS ================= */
$resultado = $model->obtenerNotificaciones();

/* ================= VIEW ================= */
require_once __DIR__ . '/../views/NotificacionesView.php';
