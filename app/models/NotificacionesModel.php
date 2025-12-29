<?php
class NotificacionesModel {

    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /* ================= OBTENER NOTIFICACIONES ================= */
    public function obtenerNotificaciones(): mysqli_result {
        return $this->conn->query("
            SELECT *
            FROM VistaNotificaciones
            ORDER BY Fecha DESC
        ");
    }

    /* ================= MARCAR COMO LEÍDA ================= */
    public function marcarComoLeida(int $idNotificacion): bool {
        return $this->conn->query("
            UPDATE Notificaciones
            SET Leida = 1
            WHERE idNotificacion = $idNotificacion
        ");
    }
}
?>