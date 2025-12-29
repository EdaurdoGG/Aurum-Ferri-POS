<?php
session_start();

/* ================= SEGURIDAD ================= */
require_once __DIR__ . '/../config/session.php';
requireRole(1);

/* ================= CONEXIÓN ================= */
require_once __DIR__ . '/../config/database.php';

/* ================= MODEL ================= */
require_once __DIR__ . '/../models/ProveedoresModel.php';

/* ================= USUARIO ================= */
$idUsuario        = $_SESSION['id'];
$nombreUsuario    = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario      = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= VARIABLE TRIGGERS ================= */
$conn->query("SET @usuario_actual = {$idUsuario}");

$model = new ProveedoresModel($conn);

/* ================= DATOS ================= */
$notificacionesNoLeidas = $model->contarNotificacionesNoLeidas();
$resultado              = $model->obtenerProveedores();

/* ================= VIEW ================= */
require_once __DIR__ . '/../views/ProveedoresView.php';
