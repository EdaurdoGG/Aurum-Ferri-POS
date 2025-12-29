<?php
$host = "db";
$db   = "HerreriaUG";
$user = "root";
$pass = "clave";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión");
}
