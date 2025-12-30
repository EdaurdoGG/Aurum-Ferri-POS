<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/InicioAdministradoresModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

/* ================= MODEL ================= */
$model = new VentaModel($conn);

/* ================= DATOS ================= */
$notificacionesNoLeidas = $model->notificacionesNoLeidas();
$clientes = $model->obtenerClientes();
$idCarrito = $model->obtenerOCrearCarrito($idUsuario);
$productos_registrados = $model->obtenerCarrito($idUsuario);

/* ================= CLIENTE ================= */
$cliente_id = $_POST['cliente_id'] ?? $_SESSION['cliente_id_seleccionado'] ?? 1;
$_SESSION['cliente_id_seleccionado'] = $cliente_id;
$venta_credito = isset($_POST['venta_credito']) ? 1 : 0;

/* ================= MENSAJES ================= */
function setMensaje($texto, $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

/* ================= ACCIONES ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['termino'])) {
        require __DIR__ . '/../helpers/AgregarProducto.php';
        exit();
    }

    if (isset($_POST['sumar']) || isset($_POST['restar'])) {
        require __DIR__ . '/../helpers/ModificarCantidad.php';
        exit();
    }

    if (isset($_POST['actualizar'])) {
        require __DIR__ . '/../helpers/ActualizarCantidad.php';
        exit();
    }

    if (isset($_POST['procesar'])) {
        require __DIR__ . '/../helpers/ProcesarVenta.php';
        exit();
    }
}

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/InicioAdministradoresView.php';
