<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes | Aurum Ferri</title>
    <link rel="stylesheet" href="assets/css/Clientes.css">
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
        <li><a href="Clientes.php" class="activo"><img src="assets/icons/Clientes.png"><span>Clientes</span></a></li>
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
    <h1 class="page-title">Clientes</h1>

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

<!-- ================= CONTENIDO ================= -->
<main class="contenido">

    <section class="acciones-barra">
        <a href="AgregarCliente.php" class="accion-btn primero">
            <img src="assets/icons/Agregar.png">
            Nuevo
        </a>

        <a href="AbonosCredito.php" class="accion-btn primero">
            <img src="assets/icons/Abonar.png">
            Abonar a Crédito
        </a>

        <a href="#" class="accion-btn segundo" onclick="descargarClientes()">
            <img src="assets/icons/Descargar.png">
            Descargar
        </a>

        <!-- ================= FILTRO ESTATUS ================= -->
        <div class="dropdown">
            <button class="accion-btn categoria-btn filtro-segundo">
                <img src="assets/icons/Filtro.png">
                Filtrar
            </button>
            <div class="dropdown-content">
                <a href="#" onclick="filtrar('all', event)">Todos</a>
                <a href="#" onclick="filtrar('Activo', event)">Activo</a>
                <a href="#" onclick="filtrar('Inactivo', event)">Inactivo</a>
            </div>
        </div>
    </section>

    <!-- ================= TARJETAS ================= -->
    <section class="cards-container">
        <?php while ($c = $clientes->fetch_assoc()): ?>

        <?php
            $estatus = $c['Estatus'] ?? 'Activo';
            $claseEstatus = strtolower($estatus) === 'activo' ? 'estatus-activo' : 'estatus-inactivo';

            // -------------------------------
            // Rutas de imagen de cliente
            // -------------------------------
            $nombreImagen = $c['Imagen'] ?? '';
            $rutaServidor = __DIR__ . '/../../public/Imagenes/Clientes/';
            $rutaWeb = 'Imagenes/Clientes/';

            if (!empty($nombreImagen) && file_exists($rutaServidor . basename($nombreImagen))) {
                $rutaImagen = $rutaWeb . basename($nombreImagen);
            } else {
                $rutaImagen = $rutaWeb . 'default.png';
            }
        ?>

        <div class="card" data-estatus="<?= htmlspecialchars($estatus) ?>">

            <span class="estatus <?= $claseEstatus ?>">
                <?= htmlspecialchars($estatus) ?>
            </span>

            <img src="<?= htmlspecialchars($rutaImagen) ?>" class="avatar">

            <div class="card-info">
                <h3><?= htmlspecialchars($c['Nombre']." ".$c['Paterno']." ".$c['Materno']) ?></h3>
                <p class="rol"><?= htmlspecialchars($c['TipoCliente'] ?? 'Cliente') ?></p>
                <p>📧 <?= htmlspecialchars($c['Email'] ?? 'Sin email') ?></p>
                <p>📞 <?= htmlspecialchars($c['Telefono'] ?? 'Sin teléfono') ?></p>
                <p>💳 Crédito: $<?= number_format($c['Credito'] ?? 0,2) ?></p>
                <p>📊 Límite: $<?= number_format($c['Limite'] ?? 0,2) ?></p>
            </div>

            <div class="card-actions">
                <a href="EditarClientes.php?idCliente=<?= $c['idCliente'] ?>" class="edit">
                    <img src="assets/icons/Editar.png">
                </a>

                <a href="BorrarCliente.php?idCliente=<?= $c['idCliente'] ?>"
                   class="delete"
                   onclick="return confirm('¿Eliminar cliente?')">
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

const textoFiltroOriginal = 'Filtrar';
let filtroEstatusActual = 'all';

function filtrar(valor, event){
    event.preventDefault();
    filtroEstatusActual = valor;

    const btnFiltro = document.querySelector('.categoria-btn');

    document.querySelectorAll(".card").forEach(card => {
        card.style.display = (valor === 'all' || card.dataset.estatus === valor) ? 'flex' : 'none';
    });

    btnFiltro.innerHTML = `<img src="assets/icons/Filtro.png">${valor === 'all' ? 'Filtrar' : valor}`;
    event.target.closest('.dropdown-content').style.display = 'none';
}

function descargarClientes() {
    let url = 'TicketClientes.php';
    if (filtroEstatusActual !== 'all') {
        url += '?estatus=' + encodeURIComponent(filtroEstatusActual);
    }
    window.location.href = url;
}

document.querySelectorAll('.categoria-btn').forEach(btn => {
    btn.onclick = (e) => {
        e.stopPropagation();
        document.querySelectorAll(".dropdown-content").forEach(dd => dd.style.display = 'none');
        btn.nextElementSibling.style.display = 'block';
    };
});

window.onclick = () => {
    document.querySelectorAll(".dropdown-content").forEach(dd => dd.style.display = 'none');
};
</script>

</body>
</html>
