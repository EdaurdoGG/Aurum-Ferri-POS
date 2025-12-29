<?php
class CategoriaModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Agregar una nueva categoría usando PROCEDIMIENTO ALMACENADO
     */
    public function agregarCategoria($nombre) {

        // Llamar al procedimiento almacenado
        $stmt = $this->conn->prepare("CALL AgregarCategoria(?)");

        if (!$stmt) {
            error_log('Error prepare AgregarCategoria: ' . $this->conn->error);
            return [
                'success' => false,
                'error'   => 'Error interno al preparar la consulta.'
            ];
        }

        $stmt->bind_param("s", $nombre);

        try {
            $stmt->execute();
            $stmt->close();

            // IMPORTANTE: limpiar resultados pendientes del CALL
            while ($this->conn->more_results() && $this->conn->next_result()) {;}

            return [
                'success' => true
            ];

        } catch (mysqli_sql_exception $e) {

            error_log('Error SQL AgregarCategoria: ' . $e->getMessage());

            $stmt->close();
            while ($this->conn->more_results() && $this->conn->next_result()) {;}

            return [
                'success' => false,
                'error'   => $e->getMessage()
            ];
        }
    }
}
