<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Proveedor</title>
    <link rel="stylesheet" href="assets/css/AgregarProveedor.css">
    <link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<main>
<section class="centro">
<div class="log">
<div class="login">

<!-- ================= MENSAJE FLOTANTE ================= -->
<?php if (!empty($_SESSION['mensaje'])): ?>
    <div class="alert-message <?= $_SESSION['tipo_mensaje']=='error' ? 'alert-error' : 'alert-success' ?>">
        <?= htmlspecialchars($_SESSION['mensaje']) ?>
    </div>
    <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="titulo">
    <h2>Agregar Proveedor</h2>
    <a href="Proveedores.php">
        <img src="assets/icons/Volver.png" class="boton-atras">
    </a>
</div>

<div class="input-group">
    <input type="text" name="Nombre" required placeholder=" ">
    <label>Nombre</label>
</div>

<div class="input-group">
    <input type="text" name="Paterno" placeholder=" ">
    <label>Apellido Paterno</label>
</div>

<div class="input-group">
    <input type="text" name="Materno" placeholder=" ">
    <label>Apellido Materno</label>
</div>

<div class="input-group">
    <input type="text" name="Telefono" placeholder=" ">
    <label>Teléfono</label>
</div>

<div class="input-group">
    <input type="email" name="Email" placeholder=" ">
    <label>Email</label>
</div>

<div class="input-group">
    <input type="file" name="Imagen" accept="image/*">
    <label>Subir Imagen (opcional)</label>
</div>

<button type="submit" name="AgregarProveedor" class="Acceder">
    Agregar Proveedor
</button>

</form>

</div>
</div>
</section>
</main>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

</body>
</html>
