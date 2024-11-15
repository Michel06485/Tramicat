<?php
include("template/cabecera.php");
include 'conexion.php';
session_start();

$error_message = ''; // Inicializar una variable para mensajes de error

$redirect = isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : ''; // Escapar el parámetro 'redirect' para evitar ataques XSS

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar y validar entradas
    $tip_doc = htmlspecialchars(trim($_POST['tip_doc']));
    $num_doc = htmlspecialchars(trim($_POST['num_doc']));
    $password = $_POST['password'];

    // Validar que num_doc solo contenga números y esté entre 8 y 11 caracteres
    if (!preg_match('/^\d{8,11}$/', $num_doc)) {
        $error_message = "El número de documento debe contener solo números y tener entre 8 y 11 caracteres.";
    } else {
        // Preparar y ejecutar la consulta SQL si la validación es exitosa
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE tip_doc = ? AND num_doc = ?");
        $stmt->bind_param("ss", $tip_doc, $num_doc);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['rol'] = $user['rol'];
                
                // Redireccionar según el rol del usuario
                if ($user['rol'] == 'administrador') {
                    header("Location: /prueba22/Cuenta_admi/Inicio_admi.php");
                } else {
                    header("Location: /prueba22/Cuenta_user/Inicio_user.php");
                }

                // Redireccionar si el usuario fue enviado desde un enlace específico
                if ($redirect === 'formulario_cambio_propietario') {
                    header("Location: /prueba22/formularios/formulario_cambio_propietario.php");
                } elseif ($redirect === 'formulario_englobe') {
                    header("Location: /prueba22/formularios/formulario_englobe.php");
                } elseif ($redirect === 'formulario_desenglobe') {
                    header("Location: /prueba22/formularios/formulario_desenglobe.php");
                } elseif ($redirect === 'Tramite_user') {
                    header("Location: /prueba22/Cuenta_user/Tramite_user.php");
                } elseif ($redirect === 'pqrs_user') {
                    header("Location: /prueba22/Cuenta_user/Pqrs_user.php");
                } elseif ($redirect === 'formulario_pqrs') {
                    header("Location: /prueba22/formularios/Formulario_pqrs.php");
                }
                exit;
            } else {
                $error_message = "Contraseña incorrecta.";
            }
        } else {
            $error_message = "Usuario no encontrado.";
        }

        // Cerrar la consulta preparada
        $stmt->close();
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
                    <p>Accede a tu cuenta o registrarte para comenzar a disfrutar de nuestros servicios.</p>
                    <a class="btn-inicio" href="/prueba22/registrar.php">Registrarse</a>
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
                <h2>Iniciar Sesión</h2>
                
                <?php if ($error_message): ?>
                    <div class="mensaje_error"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form action="" method="post">
                        <div class="field">
                            <span><i class='bx bx-credit-card-front'></i></span>
                            <select id="document_type" name="tip_doc" required placeholder="Tipo de documento de identidad">
                                <option value="ti">Tarjeta de Identidad</option>
                                <option value="cc">Cédula de Ciudadanía</option>
                                <option value="nit">NIT</option>
                            </select>
                        </div>
                        <div class="field">
                            <span><i class='bx bx-lock-alt'></i></span>
                            <input type="text" name="num_doc" class="campo" placeholder="Número de documento" required pattern="\d{8,11}" title="El número de documento debe tener entre 8 y 11 dígitos y solo puede contener números.">
                        </div>
                        
                        <div class="field">
                            <span><i class='bx bx-lock-alt' ></i></span>
                            <input type="password" name="password"  class="campo" placeholder="Contraseña" required>
                        </div>
                        <div class="campo_enlace">
                            <a href="recuperar_contraseña.php" class="forgot-password">¿Olvidaste tu contraseña?</a>
                        </div>
                        
                        <div class="campo_enlace">
    <button type="submit" >Iniciar sesion</button>
    
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
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
            padding: 45px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin: auto;
            margin-top: 39px;
}

        .mensaje_error {
            padding: 5px;
            background-color: #FF4545; /* Color de fondo suave */
            border: 1px solid #740938; /* Borde más oscuro */
            border-radius: 8px;
            color: #740938; /* Color del texto */
            text-align: center;
            font-weight: bold;
            font-size: 1.4rem;
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

