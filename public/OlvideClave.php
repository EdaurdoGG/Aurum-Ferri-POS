<?php
session_start();

/* ================= CONEXIÓN ================= */
$conn = new mysqli("db", "root", "clave", "HerreriaUG");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['verificar'])) {
        $usuario = trim($_POST['Usuario']);
        $email   = trim($_POST['Email']);
        $telefono = trim($_POST['Telefono']);

        if (!$usuario || !$email || !$telefono) {
            $error = "Todos los campos son obligatorios.";
        } else {
            $stmt = $conn->prepare("
                SELECT idUsuario, Usuario, Telefono 
                FROM VistaUsuarios 
                WHERE Usuario=? AND Email=? AND RIGHT(Telefono,4)=?
            ");
            $stmt->bind_param("sss", $usuario, $email, $telefono);
            $stmt->execute();
            $res = $stmt->get_result();
            $usuarioEncontrado = $res->fetch_assoc();
            $stmt->close();

            if ($usuarioEncontrado) {
                $_SESSION['recuperar_id'] = $usuarioEncontrado['idUsuario'];
                $_SESSION['recuperar_usuario'] = $usuarioEncontrado['Usuario'];
                $success = "Usuario verificado. Puedes cambiar tu contraseña.";
            } else {
                $error = "No se encontró coincidencia con los datos ingresados.";
            }
        }
    }

    if (isset($_POST['cambiar_contra'])) {
        if (!isset($_SESSION['recuperar_id'])) {
            $error = "Sesión expirada. Verifica tus datos nuevamente.";
        } else {
            $nuevaContra = $_POST['NuevaClave'];
            $confirmarContra = $_POST['ConfirmarClave'];

            if (!$nuevaContra || !$confirmarContra) {
                $error = "Ambos campos de contraseña son obligatorios.";
            } elseif ($nuevaContra !== $confirmarContra) {
                $error = "Las contraseñas no coinciden.";
            } else {
                $hash = password_hash($nuevaContra, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE Usuarios SET Contrasena=? WHERE idUsuario=?");
                $stmt->bind_param("si", $hash, $_SESSION['recuperar_id']);
                if ($stmt->execute()) {
                    $success = "Contraseña cambiada correctamente. Ahora puedes iniciar sesión.";
                    unset($_SESSION['recuperar_id']);
                    unset($_SESSION['recuperar_usuario']);
                } else {
                    $error = "Error al actualizar la contraseña.";
                }
                $stmt->close();
            }
        }
    }
}
?>

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
