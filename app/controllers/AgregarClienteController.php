<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // administrador

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/AgregarClienteModel.php';

/* ================= MENSAJES ================= */
function setMensaje($texto, $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

/* ================= USUARIO PARA TRIGGERS ================= */
$idUsuario = $_SESSION['id'];
$conn->query("SET @usuario_actual = $idUsuario;");

$model = new ClienteModel($conn);

/* ================= RUTA IMÁGENES ================= */
$rutaServidor = $_SERVER['DOCUMENT_ROOT'] . "/Herreria/public/Imagenes/Clientes/";
$rutaWeb = "Imagenes/Clientes/";

if (!is_dir($rutaServidor)) {
    mkdir($rutaServidor, 0755, true);
}

/* ================= PROCESAR FORM ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $nombre   = trim($_POST['Nombre'] ?? '');
        $paterno  = trim($_POST['Paterno'] ?? '');
        $materno  = trim($_POST['Materno'] ?? '');
        $telefono = trim($_POST['Telefono'] ?? '');
        $email    = trim($_POST['Email'] ?? '');
        $limite   = (float)($_POST['Limite'] ?? 0);

        if ($nombre === '' || $paterno === '' || $materno === '') {
            throw new Exception("Todos los campos obligatorios deben llenarse.");
        }

        /* IMAGEN */
        $imagen = $rutaWeb . "default.png";

        if (!empty($_FILES['Imagen']['tmp_name'])) {
            $nombreArchivo = time() . "_" . basename($_FILES['Imagen']['name']);

            if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $rutaServidor . $nombreArchivo)) {
                $imagen = $rutaWeb . $nombreArchivo;
            }
        }

        $resultado = $model->agregarCliente(
            $nombre,
            $paterno,
            $materno,
            $telefono,
            $email,
            $imagen,
            'Activo',
            0.00,
            $limite
        );

        if (!$resultado['success']) {
            throw new Exception($resultado['error'] ?? 'Error al agregar cliente.');
        }

        setMensaje("Cliente agregado correctamente.");
        header("Location: ../public/AgregarClientes.php");
        exit;

    } catch (Exception $e) {

        setMensaje($e->getMessage(), "error");
        header("Location: ../public/AgregarClientes.php");
        exit;
    }
}

/* ================= CARGAR VISTA ================= */
require __DIR__ . '/../views/AgregarClienteView.php';
