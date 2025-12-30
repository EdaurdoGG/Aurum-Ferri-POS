<?php
require_once __DIR__ . '/../config/session.php';
requireRole(3); // Solo proveedores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CarritoProveedoresModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Proveedor');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

/* ================= CLIENTE ================= */
$idCliente     = $_SESSION['cliente_seleccionado'] ?? null;
$nombreCliente = $_SESSION['cliente_nombre'] ?? null;

/* ================= RUTAS IMÁGENES ================= */
$rutaServidor = __DIR__ . '/../../public/Imagenes/Productos/';
$rutaWeb      = 'Imagenes/Productos/';

/* ================= MENSAJES ================= */
$mensaje = $_GET['pedido_ok'] ?? '';
$error   = '';

/* ================= MODEL ================= */
$model = new CarritoProveedoresModel($conn);

/* ================= DATOS ================= */
$contador = $model->contarProductosCarrito($idUsuario);
$carrito  = $model->obtenerCarrito($idUsuario);
$imagenesProductos = $model->obtenerImagenesProductos();

/* ================= ID CARRITO ================= */
$idCarrito = $carrito[0]['idCarrito'] ?? null;

/* ================= ACCIONES ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        if (!$idCarrito) {
            throw new Exception("No existe un carrito activo.");
        }

        if (isset($_POST['sumar'])) {
            $model->sumarProducto($idCarrito, $_POST['idProducto']);
        }

        if (isset($_POST['restar'])) {
            $model->restarProducto($idCarrito, $_POST['idProducto']);
        }

        if (isset($_POST['actualizar'])) {
            $model->actualizarCantidad(
                $idCarrito,
                $_POST['idProducto'],
                $_POST['cantidad']
            );
        }

        if (isset($_POST['pedido'])) {

            if (!$idCliente) {
                throw new Exception("Debes seleccionar un cliente.");
            }

            if (empty($carrito)) {
                throw new Exception("El carrito está vacío.");
            }

            $model->generarPedido($idCliente, $idUsuario, $carrito);

            unset($_SESSION['cliente_seleccionado'], $_SESSION['cliente_nombre']);
            header("Location: CarritoProveedores.php?pedido_ok=1");
            exit;
        }

        header("Location: CarritoProveedores.php");
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

/* ================= TOTALES ================= */
$subtotal = array_sum(array_column($carrito, 'Total'));

/* ================= VISTA ================= */
require __DIR__ . '/../views/CarritoProveedoresView.php';
