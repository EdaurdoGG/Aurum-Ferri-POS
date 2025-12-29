<?php

/* ================= LIMPIAR RESULTADOS ================= */
function limpiarResultados(mysqli $conn) {
    while ($conn->more_results() && $conn->next_result()) {
        $conn->store_result();
    }
}

/* ================= OBTENER EMPLEADO ================= */
function obtenerEmpleadoPorId(mysqli $conn, int $idUsuario): ?array {

    $sql = "
        SELECT 
            u.Usuario,
            u.idRol,
            p.Nombre,
            p.Paterno,
            p.Materno,
            p.Telefono,
            p.Email,
            p.Imagen,
            p.Estatus
        FROM Usuarios u
        JOIN Personas p ON u.idPersona = p.idPersona
        WHERE u.idUsuario = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) return null;

    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $res = $stmt->get_result();
    $empleado = $res->fetch_assoc();
    $stmt->close();

    return $empleado ?: null;
}

/* ================= OBTENER IMAGEN ================= */
function obtenerImagenEmpleado(mysqli $conn, int $idUsuario): string {

    $stmt = $conn->prepare(
        "SELECT p.Imagen
         FROM Usuarios u
         JOIN Personas p ON u.idPersona = p.idPersona
         WHERE u.idUsuario = ?"
    );

    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $res['Imagen'] ?? '';
}

/* ================= SUBIR IMAGEN ================= */
function subirImagenEmpleado(array $file, string $rutaServidor): string {

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg','jpeg','png','gif'];

    if (!in_array($ext, $permitidas)) {
        throw new Exception("Formato de imagen no permitido");
    }

    $nombre = uniqid('usr_') . "." . $ext;

    if (!move_uploaded_file($file['tmp_name'], $rutaServidor . $nombre)) {
        throw new Exception("Error al subir la imagen");
    }

    return "Imagenes/Usuarios/" . $nombre;
}

/* ================= EDITAR EMPLEADO (SP) ================= */
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
): void {

    limpiarResultados($conn);

    $stmt = $conn->prepare("CALL EditarUsuario(?,?,?,?,?,?,?,?,?,?)");
    if (!$stmt) {
        throw new Exception("Error al preparar el procedimiento");
    }

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
    $stmt->close();

    limpiarResultados($conn);
}
