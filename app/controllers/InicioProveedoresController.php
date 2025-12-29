<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/InicioProveedoresModel.php';

/* ================= SESIÓN ================= */
requireRole(3);

/* ================= USUARIO ================= */
$idUsuario        = $_SESSION['id'];
$nombreUsuario    = $_SESSION['nombre_completo'] ?? 'Proveedor';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Proveedor';
$fotoUsuario      = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

$conn->query("SET @usuario_actual = $idUsuario");

/* ================= MODEL ================= */
$model = new ProveedorCatalogoModel($conn);

/* ================= CLIENTES ================= */
$clientes = $model->obtenerClientesActivos();

/* ================= CLIENTE SELECCIONADO ================= */
$idCliente = $_SESSION['cliente_seleccionado'] ?? null;
$nombreCliente = $_SESSION['cliente_nombre'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCliente'])) {
    foreach ($clientes as $c) {
        if ($c['idCliente'] == $_POST['idCliente']) {
            $_SESSION['cliente_seleccionado'] = $c['idCliente'];
            $_SESSION['cliente_nombre'] = $c['Nombre'].' '.$c['Paterno'];
            header("Location: InicioProveedores.php");
            exit();
        }
    }
}

/* ================= RUTAS IMÁGENES ================= */
$rutaServidor = __DIR__ . '/../../public/Imagenes/Productos/';
$rutaWeb = 'Imagenes/Productos/';
if (!is_dir($rutaServidor)) mkdir($rutaServidor, 0755, true);

/* ================= AGREGAR AL CARRITO ================= */
if (isset($_POST['idProducto'], $_POST['cantidad'])) {
    $idCarrito = $model->obtenerOCrearCarrito($idUsuario);
    $model->agregarAlCarrito(
        $idCarrito,
        (int)$_POST['idProducto'],
        (int)$_POST['cantidad']
    );
    header("Location: InicioProveedores.php");
    exit();
}

/* ================= CONTADOR ================= */
$contador = $model->contarProductosCarrito($idUsuario);

/* ================= PRODUCTOS ================= */
$productos = $model->obtenerProductos();

/* ================= VIEW ================= */
require_once __DIR__ . '/../views/InicioProveedoresView.php';
