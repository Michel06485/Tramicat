<?php
include 'C:/laragon/www/prueba22/conexion.php';

// Columnas de búsqueda y tabla
$columnas = ['u.nombre', 'u.apellido', 'u.fecha_registro', 'u.rol'];
$tabla = 'usuarios u';

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

// Consulta SQL
$sql = "SELECT 
    u.id AS id_usuario,
    u.nombre,
    u.apellido,
    COUNT(DISTINCT ft.id) AS total_tramites,
    COUNT(DISTINCT pqrs.id) AS total_tramites_pqrs,
    u.fecha_registro,
    u.rol
FROM 
    $tabla
LEFT JOIN 
    formulario_tramites ft ON u.id = ft.id_usuario
LEFT JOIN 
    pqrs_solicitudes pqrs ON u.id = pqrs.id_usuario
$where 
GROUP BY 
    u.id, u.nombre, u.apellido, u.fecha_registro, u.rol";

$resultado = $conexion->query($sql);

$html = '';

// Generar la tabla de resultados
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['nombre'] . ' ' . $row['apellido'] . '</td>';
        $html .= '<td>' . $row['total_tramites'] . '</td>';
        $html .= '<td>' . $row['total_tramites_pqrs'] . '</td>';
        $html .= '<td>' . $row['fecha_registro'] . '</td>';
        $html .= '<td>' . $row['rol'] . '</td>';
        $html .= '<td><a href="/prueba22/Cuenta_user/ver_formulario_pqrs.php?id=' . $row['id_usuario'] . '">Ver formulario</a></td>';
        $html .= '</tr>';
    }
} else {
    $html .= '<tr>';
    $html .= '<td colspan="7">Sin resultados</td>';
    $html .= '</tr>';
}

// Enviar el HTML como respuesta
echo $html;

$conexion->close();
?>
