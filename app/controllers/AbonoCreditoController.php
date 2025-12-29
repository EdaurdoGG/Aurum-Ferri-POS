<?php

require_once __DIR__ . '/../config/session.php';
requireRole(1); // solo cajero

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../queries/abonos.php';

/* ================= USUARIO ================= */
$idUsuario = $_SESSION['id']; // Ya sabemos que existe
$nombreUsuario = $_SESSION['nombre_completo'] ?? 'Administrador';
$rolUsuarioNombre = $_SESSION['rol_nombre'] ?? 'Administrador';
$fotoUsuario = $_SESSION['foto'] ?? 'Imagenes/Usuarios/default.png';

/* ================= VARIABLE PARA TRIGGERS ================= */
$conn->query("SET @usuario_actual = $idUsuario;");

/* ================= MENSAJES ================= */
$mensaje = $tipoMensaje = null;

/* ================= PROCESAR ABONO ================= */
if (isset($_POST['abonar'])) {
    $idCliente = intval($_POST['idCliente']);
    $monto     = floatval($_POST['monto']);

    $stmt = $conn->prepare("CALL RegistrarAbonoCredito(?, ?, ?)");
    $stmt->bind_param("iid", $idUsuario, $idCliente, $monto);

    if ($stmt->execute()) {
        $mensaje = "Abono registrado correctamente.";
        $tipoMensaje = "success";
    } else {
        $mensaje = "Error al registrar el abono.";
        $tipoMensaje = "error";
    }
    $stmt->close();
    $conn->next_result();
}

/* ================= CLIENTES CON CRÉDITO ================= */
$clientes = [];
$res = $conn->query("
    SELECT *
    FROM VistaClientes
    WHERE Credito > 0
    ORDER BY Nombre
");
if ($res) {
    $clientes = $res->fetch_all(MYSQLI_ASSOC);
}
?>