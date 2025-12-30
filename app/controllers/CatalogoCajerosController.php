<?php
require_once __DIR__ . '/../config/session.php';
requireRole(2); // solo cajero
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CatalogoCajerosModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Cajero');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

/* ================= RUTAS ================= */
$rutaServidor = __DIR__ . '/../../public/Imagenes/Productos/';
$rutaWeb      = 'Imagenes/Productos/';
if (!is_dir($rutaServidor)) mkdir($rutaServidor, 0755, true);

/* ================= MODEL ================= */
$model = new CatalogoCajerosModel($conn);

/* ================= MENSAJE ================= */
$mensaje_flotante = '';

/* ================= AGREGAR AL CARRITO ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProducto'], $_POST['cantidad'])) {

    try {
        $idProducto = (int)$_POST['idProducto'];
        $cantidad   = (int)$_POST['cantidad'];

        $idCarrito = $model->obtenerOCrearCarrito($idUsuario);

        if ($model->agregarAlCarrito($idCarrito, $idProducto, $cantidad)) {
            $mensaje_flotante = "Producto agregado al carrito correctamente.";
        } else {
            $mensaje_flotante = "Error al agregar el producto.";
        }

    } catch (Exception $e) {
        $mensaje_flotante = $e->getMessage();
    }
}

/* ================= PRODUCTOS ================= */
$data = $model->obtenerProductos();
$productos  = $data['productos'];
$categorias = $data['categorias'];

/* ================= VISTA ================= */
require __DIR__ . '/../views/CatalogoCajerosView.php';
