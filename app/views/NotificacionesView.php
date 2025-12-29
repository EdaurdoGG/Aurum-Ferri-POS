<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Notificaciones | Aurum Ferri</title>
<link rel="stylesheet" href="assets/css/Notificaciones.css">
<link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<!-- ================= MENÚ ================= -->
<nav class="menu" id="menuLateral">
    <div class="menu-header">
        <img src="assets/icons/Logo.png" class="menu-logo" id="btnMenu">
        <span class="menu-title">Aurum Ferri</span>
    </div>
    <ul>
        <li><a href="InicioAdministradores.php"><img src="assets/icons/Inicio.png"><span>Inicio</span></a></li>
        <li><a href="Almacen.php"><img src="assets/icons/Almacen.png"><span>Almacén</span></a></li>
        <li><a href="Empleados.php"><img src="assets/icons/Empleados.png"><span>Empleados</span></a></li>
        <li><a href="Clientes.php"><img src="assets/icons/Clientes.png"><span>Clientes</span></a></li>
        <li><a href="Ventas.php"><img src="assets/icons/Ventas.png"><span>Ventas</span></a></li>
        <li><a href="PedidosPendientes.php"><img src="assets/icons/Pedidos.png"><span>Pedidos</span></a></li>
        <li><a href="Auditoria.php"><img src="assets/icons/Auditorias.png"><span>Auditoría</span></a></li>
        <div class="menu-separator"></div>
        <li><a href="Login.php"><img src="assets/icons/Salir.png"><span>Salir</span></a></li>
        <li class="help"><a href="Ayuda.php"><img src="assets/icons/Ayuda.png"><span>Ayuda</span></a></li>
    </ul>
</nav>

<!-- ================= HEADER ================= -->
<header class="page-header">
    <h1 class="page-title">Centro de Notificaciones</h1>

    <div class="header-user">
        <img src="<?= htmlspecialchars($fotoUsuario) ?>" class="user-avatar">
        <div class="user-info">
            <strong><?= htmlspecialchars($nombreUsuario) ?></strong>
            <span><?= htmlspecialchars($rolUsuarioNombre) ?></span>
        </div>
    </div>
</header>

<div class="divider"></div>

<main class="contenido">

<section class="notificaciones-grid">

<?php if ($resultado->num_rows > 0): ?>
<?php while ($n = $resultado->fetch_assoc()): ?>

<div class="notificacion-card <?= $n['Leida'] ? 'leida' : 'no-leida' ?>">
    <div class="notificacion-header">
        <span class="tipo <?= strtolower($n['Tipo']) ?>">
            <?= $n['Tipo'] == 'StockBajo' ? 'Stock Bajo' : 'Pedido Pendiente' ?>
        </span>
        <span class="fecha">
            <?= date('d/m/Y H:i', strtotime($n['Fecha'])) ?>
        </span>
    </div>

    <p class="mensaje"><?= htmlspecialchars($n['Mensaje']) ?></p>

    <?php if ($n['idProducto']): ?>
        <div class="detalle">
            <strong>Producto:</strong> <?= htmlspecialchars($n['NombreProducto']) ?><br>
            <strong>Stock actual:</strong> <?= $n['StockActual'] ?>
        </div>
    <?php endif; ?>

    <?php if ($n['idPedido']): ?>
        <div class="detalle">
            <strong>Pedido #<?= $n['idPedido'] ?></strong><br>
            Estado: <?= htmlspecialchars($n['EstadoPedido']) ?>
        </div>
    <?php endif; ?>

    <?php if (!$n['Leida']): ?>
        <a href="?leer=<?= $n['idNotificacion'] ?>" class="btn-leer">
            Marcar como leída
        </a>
    <?php endif; ?>
</div>

<?php endwhile; ?>
<?php else: ?>
<p class="sin-notificaciones">No hay notificaciones.</p>
<?php endif; ?>

</section>

</main>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

<script>
document.getElementById("btnMenu").onclick = () =>
    document.getElementById("menuLateral").classList.toggle("menu-activo");
</script>

</body>
</html>