<?php
class VentaCajeroModel {

    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /* ================= CLIENTES ================= */
    public function obtenerClientesActivos(): array {
        $res = $this->conn->query("
            SELECT c.idCliente id,
                   CONCAT(p.Nombre,' ',p.Paterno,' ',p.Materno) Nombre
            FROM Clientes c
            JOIN Personas p ON p.idPersona = c.idPersona
            WHERE p.Estatus = 'Activo'
        ");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    /* ================= CARRITO ================= */
    public function obtenerOCrearCarrito(int $idUsuario): int {
        $stmt = $this->conn->prepare(
            "SELECT idCarrito FROM Carrito WHERE idUsuario=? ORDER BY Fecha DESC LIMIT 1"
        );
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            return (int)$row['idCarrito'];
        }

        $stmt = $this->conn->prepare("INSERT INTO Carrito (idUsuario) VALUES (?)");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return (int)$stmt->insert_id;
    }

    public function obtenerCarritoUsuario(int $idUsuario): array {
        $items = [];
        $stmt = $this->conn->prepare("CALL ObtenerCarritoUsuario(?)");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $items[] = $row;
        }

        $stmt->close();
        $this->conn->next_result();
        return $items;
    }

    /* ================= PRODUCTOS ================= */
    public function buscarProducto(string $termino): ?array {

        // Código de barras
        $stmt = $this->conn->prepare("CALL BuscarProductoPorCodigoBarra(?)");
        $stmt->bind_param("s", $termino);
        $stmt->execute();
        $producto = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $this->conn->next_result();

        if ($producto) return $producto;

        // Nombre
        $stmt = $this->conn->prepare("CALL BuscarProductoPorNombre(?)");
        $stmt->bind_param("s", $termino);
        $stmt->execute();
        $producto = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $this->conn->next_result();

        return $producto ?: null;
    }

    public function agregarProducto(int $idCarrito, int $idProducto): bool {
        $stmt = $this->conn->prepare("CALL AgregarAlCarrito(?, ?, 1)");
        $ok = $stmt->bind_param("ii", $idCarrito, $idProducto) && $stmt->execute();
        $stmt->close();
        $this->conn->next_result();
        return $ok;
    }

    /* ================= CANTIDADES ================= */
    public function actualizarCantidad(int $idCarrito, int $idProducto, int $cantidad): bool {
        $stmt = $this->conn->prepare("CALL ActualizarCantidadCarrito(?, ?, ?)");
        $ok = $stmt->bind_param("iii", $idCarrito, $idProducto, $cantidad) && $stmt->execute();
        $stmt->close();
        $this->conn->next_result();
        return $ok;
    }

    public function sumarCantidad(int $idCarrito, int $idProducto): bool {
        $stmt = $this->conn->prepare("CALL SumarCantidadCarrito(?, ?)");
        $ok = $stmt->bind_param("ii", $idCarrito, $idProducto) && $stmt->execute();
        $stmt->close();
        $this->conn->next_result();
        return $ok;
    }

    public function restarCantidad(int $idCarrito, int $idProducto): bool {
        $stmt = $this->conn->prepare("CALL RestarCantidadCarrito(?, ?)");
        $ok = $stmt->bind_param("ii", $idCarrito, $idProducto) && $stmt->execute();
        $stmt->close();
        $this->conn->next_result();
        return $ok;
    }

    /* ================= PROCESAR VENTA ================= */
    public function procesarVenta(int $idUsuario, int $idCliente, int $credito): bool {
        $stmt = $this->conn->prepare("CALL ProcesarVentaDesdeCarrito(?, ?, ?)");
        $ok = $stmt && $stmt->bind_param("iii", $idUsuario, $idCliente, $credito) && $stmt->execute();
        if ($stmt) {
            $stmt->close();
            $this->conn->next_result();
        }
        return $ok;
    }
}
