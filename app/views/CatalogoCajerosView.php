<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inventario | Aurum Ferri</title>
<link rel="stylesheet" href="assets/css/CatalogoCajeros.css">
<link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<?php if($mensaje_flotante): ?>
    <div class="alert-message alert-success"><?= htmlspecialchars($mensaje_flotante) ?></div>
<?php endif; ?>

<!-- ================= MENÚ ================= -->
<nav class="menu" id="menuLateral">
    <div class="menu-header">
        <img src="assets/icons/Logo.png" class="menu-logo" id="btnMenu">
        <span class="menu-title">Aurum Ferri</span>
    </div>
    <ul>
        <li><a href="InicioTrabajadores.php"><img src="assets/icons/Inicio.png"><span>Inicio</span></a></li>
        <li><a href="AbonoCajeros.php"><img src="assets/icons/Abonar.png"><span>Pagos de crédito</span></a></li>
        <li><a href="PedidosCajeros.php"><img src="assets/icons/Pedidos.png"><span>Pedidos</span></a></li>
        <li><a href="CatalogoCajeros.php" class="activo"><img src="assets/icons/Catalogo.png"><span>Catálogo</span></a></li>
        <div class="menu-separator"></div>
        <li><a href="Login.php"><img src="assets/icons/Salir.png"><span>Salir</span></a></li>
        <li class="help"><a href="Ayuda.php"><img src="assets/icons/Ayuda.png"><span>Ayuda</span></a></li>
    </ul>
</nav>

<header class="page-header">
    <h1 class="page-title">Inventario</h1>
    <div class="header-user">
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

<?php
/* ================== RUTAS ================== */
$rutaServidor = __DIR__ . '/../../public/Imagenes/Productos/';
$rutaWeb = 'Imagenes/Productos/';
if (!is_dir($rutaServidor)) mkdir($rutaServidor, 0755, true);

foreach ($productos as $p):
    $estadoTexto = ($p['Stock'] > 0) ? 'Con existencias' : 'Agotado';
    $estadoClase = ($p['Stock'] > 0) ? 'estatus-activo' : 'estatus-inactivo';

    $nombreImagen = $p['Imagen'] ?? '';
    if (!empty($nombreImagen) && file_exists($rutaServidor . basename($nombreImagen))) {
        $imagenProducto = $rutaWeb . basename($nombreImagen);
    } else {
        $imagenProducto = $rutaWeb . "default.png";
    }
?>

<div class="card"
     data-categoria="<?= htmlspecialchars($p['Categoria']) ?>"
     data-estatus="<?= ($p['Stock']>0?'Activo':'Inactivo') ?>"
     onclick="abrirModal(<?= $p['idProducto'] ?>, '<?= htmlspecialchars($p['Producto']) ?>', <?= $p['Stock'] ?>)">
    <span class="estatus <?= $estadoClase ?>"><?= $estadoTexto ?></span>
    <img src="<?= htmlspecialchars($imagenProducto) ?>" class="avatar" alt="Producto">
    <div class="card-info">
        <h3><?= htmlspecialchars($p['Producto']) ?></h3>
        <p><?= htmlspecialchars($p['Categoria']) ?></p>
        <p>Stock: <strong><?= $p['Stock'] ?></strong></p>
    </div>
</div>

<?php endforeach; ?>
</section>

</main>

<!-- ================= MODAL ================= -->
<div class="modal" id="modalCantidad">
    <div class="modal-content">
        <h3 id="modalProductoNombre"></h3>
        <form method="POST">
            <input type="hidden" name="idProducto" id="modalProductoId">
            <label>Cantidad:</label>
            <input type="number" name="cantidad" id="modalCantidadInput" min="1" value="1">
            <br><br>
            <div class="btn-container">
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-guardar">Agregar</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

<script>
document.getElementById("btnMenu").onclick = () => {
    document.getElementById("menuLateral").classList.toggle("menu-activo");
};

// ================= FILTROS =================
const filtros = { categoria:'all', estatus:'all' };
const textosOriginales = { categoria:'Categorías', estatus:'Estado' };

function filtrar(valor, tipo, event){
    event.preventDefault();
    filtros[tipo] = valor;
    let cards = document.querySelectorAll(".card");
    let visibleCount = 0;
    cards.forEach(card=>{
        let visible = true;
        if(filtros.categoria !== 'all' && card.dataset.categoria !== filtros.categoria) visible = false;
        if(filtros.estatus !== 'all' && card.dataset.estatus !== filtros.estatus) visible = false;
        card.style.display = visible ? 'flex' : 'none';
        if(visible) visibleCount++;
    });
    document.getElementById('mensajeFiltro').style.display = visibleCount === 0 ? 'block' : 'none';
    const boton = tipo==='categoria'?'btnCategoria':'btnEstatus';
    document.getElementById(boton).innerHTML = `<img src="Imagenes/Filtro.png">${valor==='all'?textosOriginales[tipo]:valor}`;
    event.target.closest('.dropdown-content').style.display='none';
}

document.querySelectorAll('.categoria-btn').forEach(btn=>{
    btn.onclick = e=>{
        e.stopPropagation();
        document.querySelectorAll('.dropdown-content').forEach(d=>d.style.display='none');
        btn.nextElementSibling.style.display = 'block';
    };
});

window.onclick = ()=>document.querySelectorAll('.dropdown-content').forEach(d=>d.style.display='none');

// ================= MODAL =================
function abrirModal(id, nombre, stock){
    document.getElementById('modalProductoId').value = id;
    document.getElementById('modalProductoNombre').innerText = nombre;
    const inputCantidad = document.getElementById('modalCantidadInput');
    inputCantidad.max = stock;
    inputCantidad.value = stock>0?1:0;
    document.getElementById('modalCantidad').style.display='flex';
}
function cerrarModal(){
    document.getElementById('modalCantidad').style.display='none';
}
</script>

</body>
</html>
