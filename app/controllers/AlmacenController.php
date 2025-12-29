<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // solo administrador

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/productos.php';

/* ================= USUARIO ================= */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

/* ================= NOTIFICACIONES ================= */
$consultaNoti = $conn->query("
    SELECT COUNT(*) AS total
    FROM Notificaciones
    WHERE Leida = 0
");
$notificacionesNoLeidas = $consultaNoti->fetch_assoc()['total'] ?? 0;

/* ================= RUTAS ================= */
$rutaServidor = __DIR__ . '/../../public/Imagenes/Productos/';
$rutaWeb = 'Imagenes/Productos/';
if (!is_dir($rutaServidor)) mkdir($rutaServidor, 0755, true);

$conn->close();

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/AlmacenView.php';

?>