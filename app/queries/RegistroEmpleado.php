<?php

function registrarEmpleado(
    mysqli $conn,
    string $nombre,
    string $apellidoP,
    string $apellidoM,
    string $telefono,
    string $email,
    string $usuario,
    string $clave,
    int $idRol
) {

    $passwordHash = password_hash($clave, PASSWORD_DEFAULT);
    $imagen = "default.jpg";
    $estatus = "Activo";

    $sql = "CALL AgregarUsuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error al preparar procedimiento");
    }

    $stmt->bind_param(
        "sssssssssi",
        $nombre,
        $apellidoP,
        $apellidoM,
        $telefono,
        $email,
        $imagen,
        $estatus,
        $usuario,
        $passwordHash,
        $idRol
    );

    if (!$stmt->execute()) {
        throw new Exception("Error al registrar empleado");
    }

    $stmt->close();
}
