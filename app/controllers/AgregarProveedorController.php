<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Administrador

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/AgregarProveedorModel.php';

/* ================= MENSAJES ================= */
function setMensaje(string $texto, string $tipo = 'success'): void {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

/* ================= USUARIO PARA TRIGGERS ================= */
$idUsuario = $_SESSION['id'];
$conn->query("SET @usuario_actual = $idUsuario;");

$model = new ProveedorModel($conn);

/* ================= RUTA IMÁGENES ================= */
$rutaServidor = $_SERVER['DOCUMENT_ROOT'] . "/Herreria/public/Imagenes/Proveedores/";
$rutaWeb = "Imagenes/Proveedores/";

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

        if ($nombre === '') {
            throw new Exception("El nombre es obligatorio.");
        }

        /* IMAGEN */
        $imagen = null;

        if (!empty($_FILES['Imagen']['tmp_name'])) {
            $archivo = time() . "_" . basename($_FILES['Imagen']['name']);

            if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $rutaServidor . $archivo)) {
                $imagen = $rutaWeb . $archivo;
            } else {
                throw new Exception("Error al subir la imagen.");
            }
        }

        $resultado = $model->agregarProveedor(
            $nombre,
            $paterno,
            $materno,
            $telefono,
            $email,
            $imagen,
            'Activo',
            'Activo'
        );

        if (!$resultado['success']) {
            throw new Exception($resultado['error'] ?? 'Error al agregar proveedor.');
        }

        setMensaje("Proveedor agregado correctamente.");
        header("Location: ../public/AgregarProveedor.php");
        exit;

    } catch (Exception $e) {

        setMensaje($e->getMessage(), 'error');
        header("Location: ../public/AgregarProveedor.php");
        exit;
    }
}

/* ================= CARGAR VISTA ================= */
require __DIR__ . '/../views/AgregarProveedorView.php';
?>