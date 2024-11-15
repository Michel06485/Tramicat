<?php
// Conexión a la base de datos
include 'C:/laragon/www/prueba22/conexion.php';

// Consulta para contar el número de trámites
$sql = "SELECT COUNT(*) AS tramites_recibidos FROM formulario_tramites";
$result = $conexion->query($sql);

// Verificar si hay resultados
$tramites_recibidos = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tramites_recibidos = $row['tramites_recibidos'];
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

// Determinar el filtro: semanal o mensual
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'semanal';

// Preparar la consulta SQL basada en el filtro
if ($filter == 'semanal') {
    // Agrupar por semana y contar los tipos de trámite
    $query = "
        SELECT WEEK(fecha) as semana, YEAR(fecha) as anio, tipo_tramite, COUNT(*) as cantidad
        FROM formulario_tramites
        WHERE YEAR(fecha) = YEAR(CURRENT_DATE)
        GROUP BY YEAR(fecha), WEEK(fecha), tipo_tramite
        ORDER BY anio ASC, semana ASC";
} else {
    // Agrupar por mes
    $query = "
        SELECT MONTH(fecha) as mes, YEAR(fecha) as anio, tipo_tramite, COUNT(*) as cantidad
        FROM formulario_tramites
        WHERE YEAR(fecha) = YEAR(CURRENT_DATE)
        GROUP BY YEAR(fecha), MONTH(fecha), tipo_tramite
        ORDER BY anio ASC, mes ASC";
}

// Ejecutar la consulta
$result = $conexion->query($query);

// Inicializar arreglos para almacenar datos
$labels = [];
$cambio_propietario = [];
$englobes = [];
$desenglobes = [];

while ($row = $result->fetch_assoc()) {
    // Etiquetas de fechas (semana o mes)
    if ($filter == 'semanal') {
        $labels[] = 'Semana ' . $row['semana'] . ' - ' . $row['anio'];
    } else {
        $labels[] = $row['mes'] . '/' . $row['anio'];
    }

    // Cantidad de cada tipo de trámite
    switch ($row['tipo_tramite']) {
        case 'cambio de propietario':
            $cambio_propietario[] = (int)$row['cantidad'];
            break;
        case 'englobe':
            $englobes[] = (int)$row['cantidad'];
            break;
        case 'desenglobe':
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

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
                    <a href="/prueba22/Cuenta_admi/Estadisticas_admi.php" class="active" >
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
    <a href="/prueba22/Cuenta_admi/Inicio_admi.php">Inicio</a>
    <a href="/prueba22/Cuenta_admi/Estadisticas_admi.php" class="active">Estadisticas</a>
    <a href="/prueba22/Cuenta_admi/Tramites_admi.php" >Tramites</a>
    <a href="/prueba22/Cuenta_admi/Pqrs_admi.php">PQRS</a>
    <a href="/prueba22/Cuenta_admi/Usuarios_admi.php" >Usuarios</a>
</div>

    <main class="main" id="mainContent">
    <h1>Reportes de los tramites cambio de propietario, englobe y desenglobe.</h1>

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
        <option value="semanal" <?php echo $filter == 'semanal' ? 'selected' : ''; ?>>Semanal</option>
        <option value="mensual" <?php echo $filter == 'mensual' ? 'selected' : ''; ?>>Mensual</option>
    </select>
    <button type="submit">Filtrar</button>
</form>

<!-- Contenedor de los gráficos -->
<h3>Evolución de los Trámites (<?php echo ucfirst($filter); ?>)</h3>

<div class="chart-container">
    <!-- Gráfico Lineal (tamaño 20px x 20px) -->
    <canvas id="lineChart"></canvas>

    <!-- Gráfico Circular (tamaño 20px x 20px) -->
    <canvas id="graficoCircular" class="graCircu"></canvas>

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
    // Gráfico Circular
    const tramites = <?php echo json_encode($tramites); ?>;
    const porcentajes = <?php echo json_encode($porcentajes); ?>;

    const ctxCircular = document.getElementById('graficoCircular').getContext('2d');
    const graficoCircular = new Chart(ctxCircular, {
        type: 'pie',
        data: {
            labels: tramites,
            datasets: [{
                label: 'Porcentaje de Trámites Realizados',
                data: porcentajes,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                        }
                    }
                },
                datalabels: {
                    display: true,
                    color: '#333',
                    font: {
                        weight: 'bold',
                        size: 6 // Reducido el tamaño de la fuente debido al tamaño pequeño
                    },
                    formatter: function(value, context) {
                        return value.toFixed(2) + '%';
                    },
                    anchor: 'center',
                    align: 'center'
                }
            }
        }
    });

    // Datos para el gráfico de líneas
    const labels = <?php echo json_encode($labels); ?>;
    const cambioPropietarioData = <?php echo json_encode($cambio_propietario); ?>;
    const englobesData = <?php echo json_encode($englobes); ?>;
    const desenglobesData = <?php echo json_encode($desenglobes); ?>;

    const ctx = document.getElementById('lineChart').getContext('2d');
    const lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Cambio de Propietario',
                    data: cambioPropietarioData,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Englobe',
                    data: englobesData,
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Desenglobe',
                    data: desenglobesData,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false,
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                x: { title: { display: true, text: 'Fecha' } },
                y: { title: { display: true, text: 'Cantidad' }, beginAtZero: true }
            }
        }
    });
</script>

</div>
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
