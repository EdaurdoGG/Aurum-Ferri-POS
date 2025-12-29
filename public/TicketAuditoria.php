<?php
session_start();
require 'vendor/autoload.php';
use Dompdf\Dompdf;

/* ================== CONEXIÓN ================== */
$host = "db";
$usuario = "root";
$clave = "clave";
$bd = "HerreriaUG";

$conn = new mysqli($host, $usuario, $clave, $bd);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

/* ================== DATOS DEL USUARIO ================== */
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================== FILTROS ================== */
$fecha  = $_GET['fecha'] ?? '';
$modulo = $_GET['modulo'] ?? '';

/* ================== CONSULTA ================== */
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

$sql .= " ORDER BY Fecha DESC, idHistorial DESC";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error en la consulta: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($tipos, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

/* ================== GENERAR PDF ================== */
$html = '<h2 style="text-align:center;">Historial de Auditoría</h2>';
$html .= '<style>
    table { font-size: 9px; border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 4px; vertical-align: top; }
    th { background-color: #f2f2f2; font-weight: bold; }
    td { word-break: break-word; }
</style>';

$html .= '<table>
<tr>
    <th>Acción</th>
    <th>Tabla</th>
    <th>ID Registro</th>
    <th>Usuario</th>
    <th>Fecha</th>
    <th>Hora</th>
</tr>';

/* ================== CUERPO ================== */
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fechaHora = strtotime($row['Fecha']);
        $html .= '<tr>
            <td>' . htmlspecialchars($row['Accion']) . '</td>
            <td>' . htmlspecialchars($row['Tabla']) . '</td>
            <td>' . $row['idRegistro'] . '</td>
            <td>' . htmlspecialchars($row['NombreCompletoUsuario']) . '</td>
            <td>' . date('Y-m-d', $fechaHora) . '</td>
            <td>' . date('H:i:s', $fechaHora) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="6" style="text-align:center;">No hay registros.</td></tr>';
}

$html .= '</table>';

/* ================== DESCARGAR PDF ================== */
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("auditoria.pdf", ["Attachment" => false]);

$conn->close();
?>
