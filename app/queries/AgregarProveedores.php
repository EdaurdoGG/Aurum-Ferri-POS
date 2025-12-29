<?php

function agregarProveedor(
    mysqli $conn,
    string $nombre,
    string $paterno,
    string $materno,
    string $telefono,
    string $email,
    ?string $imagen,
    string $estatusPersona,
    string $estadoProveedor
): bool {

    $stmt = $conn->prepare("CALL AgregarProveedor(?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) return false;

    $stmt->bind_param(
        "ssssssss",
        $nombre,
        $paterno,
        $materno,
        $telefono,
        $email,
        $imagen,
        $estatusPersona,
        $estadoProveedor
    );

    $ok = $stmt->execute();
    $stmt->close();

    return $ok;
}
?>