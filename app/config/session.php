<?php
// Evitar warnings si la sesión ya está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si el usuario tiene el rol permitido
 * @param array|int $rolesPermitidos
 */
function requireRole($rolesPermitidos) {
    if (!isset($_SESSION['id'], $_SESSION['rol'])) {
        cerrarSesion();
    }

    if (!is_array($rolesPermitidos)) {
        $rolesPermitidos = [$rolesPermitidos];
    }

    if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
        cerrarSesion();
    }
}

function cerrarSesion() {
    session_unset();
    session_destroy();
    header("Location: /../public/Login.php");
    exit();
}
