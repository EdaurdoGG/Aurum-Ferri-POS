<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro Empleados</title>
    <link rel="stylesheet" href="assets/css/Registro.css" />
    <link rel="icon" href="assets/icons/Logo.png">
</head>
<body>
<main>
    <section class="centro">
        <div class="log">
            <div class="login">
                <div class="titulo">
                    <h2>Registro de Empleado</h2>
                    <img src="assets/icons/Volver.png" alt="Volver" class="boton-atras"
                         onclick="window.location.href='Empleados.php';" />
                </div>

                <!-- MENSAJE FLOTANTE -->
                <?php if (!empty($_SESSION['mensaje'])): ?>
                    <div class="alert-message <?= $_SESSION['tipo_mensaje']=='error'?'alert-error':'alert-success' ?>">
                        <?= htmlspecialchars($_SESSION['mensaje']) ?>
                    </div>
                    <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
                <?php endif; ?>

                <form method="POST" action="Registro.php">
                    <div class="input-group"><input type="text" name="Nombre" required placeholder=" " /><label>Nombre</label></div>
                    <div class="input-group"><input type="text" name="ApellidoP" required placeholder=" " /><label>Apellido Paterno</label></div>
                    <div class="input-group"><input type="text" name="ApellidoM" required placeholder=" " /><label>Apellido Materno</label></div>
                    <div class="input-group"><input type="tel" name="Telefono" required placeholder=" " /><label>Teléfono</label></div>
                    <div class="input-group"><input type="email" name="Email" required placeholder=" " /><label>Email</label></div>

                    <div class="input-group"><input type="text" name="Usuario" required placeholder=" " /><label>Usuario</label></div>
                    <div class="input-group"><input type="password" name="Clave" required placeholder=" " /><label>Contraseña</label></div>

                    <button type="submit" name="AgregarEmpleado" class="Acceder">Agregar</button>
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
