<?php

require_once __DIR__ . '/../config/session.php';
requireRole(2); // solo cajero

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/abonos.php';

/* USUARIO */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Cajero';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Cajero';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

$conn->query("SET @usuario_actual = $idUsuario;");

// ================= RUTAS DE IMAGENES =================
$rutaServidor = __DIR__ . '/../../public/Imagenes/Clientes/';
$rutaWeb = 'Imagenes/Clientes/';
if (!is_dir($rutaServidor)) mkdir($rutaServidor, 0755, true);

$mensaje = $tipoMensaje = null;

/* PROCESAR ABONO */
if (isset($_POST['abonar'])) {
    if (registrarAbono(
        $conn,
        $idUsuario,
        intval($_POST['idCliente']),
        floatval($_POST['monto'])
    )) {
        $mensaje = "Abono registrado correctamente.";
        $tipoMensaje = "success";
    } else {
        $mensaje = "Error al registrar el abono.";
        $tipoMensaje = "error";
    }
}

/* DATOS */
$clientes = obtenerClientesConCredito($conn);
