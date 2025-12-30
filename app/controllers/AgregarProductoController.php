<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/AgregarProductoModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

/* ================= MENSAJES ================= */
function setMensaje(string $texto, string $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

/* ================= RUTA DE IMÁGENES ================= */
$rutaImagenes = __DIR__ . "/Imagenes/Productos/";
if (!is_dir($rutaImagenes)) mkdir($rutaImagenes, 0755, true);

/* ================= INSTANCIA DEL MODEL ================= */
$model = new ProductosModel($conn);

/* ================= DATOS ================= */
$categorias = $model->obtenerCategorias();
$proveedores = $model->obtenerProveedores();

/* ================= PROCESO DE FORMULARIO ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['AgregarProducto'])) {

    $nombre        = trim($_POST['Nombre']);
    $codigoBarras  = trim($_POST['CodigoBarras']);
    $precioCompra  = floatval($_POST['PrecioCompra']);
    $precioVenta   = floatval($_POST['PrecioVenta']);
    $stock         = intval($_POST['Stock']);
    $stockMinimo   = intval($_POST['StockMinimo']);
    $idCategoria   = intval($_POST['idCategoria']);
    $idProveedor   = intval($_POST['idProveedor']);

    /* ================= MANEJO DE IMAGEN ================= */
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
        if ($model->agregarProducto($nombre, $codigoBarras, $precioCompra, $precioVenta, $stock, $stockMinimo, $imagen, $idCategoria, $idProveedor)) {
            setMensaje("Producto agregado correctamente.", "success");
            header("Location: AgregarProducto.php");
            exit();
        } else {
            setMensaje("Error al agregar producto.", "error");
        }
    }
}

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/AgregarProductoView.php';
?>
