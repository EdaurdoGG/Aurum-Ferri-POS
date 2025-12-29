<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Cliente | Aurum Ferri</title>

    <link rel="stylesheet" href="assets/css/AgregarCliente.css">
    <link rel="icon" href="assets/icons/Logo.png">
</head>
<body>

<main>
<section class="izquierda"></section>

<section class="centro">
<div class="log">
<div class="login">

<?php if (!empty($_SESSION['mensaje'])): ?>
    <div class="alert-message <?= $_SESSION['tipo_mensaje']==='error'?'alert-error':'alert-success' ?>">
        <?= htmlspecialchars($_SESSION['mensaje']) ?>
    </div>
<?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="titulo">
    <h2>Agregar Cliente</h2>
    <a href="Clientes.php">
        <img src="assets/icons/Volver.png" class="boton-atras">
    </a>
</div>

<div class="input-group">
    <input type="text" name="Nombre" required placeholder=" ">
    <label>Nombre</label>
</div>

<div class="input-group">
    <input type="text" name="Paterno" required placeholder=" ">
    <label>Apellido Paterno</label>
</div>

<div class="input-group">
    <input type="text" name="Materno" required placeholder=" ">
    <label>Apellido Materno</label>
</div>

<div class="input-group">
    <input type="tel" name="Telefono" pattern="[0-9]{10}" required placeholder=" ">
    <label>Teléfono</label>
</div>

<div class="input-group">
    <input type="email" name="Email" required placeholder=" ">
    <label>Email</label>
</div>

<div class="input-group">
    <input type="file" name="Imagen" accept="image/*">
    <label>Imagen</label>
</div>

<div class="input-group">
    <input type="number" step="0.01" name="Limite" min="0" value="0" required placeholder=" ">
    <label>Límite de crédito</label>
</div>

<button type="submit" class="Acceder">
    Agregar Cliente
</button>

</form>

</div>
</div>
</section>

<section class="derecha"></section>
</main>

<footer>
    <p>&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</p>
</footer>

</body>
</html>
