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

/* ================= VARIABLE PARA TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

/* ================= NOTIFICACIONES NO LEÍDAS ================= */
$consultaNoti = $conn->query("SELECT COUNT(*) AS total FROM Notificaciones WHERE Leida = 0");
$notificacionesNoLeidas = $consultaNoti->fetch_assoc()['total'] ?? 0;

/* ================= FILTROS ================= */
$fecha  = $_GET['fecha']  ?? '';
$modulo = $_GET['modulo'] ?? '';

/* ================= CONSULTA ================= */
$sql = "SELECT * FROM VistaAuditoria WHERE 1=1";
$params = [];
$tipos = "";

if (!empty($fecha)) {
    $sql .= " AND DATE(Fecha) = ?";
    $params[] = $fecha;
    $tipos .= "s";
}

if (!empty($modulo)) {
    $sql .= " AND Tabla = ?";
    $params[] = $modulo;
    $tipos .= "s";
}

$sql .= " ORDER BY Fecha DESC";
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($tipos, ...$params);
}

$stmt->execute();
$resultado = $stmt->get_result();

/* ================= DESCARGA CSV ================= */
if (isset($_GET['descargar']) && $_GET['descargar'] === '1') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=auditoria.csv');

    $out = fopen('php://output', 'w');
    fputcsv($out, ['Acción','Tabla','Columna','Dato Anterior','Dato Nuevo','ID','Usuario','Fecha','Hora']);

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
require_once __DIR__ . '/../views/AuditoriaView.php';
