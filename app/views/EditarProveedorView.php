<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Proveedor</title>
    <link rel="stylesheet" href="assets/css/EditarProveedor.css">
    <link rel="icon" href="assets/icons/Logo.png">
</head>
<body>
<main>
    <section class="centro">
        <div class="log">
            <div class="login">

                <!-- ================= MENSAJES ================= -->
                <?php if (!empty($_SESSION['mensaje'])): ?>
                    <div class="alert-message <?= $_SESSION['tipo_mensaje']=='error'?'alert-error':'alert-success' ?>">
                        <?= htmlspecialchars($_SESSION['mensaje']) ?>
                    </div>
                    <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="titulo">
                        <h2>Actualizar Proveedor</h2>
                        <a href="Proveedores.php">
                            <img src="assets/icons/Volver.png" class="boton-atras">
                        </a>
                    </div>

                    <div class="input-group">
                        <input type="text" name="Nombre" required value="<?= htmlspecialchars($proveedor['Nombre']) ?>" />
                        <label>Nombre</label>
                    </div>

                    <div class="input-group">
                        <input type="text" name="Paterno" value="<?= htmlspecialchars($proveedor['Paterno']) ?>" />
                        <label>Apellido Paterno</label>
                    </div>

                    <div class="input-group">
                        <input type="text" name="Materno" value="<?= htmlspecialchars($proveedor['Materno']) ?>" />
                        <label>Apellido Materno</label>
                    </div>

                    <div class="input-group">
                        <input type="text" name="Telefono" value="<?= htmlspecialchars($proveedor['Telefono']) ?>" />
                        <label>Teléfono</label>
                    </div>

                    <div class="input-group">
                        <input type="email" name="Email" value="<?= htmlspecialchars($proveedor['Email']) ?>" />
                        <label>Email</label>
                    </div>

                    <div class="input-group">
                        <input type="file" name="Imagen" accept="image/*" />
                        <input type="hidden" name="ImagenActual" value="<?= htmlspecialchars($proveedor['Imagen']) ?>" />
                        <label>Subir Imagen (opcional)</label>
                        <?php if (!empty($proveedor['Imagen'])): ?>
                            <img src="<?= htmlspecialchars($proveedor['Imagen']) ?>" alt="Imagen actual" style="width:80px; margin-top:5px;">
                        <?php endif; ?>
                    </div>

                    <div class="input-group">
                        <select name="EstatusPersona">
                            <option value="Activo" <?= $proveedor['Estatus']=='Activo'?'selected':'' ?>>Activo</option>
                            <option value="Inactivo" <?= $proveedor['Estatus']=='Inactivo'?'selected':'' ?>>Inactivo</option>
                        </select>
                        <label>Estatus Persona</label>
                    </div>

                    <div class="input-group">
                        <select name="EstadoProveedor">
                            <option value="Activo" <?= $proveedor['Estado']=='Activo'?'selected':'' ?>>Activo</option>
                            <option value="Inactivo" <?= $proveedor['Estado']=='Inactivo'?'selected':'' ?>>Inactivo</option>
                        </select>
                        <label>Estado Proveedor</label>
                    </div>

                    <button type="submit" name="ActualizarProveedor" class="Acceder">Actualizar Proveedor</button>
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