<?php
class AbonoCajerosModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Registrar abono usando PROCEDIMIENTO ALMACENADO
     */
    public function registrarAbono($idUsuario, $idCliente, $monto) {

        $stmt = $this->conn->prepare("
            CALL RegistrarAbonoCredito(?, ?, ?)
        ");

        if (!$stmt) {
            error_log('Error prepare RegistrarAbonoCredito: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param("iid", $idUsuario, $idCliente, $monto);

        $resultado = $stmt->execute();

        if (!$resultado) {
            error_log('Error execute RegistrarAbonoCredito: ' . $stmt->error);
            return false;
        }

        // IMPORTANTE: limpiar resultados del CALL
        while ($this->conn->more_results() && $this->conn->next_result()) {
            $this->conn->use_result();
        }

        return true;
    }

    /**
     * Obtener clientes con crédito pendiente
     */
    public function obtenerClientesConCredito() {

        $sql = "
            SELECT
                idCliente,
                Nombre,
                Paterno,
                Materno,
                Imagen,
                Estatus,
                Credito,
                Limite
            FROM VistaClientes
            WHERE Credito > 0
            ORDER BY Nombre ASC
        ";

        $resultado = $this->conn->query($sql);

        if (!$resultado) {
            error_log('Error SQL obtenerClientesConCredito: ' . $this->conn->error);
            return false;
        }

        return $resultado;
    }
}
