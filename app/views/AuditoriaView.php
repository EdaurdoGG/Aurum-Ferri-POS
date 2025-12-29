<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Auditoría | Aurum Ferri</title>
<link rel="stylesheet" href="assets/css/Auditoria.css">
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
        <li><a href="Auditoria.php" class="activo"><img src="assets/icons/Auditorias.png"><span>Auditoría</span></a></li>
        <div class="menu-separator"></div>
        <li><a href="Login.php"><img src="assets/icons/Salir.png"><span>Salir</span></a></li>
        <li class="help"><a href="Ayuda.php"><img src="assets/icons/Ayuda.png"><span>Ayuda</span></a></li>
    </ul>
</nav>

<!-- ================= HEADER ================= -->
<header class="page-header">
    <h1 class="page-title">Historial de Auditoría</h1>

    <div class="header-user">
        <a href="Notificaciones.php" class="notificacion">
            <?php if ($notificacionesNoLeidas > 0): ?>
                <span class="badge">
                    <?= $notificacionesNoLeidas > 9 ? '9+' : $notificacionesNoLeidas ?>
                </span>
            <?php endif; ?>
            <img src="assets/icons/Campana.png" alt="Notificaciones">
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

<!-- ================= BARRA DE FILTROS ================= -->
<section class="acciones-barra">
<form method="GET" id="formFiltros" class="filtros-avanzados">
    <div class="fecha-box">
        <input type="date" name="fecha" value="<?= htmlspecialchars($fecha) ?>" onchange="autoSubmit()">
    </div>

    <div class="dropdown">
        <button class="accion-btn categoria-btn filtro-segundo">
            <img src="assets/icons/Filtro.png">
            <?= $modulo ?: 'Módulos' ?>
        </button>
            <div class="dropdown-content">
                <a href="#" onclick="setModulo('', event)">Todos</a>
                <a href="#" onclick="setModulo('Usuarios', event)">Usuarios</a>
                <a href="#" onclick="setModulo('Clientes', event)">Clientes</a>
                <a href="#" onclick="setModulo('Personas', event)">Personas</a>
                <a href="#" onclick="setModulo('Productos', event)">Productos</a>
                <a href="#" onclick="setModulo('Pedidos', event)">Pedidos</a>
                <a href="#" onclick="setModulo('Devoluciones', event)">Devoluciones</a>
            </div>
    </div>

    <input type="hidden" name="modulo" id="inputModulo" value="<?= htmlspecialchars($modulo) ?>">

    <a class="accion-btn oscuro"
       href="?modulo=<?= urlencode($modulo) ?>&fecha=<?= urlencode($fecha) ?>&descargar=1">
       <img src="assets/icons/Descargar.png"> Descargar
    </a>
</form>
</section>

<!-- ================= TABLA ================= -->
<section class="table-container">
<table class="table">
<thead>
<tr>
    <th>Acción</th>
    <th>Tabla</th>
    <th>Columna</th>
    <th>Dato Anterior</th>
    <th>Dato Nuevo</th>
    <th>ID</th>
    <th>Usuario</th>
    <th>Fecha</th>
    <th>Hora</th>
</tr>
</thead>
<tbody>
<?php if ($resultado->num_rows > 0): ?>
<?php while ($fila = $resultado->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($fila['Accion']) ?></td>
    <td><?= htmlspecialchars($fila['Tabla']) ?></td>
    <td><?= htmlspecialchars($fila['ColumnaAfectada'] ?? '') ?></td>
    <td><?= htmlspecialchars($fila['DatoAnterior'] ?? '') ?></td>
    <td><?= htmlspecialchars($fila['DatoNuevo'] ?? '') ?></td>
    <td><?= $fila['idHistorial'] ?></td>
    <td><?= htmlspecialchars($fila['NombreCompletoUsuario'] ?? $fila['NombreUsuario']) ?></td>
    <td><?= date('Y-m-d', strtotime($fila['Fecha'])) ?></td>
    <td><?= date('H:i:s', strtotime($fila['Fecha'])) ?></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="9">No hay registros.</td></tr>
<?php endif; ?>
</tbody>
</table>
</section>

</main>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

<script>
document.getElementById("btnMenu").onclick = () =>
    document.getElementById("menuLateral").classList.toggle("menu-activo");

function setModulo(valor, e){
    e.preventDefault();
    document.getElementById('inputModulo').value = valor;
    document.getElementById('formFiltros').submit();
}

function autoSubmit(){
    document.getElementById('formFiltros').submit();
}

document.querySelectorAll('.categoria-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const dropdown = this.nextElementSibling;
        document.querySelectorAll('.dropdown-content').forEach(d => {
            if (d !== dropdown) d.style.display = 'none';
        });
        dropdown.style.display =
            dropdown.style.display === 'block' ? 'none' : 'block';
    });
});

document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-content').forEach(d => {
        d.style.display = 'none';
    });
});
</script>

</body>
</html>