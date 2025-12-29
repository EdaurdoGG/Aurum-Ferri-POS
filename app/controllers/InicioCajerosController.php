<?php
session_start();
require_once __DIR__ . '/../config/session.php';
requireRole(2); // Solo cajeros

require_once __DIR__ . '/../config/database.php';

/* ================= USUARIO ================= */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Cajero';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Cajero';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= VARIABLE PARA TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

/* ================= NOTIFICACIONES NO LEÍDAS ================= */
$consultaNoti = $conn->query("SELECT COUNT(*) AS total FROM Notificaciones WHERE Leida = 0");
$notificacionesNoLeidas = $consultaNoti->fetch_assoc()['total'] ?? 0;

/* ================= MENSAJES ================= */
function setMensaje($texto, $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

/* ================= OBTENER / CREAR CARRITO ================= */
$idCarrito = null;
$stmt = $conn->prepare("SELECT idCarrito FROM Carrito WHERE idUsuario=? ORDER BY Fecha DESC LIMIT 1");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) $idCarrito = $row['idCarrito'];
$stmt->close();
$conn->next_result();

if (!$idCarrito) {
    $stmt = $conn->prepare("INSERT INTO Carrito (idUsuario) VALUES (?)");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $idCarrito = $stmt->insert_id;
    $stmt->close();
}

/* ================= CLIENTES ================= */
$clientes = [];
$res = $conn->query("
    SELECT c.idCliente id, CONCAT(p.Nombre,' ',p.Paterno,' ',p.Materno) Nombre
    FROM Clientes c
    JOIN Personas p ON p.idPersona=c.idPersona
    WHERE p.Estatus='Activo'
");
if ($res) $clientes = $res->fetch_all(MYSQLI_ASSOC);

/* ================= CLIENTE SELECCIONADO ================= */
$cliente_id = $_POST['cliente_id'] ?? $_SESSION['cliente_id_seleccionado'] ?? 1;
$_SESSION['cliente_id_seleccionado'] = $cliente_id;
$venta_credito = isset($_POST['venta_credito']) ? 1 : 0;

/* ================= AGREGAR PRODUCTO ================= */
if (!empty($_POST['termino'])) {
    require_once __DIR__ . '/../helpers/AgregarProductoCajeros.php';
    exit();
}

/* ================= SUMAR / RESTAR ================= */
if (isset($_POST['sumar']) || isset($_POST['restar'])) {
    require_once __DIR__ . '/../helpers/ModificarCantidadCajeros.php';
    exit();
}

/* ================= ACTUALIZAR CANTIDAD ================= */
if (isset($_POST['actualizar'])) {
    require_once __DIR__ . '/../helpers/ActualizarCantidadCajeros.php';
    exit();
}

/* ================= PROCESAR VENTA ================= */
if (isset($_POST['procesar'])) {
    require_once __DIR__ . '/../helpers/ProcesarVentaCajeros.php';
    exit();
}

/* ================= CARRITO ================= */
$productos_registrados = [];
$stmt = $conn->prepare("CALL ObtenerCarritoUsuario(?)");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) $productos_registrados[] = $r;
$stmt->close(); $conn->next_result();

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/InicioCajerosView.php';
?>