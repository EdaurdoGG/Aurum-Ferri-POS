<?php

class ClientesModel {

    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /* ================= NOTIFICACIONES ================= */
    public function obtenerNotificacionesNoLeidas(): int {
        $sql = "SELECT COUNT(*) total FROM Notificaciones WHERE Leida = 0";
        $res = $this->conn->query($sql);
        return (int)($res->fetch_assoc()['total'] ?? 0);
    }

    /* ================= CLIENTES ================= */
    public function obtenerClientes(): mysqli_result {
        $sql = "SELECT * FROM VistaClientes WHERE idCliente != 1";
        return $this->conn->query($sql);
    }
}
?>