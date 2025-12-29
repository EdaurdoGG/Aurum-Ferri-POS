<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Abonos a Crédito | Aurum Ferri</title>
<link rel="icon" href="assets/icons/Logo.png">
<link rel="stylesheet" href="assets/css/AbonoCajeros.css">
</head>

<body>

<!-- ================= MENÚ ================= -->
<nav class="menu" id="menuLateral">
    <div class="menu-header">
        <img src="assets/icons/Logo.png" class="menu-logo" id="btnMenu">
        <span class="menu-title">Aurum Ferri</span>
    </div>
    <ul>
        <li><a href="InicioTrabajadores.php"><img src="assets/icons/Inicio.png"><span>Inicio</span></a></li>
        <li><a href="AbonoCajeros.php" class="activo"><img src="assets/icons/Abonar.png"><span>Pagos de crédito</span></a></li>
        <li><a href="PedidosCajeros.php"><img src="assets/icons/Pedidos.png"><span>Pedidos</span></a></li>
        <li><a href="CatalogoCajeros.php"><img src="assets/icons/Catalogo.png"><span>Catálogo</span></a></li>
        <div class="menu-separator"></div>
        <li><a href="Login.php"><img src="assets/icons/Salir.png"><span>Salir</span></a></li>
        <li class="help"><a href="Ayuda.php"><img src="assets/icons/Ayuda.png"><span>Ayuda</span></a></li>
    </ul>
</nav>

<!-- ================= HEADER ================= -->
<header class="page-header">
    <h1 class="page-title">Abonos a Crédito</h1>

    <div class="header-user">
        <img src="<?= htmlspecialchars($fotoUsuario) ?>" class="user-avatar">
        <div class="user-info">
            <strong><?= htmlspecialchars($nombreUsuario) ?></strong>
            <span><?= htmlspecialchars($rolUsuarioNombre) ?></span>
        </div>
    </div>
</header>

<div class="divider"></div>

<main class="contenido">

<?php if($mensaje): ?>
<div class="alert-message alert-<?= $tipoMensaje ?>">
    <?= htmlspecialchars($mensaje) ?>
</div>
<?php endif; ?>

<section class="cards-container">

<?php
// ================= RUTAS DE IMAGENES =================
$rutaServidor = __DIR__ . '/../../public/Imagenes/Clientes/';
$rutaWeb = 'Imagenes/Clientes/';
if (!is_dir($rutaServidor)) mkdir($rutaServidor, 0755, true);

foreach($clientes as $c):

    $estatusClase = strtolower($c['Estatus']) === 'activo'
        ? 'estatus-activo'
        : 'estatus-inactivo';

    $nombreImagen = $c['Imagen'] ?? '';
    if (!empty($nombreImagen) && file_exists($rutaServidor . basename($nombreImagen))) {
        $rutaImagen = $rutaWeb . basename($nombreImagen);
    } else {
        $rutaImagen = $rutaWeb . 'default.png';
    }
?>

<div class="card"
     onclick="abrirModal(
        <?= $c['idCliente'] ?>,
        '<?= htmlspecialchars($c['Nombre'].' '.$c['Paterno'].' '.$c['Materno']) ?>',
        <?= $c['Credito'] ?>
     )">

    <span class="estatus <?= $estatusClase ?>">
        <?= htmlspecialchars($c['Estatus']) ?>
    </span>

    <img src="<?= htmlspecialchars($rutaImagen) ?>" class="avatar">

    <div class="card-info">
        <h3><?= htmlspecialchars($c['Nombre'].' '.$c['Paterno'].' '.$c['Materno']) ?></h3>
        <p>💳 Crédito: $<?= number_format($c['Credito'],2) ?></p>
        <p>📊 Límite: $<?= number_format($c['Limite'],2) ?></p>
    </div>
</div>

<?php endforeach; ?>

</section>
</main>

<!-- ================= MODAL ================= -->
<div class="modal" id="modalAbono" style="display:none;" onclick="cerrarModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <h2 id="modalNombre"></h2>
        <p>
            Crédito pendiente:
            <strong>$<span id="modalCredito"></span></strong>
        </p>

        <form method="POST">
            <input type="hidden" name="idCliente" id="modalIdCliente">
            <input type="number" step="0.01" min="0.01" name="monto" placeholder="Monto a abonar" required>
            <div class="modal-actions">
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" name="abonar" class="btn-guardar">Registrar</button>
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

function abrirModal(id, nombre, credito){
    document.getElementById('modalIdCliente').value = id;
    document.getElementById('modalNombre').innerText = nombre;
    document.getElementById('modalCredito').innerText = Number(credito).toFixed(2);
    document.getElementById('modalAbono').style.display = 'flex';
}

function cerrarModal(){
    document.getElementById('modalAbono').style.display = 'none';
}
</script>

</body>
</html>
