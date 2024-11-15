<?php
$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$base_datos = "usuarios_db";

$conexion = new mysqli($servidor, $usuario, $contraseña, $base_datos);

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}
?>
