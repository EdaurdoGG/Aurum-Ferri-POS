<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Catálogo Proveedores</title>
<link rel="stylesheet" href="assets/css/InicioProveedores.css">
<link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<!-- ================= MODAL CLIENTE ================= -->
<?php if (!$idCliente): ?>
<div class="modal" style="display:flex;">
    <div class="modal-content">
        <h3>Selecciona el cliente</h3>
        <form method="POST">
            <select name="idCliente" required>
                <option value="">Seleccione un cliente…</option>
                <?php foreach ($clientes as $c): ?>
                    <option value="<?= $c['idCliente'] ?>">
                        <?= htmlspecialchars($c['Nombre'].' '.$c['Paterno']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <button type="submit" class="btn-primary">Continuar</button>
        </form>
    </div>
</div>
<?php endif; ?>

<header class="page-header">
    <h1 class="page-title">Catálogo para: <?= htmlspecialchars($nombreCliente) ?></h1>

    <div class="header-user">
        <a href="InicioProveedores.php" class="notificacion">
            <img src="assets/icons/InicioProveedores.png">
        </a>

        <a href="CarritoProveedores.php" class="notificacion">
            <img src="assets/icons/Carrito.png">
            <?php if ($contador > 0): ?>
                <span class="badge"><?= $contador ?></span>
            <?php endif; ?>
        </a>

        <a href="PedidosProveedores.php" class="notificacion">
            <img src="assets/icons/PedidosProveedores.png">
        </a>

        <a href="Login.php" class="notificacion">
            <img src="assets/icons/SalirProveedores.png">
        </a>

        <img src="<?= htmlspecialchars($fotoUsuario) ?>" class="user-avatar" alt="Usuario">
        <div class="user-info">
            <strong><?= htmlspecialchars($nombreUsuario) ?></strong>
            <span><?= htmlspecialchars($rolUsuarioNombre) ?></span>
        </div>
    </div>
</header>

<div class="divider"></div>

<main class="contenido">
<section class="cards-container">

<?php foreach ($productos as $p):
    $estadoTexto = ($p['Stock'] > 0) ? 'Con existencias' : 'Agotado';
    $estadoClase = ($p['Stock'] > 0) ? 'estatus-activo' : 'estatus-inactivo';

    // Imagen del producto
    $nombreImagen = basename($p['Imagen'] ?? '');
    $imagenProducto = (!empty($nombreImagen) && file_exists($rutaServidor . $nombreImagen))
        ? $rutaWeb . $nombreImagen
        : $rutaWeb . "default.png";
?>

<div class="card" onclick="abrirModal(<?= $p['idProducto'] ?>,'<?= htmlspecialchars($p['Producto']) ?>',<?= $p['Stock'] ?>)">
    <span class="estatus <?= $estadoClase ?>"><?= $estadoTexto ?></span>
    <img src="<?= htmlspecialchars($imagenProducto) ?>" class="avatar" alt="<?= htmlspecialchars($p['Producto']) ?>">
    <div class="card-info">
        <h3><?= htmlspecialchars($p['Producto']) ?></h3>
        <p>Stock: <?= $p['Stock'] ?></p>
        <p>$<?= number_format($p['PrecioVenta'],2) ?></p>
    </div>
</div>
<?php endforeach; ?>

</section>
</main>

<!-- ================= MODAL PRODUCTO ================= -->
<div class="modal" id="modalCantidad">
    <div class="modal-content">
        <h3 id="prodNombre"></h3>
        <form method="POST">
            <input type="hidden" name="idProducto" id="prodId">
            <input type="number" name="cantidad" id="prodCantidad" min="1">
            <br><br>
            <div class="btn-container">
                <button type="button" class="btn-cancelar" onclick="cerrar()">Cancelar</button>
                <button type="submit" class="btn-guardar">Agregar</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

<script>
function abrirModal(id, nombre, stock){
    prodId.value = id;
    prodNombre.innerText = nombre;
    prodCantidad.max = stock;
    prodCantidad.value = 1;
    modalCantidad.style.display = 'flex';
}

function cerrar(){
    modalCantidad.style.display = 'none';
}
</script>

</body>
</html>
