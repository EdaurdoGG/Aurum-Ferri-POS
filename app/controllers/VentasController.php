<?php
session_start();

/* ================= SEGURIDAD ================= */
require_once __DIR__ . '/../config/session.php';
requireRole(1);

/* ================= CONEXIÓN ================= */
require_once __DIR__ . '/../config/database.php';

/* ================= MODEL ================= */
require_once __DIR__ . '/../models/VentasModel.php';

/* ================= USUARIO ================= */
$idUsuario        = $_SESSION['id'];
$nombreUsuario    = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario      = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= TRIGGERS ================= */
$conn->query("SET @usuario_actual = {$idUsuario}");

$model = new VentasModel($conn);

/* ================= NOTIFICACIONES ================= */
$notificacionesNoLeidas = $model->contarNotificacionesNoLeidas();

/* ================= FILTROS ================= */
$fechaSeleccionada = date('Y-m-d');
$idEmpleadoFiltro  = null;
$filtroAplicado    = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filtroAplicado = true;

    if (!empty($_POST['fecha'])) {
        $fechaSeleccionada = $_POST['fecha'];
    }

    if (!empty($_POST['empleado']) && $_POST['empleado'] !== 'all') {
        $idEmpleadoFiltro = (int)$_POST['empleado'];
    }
}

/* ================= DATOS ================= */
$empleados = $model->obtenerEmpleados();
$ventas    = $model->obtenerVentas($fechaSeleccionada, $idEmpleadoFiltro);

/* ================= TOTALES ================= */
$totalVentas     = 0;
$totalGanancias  = 0;
$totalGastos     = 0;

foreach ($ventas as $v) {
    $totalVentas    += $v['TotalVenta'];
    $totalGanancias += $v['Ganancia'];
    $totalGastos    += $v['TotalInvertido'];
}

$sinResultados = count($ventas) === 0;

/* ================= VIEW ================= */
require_once __DIR__ . '/../views/VentasView.php';
