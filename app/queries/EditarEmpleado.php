<?php

function obtenerEmpleadoPorId(mysqli $conn, int $idUsuario) {
    $stmt = $conn->prepare(
        "SELECT u.Usuario,u.idRol,p.Nombre,p.Paterno,p.Materno,
                p.Telefono,p.Email,p.Imagen,p.Estatus
         FROM Usuarios u
         JOIN Personas p ON u.idPersona=p.idPersona
         WHERE u.idUsuario=?"
    );
    $stmt->bind_param("i",$idUsuario);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function obtenerImagenUsuario(mysqli $conn, int $idUsuario): string {
    $stmt = $conn->prepare(
        "SELECT p.Imagen FROM Usuarios u
         JOIN Personas p ON u.idPersona=p.idPersona
         WHERE u.idUsuario=?"
    );
    $stmt->bind_param("i",$idUsuario);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['Imagen'] ?? '';
}

function subirImagenUsuario(array $file, string $ruta): string {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg','jpeg','png','gif'];

    if (!in_array($ext, $permitidas)) {
        throw new Exception("Formato de imagen no permitido");
    }

    $nombre = uniqid() . "." . $ext;
    if (!move_uploaded_file($file['tmp_name'], $ruta . $nombre)) {
        throw new Exception("Error al subir imagen");
    }

    return "Imagenes/Usuarios/" . $nombre;
}

function editarEmpleado(
    mysqli $conn,
    int $idUsuario,
    string $nombre,
    string $paterno,
    string $materno,
    string $telefono,
    string $email,
    string $imagen,
    string $estatus,
    string $usuario,
    int $idRol
){
    $stmt = $conn->prepare("CALL EditarUsuario(?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param(
        "issssssssi",
        $idUsuario,
        $nombre,
        $paterno,
        $materno,
        $telefono,
        $email,
        $imagen,
        $estatus,
        $usuario,
        $idRol
    );
    $stmt->execute();
}
