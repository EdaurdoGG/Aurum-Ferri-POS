<?php
class AuditoriaModel {

    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /**
     * Obtener registros de auditoría con filtros
     */
    public function obtenerAuditoria(?string $fecha, ?string $modulo) {

        $sql = "SELECT * FROM VistaAuditoria WHERE 1=1";
        $params = [];
        $tipos  = "";

        if (!empty($fecha)) {
            $sql .= " AND DATE(Fecha) = ?";
            $params[] = $fecha;
            $tipos .= "s";
        }

        if (!empty($modulo)) {
            $sql .= " AND Tabla = ?";
            $params[] = $modulo;
            $tipos .= "s";
        }

        $sql .= " ORDER BY Fecha DESC";

        $stmt = $this->conn->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($tipos, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Contar notificaciones no leídas
     */
    public function contarNotificacionesNoLeidas(): int {

        $res = $this->conn->query("
            SELECT COUNT(*) total
            FROM Notificaciones
            WHERE Leida = 0
        ");

        return $res ? (int)$res->fetch_assoc()['total'] : 0;
    }
}
?>