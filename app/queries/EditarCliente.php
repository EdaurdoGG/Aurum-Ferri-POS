<?php

function obtenerClientePorId(mysqli $conn, int $idCliente): ?array {
    $sql = "SELECT c.idCliente, c.Credito, c.Limite,
                   p.Nombre, p.Paterno, p.Materno, p.Telefono, p.Email, p.Imagen, p.Estatus
            FROM Clientes c
            JOIN Personas p ON c.idPersona = p.idPersona
            WHERE c.idCliente = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return null;

    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $cliente = $resultado->fetch_assoc();
    $stmt->close();

    return $cliente ?: null;
}

function editarCliente(
    mysqli $conn,
    int $idCliente,
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
    $sql = "CALL EditarCliente(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param(
        "isssssssdd",
        $idCliente,
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
?>