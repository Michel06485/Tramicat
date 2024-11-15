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
                <li><a href="Inicio_user.php" class="active"><img src="/prueba22/icons/aplicaciones.png" alt=""><span>Inicio</span></a></li>
                <li><a href="Perfil_user.php"><img src="/prueba22/icons/avatar-de-usuario.png" alt=""><span>Perfil</span></a></li>
                <li><a href="Tramite_user.php"><img src="/prueba22/icons/documento.png" alt=""><span>Estado del tramite</span></a></li>
                <li><a href="Pqrs_user.php"><img src="/prueba22/icons/comunicacion.png" alt=""><span>PQRS</span></a></li>
            </ul>
        </nav>
    </div>

    <button class="menu-toggle" onclick="toggleMenu()">☰</button>

    <div class="menu-lateral mobile-hidden" id="menuLateral">
        <a href="Inicio_user.php" class="active">Inicio</a>
        <a href="Perfil_user.php">Perfil</a>
        <a href="Tramite_user.php">Estado del tramite</a>
        <a href="Pqrs_user.php">PQRS</a>
    </div>

    <main id="mainContent" class="main-content">
        <section id="inicio" >

            <div class="title-cards">
                <h2>¡Bienvenido a nuestro portal!</h2>
            </div>

            <div class="texto-inicio">
                <p>Nos alegra tenerte aquí. En esta sección, 
                podrás realizar un seguimiento detallado y en tiempo real del progreso de tus trámites con nosotros. Nuestro objetivo es brindarte la información más actualizada y precisa para 
                que estés al tanto de cada etapa del proceso.</p>
                <p>En nuestro portal, ofrecemos sevicios de trámites catastrales diseñados para simplificar y optimizar la gestión de tus propiedades y terrenos. Nuestro objetivo es brindarte una experiencia eficiente y conveniente al realizar cualquier tipo de gestión relacionada con la actualización, modificación o consulta de la información catastral de tus bienes inmuebles. Desde la inscripción de nuevas propiedades hasta la actualización de datos existentes, nuestro sistema está diseñado para guiarte paso a paso a través de cada proceso, asegurando que cada trámite se realice de manera rápida y precisa. Confía en nosotros para facilitar el manejo de tus trámites catastrales y mantener la información de tus propiedades actualizada y en orden.</p>
            </div>
            
            <!--   Tarjetas-->
            <div class="title-cards">
                <h2>Tramites que Ofrecemos</h2>
            </div>
            <div class="container-card">
                <div class="card">
                    <figure>
                        <a href="\prueba22\formularios\formulario_cambio_propietario.php">
                            <img src="/prueba22/icons/cambio de propietario.jpeg"/>
                        </a>
                    </figure>
                    <div class="contenido-card">
                        <h3>Cambio de Propietario</h3>
                        <p>Actualización del nombre y documento del propietario de un predio o una mejora conforme a los cambios que se presenta.</p>
                    </div>
                </div>
                <div class="card">
                    <figure>
                        <a href="/prueba22/formularios/formulario_englobe.php">
                            <img src="/prueba22/icons/englobe.jpeg"/>
                        </a>
                    </figure>
                    <div class="contenido-card">
                        <h3>Englobe</h3>
                        <p>Es el proceso de unir dos o más parcelas o lotes de terreno en una sola unidad catastral.</p>
                    </div>
                </div>
                <div class="card">
                    <figure>
                        <a href="\prueba22\formularios\formulario_desenglobe.php">
                            <img
                                src="/prueba22/icons/desenglobe.jpeg"
                            />
                        </a>
                    </figure>
                    <div class="contenido-card">
                        <h3>Desenglobe</h3>
                        <p>Es el proceso donde una parcela se divide en dos o más partes independientes.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <script>
    // Función para mostrar/ocultar el menú en móviles
    function toggleMenu() {
        var menu = document.getElementById("menuLateral");
        menu.classList.toggle("show");
    }
    </script>

    <!--Fin   Tarjetas-->
    <style>
    /* ----- Estilos Generales ----- */
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

    /* ----- Estilos de Cards ----- */
    .container-card {
        width: 100%;
        display: flex;
        max-width: 1100px;
        margin: auto;
    }

    .title-cards {
        width: 100%;
        max-width: 1080px;
        margin: auto;
        padding: 20px;
        margin-top: 20px;
        text-align: center;
        color: #7a7a7a;
    }

    .texto-inicio {
        width: 100%;
        max-width: 1080px;
        margin: auto;
        padding: 5px;
        margin-top: 20px;
        text-align: justify;
        color: #7a7a7a;
    }

    .card {
        width: 100%;
        margin: 20px;
        border-radius: 6px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0px 1px 10px rgba(0, 0, 0, 0.2);
        transition: all 400ms ease-out;
        cursor: default;
    }

    .card:hover {
        box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.4);
        transform: translateY(-3%);
    }

    .card img {
        width: 100%;
        height: 210px;
    }

    .card .contenido-card {
        padding: 15px;
        text-align: center;
    }

    .card .contenido-card h3 {
        margin-bottom: 15px;
        color: #7a7a7a;
    }

    .card .contenido-card p {
        line-height: 1.8;
        color: #6a6a6a;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .card .contenido-card a {
        display: inline-block;
        padding: 10px;
        margin-top: 10px;
        text-decoration: none;
        color: #2fb4cc;
        border: 1px solid #2fb4cc;
        border-radius: 4px;
        transition: all 400ms ease;
        margin-bottom: 5px;
    }

    .card .contenido-card a:hover {
        background: #2fb4cc;
        color: #fff;
    }

    /* Ajustes para dispositivos móviles */
    @media only screen and (min-width: 320px) and (max-width: 768px) {
        .container-card {
            flex-wrap: wrap;
        }
        .card {
            margin: 15px;
        }
    }
</style>

</body>
</html>
