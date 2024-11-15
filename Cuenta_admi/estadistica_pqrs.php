<?php
// Conexión a la base de datos
include 'C:/laragon/www/prueba22/conexion.php';

// Consultas para contar trámites
$sql = "SELECT COUNT(*) AS tramites_recibidos FROM pqrs_solicitudes";
$result = $conexion->query($sql);
$tramites_recibidos = ($result->num_rows > 0) ? $result->fetch_assoc()['tramites_recibidos'] : 0;

// Trámites pendientes
$sql = "SELECT COUNT(*) AS tramites_pendientes FROM pqrs_solicitudes WHERE estado = 'pendiente'";
$result1 = $conexion->query($sql);
$tramites_pendientes = ($result1->num_rows > 0) ? $result1->fetch_assoc()['tramites_pendientes'] : 0;

// Trámites vistos
$sql = "SELECT COUNT(*) AS tramites_vistos FROM pqrs_solicitudes WHERE estado = 'visto'";
$result1 = $conexion->query($sql);
$tramites_vistos = ($result1->num_rows > 0) ? $result1->fetch_assoc()['tramites_vistos'] : 0;

// Trámites respondidos
$sql = "SELECT COUNT(*) AS tramites_respondidos FROM pqrs_solicitudes WHERE estado = 'respondido'";
$result1 = $conexion->query($sql);
$tramites_respondidos = ($result1->num_rows > 0) ? $result1->fetch_assoc()['tramites_respondidos'] : 0;

// Consulta SQL para obtener los porcentajes de trámites
$sql = "SELECT tipo_pqrs, 
            COUNT(*) * 100.0 / (SELECT COUNT(*) FROM pqrs_solicitudes WHERE tipo_pqrs IN ('Petición', 'Queja', 'Reclamo', 'Sugerencia')) AS porcentaje
        FROM pqrs_solicitudes
        WHERE tipo_pqrs IN ('Petición', 'Queja', 'Reclamo', 'Sugerencia')
        GROUP BY tipo_pqrs";

$resultado = $conexion->query($sql);
$tramites = [];
$porcentajes = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $tramites[] = $fila['tipo_pqrs'];
        $porcentajes[] = $fila['porcentaje'];
    }
} else {
    $tramites = ['Petición', 'Queja', 'Reclamo', 'Sugerencia'];
    $porcentajes = [0, 0, 0, 0];
}

// Filtro de fecha
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'diario';
$fecha_hoy = date('Y-m-d');
$fecha_inicio = date('Y-m-01');

if ($filter == 'diario') {
    $query = "SELECT DATE(fecha_solicitud) as fecha, tipo_pqrs, COUNT(*) as cantidad
              FROM pqrs_solicitudes
              WHERE DATE(fecha_solicitud) = '$fecha_hoy'
              GROUP BY tipo_pqrs, DATE(fecha_solicitud)
              ORDER BY fecha ASC";
} else {
    $query = "SELECT MONTH(fecha_solicitud) as mes, YEAR(fecha_solicitud) as anio, tipo_pqrs, COUNT(*) as cantidad
              FROM pqrs_solicitudes
              WHERE YEAR(fecha_solicitud) = YEAR(CURRENT_DATE)
              GROUP BY MONTH(fecha_solicitud), YEAR(fecha_solicitud), tipo_pqrs
              ORDER BY anio ASC, mes ASC";
}

$result = $conexion->query($query);

$labels = [];
$Petición = [];
$Queja = [];
$Reclamo = [];
$Sugerencia = [];

