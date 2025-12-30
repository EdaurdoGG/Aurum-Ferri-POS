<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/AlmacenModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

/* ================= MODEL ================= */
$model = new AlmacenModel($conn);

/* ================= DATOS ================= */
$inventario = $model->obtenerInventario();

$productos   = $inventario['productos'];
$categorias  = $inventario['categorias'];
$proveedores = $inventario['proveedores'];

$notificacionesNoLeidas = $model->contarNotificacionesNoLeidas();

/* ================= RUTAS ================= */
$rutaServidor = $_SERVER['DOCUMENT_ROOT'] . "/Herreria/public/Imagenes/Productos/";
$rutaWeb      = "Imagenes/Productos/";

if (!is_dir($rutaServidor)) {
    mkdir($rutaServidor, 0755, true);
}

$conn->close();

/* ================= VISTA ================= */
require __DIR__ . '/../views/AlmacenView.php';
