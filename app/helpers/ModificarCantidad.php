<?php
$proc = isset($_POST['sumar']) ? "SumarCantidadCarrito" : "RestarCantidadCarrito";
$stmt = $conn->prepare("CALL $proc(?, ?)");
if ($stmt->bind_param("ii", $idCarrito, $_POST['producto_id']) && $stmt->execute()) {
    setMensaje("Cantidad actualizada.");
} else {
    setMensaje("No se pudo actualizar.", "error");
}
$stmt->close();
$conn->next_result();
header("Location: InicioAdministradores.php");
exit();
?>