<?php
// Conexión a la base de datos
include 'C:/laragon/www/prueba22/conexion.php';

// Consulta para contar usuarios registrados
$sql = "SELECT COUNT(*) AS usuarios_registrados FROM usuarios";
$result = $conexion->query($sql);

// Verificar si hay resultados
$usuarios_registrados = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $usuarios_registrados = $row['usuarios_registrados'];
}

// Consulta para contar el número de trámites
$sql = "SELECT COUNT(*) AS tramites_recibidos FROM formulario_tramites";
$result = $conexion->query($sql);

// Verificar si hay resultados
$tramites_recibidos = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tramites_recibidos = $row['tramites_recibidos'];
}

//consulta de sql 
$sql = "SELECT COUNT(*) AS pqrs_recibidos FROM pqrs_solicitudes";
$result = $conexion->query($sql);

// Verificar si hay resultados
$pqrs_recibidos = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pqrs_recibidos = $row['pqrs_recibidos'];
}

// Consulta para el número de trámites pendiente
$sql = "SELECT COUNT(*) AS tramites_pendientes FROM formulario_tramites WHERE estado = 'pendiente'";
$result1 = $conexion->query($sql);

// Verificar si hay resultados
$tramites_pendientes = 0;
if ($result1->num_rows > 0) {
    $row = $result1->fetch_assoc();
    $tramites_pendientes = $row['tramites_pendientes'];
}

// Consulta para el número de trámites vistos
$sql = "SELECT COUNT(*) AS tramites_vistos FROM formulario_tramites WHERE estado = 'visto'";
$result1 = $conexion->query($sql);

// Verificar si hay resultados
$tramites_vistos = 0;
if ($result1->num_rows > 0) {
    $row = $result1->fetch_assoc();
    $tramites_vistos = $row['tramites_vistos'];
}

// Consulta para el número de trámites respondidos
$sql = "SELECT COUNT(*) AS tramites_respondidos FROM formulario_tramites WHERE estado = 'respondido'";
$result1 = $conexion->query($sql);

// Verificar si hay resultados
$tramites_respondidos = 0;
if ($result1->num_rows > 0) {
    $row = $result1->fetch_assoc();
    $tramites_respondidos = $row['tramites_respondidos'];
}

// Consulta SQL para obtener los porcentajes de trámites
$sql = "SELECT 
            tipo_tramite,
            COUNT(*) * 100.0 / (SELECT COUNT(*) FROM formulario_tramites WHERE tipo_tramite IN ('cambio de propietario', 'englobe', 'desenglobe')) AS porcentaje
        FROM 
            formulario_tramites
        WHERE 
            tipo_tramite IN ('cambio de propietario', 'englobe', 'desenglobe')
        GROUP BY 
            tipo_tramite";

$resultado = $conexion->query($sql);

// Arreglo para almacenar los datos de la consulta
$tramites = [];
$porcentajes = [];

// Verificar si hay resultados y asignar valores
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $tramites[] = $fila['tipo_tramite'];
        $porcentajes[] = $fila['porcentaje'];
    }
} else {
    // Si no hay resultados, asignar valores predeterminados
    $tramites = ['cambio de propietario', 'englobe', 'desenglobe'];
    $porcentajes = [0, 0, 0];
}

// Determinar el tipo de filtro: diario o mensual
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'diario';
$fecha_hoy = date('Y-m-d');
$fecha_inicio = date('Y-m-01'); // Primer día del mes actual

// Realizar la consulta según el filtro seleccionado
if ($filter == 'diario') {
    // Consultar por día y contar por tipo de trámite
    $query = "
        SELECT DATE(fecha) as fecha, tipo_tramite, COUNT(*) as cantidad
        FROM formulario_tramites
        WHERE DATE(fecha) = '$fecha_hoy'
        GROUP BY tipo_tramite, DATE(fecha)
        ORDER BY fecha ASC";
} else {
    // Consultar por mes
    $query = "
        SELECT MONTH(fecha) as mes, YEAR(fecha) as anio, tipo_tramite, COUNT(*) as cantidad
        FROM formulario_tramites
        WHERE YEAR(fecha) = YEAR(CURRENT_DATE)
        GROUP BY MONTH(fecha), YEAR(fecha), tipo_tramite
        ORDER BY anio ASC, mes ASC";
}


$result = $conexion->query($query);

// Preparar los datos para la gráfica
$labels = [];
$cambio_propietario = [];
$englobes = [];
$desenglobes = [];

