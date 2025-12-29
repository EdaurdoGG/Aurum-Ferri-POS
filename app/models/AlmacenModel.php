<?php
class AlmacenModel {

    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /**
     * Obtener productos (excepto AbonosCreditos)
     * Retorna:
     * - productos
     * - categorias
     * - proveedores
     */
    public function obtenerInventario(): array {

        $sql = "
            SELECT *
            FROM VistaProductos
            WHERE Producto <> 'AbonosCreditos'
            ORDER BY Producto
        ";

        $res = $this->conn->query($sql);

        $productos   = [];
        $categorias  = [];
        $proveedores = [];

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $productos[] = $row;
                $categorias[$row['Categoria']]   = true;
                $proveedores[$row['Proveedor']] = true;
            }
        }

        return [
            'productos'   => $productos,
            'categorias'  => $categorias,
            'proveedores' => $proveedores
        ];
    }

    /**
     * Notificaciones no leídas
     */
    public function contarNotificacionesNoLeidas(): int {

        $res = $this->conn->query("
            SELECT COUNT(*) total
            FROM Notificaciones
            WHERE Leida = 0
        ");

        return $res ? (int)$res->fetch_assoc()['total'] : 0;
    }
}
