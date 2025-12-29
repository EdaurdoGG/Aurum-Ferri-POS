<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/AgregarProveedores.php';

/* MENSAJES */
function setMensaje($texto, $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

/* USUARIO */
$idUsuario = $_SESSION['id'];
$conn->query("SET @usuario_actual = $idUsuario;");

/* RUTA IMÁGENES */
$rutaImagenes = __DIR__ . '/../../public/Imagenes/Proveedores/';
if (!is_dir($rutaImagenes)) {
    mkdir($rutaImagenes, 0755, true);
}

/* PROCESAR FORMULARIO */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['AgregarProveedor'])) {

    $nombre   = trim($_POST['Nombre']);
    $paterno  = trim($_POST['Paterno']);
    $materno  = trim($_POST['Materno']);
    $telefono = trim($_POST['Telefono']);
    $email    = trim($_POST['Email']);

    $estatusPersona  = 'Activo';
    $estadoProveedor = 'Activo';

    /* IMAGEN */
    $imagen = null;
    if (!empty($_FILES['Imagen']['tmp_name'])) {
        $fileName = time() . "_" . basename($_FILES['Imagen']['name']);
        $destino  = $rutaImagenes . $fileName;

        if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $destino)) {
            $imagen = "Imagenes/Proveedores/" . $fileName;
        } else {
            setMensaje("Error al subir la imagen", "error");
        }
    }

    if (!isset($_SESSION['mensaje'])) {
        if (agregarProveedor(
            $conn,
            $nombre,
            $paterno,
            $materno,
            $telefono,
            $email,
            $imagen,
            $estatusPersona,
            $estadoProveedor
        )) {
            setMensaje("Proveedor agregado correctamente");
            header("Location: ../public/AgregarProveedor.php");
            exit;
        } else {
            setMensaje("Error al agregar proveedor", "error");
        }
    }
}
?>