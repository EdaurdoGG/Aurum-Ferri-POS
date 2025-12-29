<?php

function registrarAbono($conn, $idUsuario, $idCliente, $monto) {
    $stmt = $conn->prepare("CALL RegistrarAbonoCredito(?, ?, ?)");
    $stmt->bind_param("iid", $idUsuario, $idCliente, $monto);
    $ok = $stmt->execute();
    $stmt->close();
    $conn->next_result();
    return $ok;
}

function obtenerClientesConCredito($conn) {
    $res = $conn->query("
        SELECT *
        FROM VistaClientes
        WHERE Credito > 0
        ORDER BY Nombre
    ");
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

?>