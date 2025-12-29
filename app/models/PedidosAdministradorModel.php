<?php
class PedidoModel {

    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /* ================= PEDIDOS ================= */

    public function obtenerPedidos($fecha, $estatus, $cliente) {

        $sql = "SELECT * FROM VistaPedidos WHERE 1=1";
        $params = [];
        $types  = "";

        if ($fecha) {
            $sql .= " AND DATE(Fecha) = ?";
            $params[] = $fecha;
            $types .= "s";
        }

        if ($estatus) {
            $sql .= " AND Estado = ?";
            $params[] = $estatus;
            $types .= "s";
        }

        if ($cliente) {
            $sql .= " AND Cliente = ?";
            $params[] = $cliente;
            $types .= "s";
        }

        $sql .= " ORDER BY Fecha DESC, idPedido DESC";

        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $pedidos = [];
        $clientes = [];

        while ($row = $result->fetch_assoc()) {
            $clientes[$row['Cliente']] = $row['Cliente'];
            $id = $row['idPedido'];

            if (!isset($pedidos[$id])) {
                $pedidos[$id] = [
                    'idPedido' => $id,
                    'Fecha'    => $row['Fecha'],
                    'Hora'     => $row['Hora'],
                    'Estado'   => $row['Estado'],
                    'Cliente'  => $row['Cliente'],
                    'productos'=> []
                ];
            }

            $pedidos[$id]['productos'][] = [
                'Producto' => $row['Producto'],
                'Cantidad' => $row['Cantidad'],
                'Precio'   => $row['PrecioUnitario'],
                'Subtotal' => $row['Subtotal']
            ];
        }

        return [
            'pedidos'  => $pedidos,
            'clientes' => $clientes
        ];
    }

    /* ================= ACCIONES ================= */

    public function prepararPedido($idPedido, $idUsuario) {
        $stmt = $this->conn->prepare("CALL PrepararPedido(?, ?)");
        $stmt->bind_param("ii", $idPedido, $idUsuario);
        return $stmt->execute();
    }

    public function cancelarPedido($idPedido, $idUsuario) {
        $stmt = $this->conn->prepare("CALL CancelarPedido(?, ?)");
        $stmt->bind_param("ii", $idPedido, $idUsuario);
        return $stmt->execute();
    }

    public function cobrarPedido($idPedido) {
        $stmt = $this->conn->prepare("CALL ProcesarPedidoComoVenta(?)");
        $stmt->bind_param("i", $idPedido);
        return $stmt->execute();
    }

    /* ================= NOTIFICACIONES ================= */

    public function contarNotificaciones() {
        $res = $this->conn->query(
            "SELECT COUNT(*) total FROM Notificaciones WHERE Leida = 0"
        );
        return $res->fetch_assoc()['total'] ?? 0;
    }
}
?>