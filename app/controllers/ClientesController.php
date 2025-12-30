<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ClientesModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

/* ================= MODEL ================= */
$model = new ClientesModel($conn);

/* ================= DATOS ================= */
$notificacionesNoLeidas = $model->obtenerNotificacionesNoLeidas();
$clientes               = $model->obtenerClientes();

/* ================= RUTAS DE IMÁGENES ================= */
$rutaServidor = __DIR__ . '/../../public/Imagenes/Clientes/';
$rutaWeb      = 'Imagenes/Clientes/';
if (!is_dir($rutaServidor)) {
    mkdir($rutaServidor, 0755, true);
}

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/ClientesView.php';
