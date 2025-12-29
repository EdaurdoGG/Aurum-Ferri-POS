<?php

class CategoriasModel {

    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    /* ================= CATEGORÍAS ================= */
    public function obtenerCategorias(): array {
        $categorias = [];

        $sql = "SELECT * FROM VistaCategorias";
        $res = $this->conn->query($sql);

        while ($row = $res->fetch_assoc()) {
            $categorias[] = $row;
        }

        return $categorias;
    }

    /* ================= NOTIFICACIONES ================= */
    public function contarNotificacionesNoLeidas(): int {
        $res = $this->conn->query("
            SELECT COUNT(*) AS total
            FROM Notificaciones
            WHERE Leida = 0
        ");
        return (int)($res->fetch_assoc()['total'] ?? 0);
    }
}
?>