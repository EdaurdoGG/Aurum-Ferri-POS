<?php

/* ================= LIMPIAR RESULTADOS ================= */
function limpiarResultados(mysqli $conn) {
    while ($conn->more_results() && $conn->next_result()) {
        $conn->store_result();
    }
}

/* ================= OBTENER CATEGORÍA ================= */
function obtenerCategoria(mysqli $conn, int $idCategoria): array {
    $stmt = $conn->prepare("
        SELECT idCategoria, Nombre
        FROM Categorias
        WHERE idCategoria = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $idCategoria);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        throw new Exception("La categoría no existe");
    }

    return $res->fetch_assoc();
}

/* ================= EDITAR CATEGORÍA (SP) ================= */
function editarCategoria(mysqli $conn, int $idCategoria, string $nombre): void {

    limpiarResultados($conn);

    $stmt = $conn->prepare("CALL EditarCategoria(?, ?)");
    if (!$stmt) {
        throw new Exception("Error al preparar el procedimiento");
    }

    $stmt->bind_param("is", $idCategoria, $nombre);
    $stmt->execute();
    $stmt->close();

    limpiarResultados($conn);
}
?>