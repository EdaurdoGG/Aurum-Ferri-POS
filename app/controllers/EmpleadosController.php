<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/EmpleadoModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

// ================= MODEL =================
$model = new EmpleadoModel($conn);

// ================= DATOS =================
$notificacionesNoLeidas = $model->obtenerNotificacionesNoLeidas();
$empleados = $model->obtenerEmpleados();

// ================= RUTAS IMÁGENES =================
$rutaServidor = __DIR__ . '/../../public/Imagenes/Usuarios/';
$rutaWeb = 'Imagenes/Usuarios/';
if (!is_dir($rutaServidor)) mkdir($rutaServidor, 0755, true);

// ================= VIEW =================
require_once __DIR__ . '/../views/EmpleadosView.php';
