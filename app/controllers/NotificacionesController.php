<?php
session_start();
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';

/* ================= USUARIO ================= */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= MARCAR COMO LEÍDA ================= */
if (isset($_GET['leer'])) {
    $id = intval($_GET['leer']);
    $conn->query("UPDATE Notificaciones SET Leida = 1 WHERE idNotificacion = $id");
    header("Location: Notificaciones.php");
    exit();
}

/* ================= CONSULTA ================= */
$sql = "SELECT * FROM VistaNotificaciones ORDER BY Fecha DESC";
$resultado = $conn->query($sql);

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/NotificacionesView.php';
?>