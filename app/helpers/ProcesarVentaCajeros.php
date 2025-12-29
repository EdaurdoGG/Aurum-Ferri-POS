<?php
$stmt = $conn->prepare("CALL ProcesarVentaDesdeCarrito(?, ?, ?)");
if ($stmt && $stmt->bind_param("iii", $idUsuario, $cliente_id, $venta_credito) && $stmt->execute()) {
    setMensaje("Venta procesada correctamente.");
    $stmt->close();
    $conn->next_result();

    // Crear nuevo carrito
    $stmtNuevo = $conn->prepare("INSERT INTO Carrito (idUsuario) VALUES (?)");
    $stmtNuevo->bind_param("i", $idUsuario);
    $stmtNuevo->execute();
    $stmtNuevo->close();

    unset($_SESSION['cliente_id_seleccionado']);
} else {
    setMensaje("No se pudo procesar la venta.", "error");
    if ($stmt) { $stmt->close(); $conn->next_result(); }
}
header("Location: InicioTrabajadores.php");
exit();
?>