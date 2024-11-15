<?php
session_start();

include 'C:/laragon/www/prueba22/conexion.php';


// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'usuario') {
    header("Location: login.php");
    exit;
}

// Verificar si se ha pasado un ID de solicitud válido en la URL
if (!isset($_GET['id'])) {
    echo "ID de solicitud no especificado.";
    exit;
}

$id_solicitud = $_GET['id'];
$id_usuario = $_SESSION['id'];

// Consultar la información de la solicitud para el usuario
$sql = "SELECT tipo_pqrs, descripcion, archivo, estado, fecha_solicitud, respuesta 
        FROM pqrs_solicitudes 
        WHERE id = ? AND id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id_solicitud, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Solicitud no encontrada o no tiene permiso para verla.";
    exit;
}

$solicitud = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud PQRS</title>


    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
            <a href="" class="icons-header">
                <img src="/prueba22/icons/notificacion.png" alt="notificacion">
            </a>
            <a href="">
            <img src="/prueba22/icons/cuenta.png" alt="img-user" class="user">
            <span>salir</span>
            </a>
        </div>
    </header>

    <div class="sidebar" id="sidebar">
        <nav>
            <ul>
                <li>
                    <a href="Inicio_user.php">
                        <img src="/prueba22/icons/aplicaciones.png" alt="">
                        <span>Inicio</span>
                    </a>
                </li>
                <li>
                    <a href="Perfil_user.php">
                        <img src="/prueba22/icons/avatar-de-usuario.png" alt="">
                        <span>Perfil</span>
                    </a>
                </li>
                <li>
                    <a href="Tramite_user.php">
                        <img src="/prueba22/icons/documento.png" alt="">
                        <span>Estado del tramite</span>
                    </a>
                </li>
                <li>
                    <a href="Pqrs_user.php" class="active">
                        <img src="/prueba22/icons/comunicacion.png" alt="">
                        <span>PQRS</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <button class="menu-toggle" onclick="toggleMenu()">☰</button>

    <div class="menu-lateral mobile-hidden" id="menuLateral">
        <a href="Inicio_user.php">Inicio</a>
        <a href="Perfil_user.php">Perfil</a>
        <a href="Tramite_user.php">Estado del tramite</a>
        <a href="Pqrs_user.php"  class="active">PQRS</a>
    </div>

    <main class="main" id="mainContent">
        <div class="container">
                <div class="titulo-cuenta">
                    <h2>Solicitud de PQRS</h2>
                </div>
    
                <div class="text-info">
                    <h3>Detalles de la solicitud</h3>
                    <p>
                        En "Tramicat," estamos comprometidos con brindarte el mejor servicio. Elige entre las opciones disponibles (Petición, Queja, Reclamo, Sugerencia) según la naturaleza de tu solicitud, describe en detalle su solicitud. Una vez enviada tu solicitud, nuestro equipo se encargará de revisarla y te notificaremos el estado de la misma en el menor tiempo posible.
                    </p>
                </div>

                <style>
                    .text-info {
    padding: 20px; /* Espacio interno */
    margin: 20px 0; /* Margen superior e inferior */
}

.text-info h3 {
    color: #333; /* Color del encabezado */
    margin-bottom: 10px; /* Espacio inferior del encabezado */
}

.text-info p {
    color: #333; /* Color del texto */
    line-height: 1.6; /* Altura de línea para mejor legibilidad */
}

                </style>
               <!-- HTML para mostrar los detalles de la solicitud -->
<div class="form-group-row">
    <div class="form-group1">
        <label for="numero_predial">Tipo de solicitud de PQRS:</label>
        <span class="c21"><?php echo $solicitud['tipo_pqrs']; ?></span>
    </div>
</div>
<div class="form-group-row">
    <div class="form-group">
        <label for="numero_predial">Descripción:</label>
        <span class="c1"><?php echo $solicitud['descripcion']; ?></span>
    </div>
</div>
<div class="form-group-row">
    <div class="form-group1">
        <div class="mb-3">
            <?php if (!empty($solicitud['archivo'])): ?>
                <?php 
                    $archivos = explode(',', $solicitud['archivo']);
                    foreach ($archivos as $archivo): 
                ?>
                    <span class="campo2">
                        <a href="<?php echo '/prueba22/uploads/pqrs/' . $archivo; ?>" target="_blank">
                            <?php echo $archivo; ?>
                        </a><br>
                    </span>
                <?php endforeach; ?>
            <?php else: ?> 
                No hay archivo adjunto 
            <?php endif; ?>
        </div>
    </div>
</div>

                    </div>
                </div>
        </div>
        <div class="container"> 
            <div class="chat-message">
                <img src="/prueba22/icons/empleado-de-oficina.png" alt="Icono del Administrador" class="chat-icon">
                <div class="chat-content">
                    <?php if (!empty($solicitud['respuesta'])): ?>
                        <h3>Respuesta del Administrador</h3>
                        <p><?php echo $solicitud['respuesta']; ?></p>
                    <?php else: ?>
                        <p><strong>Respuesta:</strong> Aún no hay respuesta del administrador.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
    // Función para mostrar/ocultar el menú en móviles
    function toggleMenu() {
        var menu = document.getElementById("menuLateral");
        menu.classList.toggle("show");
    }
    </script>
