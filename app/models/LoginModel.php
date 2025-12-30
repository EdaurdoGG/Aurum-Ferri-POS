<?php
class UsuarioModel {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
        $this->conn->set_charset("utf8mb4");
    }

    // Obtener usuario por correo
    public function obtenerUsuarioPorEmail(string $email): ?array {
        $sql = "
            SELECT 
                u.idUsuario,
                u.Usuario,
                u.Contrasena,
                u.idRol,
                p.Nombre,
                p.Paterno,
                p.Materno,
                p.Email,
                p.Imagen,
                r.Nombre AS RolNombre
            FROM Usuarios u
            JOIN Personas p ON u.idPersona = p.idPersona
            JOIN Roles r ON u.idRol = r.idRol
            WHERE p.Email = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 1) return $res->fetch_assoc();
        return null;
    }

    // Registrar nuevo usuario
    public function registrarUsuario(array $data): bool {
        $stmt = $this->conn->prepare("CALL AgregarUsuario(?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param(
            "sssssssssi",
            $data['Nombre'], $data['Paterno'], $data['Materno'],
            $data['Telefono'], $data['Email'], $data['Imagen'],
            $data['Estatus'], $data['Usuario'], $data['Contrasena'],
            $data['Rol']
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
?>