while ($row = $result->fetch_assoc()) {
    if ($filter == 'diario') {
        $labels[] = $row['fecha'];
    } else {
        $labels[] = $row['mes'] . '/' . $row['anio'];
    }

    switch ($row['tipo_pqrs']) {
        case 'Petición':
            $Petición[] = (int)$row['cantidad'];
            break;
        case 'Queja':
            $Queja[] = (int)$row['cantidad'];
            break;
        case 'Reclamo':
            $Reclamo[] = (int)$row['cantidad'];
            break;
        case 'Sugerencia':
            $Sugerencia[] = (int)$row['cantidad'];
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style>
        /* Añade estilos adecuados para los canvas */
        canvas {
            width: 80% !important;
            height: 80% !important;
        }
    </style>
    <style>
        #graficoCircular {
            width: 100%;  
            height: 50%; 
            margin-top: 40px;
        }
        .contenedor-cuadros {
            display: flex;
            justify-content: space-between;
            gap: 25px;
        }
        .cuadro {
            border: 1px solid #ccc;
            padding: 10px;
            width: 30%;
            border-radius: 10px;
        }
        .titulo {
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
        }
        .valor {
            font-size: 1.2em;
            text-align: center;
        }
    </style>
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
                    <a href="/prueba22/Cuenta_admi/Inicio_admi.php">
                        <img src="/prueba22/icons/aplicaciones.png" alt="">
                        <span>Inicio</span>
                    </a>
                </li>
                <li>
                    <a href="/prueba22/Cuenta_admi/Estadisticas_admi.php" class="active">
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
    <a href="/prueba22/Cuenta_admi/Inicio_admi.php" >Inicio</a>
    <a href="/prueba22/Cuenta_admi/Estadisticas_admi.php" class="active">Estadisticas</a>
    <a href="/prueba22/Cuenta_admi/Tramites_admi.php" >Tramites</a>
    <a href="/prueba22/Cuenta_admi/Pqrs_admi.php">PQRS</a>
    <a href="/prueba22/Cuenta_admi/Usuarios_admi.php" >Usuarios</a>
</div>

    <main class="main" id="mainContent">
    <h1>Reportes de peticiones, quejas, reclamos y sugerencias.</h1>

    <style>
        h1{
            margin-bottom: 70px;
            font-size: 1.9rem;
        }
    </style>

    <div class="contenedor-cuadros">
        <div class="cuadro">
            <div class="titulo">Total de Trámites</div>
            <div id="totalTramites" class="valor"><?php echo $tramites_recibidos; ?></div>
        </div>

        <div class="cuadro">
            <div class="titulo">Trámites Pendientes</div>
            <div id="tramitespendientes" class="valor"><?php echo $tramites_pendientes; ?></div>
        </div>

        <div class="cuadro">
            <div class="titulo">Trámites Vistos</div>
            <div id="tramitesvistos" class="valor"><?php echo $tramites_vistos; ?></div>
        </div>

        <div class="cuadro">
            <div class="titulo">Trámites Respondidos</div>
            <div id="tramitesrespondidos" class="valor"><?php echo $tramites_respondidos; ?></div>
        </div>
    </div>
<div class="container">

    <!-- Filtro para seleccionar entre diario o mensual (colocado encima de la gráfica lineal) -->
    <form method="get" action="" class="filter-form">
    <label for="filter">Ver por:</label>
    <select name="filter" id="filter">
        <option value="diario" <?php echo $filter == 'diario' ? 'selected' : ''; ?>>Diario</option>
        <option value="mensual" <?php echo $filter == 'mensual' ? 'selected' : ''; ?>>Mensual</option>
    </select>
    <button type="submit">Filtrar</button>
</form>

        <div class="chart-container">
        <!-- Gráfico Lineal -->
        <canvas id="myChart"></canvas>
        <!-- Gráfico Circular -->
        <canvas id="graficoCircular" class="graCircu"></canvas>
    </div>
    </div>
<!-- Vinculamos el archivo CSS -->
<style>
    /* Estilo general para el filtro */
    .filter-form {
        margin-bottom: 50px;
    }

    /* Contenedor de los gráficos (alineación horizontal) */
    .chart-container {
        display: flex;
        justify-content: space-around;
        align-items: center;
        gap: 20px; /* Espaciado entre los gráficos */
        margin-top: 20px;
    }

    /* Estilo para los gráficos */
    canvas {
        width: 60% !important;   /* Ancho del gráfico */
        height: 60% !important;  /* Altura del gráfico */
    }
    .graCircu{
        width: 27% !important;   /* Ancho del gráfico */
        height: 30% !important;  /* Altura del gráfico */
    }

    /* Estilos adicionales (opcional) */
    select, button {
        padding: 5px;
        font-size: 19px;
    }
    .container{
        margin-top: 60px;
    }
</style>
    <script>
        const tramites = <?php echo json_encode($tramites); ?>;
        const porcentajes = <?php echo json_encode($porcentajes); ?>;
        const labels = <?php echo json_encode($labels); ?>;
        const peticionesData = <?php echo json_encode($Petición); ?>;
        const quejasData = <?php echo json_encode($Queja); ?>;
        const reclamosData = <?php echo json_encode($Reclamo); ?>;
        const sugerenciasData = <?php echo json_encode($Sugerencia); ?>;

        // Gráfico Circular
        const ctxCircular = document.getElementById('graficoCircular').getContext('2d');
        new Chart(ctxCircular, {
            type: 'pie',
            data: {
                labels: tramites,
                datasets: [{
                    data: porcentajes,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FFE31A']
                }]
            }
        });

        // Gráfico Lineal
        const ctxLineal = document.getElementById('myChart').getContext('2d');
        new Chart(ctxLineal, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Petición',
                    data: peticionesData,
                    borderColor: 'rgb(255, 99, 132)',
                    fill: false
                }, {
                    label: 'Queja',
                    data: quejasData,
                    borderColor: 'rgb(54, 162, 235)',
                    fill: false
                }, {
                    label: 'Reclamo',
                    data: reclamosData,
                    borderColor: 'rgb(75, 192, 192)',
                    fill: false
                }, {
                    label: 'Sugerencia',
                    data: sugerenciasData,
                    borderColor: 'rgb(153, 102, 255)',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        display: true,
                        color: '#36A2EB'
                    }
                }
            }
        });
    </script>
        <script>
    // Función para mostrar/ocultar el menú en móviles
    function toggleMenu() {
        var menu = document.getElementById("menuLateral");
        menu.classList.toggle("show");
    }
    </script>
</body>
</html>
