<?php 
 // es para incluir la cabecera (ubicacion de la carpeta)
 include("template/cabecera.php");
 ?>
    <section class="carrusel_automatico" id="inicio">
      <div class="carr">
          <ul>
            <li><img src="./icons/carru1.png" alt=""></li>
            <li><img src="./icons/carru5.avif" alt=""></li>
            <li><img src="./icons/carru3.png" alt=""></li>
          </ul>
        </div>

        <section class="contenedor-1">
          <div class="texto_inicio">
            <h1>¡Bienvenido a Tramicat!</h1>
            <p>
              Tramicat es una plataforma creada para facilitar tus trámites catastrales de forma rápida y sencilla. 
              A través de ella, puedes gestionar procesos como el cambio de propietario, englobe y desenglobe de inmuebles, 
              todo en línea. Su propósito es eliminar la necesidad de largas filas y permitirte realizar estos trámites desde donde 
              te encuentres, ahorrando tiempo y simplificando el proceso con comodidad.
            </p>
          </div>
        </section>

        <section class="contenedor-2" id="tramites">
            <div class="texto-tramites">
                <h1>Tramites</h1>
                <p>En nuestra plataforma, te ofrecemos una variedad de 
                    trámites diseñados para facilitar tus gestiones. 
                    A continuación, te presentamos los principales tipos 
                    de trámites que puedes realizar:</p>
            </div>
            <div class="container-card">
                <div class="card">
                    <figure>
                        <a href="requisito_1.php"> <img src="./icons/cambio de propietario.jpeg"></a>
                       
                    </figure>
                    <div class="contenido-card">
                        <h3>Cambio de Propietario</h3>
                        <p>Actualización del nombre y documento del propietario de un predio o una mejora conforme a los cambios que se presenta
                        </p>
                    </div>
                </div>
                <div class="card">
                    <figure>
                        <a href="requisito_2.php"><img src="./icons/englobe.jpeg"></a>
                        
                    </figure>
                    <div class="contenido-card">
                        <h3>Englobe</h3>
                        <p>Es el proceso de unir dos o más parcelas o lotes de terreno en una sola unidad catastral.</p>
                    </div>
                </div>
                <div class="card">
                    <figure>
                        <a href="requisito_3.php"><img src="./icons/desenglobe.jpeg"></a>
                    </figure>
                    <div class="contenido-card">
                        <h3>Desenglobe</h3>
                        <p>Es el proceso donde una parcela se divide en dos o más partes independientes.</p>
                    </div>
                </div>
            </div>
        </section>

        <!--sesion 2 de estado del tramite-->
        <section class="contenedor-3" id="estado_tramite">
            <div class="contenedor-3-img">
                <img src="./icons/Consulta_tramite.png" alt="">
            </div>
            <div class="contenedor-3-text">
                <h1>Consultar tramite</h1>
                <p>
                    Con Tramicat, puedes seguir el progreso de tus trámites en tiempo 
                    real, desde que envíes tu solicitud hasta que se resuelva. Podrás
                    ver en qué etapa se encuentra cada trámite, asegurándote de estar
                    siempre informado. Para consultar el estado de tu trámite.
                    </p>
                <a href="login.php?redirect=Tramite_user">Consultar</a>
            </div>
        </section>

        <section class="contenedor-4" id="pqrs">
            <div class="texto_pqrs">
                <h1>Peticiones, Quejas, Reclamos y Sugerencias</h1>
                <p> En nuestra página web de Tramicat, valoramos la opinión y la experiencia de cada uno de nuestros usuarios. Para mejorar continuamente nuestros servicios y atender de manera efectiva cualquier inquietud, hemos habilitado un espacio dedicado a la recepción y gestión de Peticiones, Quejas, Reclamos y Sugerencias (PQRS). </p>
            </div>
            <div class="tarjetas-container">
                <!-- Tarjeta 1 -->
                <div class="tajetas_pqrs" >
                    <div class="icon">
                        <a href="login.php?redirect=formulario_pqrs"><img src="./icons/solicitud_pqrs.png" alt="Solicitud de PQRs"></a>
                    </div>
                    <h3>Solicitud de PQRS</h3>
                </div>
      
                <!-- Tarjeta 2 -->
                <div class="tajetas_pqrs">
                    <div class="icon">
                        <a href="login.php?redirect=pqrs_user"><img src="./icons/consulta_pqrs.png" alt="Consultar PQRs"></a>
                    </div>
                    <h3 class="t4">Consultar PQRS</h3>
                </div>
          </div>
        </section>

        <!-- Sección de Preguntas Frecuentes (FAQ) -->
        <section class="contenedor-5">
            <div class="Preguntas" >
                <h1>Preguntas frecuentes</h1>

                <!-- Pregunta frecuente 0 -->
                <div class="faq">
                  <button class="accordion">
                    ¿Qué tipo de usuarios pueden utilizar tramicat?
                      <i class="fa-solid fa-chevron-down"></i>
                  </button>
                  <div class="pannel">
                    <p>
                      ​Tramicat está diseñado para cualquier persona que 
                      tenga una propiedad y necesite realizar trámites catastrales.
                    </p>
                  </div>
              </div>
                
                <!-- Pregunta frecuente 1 -->
                 <div class="faq">
                    <button class="accordion">
                        ¿Qué es el catastro y para qué sirve?
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="pannel">
                        <p>El catastro es un registro administrativo que proporciona información sobre las propiedades inmuebles de un país. Sirve para identificar y describir los bienes inmuebles, establecer su valor y uso, y ayudar en la planificación urbana y la administración fiscal.</p>
                    </div>
                </div>
                
                <!-- Pregunta frecuente 2 -->
                 <div class="faq">
                    <button class="accordion">
                        ¿Qué es el englobamiento y cómo afecta a mi propiedad?
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="pannel">
                        <p>El englobamiento es el proceso de combinar varias parcelas en una sola unidad catastral. Esto puede simplificar la administración pero cambiar el número catastral y la descripción de la propiedad.</p>
                    </div>
                </div>
                <!-- Pregunta frecuente 3 -->
                 <div class="faq">
                    <button class="accordion">
                        ¿Qué documentos necesito para realizar una actualización en el catastro?
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="pannel">
                        <p>Para actualizar la información catastral, generalmente necesitarás presentar documentos como la escritura de propiedad, planos actualizados, documentos de identificación y, en algunos casos, permisos de construcción o modificación.</p>
                    </div>
                </div>
  
                <!-- Pregunta frecuente 4 -->
                <div class="faq">
                    <button class="accordion">
                        ¿Cómo puedo corregir errores en la información catastral de mi propiedad?
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="pannel">
                        <p>Si encuentras errores en la información catastral, debes presentar una solicitud de corrección a la oficina de catastro. Esto puede requerir que completes un formulario y proporciones documentación que respalde la corrección solicitada.</p>
                    </div>
                </div>
            </div>

        </section>
    </main>

    <script src="/prueba22/js/index.js"></script>

    <?php 
 // es para incluir la cabecera (ubicacion de la carpeta)
 include("template/pie.php");
 ?>