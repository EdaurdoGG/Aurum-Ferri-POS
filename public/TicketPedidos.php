<?php
session_start();
require 'vendor/autoload.php';
use Dompdf\Dompdf;

// ================== VERIFICAR SESIÓN ==================
if (!isset($_SESSION['id'])) {
    die("No hay empleado en sesión. Por favor, inicia sesión.");
}
$nombreUsuario    = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario      = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

// ================== CONEXIÓN ==================
$conn = new mysqli("db", "root", "clave", "HerreriaUG");
if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);

// ================== FILTROS ==================
$fecha   = $_GET['fecha']   ?? '';
$estatus = $_GET['estatus'] ?? '';
$cliente = $_GET['cliente'] ?? '';

// ================== CONSULTA ==================
$sql = "SELECT * FROM VistaPedidos WHERE 1=1";
$params = [];
$tipos  = "";

if (!empty($fecha)) {
    $sql .= " AND DATE(Fecha) = ?";
    $params[] = $fecha;
    $tipos .= "s";
}

if (!empty($estatus) && $estatus !== 'all') {
    $sql .= " AND Estado = ?";
    $params[] = $estatus;
    $tipos .= "s";
}

if (!empty($cliente) && $cliente !== 'all') {
    $sql .= " AND Cliente = ?";
    $params[] = $cliente;
    $tipos .= "s";
}

$sql .= " ORDER BY Estado, idPedido, idDetallePedido";

$stmt = $conn->prepare($sql);
if (!$stmt) die("Error en la consulta: " . $conn->error);
if (!empty($params)) $stmt->bind_param($tipos, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// ================== GENERAR HTML ==================
$html = '<h2 style="text-align:center;">Reporte de Pedidos</h2>';
$html .= '<style>
    table { border-collapse: collapse; width: 100%; font-size: 11px; }
    th, td { border: 1px solid #ddd; padding: 5px; text-align: right; vertical-align: top; }
    th { background-color: #f2f2f2; font-weight: bold; }
    td.left { text-align: left; }
</style>';

$estatusActual = '';
$hayPedidos = false;

while ($fila = $result->fetch_assoc()) {
    $hayPedidos = true;

    if ($fila['Estado'] !== $estatusActual) {
        if ($estatusActual !== '') {
            $html .= '</table><br>';
        }

        $estatusActual = $fila['Estado'];
        $html .= '<h3>Pedidos ' . htmlspecialchars($estatusActual) . '</h3>';
        $html .= '<table>
        <tr>
            <th>ID Pedido</th>
            <th>Cliente</th>
            <th>Empleado</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
        </tr>';
    }

    $html .= '<tr>
        <td class="left">' . htmlspecialchars($fila['idPedido']) . '</td>
        <td class="left">' . htmlspecialchars($fila['Cliente']) . '</td>
        <td class="left">' . htmlspecialchars($fila['Empleado']) . '</td>
        <td class="left">' . htmlspecialchars($fila['Fecha']) . '</td>
        <td class="left">' . htmlspecialchars($fila['Hora']) . '</td>
        <td class="left">' . htmlspecialchars($fila['Producto']) . '</td>
        <td>' . htmlspecialchars($fila['Cantidad']) . '</td>
        <td>$' . htmlspecialchars(number_format($fila['PrecioUnitario'], 2)) . '</td>
        <td>$' . htmlspecialchars(number_format($fila['Subtotal'], 2)) . '</td>
    </tr>';
}

if ($estatusActual !== '') {
    $html .= '</table>';
}

if (!$hayPedidos) {
    $html .= '<p style="text-align:center;">No se encontraron pedidos con los filtros aplicados.</p>';
}

// ================== GENERAR PDF ==================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // Cambiar a 'portrait' si se desea
$dompdf->render();
$dompdf->stream("reporte_pedidos.pdf", ["Attachment" => false]);

$conn->close();
?>
