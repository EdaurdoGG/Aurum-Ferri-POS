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

            <!-- MENSAJE FLOTANTE -->
            <?php if (!empty($_SESSION['mensaje'])): ?>
                <div class="alert-message <?= $_SESSION['tipo_mensaje']=='error'?'alert-error':'alert-success' ?>">
                    <?= htmlspecialchars($_SESSION['mensaje']) ?>
                </div>
                <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="input-group">
                    <input type="text" name="Nombre" placeholder=" " value="<?= htmlspecialchars($usuario['Nombre']) ?>" required>
                    <label>Nombre</label>
                </div>
                <div class="input-group">
                    <input type="text" name="Paterno" placeholder=" " value="<?= htmlspecialchars($usuario['Paterno']) ?>" required>
                    <label>Apellido Paterno</label>
                </div>
                <div class="input-group">
                    <input type="text" name="Materno" placeholder=" " value="<?= htmlspecialchars($usuario['Materno']) ?>" required>
                    <label>Apellido Materno</label>
                </div>
                <div class="input-group">
                    <input type="tel" name="Telefono" placeholder=" " value="<?= htmlspecialchars($usuario['Telefono']) ?>" required>
                    <label>Teléfono</label>
                </div>
                <div class="input-group">
                    <input type="email" name="Email" placeholder=" " value="<?= htmlspecialchars($usuario['Email']) ?>" required>
                    <label>Email</label>
                </div>
                <div class="input-group">
                    <input type="file" name="Imagen" accept="image/*">
                    <label>Seleccionar Imagen</label>
                    <?php if($usuario['Imagen']): ?>
                        <p>Imagen actual: <img src="<?= htmlspecialchars($usuario['Imagen']); ?>" width="50"></p>
                    <?php endif; ?>
                </div>
                <div class="input-group">
                    <select name="Estatus" required>
                        <option value="" disabled hidden>Seleccionar estatus</option>
                        <option value="Activo" <?= ($usuario['Estatus']=='Activo')?'selected':'' ?>>Activo</option>
                        <option value="Inactivo" <?= ($usuario['Estatus']=='Inactivo')?'selected':'' ?>>Inactivo</option>
                    </select>
                    <label>Estatus</label>
                </div>
                <div class="input-group">
                    <input type="text" name="Usuario" placeholder=" " value="<?= htmlspecialchars($usuario['Usuario']) ?>" required>
                    <label>Usuario</label>
                </div>
                <div class="input-group">
                    <select name="idRol" required>
                        <option value="" disabled hidden>Seleccionar rol</option>
                        <option value="1" <?= ($usuario['idRol']==1)?'selected':'' ?>>Administrador</option>
                        <option value="2" <?= ($usuario['idRol']==2)?'selected':'' ?>>Empleado</option>
                        <option value="3" <?= ($usuario['idRol']==3)?'selected':'' ?>>Proveedor</option>
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
