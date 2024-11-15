<?php

include 'C:/laragon/www/prueba22/conexion.php';


// Inicializamos una variable para almacenar mensajes de error o éxito
$mensaje = "";

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $tip_doc = $_POST['tip_doc'];
    $num_doc = $_POST['num_doc'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $password = $_POST['password'];

    // Validaciones
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
        $mensaje = "El nombre solo debe contener letras y espacios.";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellido)) {
        $mensaje = "El apellido solo debe contener letras y espacios.";
    } elseif (!preg_match("/^[\d+]{8,10}$/", $num_doc)) {
        $mensaje = "El número de documento solo debe contener números.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El correo electrónico no es válido.";
    } elseif (!preg_match("/^[a-zA-Z0-9]{6,12}$/", $password)) {
        $mensaje = "La contraseña debe tener entre 6 y 12 caracteres, con letras y números.";
    } else {
        // Verificar si ya existe un usuario con el mismo número de documento o correo electrónico
        $sql_check = "SELECT * FROM usuarios WHERE num_doc = '$num_doc' OR email = '$email'";
        $result_check = $conexion->query($sql_check);

        if ($result_check->num_rows > 0) {
            $mensaje = "Ya existe una cuenta registrada con este número de documento o correo electrónico.";
        } else {
            // Encriptamos la contraseña
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Inserción en la base de datos
            $sql = "INSERT INTO usuarios (nombre, apellido, tip_doc, num_doc, email, password, rol)
                    VALUES ('$nombre', '$apellido', '$tip_doc', '$num_doc', '$email', '$passwordHash', '$rol')";

            // Código de validación y procesamiento...
    if ($conexion->query($sql) === TRUE) {
        $_SESSION['mensaje'] = "Usuario registrado correctamente.";
        header("Location: /prueba22/Cuenta_admi/Usuarios_admi.php");
        exit();
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="/prueba22/css/Menu.css">
</head>
<body>

    <header>
        <div class="left">
            <!--icono del menu-->
            <div class="menu-container">
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
            <img src="/prueba22/icons/cuenta.png" alt="img-user" class="user">
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
<div class="container">
    <div class="login-section">
        <h2>Registrar Nuevo usuario</h2>
        <?php if ($mensaje): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
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
            <span><i class='bx bx-credit-card-front'></i></span>
                    <select name="rol" required>
                        <option value="usuario">Usuario</option>
                        <option value="administrador">Administrador</option>
                    </select>
            </div>
            <div class="field">
                <span><i class='bx bx-lock-alt'></i></span>
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>
            <div class="campo_enlace">
                <button type="submit">Registrarse</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>



    <style>

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
        
        form{
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            height: 300PX;
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
            padding: 18px 25px;
            border-radius: 20px;
            height: 50PX;
        }

        select, input {
            border: none;
            outline: none;
            width: 88%;
        }

        .n1{
            width: 100%;
            height: 50PX;
            border: solid 1px #333;
            padding: 18px 25px;
            border-radius: 20px;
        }
       

        input{
            border: none;
            outline: none;
        }
        span{
            color: #333;
        }
        button{
            padding: 10px 20px;
            background: #7ac0e4;
            border: none;
            font-size: 16px;
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

    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background-color: #f7f7f7;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    display: flex;
    width: 85%;
    max-width: 900px;
    border-radius: 8px;
    overflow: hidden;
    background-color: white;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    height: 600px;
}

.welcome-section {
    background-color: #b3e4fd;
    padding: 80px;
    width: 48%;
    text-align: center;

}

.welcome-section h2 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #1d1d1d;
    font-weight: 600;
}

.welcome-section p {
    font-size: 16px;
    margin-bottom: 30px;
}

.welcome-section .btn {
    padding: 10px 20px;
            background: whitesmoke;
            border: none;
            font-size: 16px;
            border-radius: 20px;
            cursor: pointer;
}

.welcome-section .btn:hover {
    background: #619ebd;
    text-decoration: none;
}
.login-section {
    width: 600px;
            padding: 28px;
            border-radius: 15px;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin: auto;
            margin-top: 38px;
}


h2{
            color: #333;
            font-weight: 600;
        }

        form{
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            height: 300PX;
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
            padding: 18px 25px;
            border-radius: 20px;
            height: 50PX;
        }

        select, input {
            border: none;
            outline: none;
            width: 88%;
        }

        .n1{
            width: 100%;
            height: 50PX;
            border: solid 1px #333;
            padding: 18px 25px;
            border-radius: 20px;
        }
       

        input{
            border: none;
            outline: none;
        }
        span{
            color: #333;
        }
        button{
            padding: 10px 20px;
            background: #7ac0e4;
            border: none;
            font-size: 16px;
            border-radius: 20px;
            cursor: pointer;
        }
        button:hover{
            background: #619ebd;
        }
        hr{
            width: 100%;
        }
        
</style>







