<?php
session_start();
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/NotificacionesModel.php';

/* ================= USUARIO ================= */
$idUsuario        = $_SESSION['id'];
$nombreUsuario    = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario      = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

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
