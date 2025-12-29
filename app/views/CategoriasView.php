<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías | Aurum Ferri</title>
    <link rel="stylesheet" href="assets/css/Categorias.css">
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
        <li><a href="Almacen.php" class="activo"><img src="assets/icons/Almacen.png"><span>Almacén</span></a></li>
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
    <h1 class="page-title">Categorías</h1>

    <div class="header-user">
        <a href="Notificaciones.php" class="notificacion">
            <?php if ($notificacionesNoLeidas > 0): ?>
                <span class="badge">
                    <?= $notificacionesNoLeidas > 9 ? '9+' : $notificacionesNoLeidas ?>
                </span>
            <?php endif; ?>
            <img src="assets/icons/Campana.png">
        </a>

        <img src="<?= htmlspecialchars($fotoUsuario) ?>" class="user-avatar">

        <div class="user-info">
            <strong><?= htmlspecialchars($nombreUsuario) ?></strong>
            <span><?= htmlspecialchars($rolUsuarioNombre) ?></span>
        </div>
    </div>
</header>

<div class="divider"></div>

<main class="contenido">

<section class="acciones-barra">
    <a href="AgregarCategoria.php" class="accion-btn primero">
        <img src="assets/icons/Agregar.png">
        Nueva Categoría
    </a>
</section>

<section class="cards-container">
<?php foreach ($categorias as $c): ?>
    <div class="card">
        <div class="card-info" style="text-align:center;width:100%">
            <h3><?= htmlspecialchars($c['Nombre']) ?></h3>
        </div>
        <div class="card-actions">
            <a href="EditarCategoria.php?idCategoria=<?= $c['idCategoria'] ?>" class="edit">
                <img src="assets/icons/Editar.png">
            </a>
        </div>
    </div>
<?php endforeach; ?>
</section>

</main>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

<script>
document.getElementById("btnMenu").onclick = () => {
    document.getElementById("menuLateral").classList.toggle("menu-activo");
};
</script>

</body>
</html>
