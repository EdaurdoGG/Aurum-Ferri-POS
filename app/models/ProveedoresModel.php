<?php

class ProveedoresModel {

    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
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

    /* ================= OBTENER PROVEEDORES ================= */
    public function obtenerProveedores(): mysqli_result {
        return $this->conn->query("SELECT * FROM VistaProveedores");
    }
}
?>