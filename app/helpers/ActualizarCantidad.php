<?php
$cantidad = intval($_POST['cantidad']);
if ($cantidad < 0) {
    setMensaje("Cantidad inválida.", "error");
} else {
    $stmt = $conn->prepare("CALL ActualizarCantidadCarrito(?, ?, ?)");
    if ($stmt->bind_param("iii", $idCarrito, $_POST['producto_id'], $cantidad) && $stmt->execute()) {
        setMensaje("Cantidad actualizada.");
    } else {
        setMensaje("Stock insuficiente.", "error");
    }
    $stmt->close();
    $conn->next_result();
}
header("Location: InicioAdministradores.php");
exit();
?>