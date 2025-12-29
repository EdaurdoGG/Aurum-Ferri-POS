<?php
require_once __DIR__ . '/../config/session.php';
requireRole(2); // solo cajero

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/AbonoCajerosModel.php';

/* USUARIO */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Cajero';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Cajero';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

$conn->query("SET @usuario_actual = $idUsuario;");

$model = new AbonoCajerosModel($conn);

$mensaje = $tipoMensaje = null;

/* ================= PROCESAR ABONO ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['abonar'])) {

    $idCliente = intval($_POST['idCliente']);
    $monto     = floatval($_POST['monto']);

    if ($model->registrarAbono($idUsuario, $idCliente, $monto)) {
        $mensaje = "Abono registrado correctamente.";
        $tipoMensaje = "success";
    } else {
        $mensaje = "Error al registrar el abono.";
        $tipoMensaje = "error";
    }
}

/* ================= DATOS ================= */

$resultado = $model->obtenerClientesConCredito();

/* 🔴 VALIDACIÓN CLAVE */
if ($resultado === false) {
    // En desarrollo muestra el error real
    die("Error al obtener clientes con crédito. Revisa la consulta SQL.");
}

/* Convertir a array */
$clientes = [];
while ($fila = $resultado->fetch_assoc()) {
    $clientes[] = $fila;
}

/* Procesar datos para la vista */
$rutaServidor = __DIR__ . '/../../public/Imagenes/Clientes/';
$rutaWeb = 'Imagenes/Clientes/';

foreach ($clientes as &$c) {

    $nombreImagen = $c['Imagen'] ?? '';

    if (!empty($nombreImagen) && file_exists($rutaServidor . basename($nombreImagen))) {
        $c['rutaImagen'] = $rutaWeb . basename($nombreImagen);
    } else {
        $c['rutaImagen'] = $rutaWeb . 'default.png';
    }

    $c['estatusClase'] = strtolower($c['Estatus']) === 'activo'
        ? 'estatus-activo'
        : 'estatus-inactivo';
}
unset($c);

/* ================= CARGAR VISTA ================= */
require __DIR__ . '/../views/AbonoCajerosView.php';
?>