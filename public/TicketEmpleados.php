<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
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

/* ================== FILTRO ================== */
$filtro = $_GET['estatus'] ?? ''; // 'Activo', 'Inactivo' o vacío

/* ================== CONSULTA ================== */
$sql = "SELECT * FROM VistaUsuarios WHERE 1=1";
$params = [];
$tipos = "";

if (!empty($filtro) && $filtro !== 'all') {
    $sql .= " AND Estatus = ?";
    $params[] = $filtro;
    $tipos .= "s";
}

$sql .= " ORDER BY Nombre, Paterno, Materno";

$stmt = $conn->prepare($sql);
if ($stmt === false) die("Error en la consulta: " . $conn->error);

if (!empty($params)) {
    $stmt->bind_param($tipos, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

/* ================== GENERAR PDF ================== */
$html = '<h2 style="text-align:center;">Listado de Empleados</h2>';
$html .= '<style>
    table { font-size: 10px; border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 4px; vertical-align: top; }
    th { background-color: #f2f2f2; font-weight: bold; }
    td { word-break: break-word; }
</style>';

$html .= '<table>
<tr>
    <th>Nombre</th>
    <th>Teléfono</th>
    <th>Email</th>
    <th>Rol</th>
    <th>Usuario</th>
    <th>Estatus</th>
</tr>';

if ($result && $result->num_rows > 0) {
    while ($e = $result->fetch_assoc()) {
        $nombreCompleto = htmlspecialchars($e['Nombre'].' '.$e['Paterno'].' '.$e['Materno']);
        $telefono = htmlspecialchars($e['Telefono'] ?? '-');
        $email = htmlspecialchars($e['Email'] ?? '-');
        $rol = htmlspecialchars($e['Rol'] ?? '-');
        $usuario = htmlspecialchars($e['Usuario'] ?? '-');
        $estatus = htmlspecialchars($e['Estatus'] ?? '-');

        $html .= "<tr>
            <td>$nombreCompleto</td>
            <td>$telefono</td>
            <td>$email</td>
            <td>$rol</td>
            <td>$usuario</td>
            <td>$estatus</td>
        </tr>";
    }
} else {
    $html .= '<tr><td colspan="6" style="text-align:center;">No hay empleados.</td></tr>';
}

$html .= '</table>';

/* ================== DESCARGAR PDF ================== */
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // horizontal
$dompdf->render();
$dompdf->stream("empleados.pdf", ["Attachment" => false]);

$conn->close();
?>
