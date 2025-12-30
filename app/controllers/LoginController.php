<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/LoginModel.php';

$model = new UsuarioModel($conn);

/* ================= VARIABLES ================= */
$errorLogin = "";
$errorRegistro = "";
$exitoRegistro = "";
$modo = $_GET['registro'] ?? "login-form";

/* ================= LOGIN ================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST['accion'] ?? '') === 'login') {
    $email = $_POST['Email'] ?? '';
    $clave = $_POST['Clave'] ?? '';

    $usuario = $model->obtenerUsuarioPorEmail($email);
    if ($usuario) {
        if (password_verify($clave, $usuario['Contrasena']) || $clave === $usuario['Contrasena']) {
            // Crear sesión
            $_SESSION['id'] = $usuario['idUsuario'];
            $_SESSION['usuario'] = $usuario['Usuario'];
            $_SESSION['rol'] = $usuario['idRol'];
            $_SESSION['rol_nombre'] = $usuario['RolNombre'];
            $_SESSION['nombre_completo'] = $usuario['Nombre']." ".$usuario['Paterno']." ".$usuario['Materno'];
            $_SESSION['email'] = $usuario['Email'];
            $_SESSION['foto'] = (!empty($usuario['Imagen']) && file_exists($usuario['Imagen'])) ? $usuario['Imagen'] : 'Imagenes/Usuarios/default.png';

            // Redirigir según rol
            switch ($usuario['idRol']) {
                case 1: header("Location: InicioAdministradores.php"); break;
                case 2: header("Location: InicioTrabajadores.php"); break;
                case 3: header("Location: InicioProveedores.php"); break;
            }
            exit();
        } else {
            $errorLogin = "Contraseña incorrecta.";
        }
    } else {
        $errorLogin = "Correo no registrado.";
    }
}

/* ================= REGISTRO ================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST['accion'] ?? '') === 'registro') {
    $modo = "register-form";

    $data = [
        'Nombre' => trim($_POST['Nombre']),
        'Paterno' => trim($_POST['ApellidoP']),
        'Materno' => trim($_POST['ApellidoM']),
        'Email' => trim($_POST['EmailR']),
        'Usuario' => trim($_POST['UsuarioR']),
        'Contrasena' => password_hash($_POST['ClaveR'], PASSWORD_DEFAULT),
        'Telefono' => '',
        'Imagen' => 'default.jpg',
        'Estatus' => 'Activo',
        'Rol' => 3
    ];

    if (!$data['Nombre'] || !$data['Paterno'] || !$data['Materno'] || !$data['Email'] || !$data['Usuario'] || !$data['Contrasena']) {
        $errorRegistro = "Todos los campos son obligatorios.";
    } else {
        if ($model->registrarUsuario($data)) {
            $exitoRegistro = "Registro exitoso. Ahora inicia sesión.";
            $modo = "login-form";
        } else {
            $errorRegistro = "Error al registrar.";
        }
    }
}

/* ================= VISTA ================= */
require_once __DIR__ . '/../views/LoginView.php';
$conn->close();
?>
