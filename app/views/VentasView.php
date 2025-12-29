<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ventas | Aurum Ferri</title>
<link rel="stylesheet" href="assets/css/Ventas.css">
<link rel="icon" href="assets/icons/Logo.png">

<style>
.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.5);
    justify-content:center;
    align-items:center;
    z-index:9999;
}
.modal-content{
    background:white;
    padding:20px;
    border-radius:10px;
    width:80%;
    max-width:700px;
}
.modal-close{
    float:right;
    cursor:pointer;
    font-size:22px;
    font-weight:bold;
}
</style>
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
        <li><a href="Ventas.php" class="activo"><img src="assets/icons/Ventas.png"><span>Ventas</span></a></li>
        <li><a href="PedidosPendientes.php"><img src="assets/icons/Pedidos.png"><span>Pedidos</span></a></li>
        <li><a href="Auditoria.php"><img src="assets/icons/Auditorias.png"><span>Auditoría</span></a></li>
        <div class="menu-separator"></div>
        <li><a href="Login.php"><img src="assets/icons/Salir.png"><span>Salir</span></a></li>
        <li class="help"><a href="Ayuda.php"><img src="assets/icons/Ayuda.png"><span>Ayuda</span></a></li>
    </ul>
</nav>

<header class="page-header">
    <h1 class="page-title">Resumen de Ventas</h1>

    <div class="header-user">
        <a href="Notificaciones.php" class="notificacion">
            <?php if ($notificacionesNoLeidas > 0): ?>
                <span class="badge">
                    <?= $notificacionesNoLeidas > 9 ? '9+' : $notificacionesNoLeidas ?>
                </span>
            <?php endif; ?>
            <img src="assets/icons/Campana.png" alt="Notificaciones">
        </a>

        <img src="<?= htmlspecialchars($fotoUsuario) ?>" class="user-avatar">
        
        <div class="user-info">
            <strong><?= htmlspecialchars($nombreUsuario) ?></strong>
            <span><?= htmlspecialchars($rolUsuarioNombre) ?></span>
        </div>
    </div>
</header>

<main class="contenido">

<!-- ================= FILTROS ================= -->
<section class="acciones-barra">
<form method="POST">
<input type="date" name="fecha" value="<?= $fechaSeleccionada ?>" onchange="this.form.submit()">

<select name="empleado" onchange="this.form.submit()">
<option value="all">Todos los empleados</option>
<?php foreach ($empleados as $idEmp => $nomEmp): ?>
<option value="<?= $idEmp ?>" <?= ($idEmpleadoFiltro==$idEmp)?'selected':'' ?>>
<?= htmlspecialchars($nomEmp) ?>
</option>
<?php endforeach; ?>
</select>
</form>
</section>

<!-- ================= MENSAJE SI NO HAY RESULTADOS ================= -->
<?php if($sinResultados && $filtroAplicado): ?>
<div class="alert-message alert-error">
    No se encontraron ventas con el filtro seleccionado.
</div>
<?php endif; ?>

<!-- ================= TARJETAS ================= -->
<section class="tarjetas-resumen">
<div class="tarjeta azul1"><h3>Total Ventas</h3><p>$<?= number_format($totalVentas,2) ?></p></div>
<div class="tarjeta azul2"><h3>Ganancias</h3><p>$<?= number_format($totalGanancias,2) ?></p></div>
<div class="tarjeta azul3"><h3>Gastos</h3><p>$<?= number_format($totalGastos,2) ?></p></div>
</section>

<!-- ================= TABLA ================= -->
<?php if(!$sinResultados): ?>
<section class="tabla-detalle">
<h2>Ventas del Día</h2>
<table>
<thead>
<tr><th>Venta</th><th>Empleado</th><th>Cliente</th><th>Total</th><th>Hora</th><th>Detalles</th></tr>
</thead>
<tbody>
<?php foreach ($ventas as $v): ?>
<tr>
<td><?= $v['NumeroVenta'] ?></td>
<td><?= htmlspecialchars($v['Empleado']) ?></td>
<td><?= htmlspecialchars($v['Cliente']) ?></td>
<td>$<?= number_format($v['TotalVenta'],2) ?></td>
<td><?= $v['Hora'] ?></td>
<td>
<button class="btn-detalle" data-venta='<?= json_encode($v) ?>'>Ver</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</section>
<?php endif; ?>

<!-- ================= MODAL ================= -->
<div class="modal" id="modalDetalle">
    <div class="modal-content">
        <span class="modal-close" id="cerrarModal">&times;</span>
        <h2>Detalle de Venta</h2>
        <div id="detalleContenido"></div>
    </div>
</div>

</main>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

<script>
const modal = document.getElementById('modalDetalle');
const detalle = document.getElementById('detalleContenido');
const cerrar = document.getElementById('cerrarModal');

document.querySelectorAll('.btn-detalle').forEach(btn=>{
    btn.onclick = () => {
        const v = JSON.parse(btn.dataset.venta);
        detalle.innerHTML = `
            <p><strong>Venta:</strong> ${v.NumeroVenta}</p>
            <p><strong>Empleado:</strong> ${v.Empleado}</p>
            <p><strong>Cliente:</strong> ${v.Cliente}</p>
            <p><strong>Total Invertido:</strong> $${parseFloat(v.TotalInvertido).toFixed(2)}</p>
            <p><strong>Total Venta:</strong> $${parseFloat(v.TotalVenta).toFixed(2)}</p>
            <p><strong>Ganancia:</strong> $${parseFloat(v.Ganancia).toFixed(2)}</p>
            <p><strong>Hora:</strong> ${v.Hora}</p>
        `;
        modal.style.display = 'flex';
    };
});

cerrar.onclick = () => modal.style.display = 'none';
window.onclick = e => { if (e.target === modal) modal.style.display = 'none'; };

document.getElementById("btnMenu").onclick = () => {
    document.getElementById("menuLateral").classList.toggle("menu-activo");
};

const alerta = document.querySelector('.alert-message');
if (alerta) {
    setTimeout(() => {
        alerta.style.opacity = '0';
        setTimeout(() => alerta.remove(), 300);
    }, 2000);
}

</script>

</body>
</html>