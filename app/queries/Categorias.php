<?php
function agregarCategoria(mysqli $conn, string $nombre): bool {
    $stmt = $conn->prepare("CALL AgregarCategoria(?)");
    $stmt->bind_param("s", $nombre);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function obtenerCategoria(mysqli $conn, int $idCategoria): array {
    $stmt = $conn->prepare(
        "SELECT Nombre FROM Categorias WHERE idCategoria = ?"
    );
    $stmt->bind_param("i", $idCategoria);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        throw new Exception("La categoría no existe");
    }

    $data = $res->fetch_assoc();
    $stmt->close();
    return $data;
}

function editarCategoria(mysqli $conn, int $idCategoria, string $nombre): void {
    $stmt = $conn->prepare("CALL EditarCategoria(?, ?)");
    $stmt->bind_param("is", $idCategoria, $nombre);
    $stmt->execute();
    $stmt->close();
}
?>