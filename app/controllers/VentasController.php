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
$consultaNoti = $conn->query("
    SELECT COUNT(*) AS total
    FROM Notificaciones
    WHERE Leida = 0
");
$notificacionesNoLeidas = $consultaNoti->fetch_assoc()['total'] ?? 0;

/* ================= FILTROS ================= */
$fechaSeleccionada = date('Y-m-d');
$idEmpleadoFiltro = null;
$filtroAplicado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filtroAplicado = true;

    if (isset($_POST['fecha'])) {
        $fechaSeleccionada = $_POST['fecha'];
    }

    if (isset($_POST['empleado']) && $_POST['empleado'] !== 'all') {
        $idEmpleadoFiltro = $_POST['empleado'];
    }
}

/* ================= EMPLEADOS ================= */
$empleados = [];
$sqlEmp = "
    SELECT idUsuario, CONCAT(Nombre,' ',Paterno,' ',Materno) AS Empleado
    FROM VistaUsuarios
    WHERE Rol <> 3
    ORDER BY Empleado
";
$resEmp = $conn->query($sqlEmp);
while ($row = $resEmp->fetch_assoc()) {
    $empleados[$row['idUsuario']] = $row['Empleado'];
}

/* ================= CONSULTA VENTAS ================= */
$sql = "SELECT * FROM VistaVentas WHERE DATE(Fecha) = ?";
$params = [$fechaSeleccionada];
$tipos = "s";

if ($idEmpleadoFiltro) {
    $sql .= " AND idUsuario = ?";
    $params[] = $idEmpleadoFiltro;
    $tipos .= "i";
}

$sql .= " ORDER BY Empleado ASC, Fecha ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($tipos, ...$params);
$stmt->execute();
$resultado = $stmt->get_result();

$ventas = [];
$totalVentas = 0;
$totalGanancias = 0;
$totalGastos = 0;

while ($fila = $resultado->fetch_assoc()) {
    $ventas[] = $fila;
    $totalVentas += $fila['TotalVenta'];
    $totalGanancias += $fila['Ganancia'];
    $totalGastos += $fila['TotalInvertido'];
}

$sinResultados = count($ventas) === 0;

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/VentasView.php';
?>