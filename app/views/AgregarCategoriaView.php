<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Categoría | Aurum Ferri</title>
    <link rel="stylesheet" href="assets/css/AgregarCategoria.css">
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

<form method="POST">

<div class="titulo">
    <h2>Nueva Categoría</h2>
    <img src="assets/icons/Volver.png"
         class="boton-atras"
         onclick="window.location.href='../public/Categorias.php';">
</div>

<div class="input-group">
    <input type="text" name="Nombre" required placeholder=" ">
    <label>Nombre de la categoría</label>
</div>

<button type="submit" class="Acceder">
    Guardar Categoría
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
