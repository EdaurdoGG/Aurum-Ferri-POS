<?php
require_once __DIR__ . '/../config/session.php';
requireRole(2); // solo cajero
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/InicioCajerosModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Cajero');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];


/* ================= MENSAJES ================= */
function setMensaje($texto, $tipo='success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

/* ================= MODEL ================= */
$model = new VentaCajeroModel($conn);

/* ================= DATOS ================= */
$clientes = $model->obtenerClientesActivos();
$idCarrito = $model->obtenerOCrearCarrito($idUsuario);
$productos_registrados = $model->obtenerCarritoUsuario($idUsuario);

/* ================= CLIENTE ================= */
$cliente_id = $_POST['cliente_id'] ?? $_SESSION['cliente_id_seleccionado'] ?? 1;
$_SESSION['cliente_id_seleccionado'] = $cliente_id;
$venta_credito = isset($_POST['venta_credito']) ? 1 : 0;

/* ================= ACCIONES ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['termino'])) {
        $producto = $model->buscarProducto(trim($_POST['termino']));
        if ($producto && $model->agregarProducto($idCarrito, $producto['idProducto'])) {
            setMensaje("Producto agregado al carrito");
        } else {
            setMensaje("Producto no encontrado o sin stock", "error");
        }
        header("Location: InicioTrabajadores.php");
        exit();
    }

    if (isset($_POST['actualizar'])) {
        $ok = $model->actualizarCantidad(
            $idCarrito,
            (int)$_POST['producto_id'],
            max(0, (int)$_POST['cantidad'])
        );
        setMensaje($ok ? "Cantidad actualizada" : "Stock insuficiente", $ok?'success':'error');
        header("Location: InicioTrabajadores.php");
        exit();
    }

    if (isset($_POST['sumar'])) {
        $model->sumarCantidad($idCarrito, (int)$_POST['producto_id']);
        header("Location: InicioTrabajadores.php");
        exit();
    }

    if (isset($_POST['restar'])) {
        $model->restarCantidad($idCarrito, (int)$_POST['producto_id']);
        header("Location: InicioTrabajadores.php");
        exit();
    }

    if (isset($_POST['procesar'])) {
        if ($model->procesarVenta($idUsuario, $cliente_id, $venta_credito)) {
            setMensaje("Venta procesada correctamente");
            unset($_SESSION['cliente_id_seleccionado']);
        } else {
            setMensaje("No se pudo procesar la venta", "error");
        }
        header("Location: InicioTrabajadores.php");
        exit();
    }
}

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/InicioCajerosView.php';
