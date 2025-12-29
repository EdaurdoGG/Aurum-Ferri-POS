<?php

class CarritoProveedoresModel {

    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /* ================= LIMPIAR RESULTADOS ================= */
    private function limpiarResultados() {
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }
    }

    /* ================= CONTADOR CARRITO ================= */
    public function contarProductosCarrito(int $idUsuario): int {

        $res = $this->conn->query("
            SELECT COUNT(DISTINCT dc.idProducto) total
            FROM DetalleCarrito dc
            INNER JOIN Carrito c ON dc.idCarrito = c.idCarrito
            WHERE c.idUsuario = $idUsuario
        ");

        return (int)$res->fetch_assoc()['total'];
    }

    /* ================= OBTENER CARRITO ================= */
    public function obtenerCarrito(int $idUsuario): array {

        $carrito = [];
        $this->limpiarResultados();

        $stmt = $this->conn->prepare("CALL ObtenerCarritoUsuario(?)");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $carrito[] = $row;
        }

        $stmt->close();
        $this->limpiarResultados();

        return $carrito;
    }

    /* ================= IMÁGENES ================= */
    public function obtenerImagenesProductos(): array {

        $imagenes = [];
        $res = $this->conn->query("SELECT idProducto, Imagen FROM VistaProductos");

        while ($img = $res->fetch_assoc()) {
            $imagenes[$img['idProducto']] = $img['Imagen'];
        }

        return $imagenes;
    }

    /* ================= ACCIONES CARRITO ================= */
    public function sumarProducto(int $idCarrito, int $idProducto) {
        $this->limpiarResultados();
        $stmt = $this->conn->prepare("CALL SumarCantidadCarrito(?,?)");
        $stmt->bind_param("ii", $idCarrito, $idProducto);
        $stmt->execute();
        $stmt->close();
        $this->limpiarResultados();
    }

    public function restarProducto(int $idCarrito, int $idProducto) {
        $this->limpiarResultados();
        $stmt = $this->conn->prepare("CALL RestarCantidadCarrito(?,?)");
        $stmt->bind_param("ii", $idCarrito, $idProducto);
        $stmt->execute();
        $stmt->close();
        $this->limpiarResultados();
    }

    public function actualizarCantidad(int $idCarrito, int $idProducto, int $cantidad) {
        $this->limpiarResultados();
        $stmt = $this->conn->prepare("CALL ActualizarCantidadCarrito(?,?,?)");
        $stmt->bind_param("iii", $idCarrito, $idProducto, $cantidad);
        $stmt->execute();
        $stmt->close();
        $this->limpiarResultados();
    }

    /* ================= GENERAR PEDIDO ================= */
    public function generarPedido(
        int $idCliente,
        int $idUsuario,
        array $carrito
    ) {

        $productos = [];
        foreach ($carrito as $c) {
            $productos[] = [
                "idProducto"     => (int)$c['idProducto'],
                "Cantidad"       => (int)$c['Cantidad'],
                "PrecioProducto" => (float)$c['Precio']
            ];
        }

        $json = json_encode($productos, JSON_UNESCAPED_UNICODE);

        $this->limpiarResultados();
        $stmt = $this->conn->prepare("CALL AgregarPedido(?,?,?)");
        $stmt->bind_param("iis", $idCliente, $idUsuario, $json);
        $stmt->execute();
        $stmt->close();
        $this->limpiarResultados();
    }
}
?>