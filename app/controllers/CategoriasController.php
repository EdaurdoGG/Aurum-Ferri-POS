<?php
session_start();

require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CategoriasModel.php';

/* ================= USUARIO ================= */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

/* ================= MODEL ================= */
$model = new CategoriasModel($conn);

/* ================= DATOS ================= */
$categorias = $model->obtenerCategorias();
$notificacionesNoLeidas = $model->contarNotificacionesNoLeidas();

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/CategoriasView.php';
