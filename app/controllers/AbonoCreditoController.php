<?php

require_once __DIR__ . '/../config/session.php';
requireRole(1); // Administrador

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/AbonoCreditoModel.php';

/* ================= USUARIO ================= */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= VARIABLE PARA TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

/* ================= MODEL ================= */
$model = new AbonoCreditoModel($conn);

/* ================= MENSAJES ================= */
$mensaje = $tipoMensaje = null;

/* ================= PROCESAR ABONO ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['abonar'])) {

    $idCliente = (int) $_POST['idCliente'];
    $monto     = (float) $_POST['monto'];

    if ($monto <= 0) {
        $mensaje = "El monto debe ser mayor a 0.";
        $tipoMensaje = "error";
    } elseif ($model->registrarAbono($idUsuario, $idCliente, $monto)) {
        $mensaje = "Abono registrado correctamente.";
        $tipoMensaje = "success";
    } else {
        $mensaje = "Error al registrar el abono.";
        $tipoMensaje = "error";
    }
}

/* ================= DATOS ================= */
$clientes = $model->obtenerClientesConCredito();

/* ================= CARGAR VISTA ================= */
require __DIR__ . '/../views/AbonoCreditoView.php';
