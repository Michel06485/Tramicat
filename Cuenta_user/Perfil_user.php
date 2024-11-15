<?php
session_start();

// Asegúrate de que la ruta a conexion.php es correcta
include 'C:/laragon/www/prueba22/conexion.php';

// Verificar si el usuario ha iniciado sesión como usuario
if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'usuario') {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];

// Consultar la información del usuario usando consultas preparadas
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    die("Error en la preparación de la consulta: " . htmlspecialchars($conexion->error));
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    echo "No se encontró ninguna solicitud con ese ID.";
    exit;
}

$mensaje = isset($_SESSION['mensaje']) ? htmlspecialchars($_SESSION['mensaje']) : '';
unset($_SESSION['mensaje']);

// Verificar si se ha enviado el formulario para actualizar los datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre'])) {   
    // Obtener y sanitizar los datos enviados por el formulario
    $nombre = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre'])) : '';
    $apellido = isset($_POST['apellido']) ? htmlspecialchars(trim($_POST['apellido'])) : '';
    $tipo_doc = isset($_POST['tipo_doc']) ? htmlspecialchars(trim($_POST['tipo_doc'])) : '';
    $num_doc = isset($_POST['num_doc']) ? htmlspecialchars(trim($_POST['num_doc'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';

    // Validar que los datos cumplen con los requisitos (ejemplo de validación simple)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "El correo electrónico no es válido.";
        exit;
    }

    // Consulta de actualización utilizando consultas preparadas
    $sql = "UPDATE usuarios SET nombre=?, apellido=?, tip_doc=?, num_doc=?, email=? WHERE id=?";
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error en la preparación de la consulta de actualización: " . htmlspecialchars($conexion->error));
    }

    $stmt->bind_param("sssssi", $nombre, $apellido, $tipo_doc, $num_doc, $email, $id_usuario);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Tus datos personales se han actualizado correctamente";
        header("Location: /prueba22/Cuenta_user/Perfil_user.php");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
}

