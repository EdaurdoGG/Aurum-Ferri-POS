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

// ================== FUNCIONES DE MENSAJE ==================
function setMensaje($texto, $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

// Ruta para guardar imágenes
$rutaImagenes = __DIR__ . "/Imagenes/Productos/";
if (!is_dir($rutaImagenes)) {
    mkdir($rutaImagenes, 0755, true);
}

// Consultar categorías
$consultaCategorias = $conn->query("SELECT idCategoria, Nombre FROM Categorias");
if (!$consultaCategorias) die("Error al cargar categorías: " . $conn->error);

// Consultar proveedores
$consultaProveedores = $conn->query("
    SELECT idProveedor, CONCAT(Nombre, ' ', Paterno, ' ', Materno) AS NombreCompleto 
    FROM VistaProveedores
");
if (!$consultaProveedores) die("Error al cargar proveedores: " . $conn->error);

// ================== PROCESAR FORMULARIO ==================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['AgregarProducto'])) {

    $nombre        = trim($_POST['Nombre']);
    $codigoBarras  = trim($_POST['CodigoBarras']);
    $precioCompra  = floatval($_POST['PrecioCompra']);
    $precioVenta   = floatval($_POST['PrecioVenta']);
    $stock         = intval($_POST['Stock']);
    $stockMinimo   = intval($_POST['StockMinimo']); // NUEVO
    $idCategoria   = intval($_POST['idCategoria']);
    $idProveedor   = intval($_POST['idProveedor']);

    // Manejo de imagen
    $imagen = null;
    if (isset($_FILES['Imagen']) && $_FILES['Imagen']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['Imagen']['tmp_name'];
        $fileName = time() . "_" . basename($_FILES['Imagen']['name']);
        $rutaArchivo = $rutaImagenes . $fileName;

        if (move_uploaded_file($tmpName, $rutaArchivo)) {
            $imagen = "Imagenes/Productos/" . $fileName;
        } else {
            setMensaje("Error al subir la imagen.", "error");
        }
    }

    if (!isset($_SESSION['mensaje'])) {

        $stmt = $conn->prepare("CALL AgregarProducto(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            setMensaje("Error al preparar la consulta: " . $conn->error, "error");
        } else {

            $stmt->bind_param(
                "ssddiisii",
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
                setMensaje("Producto agregado correctamente.", "success");
                header("Location: AgregarProducto.php");
                exit();
            } else {
                setMensaje("Error al agregar producto: " . $stmt->error, "error");
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
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="assets/css/AgregarProducto.css">
    <link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<main>
    <section class="centro">
        <div class="log">
            <div class="login">

                <!-- ================= MENSAJES ================= -->
                <?php if (!empty($_SESSION['mensaje'])): ?>
                    <div class="alert-message <?= $_SESSION['tipo_mensaje']=='error'?'alert-error':'alert-success' ?>">
                        <?= htmlspecialchars($_SESSION['mensaje']) ?>
                    </div>
                    <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">

                    <div class="titulo">
                        <h2>Agregar Producto</h2>
                        <a href="Almacen.php">
                            <img src="Imagenes/Volver.png" alt="Atrás" class="boton-atras">
                        </a>
                    </div>

                    <div class="input-group">
                        <input type="text" name="Nombre" required placeholder=" ">
                        <label>Nombre del Producto</label>
                    </div>

                    <div class="input-group">
                        <input type="text" name="CodigoBarras" required placeholder=" ">
                        <label>Código de Barras</label>
                    </div>

                    <div class="input-group">
                        <input type="number" step="0.01" name="PrecioCompra" required placeholder=" ">
                        <label>Precio de Compra</label>
                    </div>

                    <div class="input-group">
                        <input type="number" step="0.01" name="PrecioVenta" required placeholder=" ">
                        <label>Precio de Venta</label>
                    </div>

                    <div class="input-group">
                        <input type="number" name="Stock" min="0" required placeholder=" ">
                        <label>Cantidad en Stock</label>
                    </div>

                    <!-- NUEVO CAMPO -->
                    <div class="input-group">
                        <input type="number" name="StockMinimo" min="0" required placeholder=" ">
                        <label>Stock Mínimo</label>
                    </div>

                    <div class="input-group">
                        <input type="file" name="Imagen" accept="image/*">
                        <label>Imagen del Producto</label>
                    </div>

                    <div class="input-group">
                        <select name="idCategoria" required>
                            <option disabled selected>Selecciona una Categoría</option>
                            <?php while ($categoria = $consultaCategorias->fetch_assoc()): ?>
                                <option value="<?= $categoria['idCategoria'] ?>">
                                    <?= htmlspecialchars($categoria['Nombre']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <label>Categoría</label>
                    </div>

                    <div class="input-group">
                        <select name="idProveedor" required>
                            <option disabled selected>Selecciona un Proveedor</option>
                            <?php while ($proveedor = $consultaProveedores->fetch_assoc()): ?>
                                <option value="<?= $proveedor['idProveedor'] ?>">
                                    <?= htmlspecialchars($proveedor['NombreCompleto']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <label>Proveedor</label>
                    </div>

                    <button type="submit" name="AgregarProducto" class="Acceder">
                        Agregar Producto
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
