<?php
class ProveedorModel {

    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function obtenerProveedorPorId(int $idProveedor): ?array {
        $stmt = $this->conn->prepare("
            SELECT 
                prov.idProveedor,
                per.Nombre,
                per.Paterno,
                per.Materno,
                per.Telefono,
                per.Email,
                per.Imagen,
                per.Estatus,
                prov.Estado
            FROM Proveedores prov
            INNER JOIN Personas per ON prov.idPersona = per.idPersona
            WHERE prov.idProveedor = ?
        ");
        $stmt->bind_param("i", $idProveedor);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $res ?: null;
    }

    public function editarProveedor(
        int $idProveedor,
        string $nombre,
        string $paterno,
        string $materno,
        string $telefono,
        string $email,
        ?string $imagen,
        string $estatusPersona,
        string $estadoProveedor
    ): void {
        $stmt = $this->conn->prepare("CALL EditarProveedor(?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param(
            "issssssss",
            $idProveedor,
            $nombre,
            $paterno,
            $materno,
            $telefono,
            $email,
            $imagen,
            $estatusPersona,
            $estadoProveedor
        );
        $stmt->execute();

        while ($this->conn->more_results() && $this->conn->next_result()) {}
        $stmt->close();
    }
}
?>