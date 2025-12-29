<?php

function agregarCliente(
    mysqli $conn,
    string $nombre,
    string $paterno,
    string $materno,
    string $telefono,
    string $email,
    string $imagen,
    string $estatus,
    float $credito,
    float $limite
): bool {

    $sql = "CALL AgregarCliente(?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) return false;

    $stmt->bind_param(
        "sssssssdd",
        $nombre,
        $paterno,
        $materno,
        $telefono,
        $email,
        $imagen,
        $estatus,
        $credito,
        $limite
    );

    $ok = $stmt->execute();
    $stmt->close();

    return $ok;
}
