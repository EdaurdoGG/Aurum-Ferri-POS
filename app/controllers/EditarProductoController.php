<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/EditarProductoModel.php';
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

/* ================= RUTAS ================= */
$rutaServidor = realpath(__DIR__ . "/../public/Imagenes/Productos/") . DIRECTORY_SEPARATOR;
$rutaWeb = '/Herreria/public/Imagenes/Productos/'; 

/* ================= INSTANCIA DEL MODEL ================= */
$model = new ProductosModel($conn);

/* ================= OBTENER DATOS ================= */
$categorias = $model->obtenerCategorias();
$proveedores = $model->obtenerProveedores();

if (!isset($_GET['idProducto']) || !is_numeric($_GET['idProducto'])) {
    die("ID inválido.");
}
$idProducto = intval($_GET['idProducto']);
$producto = $model->obtenerProducto($idProducto);
if (!$producto) die("Producto no encontrado.");

/* ================= PROCESO DE FORMULARIO ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['EditarProducto'])) {

    $nombre        = trim($_POST['Nombre']);
    $codigoBarras  = trim($_POST['CodigoBarras']);
    $precioCompra  = floatval($_POST['PrecioCompra']);
    $precioVenta   = floatval($_POST['PrecioVenta']);
    $stock         = intval($_POST['Stock']);
    $stockMinimo   = intval($_POST['StockMinimo']);
    $idCategoria   = intval($_POST['idCategoria']);
    $idProveedor   = intval($_POST['idProveedor']);

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
        if ($model->actualizarProducto($idProducto, $nombre, $codigoBarras, $precioCompra, $precioVenta, $stock, $stockMinimo, $imagen, $idCategoria, $idProveedor)) {
            setMensaje("Producto actualizado correctamente.", "success");
            header("Location: EditarProducto.php?idProducto=$idProducto");
            exit();
        } else {
            setMensaje("Error al actualizar el producto.", "error");
        }
    }
}

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/EditarProductoView.php';
?>
