<?php
session_start();
include 'C:/laragon/www/prueba22/conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit;
}

// Verificar si se ha pasado un ID de solicitud válido en la URL
if (!isset($_GET['id'])) {
    echo "ID de solicitud no especificado.";
    exit;
}

$id_solicitud = $_GET['id'];

// Consultar la información de la solicitud
$sql = "SELECT p.id, u.nombre, u.apellido, u.email, u.tip_doc, u.num_doc, p.tipo_pqrs, p.descripcion, p.archivo, p.estado, p.fecha_solicitud, p.respuesta
        FROM pqrs_solicitudes p 
        JOIN usuarios u ON p.id_usuario = u.id 
        WHERE p.id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_solicitud);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Solicitud no encontrada.";
    exit;
}

$solicitud = $result->fetch_assoc();

// Procesar la actualización del estado y el mensaje de respuesta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_estado = $_POST['estado'];
    $mensaje_respuesta = $_POST['mensaje_respuesta'];

    $sql_update = "UPDATE pqrs_solicitudes SET estado = ?, respuesta = ? WHERE id = ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param("ssi", $nuevo_estado, $mensaje_respuesta, $id_solicitud);

    if ($stmt_update->execute()) {
        header("Location: \prueba22\Cuenta_admi\Pqrs_respondidos_admi.php");
    } else {
        echo "Error al actualizar el estado: " . $conexion->error;
    }
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
                            <?php echo htmlspecialchars($solicitud['nombre']); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <span class="c1">
                            <i class='bx bx-user'></i>
                            <?php echo htmlspecialchars($solicitud['apellido']); ?>
                        </span>
                    </div>
                </div>
                
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="nombre">Tipo de documento:</label>
                        <span class="c1">
                            <i class='bx bx-credit-card-front'></i>
                            <?php echo htmlspecialchars($solicitud['tip_doc']); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Número de Documento:</label>
                        <span class="c1">
                            <i class='bx bx-credit-card-front'></i>
                            <?php echo htmlspecialchars($solicitud['num_doc']); ?>
                        </span>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label for="numero_predial">Correo Electrónico:</label>
                        <span class="c2">
                            <i class='bx bx-envelope'></i>
                            <?php echo htmlspecialchars($solicitud['email']); ?>
                        </span>
                    </div>
                </div>


            </div>

        </section>

        <div class="container">
            <div class="titulo-cuenta">
                <h2>Información de la solicitud de pqrs</h2>
            </div>

                <div class="form-group-row">
                    <div class="form-group1">
                        <label for="numero_predial">Tipo de solicitud de PQRS:</label>
                        <span class="c21">
                            <?php echo $solicitud['tipo_pqrs']; ?>
                        </span>
                    </div>
                </div>


                <div class="form-group-row">
                    <div class="form-group">
                        <label for="numero_predial">Descricion:</label>
                        <span class="c2">
                            <?php echo $solicitud['descripcion']; ?>
                        </span>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group1">
                        <label for="numero_predial">Archivo Adjunto:</label>
                        <span class="c211">
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
                        </span>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group1">
                        <label for="numero_predial">Estado del PQRS:</label>
                        <span class="c21">
                            <?php echo $solicitud['estado']; ?>
                        </span>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label>Respuesta: </label>
                        <span class="c2">
                            <?php echo $solicitud['respuesta']; ?>
                        </span>
                    </div>
                </div>
        </div>


        <div class="container">
            <div class="titulo-cuenta">
                <h2>Actualizar Estado y Enviar Respuesta</h2>
            </div>
            
            <form action="" method="post">
                <div class="form-group-row">
                    <div class="form-group1">
                        <label for="estado">Seleccionar estado:</label> <!-- Cambié 'numero_predial' a 'estado' -->
                        <select name="estado" class="c21" required>
                            <option value="pendiente" <?php if ($solicitud['estado'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
                            <option value="visto" <?php if ($solicitud['estado'] == 'visto') echo 'selected'; ?>>Visto</option>
                            <option value="respondido" <?php if ($solicitud['estado'] == 'respondido') echo 'selected'; ?>>Respondido</option>
                        </select>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label for="mensaje_respuesta">Respuesta:</label> <!-- Cambié 'numero_predial' a 'mensaje_respuesta' -->
                        <textarea class="c3" name="mensaje_respuesta" placeholder="Escribe un mensaje de respuesta" required></textarea>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">Actualizar</button>
            </form>
        </div>
    </main>
    
    <style>
        .submit-btn {
    background-color: #007bff;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.submit-btn:hover {
    background-color: #0056b3;
}

.c3 {
    width: 100%; /* Hacer que el textarea ocupe todo el ancho disponible */
    min-height: 100px; /* Altura mínima */
    padding: 10px; /* Espaciado interno */
    border: 1px solid #ccc; /* Borde ligero */
    border-radius: 5px; /* Bordes redondeados */
    font-size: 15px; /* Tamaño de fuente */
    resize: vertical; /* Permitir que el usuario ajuste la altura, pero no el ancho */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Sombra sutil */
    transition: border-color 0.3s; /* Transición suave para el cambio de borde */
}

.c3:focus {
    border-color: #007BFF; /* Cambiar el color del borde al enfocar */
    outline: none; /* Eliminar el borde de enfoque predeterminado */
}

/* Contenedor del botón */
/* Contenedor del botón */
.button-container {
    display: flex; /* Hacer que el contenedor sea un flexbox */
    justify-content: center; /* Centrar el botón horizontalmente */
    margin-top: 10px; /* Margen superior para separar del textarea */
}

/* Estilo para el botón */
.btn-modificar {
    display: inline-block; /* Para que el botón se comporte como un bloque */
    padding: 8px 12px; /* Espaciado interno reducido para hacer el botón más pequeño */
    background-color: #007BFF; /* Color de fondo */
    color: white; /* Color del texto */
    text-decoration: none; /* Sin subrayado */
    border-radius: 5px; /* Bordes redondeados */
    font-size: 14px; /* Tamaño de fuente más pequeño */
    max-width: 150px; /* Ancho máximo del botón */
    width: 100%; /* Hacer que el botón ocupe el 100% del ancho máximo */
    text-align: center; /* Alinear texto al centro */
    transition: background-color 0.3s; /* Transición suave para el color de fondo */
}

.btn-modificar:hover {
    background-color: #0056b3; /* Color de fondo al pasar el ratón */
}

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