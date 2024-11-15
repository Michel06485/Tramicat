<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$vista = $_GET['vista'] ?? 'inicio';

include 'conexion.php';

switch ($vista) {
    case 'inicio':
        include '\prueba22\Cuenta_user\Inicio_user.php';
        break;
    case 'solicitud':
        include 'formulario_pqrs.php';
        break;
    case 'estado':
        include 'estado_tramite.php';
        break;
    default:
        echo "Vista no encontrada.";
}
?>
