<?php

class CatalogoCajerosModel {

    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /* ================= OBTENER / CREAR CARRITO ================= */
    public function obtenerOCrearCarrito(int $idUsuario): int {

        $res = $this->conn->query("
            SELECT idCarrito 
            FROM Carrito 
            WHERE idUsuario = $idUsuario 
            ORDER BY Fecha DESC 
            LIMIT 1
        ");

        if ($res && $res->num_rows > 0) {
            return (int)$res->fetch_assoc()['idCarrito'];
        }

        $this->conn->query("INSERT INTO Carrito (idUsuario) VALUES ($idUsuario)");
        return $this->conn->insert_id;
    }

    /* ================= AGREGAR AL CARRITO ================= */
    public function agregarAlCarrito(int $idCarrito, int $idProducto, int $cantidad): bool {

        $stmt = $this->conn->prepare("CALL AgregarAlCarrito(?, ?, ?)");
        $stmt->bind_param("iii", $idCarrito, $idProducto, $cantidad);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    /* ================= PRODUCTOS ================= */
    public function obtenerProductos(): array {

        $sql = "SELECT * FROM VistaProductos WHERE Producto <> 'AbonosCreditos'";
        $res = $this->conn->query($sql);

        if (!$res) {
            throw new Exception("Error en consulta de productos");
        }

        $productos = [];
        $categorias = [];

        while ($row = $res->fetch_assoc()) {
            $productos[] = $row;
            $categorias[$row['Categoria']] = true;
        }

        return [
            'productos'  => $productos,
            'categorias' => $categorias
        ];
    }
}

?>