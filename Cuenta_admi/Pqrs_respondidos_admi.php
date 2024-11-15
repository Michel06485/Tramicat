<?php
session_start();
include 'C:/laragon/www/prueba22/conexion.php';

// Verificar si el usuario ha iniciado sesión como administrador
if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit;
}

// Consultar todos los trámites que ya tienen una respuesta
$sql = "SELECT pqrs.id, u.nombre, u.apellido, pqrs.tipo_pqrs, pqrs.estado, pqrs.fecha_solicitud 
        FROM pqrs_solicitudes as pqrs
        INNER JOIN usuarios as u
        ON pqrs.id_usuario = u.id 
        WHERE pqrs.respuesta IS NOT NULL";
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
                    <a href="/prueba22/Cuenta_admi/Pqrs_admi.php"  class="active">
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
    <a href="/prueba22/Cuenta_admi/Tramites_admi.php" >Tramites</a>
    <a href="/prueba22/Cuenta_admi/Pqrs_admi.php" class="active">PQRS</a>
    <a href="/prueba22/Cuenta_admi/Usuarios_admi.php" >Usuarios</a>
</div>

    <main class="main" id="mainContent">

        <section id="pqrs">
            <div class="btns-tramites">
                <a href="Pqrs_admi.php" class="btn-tra1">PQRS sin responder</a>
                <a href="/prueba22/Cuenta_admi/Pqrs_respondidos_admi.php" class="btn-tra1 active1" >PQRS respondidos</a>
            </div>
    
            <div class="container">
                <div class="filtro">
                    <h2>Solicitud de PQRS respondidos</h2>
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
                        <th>Fecha solicitud</th>
                        <th>Estado </th>
                        <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['nombre'] . ' ' . $row['apellido']; ?></td>
                            <td><?php echo $row['tipo_pqrs']; ?></td>
                            <td><?php echo $row['fecha_solicitud']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                            <td><a href="/prueba22/Cuenta_admi/Responder_pqrs.php  ?id=<?php echo $row['id']; ?>">Ver</a></td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
            </div>
        </section>
    </main>
    
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
    <script src="./js/script.js"></script>

    <script>
    // Función para mostrar/ocultar el menú en móviles
    function toggleMenu() {
        var menu = document.getElementById("menuLateral");
        menu.classList.toggle("show");
    }
    </script>
    
</body>
</html>