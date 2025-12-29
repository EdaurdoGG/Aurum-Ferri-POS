<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/proveedores.php';

/* USUARIO */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* VARIABLE TRIGGERS */
$conn->query("SET @usuario_actual = $idUsuario;");

/* DATOS */
$notificacionesNoLeidas = contarNotificacionesNoLeidas($conn);
$resultado = obtenerProveedores($conn);
?>