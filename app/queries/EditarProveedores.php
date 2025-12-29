<?php

function obtenerProveedorPorId($conn, $idProveedor) {
    $stmt = $conn->prepare("
        SELECT prov.idProveedor, per.Nombre, per.Paterno, per.Materno,
               per.Telefono, per.Email, per.Imagen, per.Estatus, prov.Estado
        FROM Proveedores prov
        INNER JOIN Personas per ON prov.idPersona = per.idPersona
        WHERE prov.idProveedor = ?
    ");
    $stmt->bind_param("i", $idProveedor);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function editarProveedor(
    $conn,
    $idProveedor,
    $nombre,
    $paterno,
    $materno,
    $telefono,
    $email,
    $imagen,
    $estatusPersona,
    $estadoProveedor
) {
    $stmt = $conn->prepare("CALL EditarProveedor(?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "issssssss",
        $idProveedor,
        $nombre,
        $paterno,
        $materno,
        $telefono,
        $email,
        $imagen,
        $estatusPersona,
        $estadoProveedor
    );
    $stmt->execute();
    while ($conn->more_results() && $conn->next_result()) {}
    $stmt->close();
}
