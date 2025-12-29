<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Cliente</title>
    <link rel="stylesheet" href="assets/css/EditarCliente.css">
    <link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<main>
<section class="centro">
<div class="log">
<div class="login">

<?php if (!empty($_SESSION['mensaje'])): ?>
<div class="alert-message <?= $_SESSION['tipo_mensaje']=='error'?'alert-error':'alert-success' ?>">
    <?= htmlspecialchars($_SESSION['mensaje']) ?>
</div>
<?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="titulo">
    <h2>Actualizar Cliente</h2>
    <a href="Clientes.php">
        <img src="assets/icons/Volver.png" class="boton-atras">
    </a>
</div>

<div class="input-group">
    <input type="text" name="Nombre" required value="<?= htmlspecialchars($cliente['Nombre']) ?>" placeholder=" ">
    <label>Nombre</label>
</div>

<div class="input-group">
    <input type="text" name="Paterno" required value="<?= htmlspecialchars($cliente['Paterno']) ?>" placeholder=" ">
    <label>Apellido Paterno</label>
</div>

<div class="input-group">
    <input type="text" name="Materno" required value="<?= htmlspecialchars($cliente['Materno']) ?>" placeholder=" ">
    <label>Apellido Materno</label>
</div>

<div class="input-group">
    <input type="tel" name="Telefono" required value="<?= htmlspecialchars($cliente['Telefono']) ?>" placeholder=" ">
    <label>Teléfono</label>
</div>

<div class="input-group">
    <input type="email" name="Email" required value="<?= htmlspecialchars($cliente['Email']) ?>" placeholder=" ">
    <label>Email</label>
</div>

<div class="input-group">
    <input type="file" name="Imagen" accept="image/*">
    <label>Imagen</label>
</div>

<div class="input-group">
    <select name="Estatus">
        <option value="Activo" <?= $cliente['Estatus']=='Activo'?'selected':'' ?>>Activo</option>
        <option value="Inactivo" <?= $cliente['Estatus']=='Inactivo'?'selected':'' ?>>Inactivo</option>
    </select>
    <label>Estatus</label>
</div>

<div class="input-group">
    <input type="number" step="0.01" name="Credito" value="<?= $cliente['Credito'] ?>">
    <label>Crédito</label>
</div>

<div class="input-group">
    <input type="number" step="0.01" name="Limite" value="<?= $cliente['Limite'] ?>">
    <label>Límite</label>
</div>

<button type="submit" class="Acceder">
    Actualizar
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
