<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/categorias.php';

function setMensaje($t,$tipo='success'){
    $_SESSION['mensaje']=$t;
    $_SESSION['tipo_mensaje']=$tipo;
}

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $idCategoria = (int)$_POST['idCategoria'];
        $nombre = trim($_POST['Nombre']);

        if ($nombre === '') {
            throw new Exception("El nombre no puede estar vacío");
        }

        editarCategoria($conn, $idCategoria, $nombre);

        setMensaje("Categoría actualizada correctamente");
        header("Location: ../public/EditarCategoria.php?idCategoria=$idCategoria");
        exit;
    }

    if (!isset($_GET['idCategoria'])) {
        throw new Exception("Categoría no especificada");
    }

    $idCategoria = (int)$_GET['idCategoria'];
    $categoria = obtenerCategoria($conn, $idCategoria);

} catch (Exception $e) {
    setMensaje($e->getMessage(), "error");
    header("Location: ../public/Categorias.php");
    exit;
}
?>