// Procesar la contraseña solo si es otro POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password_actual'])) {
    $password_actual = $_POST['password_actual'];
    $nueva_password = $_POST['nueva_password'];
    $confirmar_password = $_POST['confirmar_password'];

    // Verificamos que las contraseñas nuevas coincidan
    if ($nueva_password !== $confirmar_password) {
        $error_message = "Las nuevas contraseñas no coinciden.";
    } else {
        // Recuperamos la contraseña actual del usuario desde la base de datos
        $sql = "SELECT password FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $id_usuario);  // Usamos el id del usuario desde la sesión
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($password_hash);
        $stmt->fetch();

        // Verificamos que la contraseña actual proporcionada coincida con la almacenada en la base de datos
        if (!password_verify($password_actual, $password_hash)) {
            $error_message = "La contraseña actual es incorrecta.";
        } else {
            // Encriptamos la nueva contraseña
            $nueva_password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);

            // Actualizamos la contraseña en la base de datos
            $sql_update = "UPDATE usuarios SET password = ? WHERE id = ?";
            $stmt_update = $conexion->prepare($sql_update);
            $stmt_update->bind_param('si', $nueva_password_hash, $id_usuario);

            if ($stmt_update->execute()) {
                $success_message = "Contraseña cambiada exitosamente.";
            } else {
                $error_message = "Error al cambiar la contraseña. Intenta nuevamente.";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
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
                    <a href="Perfil_user.php" class="active">
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
                    <a href="Pqrs_user.php">
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
        <a href="Perfil_user.php" class="active">Perfil</a>
        <a href="Tramite_user.php">Estado del tramite</a>
        <a href="Pqrs_user.php">PQRS</a>
    </div>

    <main id="mainContent" class="main">
        <section class="container">
            <div class="main-content">
                <div class="container-information">
                    <img src="/prueba22/icons/cuenta.png"  class="user-icon">
                    <h1><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></h1>
                </div>
                <div class="button-container">
                    <a href="#" class="open-button" id="open-button">Editar perfil</a>
                    <a href="#" class="open-button" id="open-contraseña">Cambiar contraseña</a>
                </div>
            </div>
            <div class="informacion-user">
            <div class="titulo-cuenta">
                <h2>Información personal</h2>
                <?php if ($mensaje): ?>
                    <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
                <?php endif; ?>
                
                <?php if (isset($success_message)): ?>
                    <div class="mensaje"><?= $success_message ?></div>
                <?php endif; ?>
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
                </div>
            </div>
        </section>
    </main>
  <!-- Ventana flotante de editar perfil -->
  <div id="modalEditarPerfil" class="modal">
    <div class="modal-content">
        <span class="close-button" id="closeEditarPerfil">&times;</span>
        
        <div class="informacion-user">
            <div class="titulo-editar">
                <h2>Editar información</h2>
            </div>
            <!-- Formulario de cambio de contraseña -->
            <form id="editForm" action="\prueba22\Cuenta_user\Perfil_user.php" method="post"> 
                <input type="hidden" name="id" value="<?php echo $id_usuario; ?>">

                <!-- Formulario de edición de perfil -->
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <span>
                            <input type="text" id="nombre" class="campo" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                        </span>
                        <div class="error-message" id="nombreError"></div>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <span>
                            <input type="text" id="apellido" class="campo" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>
                        </span>
                        <div class="error-message" id="apellidoError"></div>
                    </div>
                </div>
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="document-type">Tipo de documento:</label>
                        <select id="document-type" name="tipo_doc" class="campo" required>
                            <option value="ti" <?php echo $usuario['tip_doc'] == 'ti' ? 'selected' : ''; ?>>Tarjeta de Identidad</option>
                            <option value="cc" <?php echo $usuario['tip_doc'] == 'cc' ? 'selected' : ''; ?>>Cédula de Ciudadanía</option>
                            <option value="nit" <?php echo $usuario['tip_doc'] == 'nit' ? 'selected' : ''; ?>>NIT</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="num_doc">Número de Documento:</label>
                        <span>
                            <input type="text" id="num_doc" class="campo" name="num_doc" value="<?php echo htmlspecialchars($usuario['num_doc']); ?>" required>
                        </span>
                        <div class="error-message" id="numDocError"></div>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <span>
                            <input type="email" id="email" class="campo" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                        </span>
                        <div class="error-message" id="emailError"></div>
                    </div>
                </div>

                <div class="boton_cambiar">
                    <button type="submit" >Modificar</button>
                </div>
                
            </form>
        </div>
    </div>
</div>

<!-- Ventana flotante para cambiar la contraseña -->
<div id="modalCambiarContraseña" class="modal">
    <div class="modal-content">
        <span class="close-button" id="closeCambiarContraseña">&times;</span>
        
        <div class="informacion-user">
            <div class="titulo-editar">
                <h2>Cambiar Contraseña</h2>
            </div>
            
            <!-- Mostrar mensaje de error -->
            <div id="errorMessageContainer" class="error-message"></div>

            <!-- Formulario de cambio de contraseña -->
            <form id="passwordForm" action="\prueba22\Cuenta_user\Perfil_user.php" method="post">
                <div class="form-group">
                    <label for="password_actual">Contraseña Actual:</label>
                    <input class="campo" type="password" id="password_actual" name="password_actual" required>
                </div>

                <div class="form-group">
                    <label for="nueva_password">Nueva Contraseña:</label>
                    <input class="campo" type="password" id="nueva_password" name="nueva_password" required>
                </div>

                <div class="form-group">
                    <label for="confirmar_password">Confirmar Nueva Contraseña:</label>
                    <input class="campo" type="password" id="confirmar_password" name="confirmar_password" required>
                </div>

                <div class="forgot-password-link">
                    <a href="http://">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="boton_cambiar">
                <button type="submit" >Cambiar Contraseña</button>
                </div>
                
            </form>
        </div>
    </div>
</div>

<script>
// Modal para editar perfil
var modalEditarPerfil = document.getElementById("modalEditarPerfil");
var openEditarPerfil = document.getElementById("open-button");
var closeEditarPerfil = document.getElementById("closeEditarPerfil");

// Mostrar la modal de editar perfil
openEditarPerfil.onclick = function() {
    modalEditarPerfil.style.display = "block";
}

// Cerrar la modal de editar perfil
closeEditarPerfil.onclick = function() {
    modalEditarPerfil.style.display = "none";
}

// Modal para cambiar contraseña
var modalCambiarContraseña = document.getElementById("modalCambiarContraseña");
var openCambiarContraseña = document.getElementById("open-contraseña");
var closeCambiarContraseña = document.getElementById("closeCambiarContraseña");

// Mostrar la modal de cambiar contraseña
openCambiarContraseña.onclick = function() {
    modalCambiarContraseña.style.display = "block";
}

// Cerrar la modal de cambiar contraseña
closeCambiarContraseña.onclick = function() {
    modalCambiarContraseña.style.display = "none";
}

// Validar el formulario de cambiar contraseña
var passwordForm = document.getElementById("passwordForm");
passwordForm.addEventListener("submit", function(event) {
    var isValid = true;
    var errorMessageContainer = document.getElementById("errorMessageContainer");
    errorMessageContainer.textContent = ""; // Limpiar los errores previos

    // Obtener los valores de las contraseñas
    const passwordActual = document.getElementById("password_actual").value;
    const nuevaPassword = document.getElementById("nueva_password").value;
    const confirmarPassword = document.getElementById("confirmar_password").value;

    // Validar que las contraseñas coincidan
    if (nuevaPassword !== confirmarPassword) {
        isValid = false;
        errorMessageContainer.textContent = "Las contraseñas no coinciden.";
    }

    // Verificar que la contraseña actual no esté vacía
    if (passwordActual === "") {
        isValid = false;
        errorMessageContainer.textContent += "\nLa contraseña actual no puede estar vacía.";
    }

    // Verificar que la nueva contraseña no esté vacía
    if (nuevaPassword === "") {
        isValid = false;
        errorMessageContainer.textContent += "\nLa nueva contraseña no puede estar vacía.";
    }

    // Verificar la longitud mínima de la nueva contraseña
    if (nuevaPassword.length < 6) {
        isValid = false;
        errorMessageContainer.textContent += "\nLa nueva contraseña debe tener al menos 6 caracteres.";
    }

    // Si hay errores, no cerrar la ventana modal
    if (!isValid) {
        event.preventDefault(); // Evitar que el formulario se envíe
    }
});
</script>

 
<script>
document.getElementById("editForm").addEventListener("submit", function(event) {
    let isValid = true;

    // Validar Nombre: Solo letras
    const nombre = document.getElementById("nombre").value;
    const nombreError = document.getElementById("nombreError");
    if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(nombre)) {
        nombreError.textContent = "El nombre solo debe contener letras.";
        isValid = false;
    } else {
        nombreError.textContent = "";
    }

    // Validar Apellido: Solo letras
    const apellido = document.getElementById("apellido").value;
    const apellidoError = document.getElementById("apellidoError");
    if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(apellido)) {
        apellidoError.textContent = "El apellido solo debe contener letras.";
        isValid = false;
    } else {
        apellidoError.textContent = "";
    }

    // Validar Número de Documento: Solo números, 8-11 caracteres
    const numDoc = document.getElementById("num_doc").value;
    const numDocError = document.getElementById("numDocError");
    if (!/^\d{8,11}$/.test(numDoc)) {
        numDocError.textContent = "El número de documento debe contener entre 8 y 11 dígitos.";
        isValid = false;
    } else {
        numDocError.textContent = "";
    }

    // Validar Correo Electrónico
    const email = document.getElementById("email").value;
    const emailError = document.getElementById("emailError");
    if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
        emailError.textContent = "Por favor, introduce un correo electrónico válido.";
        isValid = false;
    } else {
        emailError.textContent = "";
    }

    // Prevenir el envío del formulario si no es válido
    if (!isValid) {
        event.preventDefault();
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
<style>
        /* Estilo para el enlace de "Olvidaste tu contraseña?" */
.forgot-password-link {
    text-align: left;
    margin-top: 10px;
}

.forgot-password-link a {
    color: #007BFF;
    text-decoration: none;
}

.forgot-password-link a:hover {
    text-decoration: underline;
}

/* Estilo general para el contenedor del botón */
.boton_cambiar {
    text-align: center;
    margin-top: 10px;
}

/* Estilo del botón */
.boton_cambiar button {
    width: 27%;
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
        text-align: center;
}
/* Estilo del botón cuando el ratón pasa sobre él */
.boton_cambiar button:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}

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

.error-message {
    color: #D8000C; /* Rojo oscuro para resaltar el mensaje */
    padding: 5px;
    border-radius: 4px;
    margin-top: 5px;
    font-size: 0.9em;
}


.titulo-editar{
    margin-bottom: 40px;
        font-size: 1.1rem;
        color: #333;
}

.modal {
            display: none; /* Oculta la ventana por defecto */
            position: fixed; /* Fijo para que cubra toda la pantalla */
            z-index: 1; /* Para que esté encima de otros elementos */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Habilitar scroll si es necesario */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Color de fondo oscuro con transparencia */
            padding-top: 60px; /* Espacio en la parte superior */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 15% desde el top y centrado */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Ancho de la ventana modal */
            max-width: 50%; /* Ancho máximo */
            border-radius: 10px; /* Bordes redondeados */
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
        /* Estilos para el formulario */
        .field {
            margin-bottom: 15px;
        }
        .campo {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-editar {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-editar:hover {
            background-color: #2980b9;
        }
    </style>

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
    input{
            border: none;
            outline: none;
            font-size: 1.3rem;
        }
        span{
            color: #333;
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


    </style>

<style>
    /* Estilos generales */
    .error-message {
        color: #D8000C; /* Rojo oscuro para resaltar el mensaje */
        padding: 5px;
        border-radius: 4px;
        margin-top: 5px;
        font-size: 0.9em;
    }

    .titulo-editar {
        margin-bottom: 40px;
        font-size: 1.1rem;
        color: #333;
    }

    .modal {
        display: none; /* Oculta la ventana por defecto */
        position: fixed; /* Fijo para que cubra toda la pantalla */
        z-index: 1; /* Para que esté encima de otros elementos */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Habilitar scroll si es necesario */
        background-color: rgb(0, 0, 0); /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4); /* Color de fondo oscuro con transparencia */
        padding-top: 60px; /* Espacio en la parte superior */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto; /* 15% desde el top y centrado */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Ancho de la ventana modal */
        max-width: 50%; /* Ancho máximo */
        border-radius: 10px; /* Bordes redondeados */
    }

    .close-button {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-button:hover,
    .close-button:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Estilos para el formulario */
    .field {
        margin-bottom: 15px;
    }

    .campo {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .btn-editar {
        background-color: #3498db;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-editar:hover {
        background-color: #2980b9;
    }

    /* Estilos de la interfaz */
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

    input {
        border: none;
        outline: none;
        font-size: 1.3rem;
    }

    span {
        color: #333;
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
        gap: 20px;
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

    /* Estilos responsivos para pantallas móviles */
    @media (max-width: 768px) {
        .main-content {
            flex-direction: column;
            text-align: center;
        }

        .container-information {
            justify-content: center;
            margin-bottom: 20px;
        }

        .container-information h1 {
            font-size: 1.4rem;
        }

        .button-container {
            width: 100%;
            gap: 15px;
        }

        .open-button {
            width: 100%;
            font-size: 14px;
            padding: 12px;
        }

        .form-group-row {
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            width: 100%;
        }

        .c1, .c2 {
            width: 100%;
            font-size: 14px;
            padding: 8px;
        }

        label {
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .user-icon {
            width: 50px;
            height: 50px;
        }

        .container-information h1 {
            font-size: 1.2rem;
        }

        .open-button {
            font-size: 13px;
            padding: 10px;
        }

        .form-group-row {
            gap: 10px;
        }

        .form-group {
            flex: 1;
            margin-bottom: 10px;
        }

        .c1, .c2 {
            font-size: 13px;
            padding: 6px;
        }
    }
</style>


