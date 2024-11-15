<?php
session_start();
include 'C:/laragon/www/prueba22/conexion.php';

// Directorio donde se guardarán los archivos subidos
$uploads_dir = 'C:/laragon/www/prueba22/uploads/englobe';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0775, true);
}

// Verificación de sesión de usuario
if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'usuario') {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recoger datos del formulario
    $tipo_solicitante = $_POST['tipo_solicitante'];
    $nombres_apellidos = $_POST['nombres_apellidos'];
    $tipo_documento = $_POST['tipo_documento'];
    $numero_documento = $_POST['numero_documento'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $ciudad_municipio = $_POST['ciudad_municipio'];
    $matricula_inmobiliaria = $_POST['matricula_inmobiliaria'];
    $numero_predial = $_POST['numero_predial'];
    $descripcion = $_POST['descripcion'];
    $id_usuario = $_SESSION['id'];

    // Manejo de archivos, comprobando que sean obligatorios
    if (!empty($_FILES['archivo1']['name']) && !empty($_FILES['archivo2']['name']) && !empty($_FILES['archivo3']['name'])) {
        $archivo1 = $uploads_dir . '/' . basename($_FILES['archivo1']['name']);
        $archivo2 = $uploads_dir . '/' . basename($_FILES['archivo2']['name']);
        $archivo3 = $uploads_dir . '/' . basename($_FILES['archivo3']['name']);

        if (move_uploaded_file($_FILES['archivo1']['tmp_name'], $archivo1) &&
            move_uploaded_file($_FILES['archivo2']['tmp_name'], $archivo2) &&
            move_uploaded_file($_FILES['archivo3']['tmp_name'], $archivo3)) {
            
            // Generar un número de radicado único
            $fecha = date('Ymd'); // Formato YYYYMMDD
            $sql_max = "SELECT MAX(CAST(SUBSTRING(numero_radicado, 9) AS UNSIGNED)) AS max_radicado FROM formulario_tramites WHERE numero_radicado LIKE '$fecha%'";
            $resultado_max = $conexion->query($sql_max);
            $row = $resultado_max->fetch_assoc();
            $secuencial = $row['max_radicado'] ? $row['max_radicado'] + 1 : 1; // Incrementa el secuencial o empieza desde 1
            $numero_radicado = $fecha . str_pad($secuencial, 4, '0', STR_PAD_LEFT); // Ejemplo: 202311010001

            // Insertar datos en la base de datos
            $sql = "INSERT INTO formulario_tramites (id_usuario, tipo_solicitante, nombres_apellidos, tipo_documento, numero_documento, correo, telefono, ciudad_municipio, matricula_inmobiliaria, numero_predial, archivo1, archivo2, archivo3, descripcion, tipo_tramite, estado, numero_radicado)
                    VALUES ('$id_usuario', '$tipo_solicitante', '$nombres_apellidos', '$tipo_documento', '$numero_documento', '$correo', '$telefono', '$ciudad_municipio', '$matricula_inmobiliaria', '$numero_predial', '$archivo1', '$archivo2', '$archivo3', '$descripcion', 'Englobe', 'pendiente', '$numero_radicado')";

            if ($conexion->query($sql) === TRUE) {
                echo '
                <div class="editar_perfil" id="editar_perfil">
                <div class="container_editar" id="container_editar">

                    <a href="/prueba22/Cuenta_user/Tramite_user.php" class="close-button" id="close-button">
                        <img src="/prueba/icons/cerrar.png" alt="">
                    </a>

                    <h1 class="text1">Confirmación de Solicitud de Englobe</h1>

                    <div class="container">
                        <p>Estimado usuario, Le informamos que su solicitud de englobe ha sido enviada correctamente. Recibirá una notificación por correo electrónico a la dirección <b>' . htmlspecialchars($correo) . '</b> una vez que su solicitud haya sido procesada.</p>
                        <p>El número de radicado de su solicitud es <b>' . htmlspecialchars($numero_radicado) . '</b>. Puede consultar el estado de su trámite en cualquier momento ingresando a la sección "Consultar Estado del Trámite" en nuestro sitio web.</p>
                        <p class="t1">Gracias por su atención.</p>      
                    </div>
            </div>
        </div>

        <style>
        
/*estilo de la ventana flotante de editar perfil*/
.editar_perfil {
width: 110%;
height: 100%;
position: fixed;
top: 60px;
left: 25%;
backdrop-filter: blur(0.70px);
background-color: rgba(255, 255, 255, 0.3);
z-index: 1;
align-items: center;
justify-content: center;
overflow: hidden;
}

.container_editar {
position: relative;
background-color: #fff;
width: 700px;
padding: 4rem ;
border-radius: 10px;
animation-name: modal;
animation-duration: 0.5s;
box-shadow: 0 5px 15px -5px rgba(0, 0, 0, 0.4);
z-index: 10;
}

.close-button {
position: absolute;
top: 12px;
right: 10px;
width: 40px;
height: 40px;
display: flex;
align-items: center;
justify-content: center;
cursor: pointer;
border-radius: 50%;
border: 1px solid #eee;
}

/* Animación para abrir el modal (zoom in) */
@keyframes modal {
from {
transform: scale(0.4);
}
to {
transform: scale(1);
}
}

/* Animación para cerrar el modal (zoom out) */
@keyframes closeModal {
from {
transform: scale(1);
}
to {
transform: scale(0.4);
}
}

/* Clase que se aplica al contenedor cuando está en proceso de cierre */
.close {
animation: closeModal 1s forwards cubic-bezier(0, -0.42, 1, -0.41);
}

.container {
            
    background: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    margin: auto;
    margin-top: 38px;
}
    </style>
        ';

        
        // Aquí podrías enviar correos a usuario y administrador utilizando mail()
    } else {
        echo "Error: " . $conexion->error;
    }
} else {
    echo "Error al subir los archivos.";
}
} else {
echo "Todos los archivos PDF son obligatorios.";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de englobe</title>

    <link rel="stylesheet" href="css/estilo_formularios.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<main class="container formulario">
    <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <h1>Formulario de Englobe</h1>
        
        <fieldset>
            <legend>Información del Solicitante</legend>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="tipo_solicitante">Tipo de solicitante:</label>
                    <select id="tipo_solicitante" name="tipo_solicitante" required>
                        <option value="">Seleccione...</option>
                        <option value="persona_natural">Persona Natural</option>
                        <option value="persona_juridica">Persona Jurídica</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nombres_apellidos">Nombres y Apellidos:</label>
                    <input type="text" id="nombres_apellidos" name="nombres_apellidos" required>
                    <div id="nombresError" class="error-message"></div>
                </div>
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="tipo_documento">Tipo de documento:</label>
                    <select id="tipo_documento" name="tipo_documento" required>
                        <option value="">Seleccione...</option>
                        <option value="cc">Cédula de Ciudadanía</option>
                        <option value="ti">Tarjeta de Identidad</option>
                        <option value="ce">Cédula de Extranjería</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="numero_documento">Número de documento:</label>
                    <input type="text" id="numero_documento" name="numero_documento" required>
                    <div id="numeroDocError" class="error-message"></div>
                </div>
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" id="correo" name="correo" required>
                    <div id="correoError" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" required>
                    <div id="telefonoError" class="error-message"></div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Información del Inmueble</legend>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="ciudad_municipio">Ciudad o municipio:</label>
                    <select id="ciudad_municipio" name="ciudad_municipio" required>
                        <option value="">Seleccione...</option>
                        <option value="bucaramanga">Bucaramanga</option>
                        <option value="floridablanca">Floridablanca</option>
                        <option value="piedecuesta">Piedecuesta</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="matricula_inmobiliaria">Matrícula inmobiliaria:</label>
                    <input type="text" id="matricula_inmobiliaria" name="matricula_inmobiliaria" required>
                    <div id="matriculaError" class="error-message"></div>
                </div>
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="numero_predial">Número predial:</label>
                    <input type="text" id="numero_predial" name="numero_predial" required>
                    <div id="numeroPredialError" class="error-message"></div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Requisitos del trámite</legend>

           <!-- Requisito 1 -->
<div class="file-input-container">
    <div class="file-text">1. Plano topográfico en medio magnético:</div>
    <div id="file-preview1" class="file-preview">
        <input type="file" id="archivo1" name="archivo1" accept=".pdf" required onchange="this.nextElementSibling.textContent = this.files[0].name">
        <label for="archivo1" class="file-label">
            <i class='bx bx-file-blank file-icon'></i>
            <span class="file-name">No hay archivo seleccionado</span>
        </label>
    </div>
</div>

<!-- Requisito 2 -->
<div class="file-input-container">
    <div class="file-text">2. Plano topográfico en medio magnético:</div>
    <div id="file-preview2" class="file-preview">
        <input type="file" id="archivo2" name="archivo2" accept=".pdf" required onchange="this.nextElementSibling.textContent = this.files[0].name">
        <label for="archivo2" class="file-label">
            <i class='bx bx-file-blank file-icon'></i>
            <span class="file-name">No hay archivo seleccionado</span>
        </label>
    </div>
</div>

<!-- Requisito 3 -->
<div class="file-input-container">
    <div class="file-text">3. Referencia o copia simple de la licencia de construcción que aprueba la partición:</div>
    <div id="file-preview3" class="file-preview">
        <input type="file" id="archivo3" name="archivo3" accept=".pdf" required onchange="this.nextElementSibling.textContent = this.files[0].name">
        <label for="archivo3" class="file-label">
            <i class='bx bx-file-blank file-icon'></i>
            <span class="file-name">No hay archivo seleccionado</span>
        </label>
    </div>
</div>

<style>
.file-input-container {
    margin-bottom: 20px; /* Espaciado entre los requisitos */
}

.file-preview {
    position: relative;
    display: flex;
    align-items: center;
}

input[type="file"] {
    display: none; /* Ocultar el input file */
}

.file-label {
    cursor: pointer;
    display: flex;
    align-items: center;
    color: #4a90e2;
    font-weight: bold;
}

.file-icon {
    font-size: 24px;
    margin-right: 8px;
}

.file-name {
    font-size: 16px;
    color: #7f8c8d;
}

/* Estilo para el icono */
.file-icon:hover {
    color: #007bff;
    transition: color 0.3s;
}
</style>


        </fieldset>

        <fieldset>
            <legend>Descripción</legend>
            <div class="form-group">
                <textarea id="descripcion" name="descripcion" rows="4"></textarea>
                <div id="descripcionError" class="error-message"></div>
            </div>
        </fieldset>

        <div class="btn_enviar">
            <button type="submit" name="btn-enviar" >Enviar</button>
        </div>
    </form>

    <script>
        function validateNombres() {
            const nombres = document.getElementById('nombres_apellidos');
            const errorElement = document.getElementById('nombresError');
            errorElement.textContent = '';
            if (!/^[a-zA-Z\s]+$/.test(nombres.value)) {
                errorElement.textContent = 'Solo puede ingresar letras.';
            }
        }

        function validateNumeroDocumento() {
            const numeroDoc = document.getElementById('numero_documento');
            const errorElement = document.getElementById('numeroDocError');
            errorElement.textContent = '';
            if (!/^\d{8,10}$/.test(numeroDoc.value)) {
                errorElement.textContent = 'Solo puede ingresar números, mínimo 8 y máximo 10 dígitos.';
            }
        }

        function validateCorreo() {
            const correo = document.getElementById('correo');
            const errorElement = document.getElementById('correoError');
            errorElement.textContent = '';
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo.value)) {
                errorElement.textContent = 'Debe ingresar un correo electrónico válido.';
            }
        }

        function validateTelefono() {
            const telefono = document.getElementById('telefono');
            const errorElement = document.getElementById('telefonoError');
            errorElement.textContent = '';
            if (!/^\d{1,12}$/.test(telefono.value)) {
                errorElement.textContent = 'Solo puede ingresar números, máximo 12 dígitos.';
            }
        }

        function validateMatricula() {
            const matricula = document.getElementById('matricula_inmobiliaria');
            const errorElement = document.getElementById('matriculaError');
            errorElement.textContent = '';
            if (!/^\d+$/.test(matricula.value)) {
                errorElement.textContent = 'Solo puede ingresar números.';
            }
        }

        function validateNumeroPredial() {
            const numeroPredial = document.getElementById('numero_predial');
            const errorElement = document.getElementById('numeroPredialError');
            errorElement.textContent = '';
            if (!/^\d+$/.test(numeroPredial.value)) {
                errorElement.textContent = 'Solo puede ingresar números.';
            }
        }

        function validateDescripcion() {
            const descripcion = document.getElementById('descripcion');
            const errorElement = document.getElementById('descripcionError');
            errorElement.textContent = '';
            if (!/^[a-zA-Z0-9.,\s]*$/.test(descripcion.value)) {
                errorElement.textContent = 'Solo puede ingresar letras, números y algunos caracteres especiales.';
            }
        }

        function validateForm() {
            // Se valida el formulario al enviar
            validateNombres();
            validateNumeroDocumento();
            validateCorreo();
            validateTelefono();
            validateMatricula();
            validateNumeroPredial();
            validateDescripcion();
            
            // Se comprueba si hay errores
            return !document.querySelector('.error-message:empty') ? false : true;
        }

        // Añadir eventos para validación en tiempo real
        document.getElementById('nombres_apellidos').addEventListener('input', validateNombres);
        document.getElementById('numero_documento').addEventListener('input', validateNumeroDocumento);
        document.getElementById('correo').addEventListener('input', validateCorreo);
        document.getElementById('telefono').addEventListener('input', validateTelefono);
        document.getElementById('matricula_inmobiliaria').addEventListener('input', validateMatricula);
        document.getElementById('numero_predial').addEventListener('input', validateNumeroPredial);
        document.getElementById('descripcion').addEventListener('input', validateDescripcion);
    </script>

    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 0.5em;
        }
    </style>

