<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // administrador

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/AgregarCategoriaModel.php';

/* ================= MENSAJES ================= */
function setMensaje($texto, $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

$model = new CategoriaModel($conn);

/* ================= PROCESAR FORMULARIO ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $nombre = trim($_POST['Nombre'] ?? '');

        if ($nombre === '') {
            throw new Exception("El nombre de la categoría es obligatorio.");
        }

        if (!$model->agregarCategoria($nombre)) {
            throw new Exception("Error al guardar la categoría.");
        }

        setMensaje("Categoría agregada correctamente.");
        header("Location: ../public/AgregarCategoria.php");
        exit;

    } catch (Exception $e) {

        setMensaje($e->getMessage(), "error");
        header("Location: ../public/AgregarCategoria.php");
        exit;
    }
}

/* ================= CARGAR VISTA ================= */
require __DIR__ . '/../views/AgregarCategoriaView.php';
