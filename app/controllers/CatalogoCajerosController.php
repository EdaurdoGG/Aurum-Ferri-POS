<?php
session_start();
require_once __DIR__ . '/../config/session.php';
requireRole(2);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CatalogoCajerosModel.php';

/* ================= USUARIO ================= */
$idUsuario        = $_SESSION['id'];
$nombreUsuario    = $_SESSION['nombre_completo'] ?? 'Cajero';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Cajero';
$fotoUsuario      = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

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
