<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';

// ================= VERIFICAR SESIÓN =================
requireRole(3); // Solo proveedores

// ================= USUARIO =================
$idUsuario       = $_SESSION['id'];
$nombreUsuario   = $_SESSION['nombre_completo'] ?? 'Proveedor';
$rolUsuarioNombre= $_SESSION['rol_nombre'] ?? 'Proveedor';
$fotoUsuario     = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';
$conn->query("SET @usuario_actual = $idUsuario");

// ================= CLIENTES =================
$clientes = [];
$resClientes = $conn->query("
    SELECT *
    FROM VistaClientes
    WHERE Estatus = 'Activo'
      AND TRIM(LOWER(CONCAT(Nombre,' ',IFNULL(Paterno,'')))) 
          NOT LIKE '%cliente gener%'
");
while ($c = $resClientes->fetch_assoc()) {
    $clientes[] = $c;
}

// ================= CLIENTE SELECCIONADO =================
$idCliente = $_SESSION['cliente_seleccionado'] ?? null;
$nombreCliente = $_SESSION['cliente_nombre'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCliente'])) {
    foreach ($clientes as $c) {
        if ($c['idCliente'] == $_POST['idCliente']) {
            $_SESSION['cliente_seleccionado'] = $c['idCliente'];
            $_SESSION['cliente_nombre'] = $c['Nombre'].' '.$c['Paterno'];
            header("Location: InicioProveedores.php");
            exit();
        }
    }
}

// ================= RUTAS IMÁGENES =================
$rutaServidor = __DIR__ . '/../../public/Imagenes/Productos/';
$rutaWeb = 'Imagenes/Productos/';
if (!is_dir($rutaServidor)) mkdir($rutaServidor, 0755, true);

// ================= AGREGAR AL CARRITO =================
$mensaje = '';
$error   = '';

if (isset($_POST['idProducto'], $_POST['cantidad'])) {
    try {
        $idProducto = (int)$_POST['idProducto'];
        $cantidad   = (int)$_POST['cantidad'];

        $resCarrito = $conn->query("
            SELECT idCarrito 
            FROM Carrito 
            WHERE idUsuario = $idUsuario
            ORDER BY Fecha DESC
            LIMIT 1
        ");

        if ($resCarrito->num_rows > 0) {
            $idCarrito = $resCarrito->fetch_assoc()['idCarrito'];
        } else {
            $stmt = $conn->prepare("INSERT INTO Carrito (idUsuario) VALUES (?)");
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $idCarrito = $conn->insert_id;
            $stmt->close();
        }

        $stmt = $conn->prepare("CALL AgregarAlCarrito(?,?,?)");
        $stmt->bind_param("iii", $idCarrito, $idProducto, $cantidad);
        $stmt->execute();

        // Limpiar resultados de procedimientos almacenados
        do {
            if ($stmt->get_result()) $stmt->get_result()->free();
        } while ($stmt->more_results() && $stmt->next_result());

        $stmt->close();
        $mensaje = "Producto agregado al carrito correctamente.";

    } catch (mysqli_sql_exception $e) {
        $error = $e->getMessage();
    }
}

// ================= CONTADOR CARRITO =================
$resCount = $conn->query("
    SELECT COUNT(DISTINCT dc.idProducto) total
    FROM DetalleCarrito dc
    INNER JOIN Carrito c ON dc.idCarrito = c.idCarrito
    WHERE c.idUsuario = $idUsuario
");
$contador = (int)$resCount->fetch_assoc()['total'];

// ================= PRODUCTOS =================
$productos = [];
$resProd = $conn->query("SELECT * FROM VistaProductos WHERE Producto <> 'AbonosCreditos'");
while ($p = $resProd->fetch_assoc()) {
    $productos[] = $p;
}

$conn->close();

// ================= INCLUIR VIEW =================
require_once __DIR__ . '/../views/InicioProveedoresView.php';
