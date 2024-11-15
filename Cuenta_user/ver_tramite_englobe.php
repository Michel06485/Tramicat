<?php
session_start(); // Asegura que la sesión esté iniciada


// Conexión a la base de datos
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
$sql = "SELECT tipo_solicitante, nombres_apellidos, tipo_documento, numero_documento, correo, telefono, ciudad_municipio, matricula_inmobiliaria, numero_predial, archivo1, archivo2, archivo3, descripcion, estado, respuesta
        FROM formulario_tramites 
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
    <title>Estado del trámite</title>

    <link rel="stylesheet" href="/prueba22/css/Menu.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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

    <div class="right">
        <a href="" class="icons-header">
            <img src="/prueba22/icons/notificacion.png" alt="notificacion">
        </a>
        <a href="/prueba22/logout.php">
            <img src="/prueba22/icons/cuenta.png" alt="img-user" class="user">
            <span>salir</span>
        </a>
    </div>
</header>

    <div class="sidebar" id="sidebar">
        <nav>
            <ul>
                <li><a href="Inicio_user.php" ><img src="/prueba22/icons/aplicaciones.png" alt=""><span>Inicio</span></a></li>
                <li><a href="Perfil_user.php"><img src="/prueba22/icons/avatar-de-usuario.png" alt=""><span>Perfil</span></a></li>
                <li><a href="Tramite_user.php" class="active"><img src="/prueba22/icons/documento.png" alt=""><span>Estado del tramite</span></a></li>
                <li><a href="Pqrs_user.php"><img src="/prueba22/icons/comunicacion.png" alt=""><span>PQRS</span></a></li>
            </ul>
        </nav>
    </div>

    <button class="menu-toggle" onclick="toggleMenu()">☰</button>

    <div class="menu-lateral mobile-hidden" id="menuLateral">
        <a href="Inicio_user.php">Inicio</a>
        <a href="Perfil_user.php">Perfil</a>
        <a href="Tramite_user.php" class="active">Estado del tramite</a>
        <a href="Pqrs_user.php">PQRS</a>
    </div>

    <main class="main" id="mainContent">
    <div class="container">
            <div class="titulo-cuenta">
                <h2>Formulario de Englobe</h2>
            </div>
            
            <fieldset>
                <legend>Información del Solicitante</legend>
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="tipo_solicitante">Tipo de solicitante:</label>
                        <span class="c1"><?php echo $solicitud['tipo_solicitante']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="nombres_apellidos">Nombres y Apellidos:</label>
                        <span class="c1"><?php echo $solicitud['nombres_apellidos'];?></span>
                    </div>
                </div>     
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="tipo_documento">Tipo de documento:</label>
                        <span class="c1"><?php echo $solicitud['tipo_documento'];?></span>
                    </div>
                    <div class="form-group">
                        <label for="numero_documento">Número de documento:</label>
                        <spanclass="c1"><?php echo $solicitud['numero_documento'];?></spanclass=>
                    </div>
                </div>
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="correo">Correo electrónico:</label>
                        <span class="c1"><?php echo $solicitud['correo'];?></span>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <span class="c1"><?php echo $solicitud['telefono'];?></span>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Información del Inmueble</legend>
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="ciudad_municipio">Ciudad o municipio:</label>
                        <span class="c1"><?php echo $solicitud['ciudad_municipio'];?></span>
                    </div>
                    <div class="form-group">
                        <label for="matricula_inmobiliaria">Matrícula inmobiliaria:</label>
                        <span class="c1"><?php echo $solicitud['matricula_inmobiliaria'];?></span>
                    </div>
                </div>
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="numero_predial">Número predial:</label>
                        <span class="c1"><?php echo $solicitud['numero_predial'];?></span>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Requisitos del trámite</legend>
                <div class="file-input-container">
                    <div class="file-text">1. Plano topográfico en medio magnético:</div>
                    <p><a href="/prueba22/uploads/englobe/<?php echo basename($solicitud['archivo1']); ?>" target="_blank">Ver Archivo</a></p>
                </div>
                <div class="file-input-container">
                    <div class="file-text">2. Plano topográfico en medio magnético:</div>
                    <p><a href="/prueba22/uploads/englobe/<?php echo basename($solicitud['archivo2']); ?>" target="_blank">Ver Archivo</a></p>
                </div>
                <div class="file-input-container">
                    <div class="file-text">3. Referencia o copia simple de la licencia de construcción que aprueba la partición:</div>
                    <p><a href="/prueba22/uploads/englobe/<?php echo basename($solicitud['archivo3']); ?>" target="_blank">Ver Archivo</a></p>
                </div>
              
                </fieldset>
            <fieldset>
                <legend>Descripción</legend>
                <div class="form-group">
                <span class="c21"><?php echo $solicitud['descripcion'];?></span>
                </div>
            </fieldset>
        </div>

        <div class="container">
            <div class="chat-message">
                <img src="/prueba22/icons/empleado-de-oficina.png"class="chat-icon">
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
</body>
</html>

