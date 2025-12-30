<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/EditarEmpleadoModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];


/* ================= MENSAJES ================= */
function setMensaje(string $texto, string $tipo='success'){
    $_SESSION['mensaje']=$texto;
    $_SESSION['tipo_mensaje']=$tipo;
}

/* ================= VALIDAR ID ================= */
$idUsuario = (int)($_GET['idUsuario'] ?? 0);
if ($idUsuario <= 0) {
    header("Location: Empleados.php");
    exit;
}

/* ================= RUTA IMÁGENES ================= */
$rutaServidor = $_SERVER['DOCUMENT_ROOT'] . "/Herreria/public/Imagenes/Usuarios/";
if (!is_dir($rutaServidor)) mkdir($rutaServidor, 0755, true);

try {

    /* ================= CARGAR EMPLEADO ================= */
    $empleado = obtenerEmpleadoPorId($conn, $idUsuario);
    if (!$empleado) {
        throw new Exception("Empleado no encontrado");
    }

    /* ================= POST ================= */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nombre   = trim($_POST['Nombre']);
        $paterno  = trim($_POST['Paterno']);
        $materno  = trim($_POST['Materno']);
        $telefono = trim($_POST['Telefono']);
        $email    = trim($_POST['Email']);
        $estatus  = $_POST['Estatus'];
        $usuario  = trim($_POST['Usuario']);
        $idRol    = (int)$_POST['idRol'];

        if (!$nombre || !$paterno || !$materno || !$telefono || !$email || !$usuario) {
            throw new Exception("Completa todos los campos obligatorios");
        }

        /* ================= IMAGEN ================= */
        $imagen = obtenerImagenEmpleado($conn, $idUsuario);

        if (!empty($_FILES['Imagen']['tmp_name'])) {
            $imagen = subirImagenEmpleado($_FILES['Imagen'], $rutaServidor);
        }

        editarEmpleado(
            $conn,
            $idUsuario,
            $nombre,
            $paterno,
            $materno,
            $telefono,
            $email,
            $imagen,
            $estatus,
            $usuario,
            $idRol
        );

        setMensaje("Usuario actualizado correctamente");
        header("Location: EditarEmpleado.php?idUsuario=$idUsuario");
        exit;
    }

} catch (mysqli_sql_exception $e) {
    setMensaje($e->getMessage(), "error");
    header("Location: EditarEmpleado.php?idUsuario=$idUsuario");
    exit;

} catch (Exception $e) {
    setMensaje($e->getMessage(), "error");
    header("Location: Empleados.php");
    exit;
}

/* ================= VISTA ================= */
require __DIR__ . '/../views/EditarEmpleadoView.php';
