<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ClientesModel.php';

/* ================= USUARIO ================= */
$idUsuario        = $_SESSION['id'];
$nombreUsuario    = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario      = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

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
