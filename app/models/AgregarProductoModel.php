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
            SELECT idProveedor, CONCAT(Nombre, ' ', Paterno, ' ', Materno) AS NombreCompleto 
            FROM VistaProveedores
        ");
        while ($row = $res->fetch_assoc()) {
            $proveedores[] = $row;
        }
        return $proveedores;
    }

    public function agregarProducto(
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
        $stmt = $this->conn->prepare("CALL AgregarProducto(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) return false;

        $stmt->bind_param(
            "ssddiisii",
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
