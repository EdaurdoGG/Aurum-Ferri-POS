<?php
session_start();

/* ================= CONEXIÓN ================= */
$conn = new mysqli("db", "root", "clave", "HerreriaUG");
if ($conn->connect_error) {
    die("Error de conexión");
}
$conn->set_charset("utf8mb4");

/* ================= VARIABLES ================= */
$errorLogin = "";
$errorRegistro = "";
$exitoRegistro = "";
$modo = "login-form";

/* ===== CAMBIO DE FORMULARIO POR GET ===== */
if (isset($_GET['registro'])) {
    $modo = "register-form";
}

/* ================= LOGIN ================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST['accion'] ?? '') === 'login') {

    $emailInput = $_POST['Email'] ?? '';
    $claveInput = $_POST['Clave'] ?? '';

    $sql = "
        SELECT 
            u.idUsuario,
            u.Usuario,
            u.Contrasena,
            u.idRol,
            p.Nombre,
            p.Paterno,
            p.Materno,
            p.Email,
            p.Imagen,
            r.Nombre AS RolNombre
        FROM Usuarios u
        JOIN Personas p ON u.idPersona = p.idPersona
        JOIN Roles r ON u.idRol = r.idRol
        WHERE p.Email = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $emailInput);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $u = $res->fetch_assoc();

        if (password_verify($claveInput, $u['Contrasena']) || $claveInput === $u['Contrasena']) {

            $_SESSION['id'] = $u['idUsuario'];
            $_SESSION['usuario'] = $u['Usuario'];
            $_SESSION['rol'] = $u['idRol'];
            $_SESSION['rol_nombre'] = $u['RolNombre'];
            $_SESSION['nombre_completo'] = $u['Nombre']." ".$u['Paterno']." ".$u['Materno'];
            $_SESSION['email'] = $u['Email'];
            $_SESSION['foto'] = (!empty($u['Imagen']) && file_exists($u['Imagen']))
                ? $u['Imagen']
                : 'Imagenes/Usuarios/default.png';

            switch ($u['idRol']) {
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

    $nombre  = trim($_POST['Nombre']);
    $apP     = trim($_POST['ApellidoP']);
    $apM     = trim($_POST['ApellidoM']);
    $email   = trim($_POST['EmailR']);
    $usuario = trim($_POST['UsuarioR']);
    $clave   = $_POST['ClaveR'];

    if (!$nombre || !$apP || !$apM || !$email || !$usuario || !$clave) {
        $errorRegistro = "Todos los campos son obligatorios.";
    } else {

        $hash = password_hash($clave, PASSWORD_DEFAULT);
        $telefono = '';
        $imagen = 'default.jpg';
        $estatus = 'Activo';
        $rol = 3;

        $stmt = $conn->prepare("CALL AgregarUsuario(?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param(
            "sssssssssi",
            $nombre, $apP, $apM, $telefono,
            $email, $imagen, $estatus,
            $usuario, $hash, $rol
        );

        if ($stmt->execute()) {
            $exitoRegistro = "Registro exitoso. Ahora inicia sesión.";
            $modo = "login-form";
        } else {
            $errorRegistro = "Error al registrar.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Acceso</title>
<link rel="stylesheet" href="assets/css/Login.css">
<link rel="icon" href="assets/icons/Logo.png">
</head>

<body>

<main>
<div class="login <?= $modo ?>">

    <!-- ===== PANEL LOGO (SOLO IMAGEN) ===== -->
    <div class="panel panel-logo">
        <div>
            <img src="assets/icons/Logo.png" alt="Logo">
        </div>
    </div>

    <!-- ===== PANEL FORM ===== -->
    <div class="panel panel-form">

        <!-- ===== LOGIN ===== -->
        <?php if ($modo === "login-form"): ?>
        <div class="form-box active">
            <div class="titulo"><h2>Iniciar sesión</h2></div>

            <?php if ($errorLogin): ?>
                <div class="alert-message alert-error"><?= $errorLogin ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="accion" value="login">

                <div class="input-group">
                    <input type="email" name="Email" required placeholder=" ">
                    <label>Correo</label>
                </div>

                <div class="input-group">
                    <input type="password" name="Clave" required placeholder=" ">
                    <label>Contraseña</label>
                </div>

                <button class="Acceder">Entrar</button>
            </form>

            <div class="switch" onclick="cambiarRegistro()">¿No tienes cuenta? Regístrate</div>

            <div class="switch" style="margin-top:10px;">
                <a href="OlvideClave.php" style="text-decoration:none; color:#005bf7;">¿Olvidaste tu contraseña?</a>
            </div>
        </div>
        <?php endif; ?>

        <!-- ===== REGISTRO ===== -->
        <?php if ($modo === "register-form"): ?>
        <div class="form-box active">
            <div class="titulo"><h2>Registro</h2></div>

            <?php if ($errorRegistro): ?>
                <div class="alert-message alert-error"><?= $errorRegistro ?></div>
            <?php endif; ?>

            <?php if ($exitoRegistro): ?>
                <div class="alert-message alert-success"><?= $exitoRegistro ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="accion" value="registro">

                <div class="input-group"><input type="text" name="Nombre" required placeholder=" "><label>Nombre</label></div>
                <div class="input-group"><input type="text" name="ApellidoP" required placeholder=" "><label>Apellido paterno</label></div>
                <div class="input-group"><input type="text" name="ApellidoM" required placeholder=" "><label>Apellido materno</label></div>
                <div class="input-group"><input type="email" name="EmailR" required placeholder=" "><label>Email</label></div>
                <div class="input-group"><input type="text" name="UsuarioR" required placeholder=" "><label>Usuario</label></div>
                <div class="input-group"><input type="password" name="ClaveR" required placeholder=" "><label>Contraseña</label></div>

                <button class="Acceder">Registrarme</button>
            </form>

            <div class="switch" onclick="cambiarLogin()">← Volver a iniciar sesión</div>
        </div>
        <?php endif; ?>

    </div>
</div>
</main>

<footer>
<p>&copy; 2025 Diamonds Corporation</p>
</footer>

<script>
function cambiarRegistro(){
    window.location.href = "?registro=1";
}
function cambiarLogin(){
    window.location.href = "Login.php";
}
</script>

</body>
</html>
