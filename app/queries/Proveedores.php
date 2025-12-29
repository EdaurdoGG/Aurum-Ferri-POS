<?php

function contarNotificacionesNoLeidas(mysqli $conn): int {
    $res = $conn->query("
        SELECT COUNT(*) AS total
        FROM Notificaciones
        WHERE Leida = 0
    ");
    return (int)($res->fetch_assoc()['total'] ?? 0);
}

function obtenerProveedores(mysqli $conn): mysqli_result {
    return $conn->query("SELECT * FROM VistaProveedores");
}

?>