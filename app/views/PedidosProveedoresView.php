<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedidos | Aurum Ferri</title>
<link rel="stylesheet" href="assets/css/PedidosProveedores.css">
<link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<header class="page-header">
    <h1 class="page-title">Lista de Pedidos</h1>

    <div class="header-user">
        <a href="InicioProveedores.php" class="notificacion">
            <img src="assets/icons/InicioProveedores.png">
        </a>

        <a href="CarritoProveedores.php" class="notificacion">
            <img src="assets/icons/Carrito.png" alt="Carrito">
            <?php if ($contador > 0): ?>
                <span class="badge"><?= $contador ?></span>
            <?php endif; ?>
        </a>

        <a href="PedidosProveedores.php" class="notificacion">
            <img src="assets/icons/PedidosProveedores.png">
        </a>

        <a href="Login.php" class="notificacion">
            <img src="assets/icons/SalirProveedores.png">
        </a>

        <img src="<?= $fotoUsuario ?>" class="user-avatar">
        <div class="user-info">
            <strong><?= $nombreUsuario ?></strong>
            <span><?= $rolUsuarioNombre ?></span>
        </div>
    </div>
</header>

<div class="divider"></div>

<main class="contenido">

<!-- ================= FILTROS ================= -->
<section class="acciones-barra">
<form method="GET" id="formFiltros" class="filtros-avanzados">

    <div class="fecha-box">
        <input type="date" name="fecha" value="<?= htmlspecialchars($fecha) ?>" onchange="this.form.submit()">
    </div>

    <div class="dropdown">
        <button type="button" class="accion-btn categoria-btn filtro-segundo">
            <img src="assets/icons/Filtro.png">
            <?= $estatus ?: 'Estatus' ?>
        </button>
        <div class="dropdown-content">
            <a href="#" onclick="setEstatus('',event)">Todos</a>
            <a href="#" onclick="setEstatus('Pendiente',event)">Pendiente</a>
            <a href="#" onclick="setEstatus('Parcial',event)">Parcial</a>
            <a href="#" onclick="setEstatus('Surtido',event)">Surtido</a>
            <a href="#" onclick="setEstatus('Cancelado',event)">Cancelado</a>
        </div>
    </div>

    <div class="dropdown">
        <button type="button" class="accion-btn categoria-btn filtro-segundo">
            <img src="assets/icons/Filtro.png">
            <?= $cliente ?: 'Clientes' ?>
        </button>
        <div class="dropdown-content">
            <a href="#" onclick="setCliente('',event)">Todos</a>
            <?php foreach ($clientes as $c): ?>
                <a href="#" onclick="setCliente('<?= htmlspecialchars($c) ?>',event)">
                    <?= htmlspecialchars($c) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <input type="hidden" name="estatus" id="inputEstatus" value="<?= htmlspecialchars($estatus) ?>">
    <input type="hidden" name="cliente" id="inputCliente" value="<?= htmlspecialchars($cliente) ?>">

</form>
</section>

<?php if ($sinResultados): ?>
<div class="alert-message">No se encontraron pedidos.</div>
<?php endif; ?>

<!-- ================= TARJETAS ================= -->
<section class="cards-container">
<?php foreach ($pedidos as $p): ?>
<div class="pedido-card"
     onclick='abrirModal(<?= json_encode($p, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
    <div class="pedido-header">
        <h3>Pedido #<?= $p['idPedido'] ?></h3>
        <span class="estado <?= strtolower($p['Estado']) ?>">
            <?= $p['Estado'] ?>
        </span>
    </div>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($p['Cliente']) ?></p>
    <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($p['Fecha'])) ?></p>
</div>
<?php endforeach; ?>
</section>

</main>

<!-- ================= MODAL ================= -->
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

/* ===== MODAL ===== */
function abrirModal(pedido){
    pedidoActual = pedido;
    modalPedido.style.display = 'flex';

    modalTitulo.textContent = 'Pedido #' + pedido.idPedido;
    modalCliente.textContent = pedido.Cliente;
    modalEstado.textContent = pedido.Estado;
    modalFecha.textContent = pedido.Fecha;

    let html = '';
    pedido.productos.forEach(p => {
        html += `<tr>
            <td>${p.Producto}</td>
            <td>${p.Cantidad}</td>
            <td>$${p.Precio}</td>
            <td>$${p.Subtotal}</td>
        </tr>`;
    });
    modalProductos.innerHTML = html;

    modalAcciones.innerHTML = '';
    if (pedido.Estado === 'Pendiente') {
        modalAcciones.innerHTML = `
            <button class="btn-cancelar" onclick="accionPedido('cancelar')">
                Cancelar
            </button>`;
    }
}

function cerrarModal(){
    modalPedido.style.display = 'none';
}

function accionPedido(accion){
    if(!confirm('¿Seguro?')) return;
    const fd = new FormData();
    fd.append('accion', accion);
    fd.append('idPedido', pedidoActual.idPedido);

    fetch('', { method:'POST', body: fd })
        .then(r=>r.json())
        .then(res=>{
            alert(res.mensaje);
            if(res.ok) location.reload();
        });
}

/* ===== FILTROS ===== */
function setEstatus(v,e){
    e.preventDefault(); e.stopPropagation();
    inputEstatus.value = v;
    formFiltros.submit();
}
function setCliente(v,e){
    e.preventDefault(); e.stopPropagation();
    inputCliente.value = v;
    formFiltros.submit();
}

/* ===== DROPDOWNS ===== */
document.querySelectorAll('.categoria-btn').forEach(btn=>{
    btn.addEventListener('click',e=>{
        e.stopPropagation();
        const d = btn.nextElementSibling;
        document.querySelectorAll('.dropdown-content').forEach(x=>x.style.display='none');
        d.style.display = d.style.display==='block'?'none':'block';
    });
});
document.addEventListener('click',()=>{
    document.querySelectorAll('.dropdown-content').forEach(d=>d.style.display='none');
});
</script>

</body>
</html>