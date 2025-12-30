<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="assets/css/AgregarProducto.css">
    <link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<main>
    <section class="centro">
        <div class="log">
            <div class="login">

                <!-- ================= MENSAJES ================= -->
                <?php if (!empty($_SESSION['mensaje'])): ?>
                    <div class="alert-message <?= $_SESSION['tipo_mensaje']=='error'?'alert-error':'alert-success' ?>">
                        <?= htmlspecialchars($_SESSION['mensaje']) ?>
                    </div>
                    <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="titulo">
                        <h2>Agregar Producto</h2>
                        <a href="Almacen.php">
                            <img src="Imagenes/Volver.png" alt="Atrás" class="boton-atras">
                        </a>
                    </div>

                    <!-- INPUTS -->
                    <div class="input-group"><input type="text" name="Nombre" required placeholder=" "><label>Nombre del Producto</label></div>
                    <div class="input-group"><input type="text" name="CodigoBarras" required placeholder=" "><label>Código de Barras</label></div>
                    <div class="input-group"><input type="number" step="0.01" name="PrecioCompra" required placeholder=" "><label>Precio de Compra</label></div>
                    <div class="input-group"><input type="number" step="0.01" name="PrecioVenta" required placeholder=" "><label>Precio de Venta</label></div>
                    <div class="input-group"><input type="number" name="Stock" min="0" required placeholder=" "><label>Cantidad en Stock</label></div>
                    <div class="input-group"><input type="number" name="StockMinimo" min="0" required placeholder=" "><label>Stock Mínimo</label></div>
                    <div class="input-group"><input type="file" name="Imagen" accept="image/*"><label>Imagen del Producto</label></div>

                    <!-- SELECT CATEGORÍA -->
                    <div class="input-group">
                        <select name="idCategoria" required>
                            <option disabled selected>Selecciona una Categoría</option>
                            <?php foreach($categorias as $c): ?>
                                <option value="<?= $c['idCategoria'] ?>"><?= htmlspecialchars($c['Nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label>Categoría</label>
                    </div>

                    <!-- SELECT PROVEEDOR -->
                    <div class="input-group">
                        <select name="idProveedor" required>
                            <option disabled selected>Selecciona un Proveedor</option>
                            <?php foreach($proveedores as $p): ?>
                                <option value="<?= $p['idProveedor'] ?>"><?= htmlspecialchars($p['NombreCompleto']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label>Proveedor</label>
                    </div>

                    <button type="submit" name="AgregarProducto" class="Acceder">Agregar Producto</button>
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
