<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedidos | Aurum Ferri</title>
<link rel="stylesheet" href="assets/css/PedidosPendientes.css">
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
        <li><a href="PedidosPendientes.php" class="activo"><img src="assets/icons/Pedidos.png"><span>Pedidos</span></a></li>
        <li><a href="Auditoria.php"><img src="assets/icons/Auditorias.png"><span>Auditoría</span></a></li>
        <div class="menu-separator"></div>
        <li><a href="Login.php"><img src="assets/icons/Salir.png"><span>Salir</span></a></li>
        <li class="help"><a href="Ayuda.php"><img src="assets/icons/Ayuda.png"><span>Ayuda</span></a></li>
    </ul>
</nav>

<header class="page-header">
    <h1 class="page-title">Gestión de Pedidos</h1>
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

<!-- ================= FILTROS ================= -->
<section class="acciones-barra">
<form method="GET" id="formFiltros" class="filtros-avanzados">
    <div class="fecha-box">
        <input type="date" name="fecha" value="<?= htmlspecialchars($fecha) ?>" onchange="autoSubmit()">
    </div>

    <div class="dropdown">
        <button class="accion-btn categoria-btn filtro-segundo">
            <img src="assets/icons/Filtro.png"><?= $estatus ?: 'Estatus' ?>
        </button>
        <div class="dropdown-content">
            <a href="#" onclick="setEstatus('', event)">Todos</a>
            <a href="#" onclick="setEstatus('Pendiente', event)">Pendiente</a>
            <a href="#" onclick="setEstatus('Parcial', event)">Parcial</a>
            <a href="#" onclick="setEstatus('Surtido', event)">Surtido</a>
            <a href="#" onclick="setEstatus('Cancelado', event)">Cancelado</a>
        </div>
    </div>

    <div class="dropdown">
        <button class="accion-btn categoria-btn filtro-segundo">
            <img src="assets/icons/Filtro.png"><?= $cliente ?: 'Clientes' ?>
        </button>
        <div class="dropdown-content">
            <a href="#" onclick="setCliente('', event)">Todos</a>
            <?php foreach ($clientes as $c): ?>
            <a href="#" onclick="setCliente('<?= htmlspecialchars($c) ?>', event)">
                <?= htmlspecialchars($c) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <input type="hidden" name="estatus" id="inputEstatus" value="<?= htmlspecialchars($estatus) ?>">
    <input type="hidden" name="cliente" id="inputCliente" value="<?= htmlspecialchars($cliente) ?>">

    <a class="accion-btn oscuro"
       href="TicketPedidos.php?estatus=<?= urlencode($estatus) ?>&cliente=<?= urlencode($cliente) ?>&fecha=<?= urlencode($fecha) ?>">
       <img src="assets/icons/Descargar.png">Descargar
    </a>
</form>
</section>

<?php if ($sinResultados): ?>
<div class="alert-message">No se encontraron pedidos con el filtro seleccionado.</div>
<?php endif; ?>

<!-- ================= CARDS ================= -->
<section class="cards-container">
<?php foreach ($pedidos as $p): ?>
<div class="pedido-card" onclick='abrirModal(<?= json_encode($p, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
    <div class="pedido-header">
        <h3>Pedido #<?= $p['idPedido'] ?></h3>
        <span class="estado <?= strtolower($p['Estado']) ?>"><?= $p['Estado'] ?></span>
    </div>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($p['Cliente']) ?></p>
    <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($p['Fecha'])) ?></p>
</div>
<?php endforeach; ?>
</section>

<!-- ================= MODAL DETALLE PEDIDO ================= -->
<div class="modal" id="modalPedido">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal()">&times;</span>
        <h2 id="modalTitulo"></h2>
        <p><strong>Cliente:</strong> <span id="modalCliente"></span></p>
        <p><strong>Estado:</strong> <span id="modalEstado"></span></p>
        <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>

        <table class="modal-tabla">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody id="modalProductos"></tbody>
        </table>

        <div class="modal-acciones" id="modalAcciones"></div>
    </div>
</div>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

<script>
let pedidoActual = null;

function abrirModal(pedido){
    pedidoActual = pedido;
    document.getElementById('modalPedido').style.display = 'flex';
    document.getElementById('modalTitulo').innerText = 'Pedido #' + pedido.idPedido;
    document.getElementById('modalCliente').innerText = pedido.Cliente;
    document.getElementById('modalEstado').innerText = pedido.Estado;
    document.getElementById('modalFecha').innerText = pedido.Fecha;

    let html = '';
    pedido.productos.forEach(p => {
        html += `<tr>
            <td>${p.Producto}</td>
            <td>${p.Cantidad}</td>
            <td>$${p.Precio}</td>
            <td>$${p.Subtotal}</td>
        </tr>`;
    });
    document.getElementById('modalProductos').innerHTML = html;

    const accionesDiv = document.getElementById('modalAcciones');
    accionesDiv.innerHTML = '';

    // Ajuste según estado: Pendiente -> Preparar/Cancelar, Parcial -> Cobrar/Cancelar
    if (pedido.Estado === 'Pendiente') {
        accionesDiv.innerHTML = `
            <button class="btn-aceptar" onclick="accionPedido('preparar')">Preparar</button>
            <button class="btn-cancelar" onclick="accionPedido('cancelar')">Cancelar</button>`;
    } else if (pedido.Estado === 'Parcial') {
        accionesDiv.innerHTML = `
            <button class="btn-aceptar" onclick="accionPedido('cobrar')">Cobrar</button>
            <button class="btn-cancelar" onclick="accionPedido('cancelar')">Cancelar</button>`;
    }
}

function cerrarModal(){
    document.getElementById('modalPedido').style.display = 'none';
}

function accionPedido(accion){
    if(accion === 'cancelar' && !confirm('¿Seguro que deseas cancelar este pedido?')) return;

    const fd = new FormData();
    fd.append('accion', accion);
    fd.append('idPedido', pedidoActual.idPedido);

    fetch('', { method:'POST', body: fd })
        .then(r => r.json())
        .then(res => { alert(res.mensaje); if(res.ok) location.reload(); });
}

document.getElementById('modalPedido').addEventListener('click', e => {
    if(e.target.id === 'modalPedido') cerrarModal();
});

document.getElementById("btnMenu").onclick = () =>
    document.getElementById("menuLateral").classList.toggle("menu-activo");

function setEstatus(valor, e){
    e.preventDefault();
    document.getElementById('inputEstatus').value = valor;
    document.getElementById('formFiltros').submit();
}
function setCliente(valor, e){
    e.preventDefault();
    document.getElementById('inputCliente').value = valor;
    document.getElementById('formFiltros').submit();
}
function autoSubmit(){ document.getElementById('formFiltros').submit(); }

/* ================= DROPDOWNS ================= */
document.querySelectorAll('.categoria-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.preventDefault(); e.stopPropagation();
        const dropdown = this.nextElementSibling;
        document.querySelectorAll('.dropdown-content').forEach(d => { if(d!==dropdown)d.style.display='none'; });
        dropdown.style.display = dropdown.style.display==='block'?'none':'block';
    });
});
document.addEventListener('click',()=>document.querySelectorAll('.dropdown-content').forEach(d=>d.style.display='none'));
</script>

</body>
</html>