while ($row = $result->fetch_assoc()) {
    // Para los trámites diarios
    if ($filter == 'diario') {
        $labels[] = $row['fecha'];
    } else {
        // Para los trámites mensuales
        $labels[] = $row['mes'] . '/' . $row['anio'];
    }

    // Almacenar las cantidades de los diferentes tipos de trámite
    switch ($row['tipo_tramite']) {
        case 'Cambio de Propietario':
            $cambio_propietario[] = (int)$row['cantidad'];
            break;
        case 'Englobe':
            $englobes[] = (int)$row['cantidad'];
            break;
        case 'Desenglobe':
            $desenglobes[] = (int)$row['cantidad'];
            break;
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas de Trámites</title>
    <link rel="stylesheet" href="/prueba22/css/Menu.css">
    
</head>
<body>
<header>
        <div class="left">
            <!--icono del menu-->
            <div class="menu-container" id="menu-toggle">
            <div class="menu" id="menu">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
            <div class="brand">
                <img src="/prueba22/icons/Logo.png" alt="" class="logo">
                <span class="name">Tramicat</span>
            </div>
        </div>

        <div class="right">
        <a href="/prueba22/logout.php">
            <img src="/prueba22/icons/cuenta.png" alt="img-user" class="user">
            <span>salir</span>
        </a>
    </div>
    </header>

    <div class="sidebar" id="sidebar">
        <nav>
            <ul>
                <li>
                    <a href="/prueba22/Cuenta_admi/Inicio_admi.php" class="active">
                        <img src="/prueba22/icons/aplicaciones.png" alt="">
                        <span>Inicio</span>
                    </a>
                </li>
                <li>
                    <a href="/prueba22/Cuenta_admi/Estadisticas_admi.php" >
                        <img src="/prueba22/icons/analitica.png" alt="">
                        <span>Estadisticas</span>
                    </a>
                </li>
                <li>
                    <a href="/prueba22/Cuenta_admi/Tramites_admi.php">
                        <img src="/prueba22/icons/documento.png" alt="">
                        <span>Tramites</span>
                    </a>
                </li>
                <li>
                    <a href="/prueba22/Cuenta_admi/Pqrs_admi.php">
                        <img src="/prueba22/icons/comunicacion.png" alt="">
                        <span>PQRS</span>
                    </a>
                </li>
                <li>
                    <a href="/prueba22/Cuenta_admi/Usuarios_admi.php">
                        <img src="/prueba22/icons/audiencia.png" alt="">
                        <span>Usuarios</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <button class="menu-toggle" onclick="toggleMenu()">☰</button>

<div class="menu-lateral mobile-hidden" id="menuLateral">
    <a href="/prueba22/Cuenta_admi/Inicio_admi.php" class="active">Inicio</a>
    <a href="/prueba22/Cuenta_admi/Estadisticas_admi.php">Estadisticas</a>
    <a href="/prueba22/Cuenta_admi/Tramites_admi.php" >Tramites</a>
    <a href="/prueba22/Cuenta_admi/Pqrs_admi.php">PQRS</a>
    <a href="/prueba22/Cuenta_admi/Usuarios_admi.php" >Usuarios</a>
</div>

<main class="main" id="mainContent">
    <div class="welcome-message">
        <h1>Bienvenido a Tramicat, su plataforma de gestión de trámites</h1>
        <p>Estimado Administrador, le damos la bienvenida a Tramicat, el portal diseñado para agilizar y facilitar la recepción y procesamiento de documentos necesarios para cada trámite catastral.</p>
    </div>

    <style>
        .welcome-message {
            text-align: center;
            margin-bottom: 20px;
        }

        .welcome-message h1 {
            font-size: 2em;
            color: #2C3E50;
        }

        .welcome-message p {
            font-size: 1.1em;
            color: #555;
            margin-top: 10px;
        }

        /* Ajustes para pantallas pequeñas */
        @media (max-width: 768px) {
            .welcome-message h1 {
                font-size: 1.5em;
            }

            .welcome-message p {
                font-size: 1em;
            }
        }
    </style>

    <section class="stats-summary">
        <div class="stat-card">
            <h2>Usuarios Registrados</h2>
            <p class="stat-number"><?php echo $usuarios_registrados; ?></p>
            <p class="stat-description">Número total de usuarios activos en la plataforma.</p>
        </div>
        <div class="stat-card">
            <h2>Trámites en Proceso</h2>
            <p class="stat-number"><?php echo $tramites_recibidos; ?></p>
            <p class="stat-description">Trámites actualmente en revisión o verificación.</p>
        </div>
    </section>

    <section class="stats-summary">
        <div class="stat-card">
            <h2>Trámites Completados</h2>
            <p class="stat-number"><?php echo $tramites_respondidos; ?></p>
            <p class="stat-description">Trámites exitosamente finalizados y aprobados.</p>
        </div>
        <div class="stat-card">
            <h2>PQRs recibidas</h2>
            <p class="stat-number"><?php echo $pqrs_recibidos; ?></p>
            <p class="stat-description">Peticiones, Quejas, Reclamos y Sugerencias pendientes de respuesta.</p>
        </div>
    </section>

    <style>
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 60px;
            margin-top: 35px;
        }

        .stat-card {
            background-color: #2C3E50;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: #ECF0F1;
        }

        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #E74C3C;
            margin: 10px 0;
        }

        .stat-description {
            font-size: 0.9em;
            color: #BDC3C7;
        }

        /* Ajustes para pantallas pequeñas */
        @media (max-width: 768px) {
            .stat-card {
                padding: 15px;
            }

            .stat-card h2 {
                font-size: 1.2em;
            }

            .stat-number {
                font-size: 2em;
            }

            .stat-description {
                font-size: 0.8em;
            }
        }
    </style>

    <script>
        // Función para mostrar/ocultar el menú en móviles
        function toggleMenu() {
            var menu = document.getElementById("menuLateral");
            menu.classList.toggle("show");
        }
    </script>
</main>
</body>
</html>

