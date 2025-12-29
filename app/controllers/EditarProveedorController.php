<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/EditarProveedorModel.php';

/* MENSAJES */
function setMensaje(string $msg, string $tipo='success'){
    $_SESSION['mensaje']=$msg;
    $_SESSION['tipo_mensaje']=$tipo;
}

/* USUARIO AUDITORÍA */
$idUsuarioSesion = $_SESSION['id'];
$conn->query("SET @usuario_actual = $idUsuarioSesion");

/* VALIDAR ID */
$idProveedor = (int)($_GET['idProveedor'] ?? 0);
if ($idProveedor <= 0) {
    header("Location: Proveedores.php");
    exit;
}

/* MODEL */
$model = new ProveedorModel($conn);

/* RUTA IMÁGENES */
$rutaImagenes = $_SERVER['DOCUMENT_ROOT']."/Herreria/public/Imagenes/Proveedores/";
if (!is_dir($rutaImagenes)) mkdir($rutaImagenes, 0775, true);

/* POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre   = trim($_POST['Nombre']);
    $paterno  = trim($_POST['Paterno']);
    $materno  = trim($_POST['Materno']);
    $telefono = trim($_POST['Telefono']);
    $email    = trim($_POST['Email']);
    $estatusPersona  = $_POST['EstatusPersona'];
    $estadoProveedor = $_POST['EstadoProveedor'];
    $imagen = $_POST['ImagenActual'] ?? null;

    if ($nombre === '') {
        setMensaje("El nombre es obligatorio", "error");
    }

    /* SUBIR IMAGEN */
    if (!empty($_FILES['Imagen']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['Imagen']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','gif'])) {
            setMensaje("Formato de imagen no permitido", "error");
        } else {
            $nombreImg = uniqid().".".$ext;
            move_uploaded_file($_FILES['Imagen']['tmp_name'], $rutaImagenes.$nombreImg);
            $imagen = "Imagenes/Proveedores/".$nombreImg;
        }
    }

    if (!isset($_SESSION['tipo_mensaje'])) {
        $model->editarProveedor(
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
$proveedor = $model->obtenerProveedorPorId($idProveedor);
if (!$proveedor) {
    setMensaje("Proveedor no encontrado","error");
    header("Location: Proveedores.php");
    exit;
}

require_once __DIR__ . '/../views/EditarProveedorView.php';
