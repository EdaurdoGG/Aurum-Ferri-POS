<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proveedores | Aurum Ferri</title>
    <link rel="stylesheet" href="assets/css/Proveedores.css">
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
        <li><a href="Almacen.php"  class="activo"><img src="assets/icons/Almacen.png"><span>Almacén</span></a></li>
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
    <h1 class="page-title">Lista de Proveedor</h1>

    <div class="header-user">
        <a href="Notificaciones.php" class="notificacion">
            <?php if ($notificacionesNoLeidas > 0): ?>
                <span class="badge">
                    <?= $notificacionesNoLeidas > 9 ? '9+' : $notificacionesNoLeidas ?>
                </span>
            <?php endif; ?>
            <img src="assets/icons/Campana.png" alt="Notificaciones">
        </a>

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

<!-- ================= ACCIONES ================= -->
<main class="contenido">
    <section class="acciones-barra">
        <a href="AgregarProveedor.php" class="accion-btn rojo">
            <img src="assets/icons/Agregar.png">
            Nuevo
        </a>
    </section>

    <!-- ================= TARJETAS ================= -->
    <section class="cards-container">
        <?php while ($p = $resultado->fetch_assoc()): 
            $estatus = $p['Estado'] ?? 'Activo';
            $claseEstatus = strtolower($estatus) === 'activo' ? 'estatus-activo' : 'estatus-inactivo';
            // Concatenar nombre completo
            $nombreCompleto = htmlspecialchars($p['Nombre'] . ' ' . $p['Paterno'] . ' ' . $p['Materno']);
            $telefono = htmlspecialchars($p['Telefono'] ?? 'Sin teléfono');
            $email = htmlspecialchars($p['Email'] ?? 'Sin email');
            $imagen = $p['Imagen'] ?: 'Imagenes/Proveedor.png';
        ?>
        <div class="card">
            <!-- BADGE ESTATUS -->
            <span class="estatus <?= $claseEstatus ?>"><?= $estatus ?></span>

            <img src="<?= $imagen ?>" class="avatar">

            <div class="card-info">
                <h3><?= $nombreCompleto ?></h3>
                <p class="rol">Proveedor</p>
                <p>ID: <?= htmlspecialchars($p['idProveedor']) ?></p>
                <p>📞 <?= $telefono ?></p>
                <p>📧 <?= $email ?></p>
            </div>

            <div class="card-actions">
                <a href="EditarProveedor.php?idProveedor=<?= $p['idProveedor'] ?>" class="edit">
                    <img src="assets/icons/Editar.png">
                </a>
                <a href="BorrarProveedor.php?idProveedor=<?= $p['idProveedor'] ?>" class="delete"
                   onclick="return confirm('¿Eliminar proveedor?')">
                    <img src="assets/icons/Borrar.png">
                </a>
            </div>
        </div>
        <?php endwhile; ?>
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