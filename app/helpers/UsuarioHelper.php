<?php
/**
 * Inicializa variables del usuario autenticado
 * y configura la variable SQL @usuario_actual
 *
 * Requiere:
 * - $_SESSION iniciada
 * - $conn (mysqli)
 */

function cargarUsuarioSesion(mysqli $conn, string $rolPorDefecto = 'Usuario'): array
{
    if (!isset($_SESSION['id'])) {
        throw new Exception('Sesión no iniciada.');
    }

    $idUsuario = $_SESSION['id'];

    // Variables para la vista
    $usuario = [
        'idUsuario'        => $idUsuario,
        'nombreUsuario'    => $_SESSION['nombre_completo'] ?? $rolPorDefecto,
        'rolUsuarioNombre' => $_SESSION['rol_nombre'] ?? $rolPorDefecto,
        'fotoUsuario'      => $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png',
    ];

    // Variable para triggers SQL
    $conn->query("SET @usuario_actual = {$idUsuario}");

    return $usuario;
}
?>