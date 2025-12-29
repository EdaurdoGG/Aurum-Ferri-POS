<?php

/* ================= LIMPIAR RESULTADOS ================= */
function limpiarResultados(mysqli $conn) {
    while ($conn->more_results() && $conn->next_result()) {
        $conn->store_result();
    }
}

/* ================= OBTENER CLIENTE ================= */
function obtenerClientePorId(mysqli $conn, int $idCliente): ?array {

    $sql = "
        SELECT 
            c.idCliente,
            c.Credito,
            c.Limite,
            p.Nombre,
            p.Paterno,
            p.Materno,
            p.Telefono,
            p.Email,
            p.Imagen,
            p.Estatus
        FROM Clientes c
        JOIN Personas p ON c.idPersona = p.idPersona
        WHERE c.idCliente = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) return null;

    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $res = $stmt->get_result();
    $cliente = $res->fetch_assoc();
    $stmt->close();

    return $cliente ?: null;
}

/* ================= EDITAR CLIENTE (SP) ================= */
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
): void {

    limpiarResultados($conn);

    $stmt = $conn->prepare("CALL EditarCliente(?,?,?,?,?,?,?,?,?,?)");
    if (!$stmt) {
        throw new Exception("Error al preparar el procedimiento");
    }

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

    $stmt->execute();
    $stmt->close();

    limpiarResultados($conn);
}
?>