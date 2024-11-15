<?php 
session_start();

 // es para incluir la cabecera (ubicacion de la carpeta)
 include 'C:/laragon/www/prueba22/conexion.php';

// Verificar si el usuario ha iniciado sesión como usuario
if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'usuario') {
    header("Location: login.php");
    exit;
}
$id_usuario = $_SESSION['id'];
// Consultar todas las solicitudes del usuario
$sql = "SELECT id, tipo_pqrs, estado, fecha_solicitud FROM pqrs_solicitudes WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

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
                <li><a href="Tramite_user.php"><img src="/prueba22/icons/documento.png" alt=""><span>Estado del tramite</span></a></li>
                <li><a href="Pqrs_user.php" class="active"><img src="/prueba22/icons/comunicacion.png" alt=""><span>PQRS</span></a></li>
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
    <section>
        <div class="titulo-cuenta"><h2>Peticiones, quejas, reclamos y sugerencias</h2></div>
        <div class="container">
            <div class="filtro">
                <a href="/prueba22/formularios/Formulario_pqrs.php" class="bnt-agregar">Realizar nuevo PQRS</a>
                <!-- Formulario de Búsqueda -->
                <form action="javascript:void(0);" method="post" class="buscador">
                    <input type="text" placeholder="Buscar" id="campo" name="campo">
                    <div class="btn-buscar">
                            <img src="/prueba22/icons/lupa.png" alt="buscar">
                        </div>
                </form>
            </div>
        </div>

        <div class="container2">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo de PQRS</th>
                        <th>Fecha solicitud</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="content">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['tipo_pqrs']; ?></td>
                            <td><?php echo $row['fecha_solicitud']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                            <td><a href="/prueba22/Cuenta_user/ver_formulario_pqrs.php?id=<?php echo $row['id']; ?>">Ver formulario</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <script>
    // Event listener para el campo de búsqueda
    document.getElementById('campo').addEventListener("keyup", getData);

    function getData() {
        // Obtener el valor del campo de búsqueda
        let input = document.getElementById("campo").value;
        let content = document.getElementById("content");
        let url = "/prueba22/consultas.php"; // Asegúrate de que esta sea la ruta correcta

        // Crear un objeto FormData para enviar los datos
        let formData = new FormData();
        formData.append('campo', input);

        // Realizar la solicitud a `consultas.php`
        fetch(url, {
            method: "POST",
            body: formData
        }).then(response => response.text())
          .then(data => {
              content.innerHTML = data;
          }).catch(err => console.log("Error:", err));
    }
</script>

<script>
    // Función para mostrar/ocultar el menú en móviles
    function toggleMenu() {
        var menu = document.getElementById("menuLateral");
        menu.classList.toggle("show");
    }
    </script>

    <?php
    $conexion->close(); // Cierra la conexión aqu
    ?>

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

    .bnt-agregar {
    display: inline-block;
    padding: 10px 20px;
    color: #fff;
    background-color: #3498db;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.2s;
    cursor: pointer;
    text-decoration: none;
}

/* Hover y efecto visual */
.bnt-agregar:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}    
    </style>

</body>
</html>