</main>
</body>
</html>

<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #fafafb;
    margin: 0;
    padding: 20px;
}


h1 {
    text-align: center;
    color: #333;
    margin: 15px;
    margin-bottom: 32px;
}

form {
    margin: 0 auto;
    max-width: 60%;
    background-color: #fff;
    padding: 35px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}



fieldset {
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 15px;
    padding: 15px;
}

legend {
    padding: 0 10px;
    font-weight: bold;
    color: #000000;
    margin-bottom: 10px;
}

.form-group-row {
    display: flex;
    justify-content: space-between;
    gap: 80px; /* Espacio entre los campos */
    margin-bottom: 10px;
}

.form-group {
    flex: 1; /* Hace que los campos tomen el mismo espacio */
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="tel"],
input[type="file"],
select,
textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="file"] {
    padding: 8px;
}

textarea {
    resize: vertical;
    width: 100%;
}


button{
    background-color: #3697ff;
    color: #fff;
    padding: 15px 20px;
    border: none;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
}
button:hover{
    background-color: #0056b3;
}
.enlace{
    text-align: center;
    font-size: 14px;
    color: #333;
}

.btn_enviar{
    text-align: center;
    display: flex;
    justify-content: center;
}

hr{
    width: 100%;
}

 /* Estilos para el contenedor del archivo */
 .file-input-container {
    display: flex;
    align-items: center; /* Centra verticalmente el contenido */
    justify-content: space-between; /* Distribuye espacio entre elementos */
    padding: 10px; /* Espaciado interno */
    margin-bottom: 2px; /* Espaciado inferior */
}

