<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Abonos a Crédito | Aurum Ferri</title>

    <link rel="icon" href="assets/icons/Logo.png">
    <link rel="stylesheet" href="assets/css/AbonosCredito.css">
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

        <div class="menu-separator"></div>

        <li><a href="Login.php"><img src="assets/icons/Salir.png"><span>Salir</span></a></li>
        <li class="help"><a href="Ayuda.php"><img src="assets/icons/Ayuda.png"><span>Ayuda</span></a></li>
    </ul>
</nav>

<!-- ================= HEADER ================= -->
<header class="page-header">
    <h1 class="page-title">Abonos a Crédito</h1>

    <div class="header-user">
        <div class="notificacion">
            <img src="assets/icons/Campana.png" alt="Notificaciones">
        </div>

        <img src="<?= htmlspecialchars($fotoUsuario) ?>" class="user-avatar">

        <div class="user-info">
            <strong><?= htmlspecialchars($nombreUsuario) ?></strong>
            <span><?= htmlspecialchars($rolUsuarioNombre) ?></span>
        </div>
    </div>
</header>

<div class="divider"></div>

<!-- ================= CONTENIDO ================= -->
<main class="contenido">

<?php if (!empty($mensaje)): ?>
    <div class="alert-message alert-<?= htmlspecialchars($tipoMensaje) ?>">
        <?= htmlspecialchars($mensaje) ?>
    </div>
<?php endif; ?>

<section class="cards-container">

<?php if (empty($clientes)): ?>
    <p class="mensaje-vacio">No hay clientes con crédito pendiente.</p>
<?php endif; ?>

<?php foreach ($clientes as $c): ?>

<?php
    $estatusClase = strtolower($c['Estatus']) === 'activo'
        ? 'estatus-activo'
        : 'estatus-inactivo';

    $rutaImagen = (!empty($c['Imagen']) && file_exists(__DIR__ . '/../../public/' . $c['Imagen']))
        ? $c['Imagen']
        : 'Imagenes/Clientes/default.png';

    $nombreCompleto = trim(
        ($c['Nombre'] ?? '') . ' ' .
        ($c['Paterno'] ?? '') . ' ' .
        ($c['Materno'] ?? '')
    );
?>

<div class="card"
     onclick="abrirModal(
        <?= (int)$c['idCliente'] ?>,
        '<?= htmlspecialchars($nombreCompleto) ?>',
        <?= (float)$c['Credito'] ?>
     )">

    <span class="estatus <?= $estatusClase ?>">
        <?= htmlspecialchars($c['Estatus']) ?>
    </span>

    <img src="<?= htmlspecialchars($rutaImagen) ?>" class="avatar">

    <div class="card-info">
        <h3><?= htmlspecialchars($nombreCompleto) ?></h3>
        <p>💳 Crédito: $<?= number_format($c['Credito'], 2) ?></p>
        <p>📊 Límite: $<?= number_format($c['Limite'], 2) ?></p>
    </div>
</div>

<?php endforeach; ?>

</section>
</main>

<!-- ================= MODAL ABONO ================= -->
<div class="modal" id="modalAbono" style="display:none;" onclick="cerrarModal()">
    <div class="modal-content" onclick="event.stopPropagation()">

        <h2 id="modalNombre"></h2>

        <p>
            Crédito pendiente:
            <strong>$<span id="modalCredito"></span></strong>
        </p>

        <form method="POST">

            <input type="hidden" name="idCliente" id="modalIdCliente">

            <input
                type="number"
                name="monto"
                step="0.01"
                min="0.01"
                placeholder="Monto a abonar"
                required
            >

            <div class="modal-actions">
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">
                    Cancelar
                </button>
                <button type="submit" name="abonar" class="btn-guardar">
                    Registrar
                </button>
            </div>
        </form>

    </div>
</div>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

<!-- ================= JS ================= -->
<script>
document.getElementById("btnMenu").onclick = () => {
    document.getElementById("menuLateral").classList.toggle("menu-activo");
};

function abrirModal(id, nombre, credito) {
    document.getElementById('modalIdCliente').value = id;
    document.getElementById('modalNombre').innerText = nombre;
    document.getElementById('modalCredito').innerText = Number(credito).toFixed(2);
    document.getElementById('modalAbono').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('modalAbono').style.display = 'none';
}
</script>

</body>
</html>
