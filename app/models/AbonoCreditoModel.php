<?php

class AbonoCreditoModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Registrar abono usando PROCEDIMIENTO ALMACENADO
     */
    public function registrarAbono($idUsuario, $idCliente, $monto) {

        $stmt = $this->conn->prepare("CALL RegistrarAbonoCredito(?, ?, ?)");

        if (!$stmt) {
            error_log('Error prepare RegistrarAbonoCredito: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param("iid", $idUsuario, $idCliente, $monto);

        if (!$stmt->execute()) {
            error_log('Error execute RegistrarAbonoCredito: ' . $stmt->error);
            return false;
        }

        // Limpiar resultados del CALL
        while ($this->conn->more_results() && $this->conn->next_result()) {
            $this->conn->use_result();
        }

        return true;
    }

    /**
     * Obtener clientes con crédito
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
            ORDER BY Nombre
        ";

        $res = $this->conn->query($sql);

        if (!$res) {
            error_log('Error SQL obtenerClientesConCredito: ' . $this->conn->error);
            return [];
        }

        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
