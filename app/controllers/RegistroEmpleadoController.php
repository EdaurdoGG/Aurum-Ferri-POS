<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1); // Solo administradores
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/RegistroEmpleadosModel.php';
require_once __DIR__ . '/../helpers/UsuarioHelper.php';

$usuario = cargarUsuarioSesion($conn, 'Administrador');

$idUsuario        = $usuario['idUsuario'];
$nombreUsuario    = $usuario['nombreUsuario'];
$rolUsuarioNombre = $usuario['rolUsuarioNombre'];
$fotoUsuario      = $usuario['fotoUsuario'];

/* ================= MENSAJES ================= */
function setMensaje(string $texto, string $tipo = 'success'): void {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

$model = new RegistroEmpleadosModel($conn);

/* ================= POST ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $nombre     = trim($_POST['Nombre'] ?? '');
        $apellidoP  = trim($_POST['ApellidoP'] ?? '');
        $apellidoM  = trim($_POST['ApellidoM'] ?? '');
        $telefono   = trim($_POST['Telefono'] ?? '');
        $email      = trim($_POST['Email'] ?? '');
        $usuarioReg = trim($_POST['Usuario'] ?? '');
        $claveReg   = $_POST['Clave'] ?? '';
        $idRol      = 2; // Empleado

        if (
            !$nombre || !$apellidoP || !$apellidoM ||
            !$telefono || !$email || !$usuarioReg || !$claveReg
        ) {
            setMensaje("Todos los campos son obligatorios.", "error");
            header("Location: Registro.php");
            exit;
        }

        $model->registrarEmpleado(
            $nombre,
            $apellidoP,
            $apellidoM,
            $telefono,
            $email,
            $usuarioReg,
            $claveReg,
            $idRol
        );

        setMensaje("Empleado registrado correctamente.");
        header("Location: Registro.php");
        exit;

    } catch (Throwable $e) {
        setMensaje("Error durante el registro: " . $e->getMessage(), "error");
        header("Location: Registro.php");
        exit;
    }
}

/* ================= VIEW ================= */
require_once __DIR__ . '/../views/RegistroEmpleadosView.php';
?>