<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CategoriasModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

/* ================= MODEL ================= */
$model = new CategoriasModel($conn);

/* ================= DATOS ================= */
$categorias = $model->obtenerCategorias();
$notificacionesNoLeidas = $model->contarNotificacionesNoLeidas();

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/CategoriasView.php';
