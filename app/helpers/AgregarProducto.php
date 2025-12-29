<?php
$termino = trim($_POST['termino']);
$producto = null;

// Buscar por código de barras
$stmt = $conn->prepare("CALL BuscarProductoPorCodigoBarra(?)");
$stmt->bind_param("s", $termino);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->next_result();

// Si no se encuentra, buscar por nombre
if (!$producto) {
    $stmt = $conn->prepare("CALL BuscarProductoPorNombre(?)");
    $stmt->bind_param("s", $termino);
    $stmt->execute();
    $producto = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->next_result();
}

// Agregar al carrito
if ($producto) {
    $stmt = $conn->prepare("CALL AgregarAlCarrito(?, ?, 1)");
    if ($stmt->bind_param("ii", $idCarrito, $producto['idProducto']) && $stmt->execute()) {
        setMensaje("Producto agregado al carrito.");
    } else {
        setMensaje("No hay suficiente stock.", "error");
    }
    $stmt->close();
    $conn->next_result();
} else {
    setMensaje("Producto no encontrado.", "error");
}
header("Location: InicioAdministradores.php");
exit();
?>