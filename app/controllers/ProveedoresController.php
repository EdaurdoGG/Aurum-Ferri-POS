<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ProveedoresModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

$model = new ProveedoresModel($conn);

/* ================= DATOS ================= */
$notificacionesNoLeidas = $model->contarNotificacionesNoLeidas();
$resultado              = $model->obtenerProveedores();

/* ================= VIEW ================= */
require_once __DIR__ . '/../views/ProveedoresView.php';
