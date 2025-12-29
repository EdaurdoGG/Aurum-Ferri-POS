<?php

class VentasModel {

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

    /* ================= EMPLEADOS ================= */
    public function obtenerEmpleados(): array {
        $empleados = [];

        $sql = "
            SELECT idUsuario, CONCAT(Nombre,' ',Paterno,' ',Materno) AS Empleado
            FROM VistaUsuarios
            WHERE Rol <> 3
            ORDER BY Empleado
        ";
        $res = $this->conn->query($sql);

        while ($row = $res->fetch_assoc()) {
            $empleados[$row['idUsuario']] = $row['Empleado'];
        }

        return $empleados;
    }

    /* ================= VENTAS ================= */
    public function obtenerVentas(string $fecha, ?int $idEmpleado): array {

        $sql = "SELECT * FROM VistaVentas WHERE DATE(Fecha) = ?";
        $params = [$fecha];
        $tipos  = "s";

        if ($idEmpleado) {
            $sql .= " AND idUsuario = ?";
            $params[] = $idEmpleado;
            $tipos   .= "i";
        }

        $sql .= " ORDER BY Empleado ASC, Fecha ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($tipos, ...$params);
        $stmt->execute();
        $res = $stmt->get_result();

        $ventas = [];
        while ($row = $res->fetch_assoc()) {
            $ventas[] = $row;
        }

        return $ventas;
    }
}
?>