<?php

class PedidosProveedoresModel {

    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    /* ================= CONTADOR CARRITO ================= */
    public function contarCarrito(int $idUsuario): int {
        $sql = "
            SELECT COUNT(DISTINCT dc.idProducto) total
            FROM DetalleCarrito dc
            INNER JOIN Carrito c ON dc.idCarrito = c.idCarrito
            WHERE c.idUsuario = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return (int)($stmt->get_result()->fetch_assoc()['total'] ?? 0);
    }

    /* ================= OBTENER PEDIDOS ================= */
    public function obtenerPedidos(
        int $idUsuario,
        string $fecha,
        string $estatus,
        string $cliente
    ): array {

        $sql = "SELECT * FROM VistaPedidos WHERE idUsuario = ?";
        $params = [$idUsuario];
        $types  = "i";

        if ($fecha !== '') {
            $sql .= " AND DATE(Fecha) = ?";
            $params[] = $fecha;
            $types .= "s";
        }

        if ($estatus !== '') {
            $sql .= " AND Estado = ?";
            $params[] = $estatus;
            $types .= "s";
        }

        if ($cliente !== '') {
            $sql .= " AND Cliente = ?";
            $params[] = $cliente;
            $types .= "s";
        }

        $sql .= " ORDER BY Fecha DESC, idPedido DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $pedidos  = [];
        $clientes = [];

        while ($row = $result->fetch_assoc()) {

            $clientes[$row['Cliente']] = $row['Cliente'];
            $id = $row['idPedido'];

            if (!isset($pedidos[$id])) {
                $pedidos[$id] = [
                    'idPedido'  => $id,
                    'Fecha'     => $row['Fecha'],
                    'Hora'      => $row['Hora'],
                    'Estado'    => $row['Estado'],
                    'Cliente'   => $row['Cliente'],
                    'productos' => []
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
    public function cancelarPedido(int $idPedido, int $idUsuario): void {
        $stmt = $this->conn->prepare("CALL CancelarPedido(?, ?)");
        $stmt->bind_param("ii", $idPedido, $idUsuario);
        $stmt->execute();
    }
}
?>