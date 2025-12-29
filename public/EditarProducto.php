<?php
session_start();

// ================= VERIFICAR SESIÓN =================
// Solo permitir administradores (idRol = 1)
if (!isset($_SESSION['id']) || !isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    session_unset();
    session_destroy();
    header("Location: Login.php");
    exit();
}

/* ================= CONEXIÓN ================= */
$conn = new mysqli("db", "root", "clave", "HerreriaUG");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

/* ================= USUARIO ================= */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= VARIABLE PARA TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

// ================== MENSAJES ==================
function setMensaje($texto, $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

// ================== RUTAS ==================
$rutaServidor = realpath(__DIR__ . "/public/Imagenes/Productos/") . DIRECTORY_SEPARATOR;
$rutaWeb = '/Herreria/public/Imagenes/Productos/'; // Ruta accesible desde el navegador

// ================== CONSULTAS ==================
$consultaCategorias = $conn->query("SELECT idCategoria, Nombre FROM Categorias");
$consultaProveedores = $conn->query("
    SELECT idProveedor, CONCAT(Nombre,' ',Paterno,' ',Materno) AS NombreCompleto
    FROM VistaProveedores
");

// ================== OBTENER PRODUCTO ==================
if (!isset($_GET['idProducto']) || !is_numeric($_GET['idProducto'])) {
    die("ID inválido.");
}
$idProducto = intval($_GET['idProducto']);

$stmt = $conn->prepare("SELECT * FROM Productos WHERE idProducto = ?");
$stmt->bind_param("i", $idProducto);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("Producto no encontrado.");
$producto = $result->fetch_assoc();
$stmt->close();

// ================== PROCESAR FORMULARIO ==================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['EditarProducto'])) {

    $nombre        = trim($_POST['Nombre']);
    $codigoBarras  = trim($_POST['CodigoBarras']);
    $precioCompra  = floatval($_POST['PrecioCompra']);
    $precioVenta   = floatval($_POST['PrecioVenta']);
    $stock         = intval($_POST['Stock']);
    $stockMinimo   = intval($_POST['StockMinimo']); // NUEVO
    $idCategoria   = intval($_POST['idCategoria']);
    $idProveedor   = intval($_POST['idProveedor']);

    // ================== IMAGEN ==================
    $imagen = $producto['Imagen'];
    if (!empty($_FILES['Imagen']['name'])) {
        $fileName = time() . "_" . basename($_FILES['Imagen']['name']);
        if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $rutaServidor . $fileName)) {
            $imagen = $rutaWeb . $fileName;
        } else {
            setMensaje("Error al subir la imagen.", "error");
        }
    }

    if (!isset($_SESSION['mensaje'])) {
        $stmt = $conn->prepare("CALL EditarProducto(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            setMensaje("Error al preparar: " . $conn->error, "error");
        } else {
            $stmt->bind_param(
                "issddiisii",
                $idProducto,
                $nombre,
                $codigoBarras,
                $precioCompra,
                $precioVenta,
                $stock,
                $stockMinimo,
                $imagen,
                $idCategoria,
                $idProveedor
            );

            if ($stmt->execute()) {
                setMensaje("Producto actualizado correctamente.", "success");
                header("Location: EditarProducto.php?idProducto=$idProducto");
                exit();
            } else {
                setMensaje("Error al actualizar: " . $stmt->error, "error");
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="assets/css/EditarProducto.css">
    <link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<main>
<section class="centro">
<div class="log">
<div class="login">

<?php if (!empty($_SESSION['mensaje'])): ?>
<div class="alert-message <?= $_SESSION['tipo_mensaje']=='error'?'alert-error':'alert-success' ?>">
    <?= htmlspecialchars($_SESSION['mensaje']) ?>
</div>
<?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="titulo">
    <h2>Editar Producto</h2>
    <a href="Almacen.php"><img src="assets/icons/Volver.png" class="boton-atras"></a>
</div>

<div class="input-group">
    <input type="text" name="Nombre" value="<?= htmlspecialchars($producto['Nombre']) ?>" required>
    <label>Nombre</label>
</div>

<div class="input-group">
    <input type="text" name="CodigoBarras" value="<?= htmlspecialchars($producto['CodigoBarras']) ?>" required>
    <label>Código de Barras</label>
</div>

<div class="input-group">
    <input type="number" step="0.01" name="PrecioCompra" value="<?= $producto['PrecioCompra'] ?>" required>
    <label>Precio Compra</label>
</div>

<div class="input-group">
    <input type="number" step="0.01" name="PrecioVenta" value="<?= $producto['PrecioVenta'] ?>" required>
    <label>Precio Venta</label>
</div>

<div class="input-group">
    <input type="number" name="Stock" value="<?= $producto['Stock'] ?>" required>
    <label>Stock</label>
</div>

<!-- NUEVO -->
<div class="input-group">
    <input type="number" name="StockMinimo" value="<?= $producto['StockMinimo'] ?>" required>
    <label>Stock Mínimo</label>
</div>

<div class="input-group">
    <input type="file" name="Imagen">
    <label>Imagen</label>
    <?php if (!empty($producto['Imagen'])): ?>
        <img src="<?= htmlspecialchars($producto['Imagen']) ?>" style="width:80px;margin-top:5px;">
    <?php endif; ?>
</div>

<div class="input-group">
<select name="idCategoria" required>
<?php $consultaCategorias->data_seek(0);
while ($cat = $consultaCategorias->fetch_assoc()): ?>
<option value="<?= $cat['idCategoria'] ?>" <?= $producto['idCategoria']==$cat['idCategoria']?'selected':'' ?>>
    <?= htmlspecialchars($cat['Nombre']) ?>
</option>
<?php endwhile; ?>
</select>
<label>Categoría</label>
</div>

<div class="input-group">
<select name="idProveedor" required>
<?php $consultaProveedores->data_seek(0);
while ($prov = $consultaProveedores->fetch_assoc()): ?>
<option value="<?= $prov['idProveedor'] ?>" <?= $producto['idProveedor']==$prov['idProveedor']?'selected':'' ?>>
    <?= htmlspecialchars($prov['NombreCompleto']) ?>
</option>
<?php endwhile; ?>
</select>
<label>Proveedor</label>
</div>

<button type="submit" name="EditarProducto" class="Acceder">
Actualizar Producto
</button>

</form>
</div>
</div>
</section>
</main>

<footer>
<p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

</body>
</html>
