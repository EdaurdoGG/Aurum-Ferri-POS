<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ventas | Aurum Ferri</title>
<link rel="stylesheet" href="assets/css/InicioTrabajadores.css">
<link rel="icon" href="assets/icons/Logo.png">
<style>
.table .acciones-form {
    display: flex;
    gap: 5px;
    justify-content: center;
    align-items: center;
}

.table input[type=number] {
    width: 60px;
    text-align: center;
}

.accion-btn.pequeño {
    padding: 2px 6px;
    font-size: 0.9rem;
}
#label_credito {
    margin-left: 10px;
}
</style>
</head>
<body>

<?php if (!empty($_SESSION['mensaje'])): ?>
<div class="alert-message <?= $_SESSION['tipo_mensaje']=='error'?'alert-error':'alert-success' ?>">
    <?= htmlspecialchars($_SESSION['mensaje']) ?>
</div>
<?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); endif; ?>

<nav class="menu" id="menuLateral">
    <div class="menu-header">
        <img src="assets/icons/Logo.png" class="menu-logo" id="btnMenu">
        <span class="menu-title">Aurum Ferri</span>
    </div>
    <ul>
        <li><a href="InicioTrabajadores.php" class="activo"><img src="assets/icons/Inicio.png"><span>Inicio</span></a></li>
        <li><a href="AbonoCajeros.php"><img src="assets/icons/Abonar.png"><span>Pagos de credito</span></a></li>
        <li><a href="PedidosCajeros.php"><img src="assets/icons/Pedidos.png"><span>Pedidos</span></a></li>
        <li><a href="CatalogoCajeros.php"><img src="assets/icons/Catalogo.png"><span>Catalogo</span></a></li>
        <div class="menu-separator"></div>
        <li><a href="Login.php"><img src="assets/icons/Salir.png"><span>Salir</span></a></li>
        <li class="help"><a href="Ayuda.php"><img src="assets/icons/Ayuda.png"><span>Ayuda</span></a></li>
    </ul>
</nav>

<header class="page-header">
    <h1 class="page-title">Punto de Ventas</h1>
    
    <div class="header-user">

        <!-- Imagen del usuario logueado -->
        <img src="<?= htmlspecialchars($fotoUsuario) ?>" class="user-avatar" alt="Usuario">

        <div class="user-info">
            <!-- Nombre del usuario -->
            <strong><?= htmlspecialchars($nombreUsuario) ?></strong>
            <!-- Nombre del rol -->
            <span><?= htmlspecialchars($rolUsuarioNombre) ?></span>
        </div>
    </div>
</header>

<div class="divider"></div>

<main class="contenido">

<section class="acciones-barra">
<form method="POST" class="barcode-form">
    <input type="text" name="termino" placeholder="Nombre o código de barras" autofocus>

    <select name="cliente_id" id="cliente_id" onchange="toggleCredito()">
        <?php foreach ($clientes as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $cliente_id==$c['id']?'selected':'' ?>>
                <?= htmlspecialchars($c['Nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label id="label_credito">
        <input type="checkbox" name="venta_credito" <?= $venta_credito?'checked':'' ?>>
        Venta a crédito
    </label>

    <button type="submit" class="accion-btn oscuro">Agregar al carrito</button>
    <button type="submit" name="procesar" class="accion-btn oscuro">Procesar Venta</button>
</form>
</section>

<section class="table-container">
<table class="table">
<thead>
<tr>
<th>Producto</th>
<th>Cantidad</th>
<th>Precio</th>
<th>Total</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
<?php $total=0; foreach ($productos_registrados as $p):
$total += $p['Total']; ?>
<tr>
<td><?= htmlspecialchars($p['NombreProducto']) ?></td>
<td>
    <form method="POST" class="acciones-form">
        <input type="hidden" name="producto_id" value="<?= $p['idProducto'] ?>">
        <input type="number" name="cantidad" value="<?= $p['Cantidad'] ?>" min="0">
        <button type="submit" name="actualizar" class="accion-btn pequeño">Actualizar</button>
    </form>
</td>
<td>$<?= number_format($p['Precio'],2) ?></td>
<td>$<?= number_format($p['Total'],2) ?></td>
<td>
<form method="POST" class="acciones-form">
    <input type="hidden" name="producto_id" value="<?= $p['idProducto'] ?>">
    <button name="sumar" class="accion-btn pequeño">+</button>
    <button name="restar" class="accion-btn pequeño">-</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<tr>
<td colspan="3" style="text-align:right;font-weight:bold">TOTAL</td>
<td colspan="2" style="font-weight:bold">$<?= number_format($total,2) ?></td>
</tr>
</tbody>
</table>
</section>

</main>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

<script>
document.getElementById("btnMenu").onclick = () => {
    document.getElementById("menuLateral").classList.toggle("menu-activo");
};

function toggleCredito() {
    const clienteId = document.getElementById('cliente_id').value;
    const checkbox = document.querySelector('input[name="venta_credito"]');
    const label = document.getElementById('label_credito');
    if(clienteId == '1'){
        checkbox.checked = false;
        checkbox.disabled = true;
        label.style.opacity = 0.5;
    } else {
        checkbox.disabled = false;
        label.style.opacity = 1;
    }
}
toggleCredito();
</script>

</body>
</html> 