<?php
class ProductosModel {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function obtenerCategorias(): array {
        $categorias = [];
        $res = $this->conn->query("SELECT idCategoria, Nombre FROM Categorias");
        while ($row = $res->fetch_assoc()) {
            $categorias[] = $row;
        }
        return $categorias;
    }

    public function obtenerProveedores(): array {
        $proveedores = [];
        $res = $this->conn->query("
            SELECT idProveedor, CONCAT(Nombre,' ',Paterno,' ',Materno) AS NombreCompleto
            FROM VistaProveedores
        ");
        while ($row = $res->fetch_assoc()) {
            $proveedores[] = $row;
        }
        return $proveedores;
    }

    public function obtenerProducto(int $idProducto): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM Productos WHERE idProducto = ?");
        $stmt->bind_param("i", $idProducto);
        $stmt->execute();
        $res = $stmt->get_result();
        $producto = $res->fetch_assoc() ?: null;
        $stmt->close();
        return $producto;
    }

    public function actualizarProducto(
        int $idProducto,
        string $nombre,
        string $codigoBarras,
        float $precioCompra,
        float $precioVenta,
        int $stock,
        int $stockMinimo,
        ?string $imagen,
        int $idCategoria,
        int $idProveedor
    ): bool {
        $stmt = $this->conn->prepare("CALL EditarProducto(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) return false;

        $stmt->bind_param(
            "issddiisii",
            $idProducto,
            $nombre,
            $codigoBarras,
            $precioCompra,
            $precioVenta,
            $stock,
            $stockMinimo,
            $imagen,
            $idCategoria,
            $idProveedor
        );

        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>
