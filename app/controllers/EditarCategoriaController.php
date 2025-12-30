<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/EditarCategoriaModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];


/* ================= MENSAJES ================= */
function setMensaje(string $texto, string $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

try {

    /* ================= POST ================= */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $idCategoria = (int)($_POST['idCategoria'] ?? 0);
        $nombre = trim($_POST['Nombre'] ?? '');

        if ($idCategoria <= 0) {
            throw new Exception("Categoría inválida");
        }

        if ($nombre === '') {
            throw new Exception("El nombre no puede estar vacío");
        }

        editarCategoria($conn, $idCategoria, $nombre);

        setMensaje("Categoría actualizada correctamente");
        header("Location: ../public/EditarCategoria.php?idCategoria=$idCategoria");
        exit;
    }

    /* ================= GET ================= */
    if (!isset($_GET['idCategoria'])) {
        throw new Exception("Categoría no especificada");
    }

    $idCategoria = (int)$_GET['idCategoria'];
    $categoria = obtenerCategoria($conn, $idCategoria);

} catch (mysqli_sql_exception $e) {
    // Captura errores lanzados por SIGNAL en el SP
    setMensaje($e->getMessage(), "error");
    header("Location: ../public/EditarCategoria.php?idCategoria=$idCategoria");
    exit;

} catch (Exception $e) {
    setMensaje($e->getMessage(), "error");
    header("Location: ../public/Categorias.php");
    exit;
}
