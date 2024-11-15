<?php
session_start();
include 'C:/laragon/www/prueba22/conexion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    echo 'Error: Usuario no autenticado';
    exit;
}

// Obtener el ID del usuario desde la sesión
$id_usuario = $_SESSION['id'];

// Columnas de búsqueda y tabla
$columnas = ['tipo_pqrs', 'fecha_solicitud', 'estado'];
$tabla = 'pqrs_solicitudes';

// Obtener el campo de búsqueda
$campo = isset($_POST['campo']) ? $conexion->real_escape_string($_POST['campo']) : null;
$where = "WHERE id_usuario = $id_usuario"; // Condición para el usuario actual

// Agregar condiciones de búsqueda si hay un valor en el campo
if ($campo != null) {
    $conditions = [];
    foreach ($columnas as $columna) {
        $conditions[] = "$columna LIKE '%$campo%'";
    }
    // Combinar la condición de usuario con las condiciones de búsqueda
    $where .= " AND (" . implode(" OR ", $conditions) . ")";
}

// Consulta SQL con el filtro del usuario
$sql = "SELECT id, " . implode(", ", $columnas) . " FROM $tabla $where";
$resultado = $conexion->query($sql);

$html = '';

// Generar la tabla de resultados
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . $row['tipo_pqrs'] . '</td>';
        $html .= '<td>' . $row['fecha_solicitud'] . '</td>';
        $html .= '<td>' . $row['estado'] . '</td>';
        $html .= '<td><a href="/prueba22/Cuenta_user/ver_formulario_pqrs.php?id=' . $row['id'] . '">Ver formulario</a></td>';
        $html .= '</tr>';
    }
} else {
    $html .= '<tr>';
    $html .= '<td colspan="5">Sin resultados</td>';
    $html .= '</tr>';
}

// Enviar el HTML como respuesta
echo $html;

$conexion->close();
?>
