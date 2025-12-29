<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

/* ================= USUARIO ================= */
$idUsuario        = $_SESSION['id'];
$nombreUsuario    = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario      = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

/* ================= FILTROS ================= */
$fecha  = $_GET['fecha']  ?? '';
$modulo = $_GET['modulo'] ?? '';

/* ================= MODEL ================= */
$model = new AuditoriaModel($conn);

/* ================= DATOS ================= */
$resultado = $model->obtenerAuditoria($fecha, $modulo);
$notificacionesNoLeidas = $model->contarNotificacionesNoLeidas();

/* ================= DESCARGA CSV ================= */
if (isset($_GET['descargar']) && $_GET['descargar'] === '1') {

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=auditoria.csv');

    $out = fopen('php://output', 'w');
    fputcsv($out, [
        'Acción','Tabla','Columna','Dato Anterior',
        'Dato Nuevo','ID','Usuario','Fecha','Hora'
    ]);

    while ($f = $resultado->fetch_assoc()) {
        fputcsv($out, [
            $f['Accion'],
            $f['Tabla'],
            $f['ColumnaAfectada'] ?? '',
            $f['DatoAnterior'] ?? '',
            $f['DatoNuevo'] ?? '',
            $f['idHistorial'],
            $f['NombreCompletoUsuario'] ?? $f['NombreUsuario'],
            date('Y-m-d', strtotime($f['Fecha'])),
            date('H:i:s', strtotime($f['Fecha']))
        ]);
    }
    exit;
}

/* ================= VISTA ================= */
require __DIR__ . '/../views/AuditoriaView.php';
