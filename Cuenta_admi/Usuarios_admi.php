<?php
session_start();
include 'C:/laragon/www/prueba22/conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit;
}

$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';
unset($_SESSION['mensaje']);

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
    usuarios u
LEFT JOIN 
    formulario_tramites ft ON u.id = ft.id_usuario
LEFT JOIN 
    pqrs_solicitudes pqrs ON u.id = pqrs.id_usuario
GROUP BY 
    u.id, u.nombre, u.apellido, u.fecha_registro, u.rol";

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
                    <a href="/prueba22/Cuenta_admi/Pqrs_admi.php">
                        <img src="/prueba22/icons/comunicacion.png" alt="">
                        <span>PQRS</span>
                    </a>
                </li>
                <li>
                    <a href="/prueba22/Cuenta_admi/Usuarios_admi.php" class="active">
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
    <a href="/prueba22/Cuenta_admi/Tramites_admi.php">Tramites</a>
    <a href="/prueba22/Cuenta_admi/Pqrs_admi.php">PQRS</a>
    <a href="/prueba22/Cuenta_admi/Usuarios_admi.php" class="active">Usuarios</a>
</div>

<main id="mainContent" class="main">

        <section id="usuarios">
            <div class="container">
                <div class="filtro">
                    <h2>Lista de usuarios registrados</h2>
                    <div class="buscador">
                        <form action="javascript:void(0);" method="post" class="buscador" id="buscador">
                            <input type="text" placeholder="Buscar" id="campo" name="campo">
                            <div class="btn-buscar">
                                <img src="/prueba22/icons/lupa.png" alt="buscar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Mostrar el mensaje si existe -->
    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

            <div class="btn-registrar">
            <a  href="/prueba22/Cuenta_admi/crear_usuario.php" class="bnt-agregar">Agregar</a>
            </div>
            <style>

.mensaje {
        margin: 15px 0;
        padding: 10px;
        background-color: #e0f7fa; /* Color de fondo suave */
        border: 1px solid #4dd0e1; /* Borde más oscuro */
        border-radius: 8px;
        color: #00695c; /* Color del texto */
        text-align: left;
        font-weight: bold;
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
    
            <div class="container2">
                <table>
                    <thead>
                        <th>Nombre de usuario</th>
                        <th>Tramites Procesados</th>
                        <th>Tramites Procesados de pqrs</th>
                        <th>Fecha de registros</th>
                        <th>Rol</th>
                        <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="content">
                    <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                <td><?php echo $row['nombre'] . ' ' . $row['apellido']; ?></td>
                    <td><?php echo $row['total_tramites']; ?></td>
                    <td><?php echo $row['total_tramites_pqrs']; ?></td>
                    <td><?php echo $row['fecha_registro']; ?></td>
                    <td><?php echo $row['rol']; ?></td>
                    <td><a href="/prueba22/Cuenta_admi/ver_info_user.php  ?id=<?php echo $row['id_usuario']; ?>">Ver</a></td> <!-- Se pasa el ID del usuario -->
                </tr>
                <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <script>
            // Escucha el evento de teclado en el campo de búsqueda
            document.getElementById('campo').addEventListener("keyup", getData);

            function getData() {
                // Obtener el valor del campo de búsqueda
                let input = document.getElementById("campo").value;
                let content = document.getElementById("content");
                let url = "/prueba22/Cuenta_admi/filtro_usuario.php"; // Asegúrate de que esta sea la ruta correcta

                // Crear un objeto FormData para enviar los datos
                let formData = new FormData();
                formData.append('campo', input);

                // Realizar la solicitud a `filtro_usuario.php`
                fetch(url, {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    content.innerHTML = data; // Actualizar la tabla con los resultados
                })
                .catch(err => console.log("Error:", err));
            }
            </script>

        </section>
    </main>
    <script>
    // Función para mostrar/ocultar el menú en móviles
    function toggleMenu() {
        var menu = document.getElementById("menuLateral");
        menu.classList.toggle("show");
    }
    </script>

    <script src="./js/script.js"></script>
</body>
</html>