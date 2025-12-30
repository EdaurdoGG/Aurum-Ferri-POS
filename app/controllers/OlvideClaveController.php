<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/OlvideClaveModel.php';

$model = new RecuperarModel($conn);

$error = '';
$success = '';

// Acción POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ===== Verificar usuario =====
    if (isset($_POST['verificar'])) {
        $usuario = trim($_POST['Usuario']);
        $email = trim($_POST['Email']);
        $telefono = trim($_POST['Telefono']);

        if (!$usuario || !$email || !$telefono) {
            $error = "Todos los campos son obligatorios.";
        } else {
            $usuarioEncontrado = $model->verificarUsuario($usuario, $email, $telefono);
            if ($usuarioEncontrado) {
                $_SESSION['recuperar_id'] = $usuarioEncontrado['idUsuario'];
                $_SESSION['recuperar_usuario'] = $usuarioEncontrado['Usuario'];
                $success = "Usuario verificado. Puedes cambiar tu contraseña.";
            } else {
                $error = "No se encontró coincidencia con los datos ingresados.";
            }
        }
    }

    // ===== Cambiar contraseña =====
    if (isset($_POST['cambiar_contra'])) {
        if (!isset($_SESSION['recuperar_id'])) {
            $error = "Sesión expirada. Verifica tus datos nuevamente.";
        } else {
            $nuevaContra = $_POST['NuevaClave'] ?? '';
            $confirmarContra = $_POST['ConfirmarClave'] ?? '';

            if (!$nuevaContra || !$confirmarContra) {
                $error = "Ambos campos de contraseña son obligatorios.";
            } elseif ($nuevaContra !== $confirmarContra) {
                $error = "Las contraseñas no coinciden.";
            } else {
                if ($model->cambiarContrasena($_SESSION['recuperar_id'], $nuevaContra)) {
                    $success = "Contraseña cambiada correctamente. Ahora puedes iniciar sesión.";
                    unset($_SESSION['recuperar_id']);
                    unset($_SESSION['recuperar_usuario']);
                } else {
                    $error = "Error al actualizar la contraseña.";
                }
            }
        }
    }
}

// Cargar la vista
require_once __DIR__ . '/../views/OlvideClaveView.php';
$conn->close();
?>
