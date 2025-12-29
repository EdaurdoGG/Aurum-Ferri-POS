<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/EditarClienteModel.php';

/* ================= MENSAJES ================= */
function setMensaje(string $texto, string $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

/* ================= USUARIO PARA TRIGGERS ================= */
$idUsuario = $_SESSION['id'];
$conn->query("SET @usuario_actual = $idUsuario;");

/* ================= RUTA IMÁGENES ================= */
$rutaServidor = $_SERVER['DOCUMENT_ROOT'] . "/Herreria/public/Imagenes/Clientes/";
$rutaWeb = "Imagenes/Clientes/";
if (!is_dir($rutaServidor)) mkdir($rutaServidor, 0755, true);

try {

    /* ================= OBTENER ID ================= */
    if (!isset($_GET['idCliente'])) {
        throw new Exception("Cliente no especificado");
    }

    $idCliente = (int)$_GET['idCliente'];
    $cliente = obtenerClientePorId($conn, $idCliente);

    if (!$cliente) {
        throw new Exception("Cliente no encontrado");
    }

    /* ================= POST ================= */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nombre   = trim($_POST['Nombre']);
        $paterno  = trim($_POST['Paterno']);
        $materno  = trim($_POST['Materno']);
        $telefono = trim($_POST['Telefono']);
        $email    = trim($_POST['Email']);
        $estatus  = $_POST['Estatus'];
        $credito  = (float)$_POST['Credito'];
        $limite   = (float)$_POST['Limite'];

        if ($nombre === '' || $paterno === '' || $materno === '') {
            throw new Exception("Los nombres no pueden estar vacíos");
        }

        /* ================= IMAGEN ================= */
        $imagen = $cliente['Imagen'] ?: $rutaWeb . "default.png";

        if (!empty($_FILES['Imagen']['tmp_name'])) {
            $fileName = time() . "_" . basename($_FILES['Imagen']['name']);
            if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $rutaServidor . $fileName)) {
                $imagen = $rutaWeb . $fileName;
            }
        }

        editarCliente(
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
        );

        setMensaje("Cliente actualizado correctamente");
        header("Location: ../public/EditarClientes.php?idCliente=$idCliente");
        exit;
    }

} catch (mysqli_sql_exception $e) {
    setMensaje($e->getMessage(), "error");
    header("Location: ../public/EditarClientes.php?idCliente=$idCliente");
    exit;

} catch (Exception $e) {
    setMensaje($e->getMessage(), "error");
    header("Location: ../public/Clientes.php");
    exit;
}

/* ================= VISTA ================= */
require __DIR__ . '/../views/EditarClienteView.php';
