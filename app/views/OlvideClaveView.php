<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Recuperar Contraseña</title>
<link rel="stylesheet" href="assets/css/OlvideClave.css">
<link rel="icon" href="assets/icons/Logo.png">
</head>
<body>
<main>
    <div class="centro">
        <div class="log">
            <div class="login">
                <div class="titulo">
                    <h2>Recuperar Contraseña</h2>
                    <a href="Login.php">
                        <img src="assets/icons/Volver.png" alt="Botón Atrás" class="boton-atras" />
                    </a>
                </div>

                <?php if($error): ?>
                    <div class="alert-message alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if($success): ?>
                    <div class="alert-message alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <?php if(!isset($_SESSION['recuperar_id'])): ?>
                    <form method="POST">
                        <div class="input-group">
                            <input type="text" name="Usuario" placeholder=" " required>
                            <label>Usuario</label>
                        </div>
                        <div class="input-group">
                            <input type="email" name="Email" placeholder=" " required>
                            <label>Correo electrónico</label>
                        </div>
                        <div class="input-group">
                            <input type="text" name="Telefono" placeholder=" " required maxlength="4">
                            <label>Últimos 4 dígitos del teléfono</label>
                        </div>
                        <button type="submit" name="verificar" class="Acceder">Verificar</button>
                    </form>
                <?php else: ?>
                    <div class="modal" id="modal">
                        <div class="modal-contenido">
                            <span class="cerrar" onclick="document.getElementById('modal').style.display='none';">&times;</span>
                            <h3>Cambiar contraseña de <?= htmlspecialchars($_SESSION['recuperar_usuario']) ?></h3>
                            <form method="POST">
                                <input type="password" name="NuevaClave" placeholder="Nueva contraseña" required>
                                <input type="password" name="ConfirmarClave" placeholder="Confirmar contraseña" required>
                                <button type="submit" name="cambiar_contra" class="Acceder">Actualizar Contraseña</button>
                            </form>
                        </div>
                    </div>
                    <script>
                        document.getElementById('modal').style.display = 'flex';
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
</body>
</html>
