<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Usuario</title>
<link rel="stylesheet" href="assets/css/EditarEmpleado.css">
<link rel="icon" href="assets/icons/Logo.png">
</head>
<body>
<main>
<section class="centro">
<div class="log">
<div class="login">

<div class="titulo">
    <h2>Editar Usuario</h2>
    <img src="assets/icons/Volver.png" alt="Volver" class="boton-atras"
         onclick="window.location.href='Empleados.php'">
</div>

<!-- MENSAJE -->
<?php if (!empty($_SESSION['mensaje'])): ?>
    <div class="alert-message <?= $_SESSION['tipo_mensaje']=='error'?'alert-error':'alert-success' ?>">
        <?= htmlspecialchars($_SESSION['mensaje']) ?>
    </div>
    <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="input-group">
    <input type="text" name="Nombre" value="<?= htmlspecialchars($empleado['Nombre'] ?? '') ?>" required>
    <label>Nombre</label>
</div>

<div class="input-group">
    <input type="text" name="Paterno" value="<?= htmlspecialchars($empleado['Paterno'] ?? '') ?>" required>
    <label>Apellido Paterno</label>
</div>

<div class="input-group">
    <input type="text" name="Materno" value="<?= htmlspecialchars($empleado['Materno'] ?? '') ?>" required>
    <label>Apellido Materno</label>
</div>

<div class="input-group">
    <input type="tel" name="Telefono" value="<?= htmlspecialchars($empleado['Telefono'] ?? '') ?>" required>
    <label>Teléfono</label>
</div>

<div class="input-group">
    <input type="email" name="Email" value="<?= htmlspecialchars($empleado['Email'] ?? '') ?>" required>
    <label>Email</label>
</div>

<div class="input-group">
    <input type="file" name="Imagen" accept="image/*">
    <label>Imagen</label>

    <?php if (!empty($empleado['Imagen'])): ?>
        <p>
            Imagen actual:<br>
            <img src="<?= htmlspecialchars($empleado['Imagen']) ?>" width="60">
        </p>
    <?php endif; ?>
</div>

<div class="input-group">
    <select name="Estatus" required>
        <option value="Activo" <?= ($empleado['Estatus']=='Activo')?'selected':'' ?>>Activo</option>
        <option value="Inactivo" <?= ($empleado['Estatus']=='Inactivo')?'selected':'' ?>>Inactivo</option>
    </select>
    <label>Estatus</label>
</div>

<div class="input-group">
    <input type="text" name="Usuario" value="<?= htmlspecialchars($empleado['Usuario'] ?? '') ?>" required>
    <label>Usuario</label>
</div>

<div class="input-group">
    <select name="idRol" required>
        <option value="1" <?= ($empleado['idRol']==1)?'selected':'' ?>>Administrador</option>
        <option value="2" <?= ($empleado['idRol']==2)?'selected':'' ?>>Empleado</option>
        <option value="3" <?= ($empleado['idRol']==3)?'selected':'' ?>>Proveedor</option>
    </select>
    <label>Rol</label>
</div>

<button type="submit" class="Acceder">Actualizar Usuario</button>

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
