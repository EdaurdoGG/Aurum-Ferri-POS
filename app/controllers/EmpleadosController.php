<?php
// ================= ERRORES =================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ================= SESIÓN =================
require_once __DIR__ . '/../config/session.php';
requireRole(1);

// ================= BASE DE DATOS =================
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/empleados.php';

// ================= USUARIO =================
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

// ================= TRIGGERS =================
$conn->query("SET @usuario_actual = $idUsuario;");

// ================= NOTIFICACIONES =================
$notificacionesNoLeidas = obtenerNotificacionesNoLeidas($conn);

// ================= EMPLEADOS =================
$empleados = obtenerEmpleados($conn);

// ================= RUTAS DE IMAGENES =================
$rutaServidor = __DIR__ . '/../../public/Imagenes/Usuarios/';
$rutaWeb = 'Imagenes/Usuarios/';
if (!is_dir($rutaServidor)) mkdir($rutaServidor, 0755, true);

?>