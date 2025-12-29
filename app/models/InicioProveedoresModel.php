<?php
class ProveedorCatalogoModel {

    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /* ================= CLIENTES ================= */
    public function obtenerClientesActivos(): array {
        $clientes = [];
        $res = $this->conn->query("
            SELECT *
            FROM VistaClientes
            WHERE Estatus = 'Activo'
              AND TRIM(LOWER(CONCAT(Nombre,' ',IFNULL(Paterno,''))))
                  NOT LIKE '%cliente gener%'
        ");
        while ($row = $res->fetch_assoc()) {
            $clientes[] = $row;
        }
        return $clientes;
    }

    /* ================= CARRITO ================= */
    public function obtenerOCrearCarrito(int $idUsuario): int {
        $res = $this->conn->query("
            SELECT idCarrito
            FROM Carrito
            WHERE idUsuario = $idUsuario
            ORDER BY Fecha DESC
            LIMIT 1
        ");

        if ($res->num_rows > 0) {
            return (int)$res->fetch_assoc()['idCarrito'];
        }

        $stmt = $this->conn->prepare("INSERT INTO Carrito (idUsuario) VALUES (?)");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return (int)$this->conn->insert_id;
    }

    public function agregarAlCarrito(int $idCarrito, int $idProducto, int $cantidad): bool {
        try {
            $stmt = $this->conn->prepare("CALL AgregarAlCarrito(?,?,?)");
            $stmt->bind_param("iii", $idCarrito, $idProducto, $cantidad);
            $stmt->execute();

            // Limpiar resultados
            do {
                if ($stmt->get_result()) $stmt->get_result()->free();
            } while ($stmt->more_results() && $stmt->next_result());

            $stmt->close();
            return true;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    public function contarProductosCarrito(int $idUsuario): int {
        $res = $this->conn->query("
            SELECT COUNT(DISTINCT dc.idProducto) total
            FROM DetalleCarrito dc
            JOIN Carrito c ON dc.idCarrito = c.idCarrito
            WHERE c.idUsuario = $idUsuario
        ");
        return (int)$res->fetch_assoc()['total'];
    }

    /* ================= PRODUCTOS ================= */
    public function obtenerProductos(): array {
        $productos = [];
        $res = $this->conn->query("
            SELECT *
            FROM VistaProductos
            WHERE Producto <> 'AbonosCreditos'
        ");
        while ($row = $res->fetch_assoc()) {
            $productos[] = $row;
        }
        return $productos;
    }
}
?>