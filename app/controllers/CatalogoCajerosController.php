<?php
session_start();
require_once __DIR__ . '/../config/session.php';
requireRole(2); // Solo cajeros

require_once __DIR__ . '/../config/database.php';

/* ================= USUARIO ================= */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Cajero';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Cajero';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= VARIABLE PARA TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

/* ================== RUTAS ================== */
$rutaServidor = __DIR__ . '/../../public/Imagenes/Productos/';
$rutaWeb = 'Imagenes/Productos/';
if (!is_dir($rutaServidor)) mkdir($rutaServidor, 0755, true);

/* ================== AGREGAR AL CARRITO ================== */
$mensaje_flotante = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProducto'], $_POST['cantidad'])) {
    $idProducto = (int)$_POST['idProducto'];
    $cantidad = (int)$_POST['cantidad'];

    // Obtener o crear carrito
    $idCarrito = 0;
    $resCarrito = $conn->query("SELECT idCarrito FROM Carrito WHERE idUsuario = $idUsuario ORDER BY Fecha DESC LIMIT 1");
    if ($resCarrito && $resCarrito->num_rows > 0) {
        $row = $resCarrito->fetch_assoc();
        $idCarrito = $row['idCarrito'];
    } else {
        $conn->query("INSERT INTO Carrito (idUsuario) VALUES ($idUsuario)");
        $idCarrito = $conn->insert_id;
    }

    // Llamar procedimiento
    $stmt = $conn->prepare("CALL AgregarAlCarrito(?, ?, ?)");
    $stmt->bind_param("iii", $idCarrito, $idProducto, $cantidad);
    if ($stmt->execute()) {
        $mensaje_flotante = "Producto agregado al carrito correctamente.";
    } else {
        $mensaje_flotante = "Error al agregar el producto: " . $stmt->error;
    }
    $stmt->close();
}

/* ================== OBTENER PRODUCTOS ================== */
$sql = "SELECT * FROM VistaProductos WHERE Producto <> 'AbonosCreditos'";
$resultado = $conn->query($sql);
if (!$resultado) die("Error en consulta SQL: " . $conn->error);

$productos = [];
$categorias = [];
while ($row = $resultado->fetch_assoc()) {
    $productos[] = $row;
    $categorias[$row['Categoria']] = true;
}

$conn->close();

/* ================= PASAR DATOS A LA VISTA ================= */
$viewData = [
    'fotoUsuario' => $fotoUsuario,
    'nombreUsuario' => $nombreUsuario,
    'rolUsuarioNombre' => $rolUsuarioNombre,
    'productos' => $productos,
    'categorias' => $categorias,
    'mensaje_flotante' => $mensaje_flotante,
    'rutaServidor' => $rutaServidor,
    'rutaWeb' => $rutaWeb
];

extract($viewData);
require __DIR__ . '/../views/CatalogoCajerosView.php';
?>
