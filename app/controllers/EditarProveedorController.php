<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/EditarProveedores.php';

/* MENSAJES */
function setMensaje($texto, $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

/* USUARIO */
$idUsuario = $_SESSION['id'];
$conn->query("SET @usuario_actual = $idUsuario;");

/* VALIDAR ID */
$idProveedor = (int)($_GET['idProveedor'] ?? 0);
if ($idProveedor <= 0) {
    header("Location: Proveedores.php");
    exit;
}

/* RUTA IMÁGENES */
$rutaImagenes = $_SERVER['DOCUMENT_ROOT'] . "/Herreria/public/Imagenes/Proveedores/";
if (!is_dir($rutaImagenes)) mkdir($rutaImagenes, 0755, true);

/* LIMPIAR MENSAJES ANTERIORES */
unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);

/* POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre   = trim($_POST['Nombre']);
    $paterno  = trim($_POST['Paterno']);
    $materno  = trim($_POST['Materno']);
    $telefono = trim($_POST['Telefono']);
    $email    = trim($_POST['Email']);
    $estatusPersona  = $_POST['EstatusPersona'];
    $estadoProveedor = $_POST['EstadoProveedor'];

    if ($nombre === '') {
        setMensaje("El nombre es obligatorio", "error");
    }

    /* IMAGEN */
    $imagen = $_POST['ImagenActual'] ?? null;
    if (!empty($_FILES['Imagen']['tmp_name'])) {
        $fileName = time() . "_" . basename($_FILES['Imagen']['name']);
        if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $rutaImagenes . $fileName)) {
            $imagen = "Imagenes/Proveedores/" . $fileName;
        }
    }

    if (!isset($_SESSION['mensaje'])) {
        editarProveedor(
            $conn,
            $idProveedor,
            $nombre,
            $paterno,
            $materno,
            $telefono,
            $email,
            $imagen,
            $estatusPersona,
            $estadoProveedor
        );

        setMensaje("Proveedor actualizado correctamente");
        header("Location: EditarProveedor.php?idProveedor=$idProveedor");
        exit;
    }
}

/* CARGAR DATOS */
$proveedor = obtenerProveedorPorId($conn, $idProveedor);

require_once __DIR__ . '/../views/EditarProveedorView.php';
?>