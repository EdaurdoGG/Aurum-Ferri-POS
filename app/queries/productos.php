<?php

$sql = "
    SELECT *
    FROM VistaProductos
    WHERE Producto <> 'AbonosCreditos'
";

$resultado = $conn->query($sql);
$productos = [];
$proveedores = [];
$categorias = [];

while ($row = $resultado->fetch_assoc()) {
    $productos[] = $row;
    $proveedores[$row['Proveedor']] = true;
    $categorias[$row['Categoria']] = true;
}

?>