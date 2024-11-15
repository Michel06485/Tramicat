<?php
session_start();
include 'C:/laragon/www/prueba22/conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit;
}

$sql = "SELECT f.id, u.nombre, u.apellido, f.tipo_tramite, f.numero_radicado, f.estado, f.fecha
        FROM formulario_tramites f
        JOIN usuarios u 
        ON f.id_usuario = u.id 
        WHERE f.estado = 'pendiente'";
$result = $conexion->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="/prueba22/css/Menu.css">
</head>
<body>

    <header>
        <div class="left">
            <!-- Menu Icon -->
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

        <<div class="right">
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
                    <a href="/prueba22/Cuenta_admi/Estadisticas_admi.php" >
                        <img src="/prueba22/icons/analitica.png" alt="">
                        <span>Estadisticas</span>
                    </a>
                </li>
                <li>
                    <a href="/prueba22/Cuenta_admi/Tramites_admi.php"  class="active">
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
    <a href="/prueba22/Cuenta_admi/Estadisticas_admi.php">Estadisticas</a>
    <a href="/prueba22/Cuenta_admi/Tramites_admi.php" class="active">Tramites</a>
    <a href="/prueba22/Cuenta_admi/Pqrs_admi.php">PQRS</a>
    <a href="/prueba22/Cuenta_admi/Usuarios_admi.php" >Usuarios</a>
</div>

<main id="mainContent" class="main">

        <section id="tramites">
            <div class="btns-tramites">
                <a href="/prueba22/Cuenta_admi/Tramites_admi.php" class="btn-tra1 active1">Tamites sin responder</a>
                <a href="/prueba22/Cuenta_admi/Tramites_respondidos.php" class="btn-tra1">Tamites respondidos</a>
            </div>
    
            <div class="container">
                <div class="filtro">
                    <h2>Solicitud de trámites recibidas</h2>
                    <div class="buscador">
                        <input type="text" placeholder="Buscar">
                        <div class="btn-buscar">
                            <img src="/prueba22/icons/lupa.png" alt="buscar">
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="container2">
                <table>
                    <thead>
                        <th>Nombre usuario</th>
                        <th>Tipo de solicitud</th>
                        <th>Numero de radicado</th>
                        <th>Fecha solicitud</th>
                        <th>Estado </th>
                        <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nombre'] . ' ' . $row['apellido']; ?></td>
                            <td><?php echo $row['tipo_tramite']; ?></td>
                            <td><?php echo $row['numero_radicado']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                            <td><?php echo $row['fecha']; ?></td><td>
                        <?php
                        // Determinar la url de cada fomulario segun su tipo de tramite
                        switch ($row['tipo_tramite']){
                            case 'Cambio de Propietario' :
                                $url = "\prueba22\Cuenta_admi\Responder_cambio_propietario.php ?id=" . $row['id'];
                                break;
                                case 'Englobe':
                                    $url = "\prueba22\Cuenta_admi\Responder_englobe.php?id=" . $row['id'];
                                    break;
                                case 'Desenglobe':
                                    $url = "\prueba22\Cuenta_admi\Responder_desenglobe.php?id=" . $row['id'];
                                    break;
                                default:
                                    $url = "#"; // Enlace predeterminado en caso de tipo desconocido
                                    break;
                        }
                        ?>
                        <a href="<?php echo $url; ?>">Ver</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <script>
    // Función para mostrar/ocultar el menú en móviles
    function toggleMenu() {
        var menu = document.getElementById("menuLateral");
        menu.classList.toggle("show");
    }
    </script>
    <script>
        function selectButton(element) {
    // Elimina la clase 'selected' de todos los botones
    const buttons = document.querySelectorAll('.btns-tramites a');
    buttons.forEach(button => button.classList.remove('selected'));
    
    // Agrega la clase 'selected' al botón clicado
    element.classList.add('selected');
}

    </script>
    <script src="./js/script.js"></script>
    <style>
       /* Estilos del contenedor para centrar */
       .btns-tramites {
        display: flex;
        justify-content: center;
        gap: 20px; /* Espacio entre botones */
        margin: 20px 0;
    }
    
    /* Estilos para los botones */
    .btn-tra1 {
        padding: 10px 20px;
        text-decoration: none;
        background-color: #34B3F1;
        color: white;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    
    /* Efecto hover */
    .btn-tra1:hover {
        background-color: #0D92F4;
    }
    
    /* Estilo del botón activo */
    .active1 {
        background-color: #0D92F4; /* Color diferente para el botón seleccionado */
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>
</body>
</html>