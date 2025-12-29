<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/AgregarClientes.php';

/* MENSAJES */
function setMensaje($texto, $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

/* USUARIO PARA TRIGGERS */
$idUsuario = $_SESSION['id'];
$conn->query("SET @usuario_actual = $idUsuario;");

/* RUTA IMÁGENES */
$rutaImagenes = $_SERVER['DOCUMENT_ROOT'] . "/Herreria/public/Imagenes/Clientes/";
if (!is_dir($rutaImagenes)) mkdir($rutaImagenes, 0755, true);

/* POST */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre   = trim($_POST['Nombre']);
    $paterno  = trim($_POST['Paterno']);
    $materno  = trim($_POST['Materno']);
    $telefono = trim($_POST['Telefono']);
    $email    = trim($_POST['Email']);

    $credito = 0;
    $limite  = (float)$_POST['Limite'];
    $estatus = 'Activo';

    if ($nombre === '' || $paterno === '' || $materno === '') {
        setMensaje("Todos los campos son obligatorios", "error");
        header("Location: AgregarCliente.php");
        exit;
    }

    /* IMAGEN */
    $imagen = "Imagenes/Clientes/default.png";
    if (!empty($_FILES['Imagen']['tmp_name'])) {
        $fileName = time() . "_" . basename($_FILES['Imagen']['name']);
        if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $rutaImagenes . $fileName)) {
            $imagen = "Imagenes/Clientes/" . $fileName;
        }
    }

    if (agregarCliente(
        $conn,
        $nombre,
        $paterno,
        $materno,
        $telefono,
        $email,
        $imagen,
        $estatus,
        $credito,
        $limite
    )) {
        setMensaje("Cliente agregado correctamente");
    } else {
        setMensaje("Error al agregar cliente", "error");
    }

    header("Location: ../public/AgregarClientes.php");
    exit;
}

/* VISTA */
require_once __DIR__ . '/../views/AgregarClienteView.php';
