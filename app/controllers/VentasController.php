<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/VentasModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

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
