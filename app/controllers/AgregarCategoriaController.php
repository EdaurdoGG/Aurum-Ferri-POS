<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/categorias.php';

function setMensaje($t, $tipo='success'){
    $_SESSION['mensaje']=$t;
    $_SESSION['tipo_mensaje']=$tipo;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombre = trim($_POST['Nombre'] ?? '');

        if ($nombre === '') {
            throw new Exception("El nombre es obligatorio");
        }

        agregarCategoria($conn, $nombre);

        setMensaje("Categoría agregada correctamente");
        header("Location: ../public/AgregarCategoria.php");
        exit;

    } catch (Exception $e) {
        setMensaje($e->getMessage(), "error");
        header("Location: ../public/AgregarCategoria.php");
        exit;
    }
}