<style>
    
        /* Estilos para el contenedor del menú lateral */
        .menu-lateral {
        position: fixed;
        left: 0;
        top: 0;
        width: 250px;
        height: 17rem;
        margin-top: 5rem;
        background-color: white;
        color: #fff;
        padding-top: 20px;
        transition: transform 0.3s ease;
    }

    /* Estilos para enlaces en el menú */
    .menu-lateral a {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.2rem 0.7rem;
        text-decoration: none;
        margin: 0 0.5rem;
        border-radius: 0.5rem;
        white-space: nowrap;
        overflow: hidden;
        color: var(--text-color);
    }

    .menu-lateral a:hover {
        background-color: var(--background-hover);
    }

    /* Estilos para el contenido principal */
    .main-content {
        margin-left: 250px;
        padding: 20px;
        transition: margin-left 0.3s ease;
    }

    /* Ocultar el menú lateral en dispositivos móviles inicialmente */
    .menu-lateral.mobile-hidden {
        transform: translateX(-100%);
    }

    /* ----- Media Queries ----- */
    /* Ajustes para dispositivos móviles */
    @media (max-width: 768px) {
        /* El menú lateral ocupa menos espacio en dispositivos móviles */
        .menu-lateral {
            width: 200px;
            z-index: 1000;
            transform: translateX(-100%);
        }

        /* Cuando se muestre el menú, desplazamos el contenido principal */
        .menu-lateral.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
            padding-top: 60px;
        }

        /* Botón de menú para dispositivos móviles */
        .menu-toggle {
            position: fixed;
            top: 10px;
            left: 10px;
            color: black;
            border: none;
            padding: 10px 15px;
            font-size: 18px;
            cursor: pointer;
            z-index: 1001;
        }
    }

    /* ----- Ajustes Adicionales para el Menú Lateral en Pantallas Pequeñas ----- */
    @media only screen and (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        .sidebar.show {
            transform: translateX(0);
        }
        main {
            margin-left: 0;
        }
    }

    /* Ajustar el contenido principal cuando el menú lateral está visible */
    .sidebar.show + main {
        margin-left: 18.75rem;
    }
        .container{
        align-items: center;
        max-width: 100%;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
     .main-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 100%;
        margin: 20px auto;
        padding: 20px;
        border-radius: 8px;
    }

    .container-information {
        display: flex;
        align-items: center;
    }

    .user-icon {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .container-information h1 {
        font-size: 1.6rem;
        color: #333;
    }

    .button-container {
        display: flex;
        flex-direction: column; /* Organiza los botones en una columna */
        gap:20px;
    }

    .titulo-cuenta{
        margin-bottom: 40px;
        font-size: 1.1rem;
        color: #333;
        text-align: center;
    }

    .form-group-row {
        display: flex;
        justify-content: space-between;
        gap: 80px; /* Espacio entre los campos */
        margin-bottom: 40px;
    }

    .form-group {
        flex: 1; /* Hace que los campos tomen el mismo espacio */
        
    }

    .c1{
        border: 1px solid #000; /* Borde de 1 píxel de color negro */
        padding: 5px;
        border-radius: 5px;
        height: 40px; /* Ajusta la altura a 40 píxeles */
        width: 100%; /* Ajusta el ancho a 200 píxeles */
        display: inline-flex; /* Permite centrar el contenido dentro del span */
        align-items: center; /* Centra verticalmente el contenido */
        justify-content: left; /* Centra horizontalmente el contenido */
        font-size: 15px;
    }
    .c1 i, .c2 i {
    margin-right: 8px; /* Añade separación de 8 píxeles entre el icono y el texto */
    }

    label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    font-size: 18px;
    }

    .form-group1 {
    display: flex; /* Usar flexbox para alinear en una sola línea */
    align-items: center; /* Alinear verticalmente al centro */
    }

    .form-group1 label {
        margin-right: 10px; /* Espaciado entre el label y el span */
    }
    .c21 {
        display: flex; /* Alinear el icono y el texto en línea */
        align-items: center; /* Alinear verticalmente al centro */
        border: 1px solid #000; /* Borde de 1 píxel de color negro */
        border-radius: 5px; /* Bordes redondeados */
        font-size: 15px; /* Tamaño de fuente */
        padding: 10px; /* Espaciado interno para hacer el span más grande */
        min-width: 200px; /* Ancho mínimo del span */
        margin-left: 10px; /* Espaciado a la izquierda del span */
    }

    .chat-message {
    display: flex;
    align-items: flex-start; /* Alinea el ícono y el contenido verticalmente */
    margin: 15px 0; /* Espaciado entre mensajes */
    padding: 5px; /* Espaciado interno */
    }

    .chat-icon {
        width: 40px; /* Ancho del ícono */
        height: 40px; /* Alto del ícono */
        border-radius: 50%; /* Hace que el ícono sea circular */
        margin-right: 10px; /* Espaciado a la derecha del ícono */
    }

    .chat-content h3 {
        margin: 0; /* Elimina el margen del título */
        font-size: 1.1em; /* Tamaño de fuente del título */
        color: #333; /* Color del texto del título */
    }

    .chat-content p {
        margin: 5px 0 0; /* Espaciado del párrafo */
        color: #555; /* Color del texto de la respuesta */
        font-size: 0.9em; /* Tamaño de fuente del párrafo */
    }


    </style>

    <script src="./js/script.js"></script>
</body>
</html>