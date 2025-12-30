<?php
// ===== Inicializar variables para evitar errores =====
$modo = $modo ?? 'login-form'; // valor por defecto
$errorLogin = $errorLogin ?? '';
$errorRegistro = $errorRegistro ?? '';
$exitoRegistro = $exitoRegistro ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Acceso</title>
<link rel="stylesheet" href="assets/css/Login.css">
<link rel="icon" href="assets/icons/Logo.png">

<style>
/* Aseguramos que solo el formulario activo se vea */
.form-box { display: none; }
.form-box.active { display: block; }
</style>
</head>

<body>

<main>
<div class="login">

    <!-- ===== PANEL LOGO (SOLO IMAGEN) ===== -->
    <div class="panel panel-logo">
        <div>
            <img src="assets/icons/Logo.png" alt="Logo">
        </div>
    </div>

    <!-- ===== PANEL FORM ===== -->
    <div class="panel panel-form">

        <!-- ===== LOGIN ===== -->
        <div id="loginForm" class="form-box <?= $modo === 'login-form' ? 'active' : '' ?>">
            <div class="titulo"><h2>Iniciar sesión</h2></div>

            <?php if ($errorLogin): ?>
                <div class="alert-message alert-error"><?= htmlspecialchars($errorLogin) ?></div>
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

            <div class="switch" onclick="mostrarRegistro()">¿No tienes cuenta? Regístrate</div>
            
            <div class="switch" style="margin-top:10px;">
                <a href="OlvideClave.php" style="text-decoration:none; color:#005bf7;">¿Olvidaste tu contraseña?</a>
            </div>
        </div>

        <!-- ===== REGISTRO ===== -->
        <div id="registerForm" class="form-box <?= $modo === 'register-form' ? 'active' : '' ?>">
            <div class="titulo"><h2>Registro</h2></div>

            <?php if ($errorRegistro): ?>
                <div class="alert-message alert-error"><?= htmlspecialchars($errorRegistro) ?></div>
            <?php endif; ?>

            <?php if ($exitoRegistro): ?>
                <div class="alert-message alert-success"><?= htmlspecialchars($exitoRegistro) ?></div>
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

            <div class="switch" onclick="mostrarLogin()">← Volver a iniciar sesión</div>
        </div>

    </div>
</div>
</main>

<footer>
<p>&copy; 2025 Diamonds Corporation</p>
</footer>

<script>
// Cambiar entre login y registro sin recargar
function mostrarRegistro() {
    document.getElementById('loginForm').classList.remove('active');
    document.getElementById('registerForm').classList.add('active');
}

function mostrarLogin() {
    document.getElementById('registerForm').classList.remove('active');
    document.getElementById('loginForm').classList.add('active');
}
</script>

</body>
</html>
