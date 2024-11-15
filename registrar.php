<?php
include("template/cabecera.php");
include 'conexion.php';

// Inicializamos una variable para almacenar mensajes de error o éxito
$mensaje = "";
$mensaje_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $tip_doc = $_POST['tip_doc'];
    $num_doc = $_POST['num_doc'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validaciones
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
        $mensaje_error = "El nombre solo debe contener letras y espacios.";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellido)) {
        $mensaje_error = "El apellido solo debe contener letras y espacios.";
    } elseif (!preg_match("/^[\d+]{8,10}$/", $num_doc)) {
        $mensaje_error = "El número de documento solo debe contener números.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje_error = "El correo electrónico no es válido.";
    } elseif (!preg_match("/^[a-zA-Z0-9]{6,12}$/", $password)) {
        $mensaje_error = "La contraseña debe tener entre 6 y 12 caracteres, con letras y números.";
    } else {
        // Verificar si ya existe un usuario con el mismo número de documento o correo electrónico
        $sql_check = "SELECT * FROM usuarios WHERE num_doc = '$num_doc' OR email = '$email'";
        $result_check = $conexion->query($sql_check);

        if ($result_check->num_rows > 0) {
            $mensaje_error = "Ya existe una cuenta registrada con este número de documento o correo electrónico.";
        } else {
            // Encriptamos la contraseña
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Inserción en la base de datos
            $sql = "INSERT INTO usuarios (nombre, apellido, tip_doc, num_doc, email, password, rol, fecha_registro) 
                    VALUES ('$nombre', '$apellido', '$tip_doc', '$num_doc', '$email', '$passwordHash', 'usuario', NOW())";

            if ($conexion->query($sql) === TRUE) {
                $mensaje = "Usuario registrado, ya puede iniciar sesión.";
            } else {
                $mensaje = "Error: " . $conexion->error;
            }
        }
        $conexion->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/prueba22/css/estilo_login.css">
    <title>FORMULARIO DE REGISTRO E INICIO SESIÓN</title>
</head>
<body>
    <main class="main">  

        <div class="container-form register">
            <div class="information">
                <div class="info-childs">
                    <h2>Bienvenido a Tramicat</h2>
                    <p>Accede a tu cuenta o regístrate para disfrutar de nuestros servicios.</p>
                    <a class="btn-inicio" href="/prueba22/login.php">Iniciar sesion</a>
                </div>
                <style>
                    .btn-inicio {
                        padding: 10px 20px;
                        background:white;
                        border: none;
                        font-size: 17px;
                        border-radius: 20px;
                        cursor: pointer;
                        text-decoration: none;
                    }
                    
                    .btn-inicio:hover{
                        text-decoration: none;
                        background: #BBE9FF;
                    }
            </style>
        </div>
        <div class="form-information">
            <div class="login-section">
                <h2>Registrarse</h2>
                <?php if ($mensaje): ?>
                    <div class="mensaje"><?php echo $mensaje; ?></div>
                    <?php endif; ?>
                    <?php if ($mensaje_error): ?>
                        <div class="mensaje_error"><?php echo $mensaje_error; ?></div>
                        <?php endif; ?>
                        <form action="" method="post">
                            <div class="fields">
                                <div class="field">
                                    <span><i class='bx bx-user'></i></span>
                                    <input type="text" name="nombre" placeholder="Nombres" required>
                                </div>
                                <div class="field">
                                    <span><i class='bx bx-user'></i></span>
                                    <input type="text" name="apellido" placeholder="Apellidos" required>
                                </div>
                            </div>
            <div class="columna">
                <div class="field">
                    <span><i class='bx bx-credit-card-front'></i></span>
                    <select name="tip_doc" required>
                        <option value="ti">Tarjeta de Identidad</option>
                        <option value="cc">Cédula de Ciudadanía</option>
                        <option value="nit">NIT</option>
                    </select>
                </div>
                <div class="field">
                    <span><i class='bx bx-lock-alt'></i></span>
                    <input type="text" name="num_doc" placeholder="Número de documento" required>
                </div>
            </div>
            <div class="field">
                <span><i class='bx bx-envelope'></i></span>
                <input type="email" name="email" placeholder="Correo electrónico" required>
            </div>
            <div class="field">
                <span><i class='bx bx-lock-alt'></i></span>
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>
            <div class="campo_enlace">
                <button type="submit">Registrar</button>
            </div>
        </form>
    </div>
</div>
</div>

</main>
<footer class="pie-pagina">
    <div class="grupo-1">
        <div class="box">
            <figure>
                <a href="#" class="logo">
                    <img src="./icons/Logo.png" alt="Logo">
                    <h5 class="tl1">Tramicat</h5>
                </a>
            </figure>
        </div>
        <div class="box">
            <h4>SOBRE NOSOTROS</h4>
            <P>Atención de: Trámites Catastrales</P>
            <P>Correo: info@tramicat.gov.co</P>
            <P>Teléfono: +57 60 7 6444831.</P>
        </div>
        <div class="box">
            <h4>Nuestro Servicios</h4>
            <div class="servicios">
                <p><a href="Requisito1.html">Cambio de Propietario</a></p>
                <p><a href="Requisito2.html">Englobe</a></p>
                <p><a href="Requisito3.html">Desenglobe</a></p>
            </div>
        </div>
    </div>
    <div class="grupo-2">
        <small>&copy; 2024 <b>Tramicat</b> - Todos los derechos reservados.</small>
    </div>
</footer>

<script src="./js/index.js"></script>
<script src="./js/formularios.js"></script>
</body>


<style>
    /* Estilo para asegurarse de que el pie de página quede al final */
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    /* Se asegura que el contenido principal ocupe al menos toda la altura de la pantalla */
    .main {
        flex: 1;  /* Ocupa el espacio restante */
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        margin-top: 46rem;
    }

/* Pie de página */
.pie-pagina {
    width: 100%;
    background-color: #2c3e50;
    color: #ecf0f1;
    box-sizing: border-box;
    padding: 40px 0;
    margin-top: 50px;  /* Añadir separación con el contenido superior */
    position: relative;
    bottom: 0;
}

.pie-pagina .grupo-1 {
    width: 100%;
    max-width: 1200px;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-gap: 50px;
    padding: 45px 0;
}

.tl1 {
    font-size: 17px;
}

.pie-pagina .grupo-1 .box h4 {
    color: #ecf0f1;
    margin-bottom: 25px;
    font-size: 22px;
    font-weight: bold;
}

.pie-pagina .grupo-1 .box p {
    color: #bdc3c7;
    margin-bottom: 10px;
}

.pie-pagina .grupo-1 .box a {
    color: #ecf0f1;
    text-decoration: none;
    transition: color 0.3s ease;
}

.pie-pagina .grupo-1 .box a:hover {
    color: #3498db;
    text-decoration: none;
}

.pie-pagina .grupo-1 .box figure img {
    display: block;
    width: 100px;
    margin-right: 10px;
}

.pie-pagina .grupo-2 {
    background-color: #34495e;
    padding: 15px 10px;
    text-align: center;
    color: #ecf0f1;
}

.pie-pagina .grupo-2 small {
    font-size: 15px;
    color: #bdc3c7;
}

        
.login-section {
    width: 600px;
            padding: 29px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin: auto;
            margin-top: 39px;
}

        .mensaje {
            margin: 15px 0;
            padding: 10px;
            background-color: #e0f7fa; /* Color de fondo suave */
            border: 1px solid #4dd0e1; /* Borde más oscuro */
            border-radius: 8px;
            color: #00695c; /* Color del texto */
            text-align: center;
            font-weight: bold;
        }

        .mensaje_error {
            margin: 15px 0;
            padding: 10px;
            background-color: #FF4545; /* Color de fondo suave */
            border: 1px solid #740938; /* Borde más oscuro */
            border-radius: 8px;
            color: #740938; /* Color del texto */
            text-align: center;
            font-weight: bold;
        }

        form{
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            height: 40px;
            font-size: 1.3rem;
        }

        .columna{
            display: flex;
            gap: 15px;
            width: 100%;
        }

        .fields {
            display: flex;
            gap: 15px;
            width: 100%;
        }

        .field {
            flex: 1; /* Ensures that the divs take up equal space */
            border: solid 1px #333;
            padding: 9px 8px;
            border-radius: 20px;
            height: 40PX;
        }

        select, input {
            border: none;
            outline: none;
            width: 88%;
            font-size: 1.3rem;
        }

        .n1{
            width: 100%;
            height: 40PX;
            border: solid 1px #333;
            border-radius: 20px;
        }
       

        input{
            border: none;
            outline: none;
            font-size: 1.3rem;
        }
        span{
            color: #333;
        }
        button{
            padding: 10px 20px;
            background: #08C2FF;
            border: none;
            font-size: 17px;
            border-radius: 20px;
            cursor: pointer;
        }
        button:hover{
            background: #619ebd;
        }
        .enlace{
            text-align: center;
            font-size: 14px;
            color: #333;
        }

        .campo_enlace{
            text-align: center;
            display: flex;
            justify-content: center;
        }

        hr{
            width: 100%;
        }
    </style>
    <script src="js/script.js"></script>
    <script src="js/register.js"></script>
    <script src="js/login.js"></script>
</body>
</html>


<script>
var eye = document.getElementById('Eye');
var input = document.getElementById('Input');
eye.addEventListener("click", function(){
if(input.type == "password"){
    input.type = "text"
    eye.style.opacity=0.8
}else{
    input.type = "password"
    eye.style.opacity=0.2
}
})
</script>


    <style>
        .error-message {
    margin-top: 10px;
    padding: 8px;
    background-color: #facccc; /* Color de fondo suave */
    border: 1px solid #D91656; /* Borde más oscuro */
    border-radius: 8px;
    color: red; /* Color del texto */
    text-align: center;
    font-weight: bold;
}       
.login-section {
    width: 600px;
            padding: 29px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin: auto;
            margin-top: 39px;
}

        .mensaje {
            margin: 15px 0;
            padding: 10px;
            background-color: #e0f7fa; /* Color de fondo suave */
            border: 1px solid #4dd0e1; /* Borde más oscuro */
            border-radius: 8px;
            color: #00695c; /* Color del texto */
            text-align: center;
            font-weight: bold;
        }

        .mensaje_error {
            margin: 15px 0;
            padding: 10px;
            background-color: #FF4545; /* Color de fondo suave */
            border: 1px solid #740938; /* Borde más oscuro */
            border-radius: 8px;
            color: #740938; /* Color del texto */
            text-align: center;
            font-weight: bold;
        }

        form{
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            height: 40px;
            font-size: 1.3rem;
        }

        .columna{
            display: flex;
            gap: 15px;
            width: 100%;
        }

        .fields {
            display: flex;
            gap: 15px;
            width: 100%;
        }

        .field {
            flex: 1; /* Ensures that the divs take up equal space */
            border: solid 1px #333;
            padding: 9px 8px;
            border-radius: 20px;
            height: 40PX;
        }

        select, input {
            border: none;
            outline: none;
            width: 88%;
            font-size: 1.3rem;
        }

        .n1{
            width: 100%;
            height: 40PX;
            border: solid 1px #333;
            border-radius: 20px;
        }
       

        input{
            border: none;
            outline: none;
            font-size: 1.3rem;
        }
        span{
            color: #333;
        }
        button{
            padding: 10px 20px;
            background: #08C2FF;
            border: none;
            font-size: 17px;
            border-radius: 20px;
            cursor: pointer;
        }
        button:hover{
            background: #619ebd;
        }
        .enlace{
            text-align: center;
            font-size: 14px;
            color: #333;
        }

        .campo_enlace{
            text-align: center;
            display: flex;
            justify-content: center;
        }

        hr{
            width: 100%;
        }
    </style>
    <script src="js/script.js"></script>
    <script src="js/register.js"></script>
    <script src="js/login.js"></script>
</body>
</html>

