
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas de Trámites</title>
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
                    <a href="/prueba22/Cuenta_admi/Estadisticas_admi.php" class="active">
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
    <a href="/prueba22/Cuenta_admi/Estadisticas_admi.php" class="active">Estadisticas</a>
    <a href="/prueba22/Cuenta_admi/Tramites_admi.php" >Tramites</a>
    <a href="/prueba22/Cuenta_admi/Pqrs_admi.php">PQRS</a>
    <a href="/prueba22/Cuenta_admi/Usuarios_admi.php" >Usuarios</a>
</div>

    <main class="main">
    <h1>Reportes estadisticos de los tramites</h1>

    <div class="card-container">
    <div class="card">
        <a href="/prueba22/Cuenta_admi/estaditica_tramites.php">

            <img src="/prueba22/icons/Esta_tramites.png" alt="Imagen 1" class="card-image">
            <h3>Reportes de tramites</h3>
        </a>
    </div>
    <div class="card">
        <a href="/prueba22/Cuenta_admi/estadistica_pqrs.php">

            <img src="/prueba22/icons/Esta_pqrs.png" alt="Imagen 2" class="card-image">
            <h3>Reportes de pqrs</h3>
        </a>
    </div>
  </div>
    </main>

    <style>
a{
    text-decoration: none;
    color: #333;
}
        h3{
            font-size: 1.3rem;
            padding: 15px;
            text-align: center;
        }
/* Contenedor de las tarjetas */
.card-container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 90px;
  padding: 90px 10px 10px 10px;
  flex-wrap: wrap;
}

/* Estilos para las tarjetas */
.card {
  width: 30rem;
  max-width: 300px;
  overflow: hidden;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s;
}

/* Efecto hover en las tarjetas */
.card:hover {
  transform: scale(1.05);
}

/* Imagen dentro de la tarjeta */
.card-image {
  width: 100%;
  height: auto;
  display: block;
}

/* Media query para pantallas más pequeñas */
@media (max-width: 768px) {
  .card-container {
    flex-direction: column;
    align-items: center;
  }
}
    </style>

    <script>
        // Selección de las tarjetas
const cards = document.querySelectorAll('.card');

// Agrega un evento de clic a cada tarjeta
cards.forEach(card => {
  card.addEventListener('click', () => {
    console.log('¡Tarjeta clickeada!');
  });
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
