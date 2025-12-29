<?php

class EmpleadoModel {

    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /* ================= NOTIFICACIONES ================= */
    public function obtenerNotificacionesNoLeidas(): int {
        $res = $this->conn->query("
            SELECT COUNT(*) total
            FROM Notificaciones
            WHERE Leida = 0
        ");
        return $res->fetch_assoc()['total'] ?? 0;
    }

    /* ================= EMPLEADOS ================= */
    public function obtenerEmpleados(): mysqli_result {
        return $this->conn->query("SELECT * FROM VistaUsuarios");
    }
}
?>