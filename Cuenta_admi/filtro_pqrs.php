<?php
include 'C:/laragon/www/prueba22/conexion.php';

// Columnas de búsqueda y tabla
$columnas = ['u.nombre', 'u.apellido', 'p.tipo_pqrs', 'p.fecha_solicitud', 'p.estado'];
$tabla = 'usuarios u';
$tabla2 = 'pqrs_solicitudes p';

// Obtener el campo de búsqueda
$campo = isset($_POST['campo']) ? $conexion->real_escape_string($_POST['campo']) : null;
$where = '';

// Condiciones de búsqueda
if ($campo != null) {
    $where = "WHERE ";
    $conditions = [];
    foreach ($columnas as $columna) {
        $conditions[] = "$columna LIKE '%$campo%'";
    }
    $where .= implode(" OR ", $conditions);
}

// Consulta SQL con GROUP BY
$sql = "SELECT p.id, u.nombre, u.apellido, p.tipo_pqrs, p.fecha_solicitud, p.estado
        FROM pqrs_solicitudes p
        JOIN usuarios u ON p.id_usuario = u.id
        $where
        GROUP BY p.id, u.nombre, u.apellido, p.tipo_pqrs, p.fecha_solicitud, p.estado";  // Se agrega GROUP BY

$resultado = $conexion->query($sql);

$html = '';

// Generar la tabla de resultados
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['nombre'] . ' ' . $row['apellido'] . '</td>';
        $html .= '<td>' . $row['tipo_pqrs'] . '</td>';
        $html .= '<td>' . $row['fecha_solicitud'] . '</td>';
        $html .= '<td>' . $row['estado'] . '</td>';
        $html .= '<td><a href="/prueba22/Cuenta_admi/Responder_pqrs.php?id=' . $row['id'] . '">Ver</a></td>';
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
