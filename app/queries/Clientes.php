<?php

function obtenerNotificacionesNoLeidas(mysqli $conn): int {
    $sql = "SELECT COUNT(*) total FROM Notificaciones WHERE Leida = 0";
    $res = $conn->query($sql);
    return (int)($res->fetch_assoc()['total'] ?? 0);
}

function obtenerClientes(mysqli $conn): mysqli_result {
    $sql = "SELECT * FROM VistaClientes WHERE idCliente != 1";
    return $conn->query($sql);
}
