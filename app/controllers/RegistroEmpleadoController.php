<?php
require_once __DIR__ . '/../config/session.php';
requireRole(1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/registroEmpleado.php';

/* USUARIO */
$idUsuario = $_SESSION['id'];
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* TRIGGERS */
$conn->query("SET @usuario_actual = $idUsuario;");

/* MENSAJES */
function setMensaje($texto, $tipo = 'success') {
    $_SESSION['mensaje'] = $texto;
    $_SESSION['tipo_mensaje'] = $tipo;
}

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nombre     = trim($_POST['Nombre']);
        $apellidoP  = trim($_POST['ApellidoP']);
        $apellidoM  = trim($_POST['ApellidoM']);
        $telefono   = trim($_POST['Telefono']);
        $email      = trim($_POST['Email']);
        $usuarioReg = trim($_POST['Usuario']);
        $claveReg   = $_POST['Clave'];
        $idRol      = 2; // Empleado

        if (!$nombre || !$apellidoP || !$apellidoM || !$telefono || !$email || !$usuarioReg || !$claveReg) {
            setMensaje("Todos los campos son obligatorios.", "error");
            header("Location: Registro.php");
            exit;
        }

        registrarEmpleado(
            $conn,
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
    }

} catch (Exception $e) {
    setMensaje("Error durante el registro: " . $e->getMessage(), "error");
    header("Location: Registro.php");
    exit;
}

require_once __DIR__ . '/../views/RegistroEmpleadoView.php';
