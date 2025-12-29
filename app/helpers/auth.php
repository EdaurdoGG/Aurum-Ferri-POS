function esAdmin() {
    return $_SESSION['rol'] == 1;
}
function esEmpleado() {
    return $_SESSION['rol'] == 2;
}
function esProveedor() {
    return $_SESSION['rol'] == 3;
}