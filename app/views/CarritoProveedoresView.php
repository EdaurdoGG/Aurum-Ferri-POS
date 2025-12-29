<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Carrito</title>
<link rel="stylesheet" href="assets/css/InicioProveedores.css">
<link rel="stylesheet" href="assets/css/CarritoProveedores.css">
<link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<header class="page-header">
    <h1 class="page-title">Carrito de compras</h1>

    <div class="header-user">

        <a href="InicioProveedores.php" class="notificacion">
            <img src="assets/icons/InicioProveedores.png" alt="Inicio">
        </a>

        <a href="CarritoProveedores.php" class="notificacion">
            <img src="assets/icons/Carrito.png" alt="Carrito">
            <?php if ($contador > 0): ?>
                <span class="badge"><?= $contador ?></span>
            <?php endif; ?>
        </a>

        <a href="PedidosProveedores.php" class="notificacion">
            <img src="assets/icons/PedidosProveedores.png" alt="Pedidos">
        </a>

        <a href="Login.php" class="notificacion">
            <img src="assets/icons/SalirProveedores.png" alt="Salir">
        </a>

        <img src="<?= htmlspecialchars($fotoUsuario) ?>" class="user-avatar" alt="Usuario">
        <div class="user-info">
            <strong><?= htmlspecialchars($nombreUsuario) ?></strong>
            <span><?= htmlspecialchars($rolUsuarioNombre) ?></span>
        </div>
    </div>
</header>

<div class="divider"></div>

<?php if ($mensaje): ?>
<div class="alert-message alert-success"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert-message alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<main class="contenido">

<?php if ($idCliente): ?>
<p class="cliente-actual">
Cliente seleccionado:
<strong><?= htmlspecialchars($nombreCliente) ?></strong>
</p>
<?php else: ?>
<p class="alert-message alert-error">
No hay cliente seleccionado.
<a href="InicioProveedores.php">Seleccionar cliente</a>
</p>
<?php endif; ?>

<div class="contenedor">
<div class="lista">

<?php foreach ($carrito as $c):
    // Obtener imagen correctamente
    $imgProducto  = $imagenesProductos[$c['idProducto']] ?? '';
    $nombreImagen = basename($imgProducto);
    $imagen = (!empty($nombreImagen) && file_exists($rutaServidor . $nombreImagen))
        ? $rutaWeb . $nombreImagen
        : $rutaWeb . "default.png";
?>

<div class="item">

    <img src="<?= htmlspecialchars($imagen) ?>" class="cart-img" alt="<?= htmlspecialchars($c['NombreProducto']) ?>">

    <div class="info">
        <strong><?= htmlspecialchars($c['NombreProducto']) ?></strong>
        <small>Código: <?= htmlspecialchars($c['CodigoBarras']) ?></small>

        <div class="cantidad">
            <form method="POST">
                <input type="hidden" name="idProducto" value="<?= $c['idProducto'] ?>">

                <button name="restar" type="submit">−</button>

                <input type="number" name="cantidad"
                       value="<?= $c['Cantidad'] ?>" min="1">

                <button name="sumar" type="submit">+</button>

                <button class="ok" name="actualizar" type="submit">✓</button>
            </form>
        </div>

        <div class="total-producto">
            Total: $<?= number_format($c['Total'], 2) ?>
        </div>
    </div>

</div>

<?php endforeach; ?>
</div>

<div class="resumen">
<h3>Resumen de la orden</h3>
<p>Subtotal <span>$<?= number_format($subtotal, 2) ?></span></p>
<hr>
<p class="total">Total <span>$<?= number_format($subtotal, 2) ?></span></p>
<form method="POST">
<button class="checkout" name="pedido" <?= !$idCliente ? 'disabled' : '' ?>>
Procesar Pedido
</button>
</form>
</div>
</div>
</main>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

</body>
</html>
