<?php

function obtenerNotificacionesNoLeidas(mysqli $conn): int {
    $res = $conn->query("
        SELECT COUNT(*) total
        FROM Notificaciones
        WHERE Leida = 0
    ");
    return $res->fetch_assoc()['total'] ?? 0;
}

function obtenerEmpleados(mysqli $conn): mysqli_result {
    return $conn->query("SELECT * FROM VistaUsuarios");
}
