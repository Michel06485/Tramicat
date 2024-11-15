<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina_principal</title>

    <link rel="stylesheet" href="/prueba22/css/estilo_index.css">
    <link rel="stylesheet" href="/prueba22/css/estilo_requisitos.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
      integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />

    <!-- Conexion del tipo de letra del sitio de google fonts -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700SS&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
     <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


</head>
<body>

    
    <!--Header del menu -->
    <header>
        <div class="navbar">
  
            <div>
              <a href="index.php" class="logo">
                <img class="imagen" src="./icons/Logo.png" alt="" >
                <div class="logo_name">Tramicat</div>
              </a>   
            </div>
  
            <div class="enlaces-menu">
              <ul class="links">
                  <li><a href="#inicio">Inicio</a></li>
                  <li><a href="#tramites">Tramites</a></li>
                  <li><a href="#estado_tramite">Estado del tramite</a></li>
                  <li><a href="#pqrs">PQRS</a></li>
              </ul>
            </div>
  
            <div class="action_btn">
              <a href="login.php">Iniciar Sesión</a>
            <a href="registrar.php">Registrar</a>
            </div>
  
            <!---->
            <div class="icono_menu">
                <i class='bx bx-menu' id="icon_menu"></i>
            </div>
        </div>
  
        <!--Mostrar menu en el icono o la pantalla pequeña-->
        <div class="mostrar-menu">
            <li><a href="#inicio">Inicio</a></li>
            <li><a href="#tramites">Tramites</a></li>
            <li><a href="#estado_tramite">Estado del tramite</a></li>
            <li><a href="#pqrs">PQRS</a></li>
            <li><a href="/prueba22/login.php" class="action_btn">Iniciar sesión</a></li>
            <li><a href="/prueba22/registrar.php" class="action_btn">Registrarse</a></li>
        </div>
    </header>
    