<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/EditarCliente.php';

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

/* OBTENER ID DEL CLIENTE */
$idCliente = isset($_GET['idCliente']) ? (int)$_GET['idCliente'] : null;
if (!$idCliente) die("Cliente no encontrado.");

/* OBTENER DATOS DEL CLIENTE */
$cliente = obtenerClientePorId($conn, $idCliente);
if (!$cliente) die("Cliente no encontrado.");

/* POST */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ActualizarCliente'])) {
    $nombre   = trim($_POST['Nombre']);
    $paterno  = trim($_POST['Paterno']);
    $materno  = trim($_POST['Materno']);
    $telefono = trim($_POST['Telefono']);
    $email    = trim($_POST['Email']);
    $credito  = (float)$_POST['Credito'];
    $limite   = (float)$_POST['Limite'];
    $estatus  = $_POST['Estatus'];

    /* IMAGEN */
    $imagen = $cliente['Imagen'] ?: "Imagenes/Clientes/default.png";
    if (!empty($_FILES['Imagen']['tmp_name'])) {
        $fileName = time() . "_" . basename($_FILES['Imagen']['name']);
        if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $rutaImagenes . $fileName)) {
            $imagen = "Imagenes/Clientes/" . $fileName;
        }
    }

    /* ACTUALIZAR CLIENTE */
    if (editarCliente(
        $conn,
        $idCliente,
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
        setMensaje("Cliente actualizado correctamente");
    } else {
        setMensaje("Error al actualizar cliente", "error");
    }

    header("Location: ../public/EditarClientes.php?idCliente=$idCliente");
    exit;
}

/* VISTA */
require_once __DIR__ . '/../views/EditarClienteView.php';
