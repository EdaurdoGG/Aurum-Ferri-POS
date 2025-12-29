<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inventario | Aurum Ferri</title>
<link rel="stylesheet" href="assets/css/Almacen.css">
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

<header class="page-header">
    <h1 class="page-title">Inventario</h1>
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

<!-- ================= ACCIONES ================= -->
<section class="acciones-barra">
    <a href="AgregarProducto.php" class="accion-btn primero"><img src="assets/icons/Agregar.png">Nuevo</a>
    <a href="Proveedores.php" class="accion-btn segundo"><img src="assets/icons/Proveedor.png">Proveedores</a>
    <a href="Categorias.php" class="accion-btn segundo"><img src="assets/icons/Categorias.png">Categorías</a>
    <a href="#" class="accion-btn segundo" onclick="descargarInventario()">
        <img src="assets/icons/Descargar.png">Descargar
    </a>


    <!-- ================= FILTROS ================= -->
    <div class="dropdown">
        <button id="btnCategoria" class="accion-btn categoria-btn filtro-segundo">
            <img src="assets/icons/Filtro.png">Categorías
        </button>
        <div class="dropdown-content">
            <a href="#" onclick="filtrar('all','categoria',event)">Todos</a>
            <?php foreach(array_keys($categorias) as $cat): ?>
                <a href="#" onclick="filtrar('<?= htmlspecialchars($cat) ?>','categoria',event)">
                    <?= htmlspecialchars($cat) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="dropdown">
        <button id="btnProveedor" class="accion-btn categoria-btn filtro-segundo">
            <img src="assets/icons/Filtro.png">Proveedores
        </button>
        <div class="dropdown-content">
            <a href="#" onclick="filtrar('all','proveedor',event)">Todos</a>
            <?php foreach(array_keys($proveedores) as $prov): ?>
                <a href="#" onclick="filtrar('<?= htmlspecialchars($prov) ?>','proveedor',event)">
                    <?= htmlspecialchars($prov) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="dropdown">
        <button id="btnEstatus" class="accion-btn categoria-btn filtro-segundo">
            <img src="assets/icons/Filtro.png">Estado
        </button>
        <div class="dropdown-content">
            <a href="#" onclick="filtrar('all','estatus',event)">Todos</a>
            <a href="#" onclick="filtrar('Activo','estatus',event)">Activo</a>
            <a href="#" onclick="filtrar('Inactivo','estatus',event)">Inactivo</a>
        </div>
    </div>
</section>

<div id="mensajeFiltro" class="alert-message alert-error" style="display:none">
    No se encontraron productos con el filtro seleccionado.
</div>

<!-- ================= PRODUCTOS ================= -->
<section class="cards-container" id="cardsContainer">
<?php foreach ($productos as $p):
    $estadoTexto = ($p['Stock'] > 0) ? 'Con existencias' : 'Agotado';
    $estadoClase = ($p['Stock'] > 0) ? 'estatus-activo' : 'estatus-inactivo';

    // Imagen del producto
    $imagenProducto = !empty($p['Imagen']) && file_exists($rutaServidor . basename($p['Imagen']))
        ? $rutaWeb . basename($p['Imagen'])
        : $rutaWeb . "default.png";
?>
<div class="card"
     data-categoria="<?= htmlspecialchars($p['Categoria']) ?>"
     data-proveedor="<?= htmlspecialchars($p['Proveedor']) ?>"
     data-estatus="<?= ($p['Stock']>0?'Activo':'Inactivo') ?>">

    <span class="estatus <?= $estadoClase ?>"><?= $estadoTexto ?></span>
    <img src="<?= htmlspecialchars($imagenProducto) ?>" class="avatar" alt="Producto">

    <div class="card-info">
        <h3><?= htmlspecialchars($p['Producto']) ?></h3>
        <p><?= htmlspecialchars($p['Categoria']) ?></p>
        <p>Proveedor: <?= htmlspecialchars($p['Proveedor']) ?></p>
        <p>Stock: <strong><?= $p['Stock'] ?></strong></p>
        <p>Compra: $<?= number_format($p['PrecioCompra'],2) ?></p>
        <p>Venta: $<?= number_format($p['PrecioVenta'],2) ?></p>
    </div>

    <div class="card-actions">
        <a href="EditarProducto.php?idProducto=<?= $p['idProducto'] ?>"><img src="assets/icons/Editar.png"></a>
        <a href="BorrarProducto.php?idProducto=<?= $p['idProducto'] ?>" onclick="return confirm('¿Eliminar producto?')"><img src="assets/icons/Borrar.png"></a>
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

const filtros = { categoria:'all', proveedor:'all', estatus:'all' };
const textosOriginales = { categoria:'Categorías', proveedor:'Proveedores', estatus:'Estado' };

function filtrar(valor, tipo, event){
    event.preventDefault();
    filtros[tipo] = valor;

    let cards = document.querySelectorAll(".card");
    let visibleCount = 0;

    cards.forEach(card=>{
        let visible = true;
        if(filtros.categoria !== 'all' && card.dataset.categoria !== filtros.categoria) visible = false;
        if(filtros.proveedor !== 'all' && card.dataset.proveedor !== filtros.proveedor) visible = false;
        if(filtros.estatus !== 'all' && card.dataset.estatus !== filtros.estatus) visible = false;
        card.style.display = visible ? 'flex' : 'none';
        if(visible) visibleCount++;
    });

    document.getElementById('mensajeFiltro').style.display = visibleCount === 0 ? 'block' : 'none';

    const boton = document.getElementById(
        tipo === 'categoria' ? 'btnCategoria' :
        tipo === 'proveedor' ? 'btnProveedor' :
        'btnEstatus'
    );
    boton.innerHTML = `<img src="assets/icons/Filtro.png">${valor === 'all' ? textosOriginales[tipo] : valor}`;

    event.target.closest('.dropdown-content').style.display = 'none';
}

document.querySelectorAll('.categoria-btn').forEach(btn=>{
    btn.onclick = e=>{
        e.stopPropagation();
        document.querySelectorAll('.dropdown-content').forEach(d=>d.style.display='none');
        btn.nextElementSibling.style.display = 'block';
    };
});

window.onclick = ()=>document.querySelectorAll('.dropdown-content').forEach(d=>d.style.display='none');

function descargarInventario() {
    const params = new URLSearchParams();

    if (filtros.categoria !== 'all') {
        params.append('categoria', filtros.categoria);
    }
    if (filtros.proveedor !== 'all') {
        params.append('proveedor', filtros.proveedor);
    }
    if (filtros.estatus !== 'all') {
        params.append('estatus', filtros.estatus);
    }

    window.location.href = 'TicketInventario.php?' + params.toString();
}

</script>

</body>
</html>
