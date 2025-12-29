<?php
class VentaModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /* ================= NOTIFICACIONES ================= */
    public function notificacionesNoLeidas() {
        $res = $this->conn->query(
            "SELECT COUNT(*) total FROM Notificaciones WHERE Leida = 0"
        );
        return $res->fetch_assoc()['total'] ?? 0;
    }

    /* ================= CLIENTES ================= */
    public function obtenerClientes() {
        $res = $this->conn->query("
            SELECT c.idCliente id,
                   CONCAT(p.Nombre,' ',p.Paterno,' ',p.Materno) Nombre
            FROM Clientes c
            JOIN Personas p ON p.idPersona = c.idPersona
            WHERE p.Estatus='Activo'
        ");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    /* ================= CARRITO ================= */
    public function obtenerOCrearCarrito($idUsuario) {
        $stmt = $this->conn->prepare(
            "SELECT idCarrito FROM Carrito WHERE idUsuario=? ORDER BY Fecha DESC LIMIT 1"
        );
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) return $row['idCarrito'];

        $stmt = $this->conn->prepare("INSERT INTO Carrito (idUsuario) VALUES (?)");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function obtenerCarrito($idUsuario) {
        $productos = [];
        $stmt = $this->conn->prepare("CALL ObtenerCarritoUsuario(?)");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) $productos[] = $r;
        $stmt->close();
        $this->conn->next_result();
        return $productos;
    }
}
?>