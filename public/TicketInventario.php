<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;

// ================== VERIFICAR SESIÓN ==================
if (!isset($_SESSION['id'])) {
    die("No hay empleado en sesión. Por favor, inicia sesión.");
}
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

// ================== CONEXIÓN ==================
$conn = new mysqli("db", "root", "clave", "HerreriaUG");
if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);

// ================== FILTROS ==================
$filtroCategoria = $_GET['categoria'] ?? '';
$filtroProveedor = $_GET['proveedor'] ?? '';
$filtroEstatus = $_GET['estatus'] ?? '';

// ================== CONSULTA ==================
$sql = "SELECT * FROM VistaProductos WHERE 1=1";
$params = [];
$tipos = "";

if (!empty($filtroCategoria) && $filtroCategoria !== 'all') {
    $sql .= " AND Categoria = ?";
    $params[] = $filtroCategoria;
    $tipos .= "s";
}
if (!empty($filtroProveedor) && $filtroProveedor !== 'all') {
    $sql .= " AND Proveedor = ?";
    $params[] = $filtroProveedor;
    $tipos .= "s";
}
if (!empty($filtroEstatus) && $filtroEstatus !== 'all') {
    if ($filtroEstatus === 'Activo') {
        $sql .= " AND Stock > 0";
    } elseif ($filtroEstatus === 'Inactivo') {
        $sql .= " AND Stock = 0";
    }
}

$sql .= " ORDER BY Proveedor, Producto";

$stmt = $conn->prepare($sql);
if (!$stmt) die("Error en la consulta: " . $conn->error);
if (!empty($params)) $stmt->bind_param($tipos, ...$params);

$stmt->execute();
$result = $stmt->get_result();

// ================== GENERAR PDF ==================
$iva = 0.16; // IVA fijo del 16%
$html = '<h2 style="text-align:center;">Estado de Inventario</h2>';
$html .= '<style>
    table { border-collapse: collapse; width: 100%; font-size: 11px; }
    th, td { border: 1px solid #ddd; padding: 5px; vertical-align: top; }
    th { background-color: #f2f2f2; font-weight: bold; }
    td { word-break: break-word; text-align: right; }
    td.left { text-align: left; }
</style>';

$html .= '<table>
<tr>
    <th>Producto</th>
    <th>Categoría</th>
    <th>Proveedor</th>
    <th>Stock</th>
    <th>Compra</th>
    <th>Venta</th>
</tr>';

$totalStock = 0;
$totalCompra = 0;
$totalVenta = 0;

if ($result && $result->num_rows > 0) {
    while ($p = $result->fetch_assoc()) {
        $producto = htmlspecialchars($p['Producto']);
        $categoria = htmlspecialchars($p['Categoria']);
        $proveedor = htmlspecialchars($p['Proveedor']);
        $stock = (int)$p['Stock'];
        $compra = (float)$p['PrecioCompra'];
        $venta = (float)$p['PrecioVenta'];

        $totalStock += $stock;
        $totalCompra += $compra * $stock;
        $totalVenta += $venta * $stock;

        $html .= "<tr>
            <td class='left'>$producto</td>
            <td class='left'>$categoria</td>
            <td class='left'>$proveedor</td>
            <td>$stock</td>
            <td>$" . number_format($compra, 2) . "</td>
            <td>$" . number_format($venta, 2) . "</td>
        </tr>";
    }
} else {
    $html .= '<tr><td colspan="7" style="text-align:center;">No hay productos para mostrar.</td></tr>';
}

$html .= '</table>';

// ================== DESCARGAR PDF ==================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("inventario.pdf", ["Attachment" => false]);

$conn->close();
?>
