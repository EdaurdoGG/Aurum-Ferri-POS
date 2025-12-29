<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Empleados | Aurum Ferri</title>
<link rel="stylesheet" href="assets/css/Empleados.css">
<link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<nav class="menu" id="menuLateral">
    <div class="menu-header">
        <img src="assets/icons/Logo.png" class="menu-logo" id="btnMenu">
        <span class="menu-title">Aurum Ferri</span>
    </div>
    <ul>
        <li><a href="InicioAdministradores.php"><img src="assets/icons/Inicio.png"><span>Inicio</span></a></li>
        <li><a href="Almacen.php"><img src="assets/icons/Almacen.png"><span>Almacén</span></a></li>
        <li><a href="Empleados.php" class="activo"><img src="assets/icons/Empleados.png"><span>Empleados</span></a></li>
        <li><a href="Clientes.php"><img src="assets/icons/Clientes.png"><span>Clientes</span></a></li>
        <li><a href="Ventas.php"><img src="assets/icons/Ventas.png"><span>Ventas</span></a></li>
        <li><a href="PedidosPendientes.php"><img src="assets/icons/Pedidos.png"><span>Pedidos</span></a></li>
        <li><a href="Auditoria.php"><img src="assets/icons/Auditorias.png"><span>Auditoría</span></a></li>
        <div class="menu-separator"></div>
        <li><a href="Login.php"><img src="assets/icons/Salir.png"><span>Salir</span></a></li>
        <li class="help"><a href="Ayuda.php"><img src="assets/icons/Ayuda.png"><span>Ayuda</span></a></li>
    </ul>
</nav>

<header class="page-header">
    <h1 class="page-title">Empleados</h1>
    <div class="header-user">
        <a href="Notificaciones.php" class="notificacion">
            <?php if ($notificacionesNoLeidas > 0): ?>
                <span class="badge"><?= $notificacionesNoLeidas > 9 ? '9+' : $notificacionesNoLeidas ?></span>
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
    <a href="Registro.php" class="accion-btn primero">
        <img src="assets/icons/Agregar.png"> Nuevo
    </a>
    <a href="#" class="accion-btn segundo" onclick="descargarEmpleados()">
        <img src="assets/icons/Descargar.png"> Descargar
    </a>
</section>

<section class="cards-container">
<?php while ($e = $empleados->fetch_assoc()):
    $estatus = $e['Estatus'] ?? 'Inactivo';
    $claseEstatus = strtolower($estatus) === 'activo'
        ? 'estatus-activo'
        : 'estatus-inactivo';

    $nombreImagen = $e['Imagen'] ?? '';
    $rutaImagen = (!empty($nombreImagen) && file_exists($rutaServidor.basename($nombreImagen)))
        ? $rutaWeb.basename($nombreImagen)
        : $rutaWeb.'default.png';
?>
<div class="card" data-estatus="<?= htmlspecialchars($estatus) ?>">
    <span class="estatus <?= $claseEstatus ?>"><?= htmlspecialchars($estatus) ?></span>

    <img src="<?= htmlspecialchars($rutaImagen) ?>" class="avatar">

    <div class="card-info">
        <h3><?= htmlspecialchars($e['Nombre'].' '.$e['Paterno'].' '.$e['Materno']) ?></h3>
        <p class="rol"><?= htmlspecialchars($e['Rol']) ?></p>
        <p>📞 <?= htmlspecialchars($e['Telefono'] ?? 'Sin teléfono') ?></p>
        <p>👤 <?= htmlspecialchars($e['Usuario'] ?? 'Sin usuario') ?></p>
    </div>

    <div class="card-actions">
        <a href="EditarEmpleado.php?idUsuario=<?= $e['idUsuario'] ?>" class="edit">
            <img src="assets/icons/Editar.png">
        </a>
        <a href="BorrarEmpleado.php?idUsuario=<?= $e['idUsuario'] ?>"
           class="delete"
           onclick="return confirm('¿Eliminar empleado?')">
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

function descargarEmpleados(){
    window.location.href = 'TicketEmpleados.php';
}
</script>

</body>
</html>
