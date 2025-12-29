<?php
class ProveedorModel {

    private $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /**
     * Agregar proveedor usando PROCEDIMIENTO ALMACENADO
     */
    public function agregarProveedor(
        string $nombre,
        string $paterno,
        string $materno,
        string $telefono,
        string $email,
        ?string $imagen,
        string $estatusPersona,
        string $estadoProveedor
    ): array {

        $stmt = $this->conn->prepare("CALL AgregarProveedor(?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            error_log('Error prepare AgregarProveedor: ' . $this->conn->error);
            return [
                'success' => false,
                'error'   => 'Error interno al preparar la consulta'
            ];
        }

        $stmt->bind_param(
            "ssssssss",
            $nombre,
            $paterno,
            $materno,
            $telefono,
            $email,
            $imagen,
            $estatusPersona,
            $estadoProveedor
        );

        try {
            $stmt->execute();
            $stmt->close();

            // Limpiar resultados del CALL
            while ($this->conn->more_results() && $this->conn->next_result()) {;}

            return ['success' => true];

        } catch (mysqli_sql_exception $e) {

            error_log('Error SQL AgregarProveedor: ' . $e->getMessage());

            $stmt->close();
            while ($this->conn->more_results() && $this->conn->next_result()) {;}

            return [
                'success' => false,
                'error'   => $e->getMessage()
            ];
        }
    }
}
?>