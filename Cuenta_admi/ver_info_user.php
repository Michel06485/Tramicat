<?php
session_start();
include 'C:/laragon/www/prueba22/conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit;
}

// Verifica que se haya pasado el id
if (isset($_GET['id'])) {
    $id_usuario = intval($_GET['id']); // Asegúrate de que sea un entero

    // Consulta para obtener la información del usuario específico
    $sql_usuario = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($sql_usuario);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result_usuario = $stmt->get_result();

    // Si el usuario existe, obtén su información
    if ($result_usuario->num_rows > 0) {
        $usuario = $result_usuario->fetch_assoc();
    } else {
        echo "Usuario no encontrado.";
        exit;
    }

    // Consulta para obtener los trámites del usuario
    $sql_tramites = "SELECT * FROM formulario_tramites WHERE id_usuario = ?";
    $stmt_tramites = $conexion->prepare($sql_tramites);
    $stmt_tramites->bind_param("i", $id_usuario);
    $stmt_tramites->execute();
    $result_tramites = $stmt_tramites->get_result();

    // Consulta para obtener los PQRS del usuario
    $sql_pqrs = "SELECT * FROM pqrs_solicitudes WHERE id_usuario = ?";
    $stmt_pqrs = $conexion->prepare($sql_pqrs);
    $stmt_pqrs->bind_param("i", $id_usuario);
    $stmt_pqrs->execute();
    $result_pqrs = $stmt_pqrs->get_result();
} else {
    echo "No se ha especificado el usuario.";
    exit;
}
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
        <section class="container">
            <div class="informacion-user">
                <div class="titulo-cuenta">
                    <h2>Información personal</h2>
                </div>
                
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <span class="c1">
                            <i class='bx bx-user'></i>
                            <?php echo htmlspecialchars($usuario['nombre']); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <span class="c1">
                            <i class='bx bx-user'></i>
                            <?php echo htmlspecialchars($usuario['apellido']); ?>
                        </span>
                    </div>
                </div>
                
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="nombre">Tipo de documento:</label>
                        <span class="c1">
                            <i class='bx bx-credit-card-front'></i>
                            <?php echo htmlspecialchars($usuario['tip_doc']); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Número de Documento:</label>
                        <span class="c1">
                            <i class='bx bx-credit-card-front'></i>
                            <?php echo htmlspecialchars($usuario['num_doc']); ?>
                        </span>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                    <label for="numero_predial">Correo Electrónico:</label>
                        <span class="c2">
                            <i class='bx bx-envelope'></i>
                            <?php echo htmlspecialchars($usuario['email']); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Rol:</label>
                        <span class="c1">
                            <i class='bx bx-credit-card-front'></i>
                            <?php echo htmlspecialchars($usuario['rol']); ?>
                        </span>
                    </div>
                </div>
            </div>
        </section>
        
        <div class="titulo-cuenta">
                    <h2>Tramites realizados</h2>
                </div>
        <div class="container2">
        <?php if ($result_tramites->num_rows > 0): ?>
                <table>
                    <thead>
                        <th>Tipo de tramite</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result_tramites->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['tipo_tramite']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                            <td><?php echo $row['fecha']; ?></td>
                            <td>
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
                            </td> <!-- Se pasa el ID del usuario -->
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
        <p>No se encontraron trámites para este usuario.</p>
    <?php endif; ?>
            </div>


            <div class="titulo-cuenta">
                    <h2>Tramites realizados de pqrs</h2>
                </div>
            <div class="container2">
            <?php if ($result_pqrs->num_rows > 0): ?>
                <table>
                <thead>
                        <th>Tipo de tramite</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result_pqrs->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['tipo_pqrs']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                            <td><?php echo $row['fecha_solicitud']; ?></td>
                            <td><a href="/prueba22/Cuenta_admi/Responder_pqrs.php?id=<?php echo $row['id']; ?>">Ver</a></td> <!-- Se pasa el ID del usuario -->
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
        <p>No se encontraron PQRS para este usuario.</p>
    <?php endif; ?>
            </div>
</main>

<style>
/* Estilos para pantallas grandes */
.container {
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
    flex-direction: column;
    gap: 20px;
}
.open-button {
    display: inline-block;
    padding: 10px 20px;
    color: #fff;
    background-color: #3498db;
    border: none;
    border-radius: 15px;
    font-size: 16px;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.2s;
    cursor: pointer;
    text-align: center;
}
.open-button:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}
.titulo-cuenta {
    margin-bottom: 40px;
    font-size: 1.1rem;
    color: #333;
}
.form-group-row {
    display: flex;
    justify-content: space-between;
    gap: 80px;
    margin-bottom: 40px;
}
.form-group {
    flex: 1;
}
.c1, .c2 {
    border: 1px solid #000;
    padding: 5px;
    border-radius: 5px;
    height: 40px;
    width: 100%;
    display: inline-flex;
    align-items: center;
    justify-content: left;
    font-size: 15px;
}
.c1 i, .c2 i {
    margin-right: 8px;
}
label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    font-size: 18px;
}

/* Estilos para pantallas móviles */
@media (max-width: 768px) {
    .main-content {
        flex-direction: column;
        align-items: center;
    }
    
    .form-group-row {
        flex-direction: column;
        gap: 20px;
    }

    .titulo-cuenta {
        text-align: center;
    }

    .container-information h1 {
        font-size: 1.2rem;
    }

    .open-button {
        width: 100%; /* Hacer que el botón ocupe todo el ancho disponible en móviles */
        padding: 10px;
    }

    .button-container {
        flex-direction: row; /* Colocar los botones en una fila en pantallas pequeñas */
        gap: 10px;
        justify-content: center;
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
    
</body>
</html>