<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="assets/css/EditarProducto.css">
    <link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<main>
<section class="centro">
<div class="log">
<div class="login">

<!-- MENSAJES -->
<?php if (!empty($_SESSION['mensaje'])): ?>
<div class="alert-message <?= $_SESSION['tipo_mensaje']=='error'?'alert-error':'alert-success' ?>">
    <?= htmlspecialchars($_SESSION['mensaje']) ?>
</div>
<?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="titulo">
    <h2>Editar Producto</h2>
    <a href="Almacen.php"><img src="assets/icons/Volver.png" class="boton-atras"></a>
</div>

<div class="input-group">
    <input type="text" name="Nombre" value="<?= htmlspecialchars($producto['Nombre']) ?>" required>
    <label>Nombre</label>
</div>

<div class="input-group">
    <input type="text" name="CodigoBarras" value="<?= htmlspecialchars($producto['CodigoBarras']) ?>" required>
    <label>Código de Barras</label>
</div>

<div class="input-group">
    <input type="number" step="0.01" name="PrecioCompra" value="<?= $producto['PrecioCompra'] ?>" required>
    <label>Precio Compra</label>
</div>

<div class="input-group">
    <input type="number" step="0.01" name="PrecioVenta" value="<?= $producto['PrecioVenta'] ?>" required>
    <label>Precio Venta</label>
</div>

<div class="input-group">
    <input type="number" name="Stock" value="<?= $producto['Stock'] ?>" required>
    <label>Stock</label>
</div>

<div class="input-group">
    <input type="number" name="StockMinimo" value="<?= $producto['StockMinimo'] ?>" required>
    <label>Stock Mínimo</label>
</div>

<div class="input-group">
    <input type="file" name="Imagen">
    <label>Imagen</label>
    <?php if (!empty($producto['Imagen'])): ?>
        <img src="<?= htmlspecialchars($producto['Imagen']) ?>" style="width:80px;margin-top:5px;">
    <?php endif; ?>
</div>

<div class="input-group">
<select name="idCategoria" required>
<?php foreach ($categorias as $cat): ?>
<option value="<?= $cat['idCategoria'] ?>" <?= $producto['idCategoria']==$cat['idCategoria']?'selected':'' ?>>
    <?= htmlspecialchars($cat['Nombre']) ?>
</option>
<?php endforeach; ?>
</select>
<label>Categoría</label>
</div>

<div class="input-group">
<select name="idProveedor" required>
<?php foreach ($proveedores as $prov): ?>
<option value="<?= $prov['idProveedor'] ?>" <?= $producto['idProveedor']==$prov['idProveedor']?'selected':'' ?>>
    <?= htmlspecialchars($prov['NombreCompleto']) ?>
</option>
<?php endforeach; ?>
</select>
<label>Proveedor</label>
</div>

<button type="submit" name="EditarProducto" class="Acceder">
Actualizar Producto
</button>

</form>
</div>
</div>
</section>
</main>

<footer>
<p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

</body>
</html>