/* Estilos para el texto del archivo */
.file-text {
    font-size: 16px; /* Tamaño de fuente */
    color: #333; /* Color del texto */
    flex: 1; /* Ocupa el espacio disponible */
}

/* Estilos para la vista previa del archivo */
.file-preview {
    margin-right: 10px; /* Espaciado derecho */
}

/* Estilos para el input del archivo */
.file-input {
    display: none; /* Oculta el input de archivo por defecto */
}

/* Estilos para el ícono del archivo */
.file-icon {
    font-size: 24px; /* Tamaño del ícono */
    color: #fe1a1a; /* Color del ícono */
    cursor: pointer; /* Cambia el cursor al pasar sobre el ícono */
    transition: color 0.3s; /* Transición de color */
}

/* Cambia el color del ícono al pasar el mouse sobre él */
.file-icon:hover {
    color: #0056b3; /* Color del ícono al pasar el mouse */
}


@media (max-width: 1200px) {
    .form-group-row {
        gap: 20px;
    }
}

@media (max-width: 992px) {
    .form-group-row {
        flex-direction: column; /* Cambia la dirección de los elementos a columna en pantallas medianas */
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .btn_enviar {
        flex-direction: column;
    }
}

@media (max-width: 768px) {
    form {
        padding: 15px;
    }
    
    .form-group-row {
        gap: 10px;
    }
}

@media (max-width: 576px) {
    .form-group-row {
        flex-direction: column;
        gap: 0;
    }
    
    .form-group {
        margin-bottom: 10px;
    }
    
    button {
        width: 100%;
        padding: 12px 0;
    }
}
</style>