<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/editarEmpleado.php';

/* MENSAJES */
function setMensaje($texto, $tipo='success'){
    $_SESSION['mensaje']=$texto;
    $_SESSION['tipo_mensaje']=$tipo;
}

/* USUARIO ACTUAL */
$idUsuarioSesion = $_SESSION['id'];
$conn->query("SET @usuario_actual = $idUsuarioSesion;");

/* VALIDAR ID */
$idUsuario = (int)($_GET['idUsuario'] ?? 0);
if ($idUsuario <= 0) {
    header("Location: Empleados.php");
    exit;
}

/* RUTA IMÁGENES */
$rutaImagenes = $_SERVER['DOCUMENT_ROOT'] . "/Herreria/public/Imagenes/Usuarios/";
if (!is_dir($rutaImagenes)) mkdir($rutaImagenes, 0775, true);

/* POST */
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
        setMensaje("Completa todos los campos obligatorios", "error");
        header("Location: EditarEmpleado.php?idUsuario=$idUsuario");
        exit;
    }

    $imagenActual = obtenerImagenUsuario($conn, $idUsuario);
    $imagen = $imagenActual;

    if (!empty($_FILES['Imagen']['tmp_name'])) {
        $imagen = subirImagenUsuario($_FILES['Imagen'], $rutaImagenes);
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

/* CARGAR DATOS */
$usuario = obtenerEmpleadoPorId($conn, $idUsuario);

require_once __DIR__ . '/../views/EditarEmpleadoView.php';
