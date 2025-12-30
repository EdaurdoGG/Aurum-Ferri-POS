<?php
class RecuperarModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Verifica si existe el usuario con email y últimos 4 dígitos del teléfono
    public function verificarUsuario($usuario, $email, $ultimos4) {
        $stmt = $this->conn->prepare("
            SELECT idUsuario, Usuario, Telefono 
            FROM VistaUsuarios 
            WHERE Usuario=? AND Email=? AND RIGHT(Telefono,4)=?
        ");
        $stmt->bind_param("sss", $usuario, $email, $ultimos4);
        $stmt->execute();
        $res = $stmt->get_result();
        $usuarioEncontrado = $res->fetch_assoc();
        $stmt->close();
        return $usuarioEncontrado ?: false;
    }

    // Actualiza la contraseña del usuario
    public function cambiarContrasena($idUsuario, $nuevaContra) {
        $hash = password_hash($nuevaContra, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE Usuarios SET Contrasena=? WHERE idUsuario=?");
        $stmt->bind_param("si", $hash, $idUsuario);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>
