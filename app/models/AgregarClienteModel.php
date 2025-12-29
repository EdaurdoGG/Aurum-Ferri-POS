<?php
class ClienteModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Agregar cliente usando PROCEDIMIENTO ALMACENADO
     */
    public function agregarCliente(
        string $nombre,
        string $paterno,
        string $materno,
        string $telefono,
        string $email,
        string $imagen,
        string $estatus,
        float $credito,
        float $limite
    ): array {

        $stmt = $this->conn->prepare("CALL AgregarCliente(?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            error_log('Error prepare AgregarCliente: ' . $this->conn->error);
            return [
                'success' => false,
                'error'   => 'Error interno al preparar la consulta'
            ];
        }

        $stmt->bind_param(
            "sssssssdd",
            $nombre,
            $paterno,
            $materno,
            $telefono,
            $email,
            $imagen,
            $estatus,
            $credito,
            $limite
        );

        try {
            $stmt->execute();
            $stmt->close();

            // Limpiar resultados del CALL
            while ($this->conn->more_results() && $this->conn->next_result()) {;}

            return ['success' => true];

        } catch (mysqli_sql_exception $e) {

            error_log('Error SQL AgregarCliente: ' . $e->getMessage());

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