<script>
    // Función para mostrar/ocultar el menú en móviles
    function toggleMenu() {
        var menu = document.getElementById("menuLateral");
        menu.classList.toggle("show");
    }
    </script>
    <style>

     
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

    .open-button {
        display: inline-block;
        padding: 10px 20px;
        color: #fff;
        background-color: #3498db;
        border: none;
        border-radius: 15px;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.3s, transform 0.2s;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
    }

    .open-button:hover {
        background-color: #2980b9;
        transform: scale(1.05);
    }

    .titulo-cuenta{
        margin-bottom: 40px;
        font-size: 1.1rem;
        color: #333;
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

    .c1, .c2 {
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

    fieldset {
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 15px;
    padding: 15px;
}

legend {
    padding: 0 10px;
    font-weight: bold;
    color: #000000;
    margin-bottom: 10px;
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
                /* Estilos para el contenedor del archivo */
                .file-input-container {
    display: flex;
    align-items: center; /* Centra verticalmente el contenido */
    justify-content: space-between; /* Distribuye espacio entre elementos */
    padding: 10px; /* Espaciado interno */
    margin-bottom: 2px; /* Espaciado inferior */
}

/* Estilos para el texto del archivo */
.file-text {
    font-size: 16px; /* Tamaño de fuente */
    color: #333; /* Color del texto */
    flex: 1; /* Ocupa el espacio disponible */
}

/* Estilos para la vista previa del archivo */
.file-preview {
    margin-right: 10px; /* Espaciado derecho */
}

/* Estilos para el input del archivo */
.file-span {
    display: none; /* Oculta el input de archivo por defecto */
}
/* Código CSS existente aquí */

/* Adaptación para pantallas móviles */
@media (max-width: 768px) {
    .container, .main-content, .button-container {
        padding: 10px;
        margin: 10px;
    }
    
    .form-group-row {
        flex-direction: column; /* Colocar los elementos uno debajo del otro */
        gap: 10px; /* Espaciado entre los elementos */
    }

    .form-group {
        width: 100%; /* Hacer que los grupos de formulario ocupen todo el ancho */
    }

    .c1, .c2, .c21 {
        font-size: 0.9rem; /* Reducir el tamaño de texto para adaptarlo mejor */
    }

    .titulo-cuenta h2 {
        font-size: 1.2rem; /* Ajustar el tamaño del título */
    }

    .submit-btn {
        width: 100%; /* Asegurar que el botón ocupe todo el ancho disponible */
        font-size: 14px; /* Ajustar el tamaño de fuente del botón */
    }

    .open-button, .btn-modificar {
        width: 100%; /* Botones a todo el ancho */
        font-size: 14px;
        padding: 10px;
    }

    /* Ajustes para iconos o avatares de usuario en el móvil */
    .user-icon {
        width: 50px;
        height: 50px;
        margin-right: 10px;
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
