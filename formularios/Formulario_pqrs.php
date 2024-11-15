<?php
session_start();
include 'C:/laragon/www/prueba22/conexion.php';

// Directorio donde se guardarán los archivos subidos
$uploads_dir = 'C:/laragon/www/prueba22/uploads/pqrs';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0775, true);
}

// Verificar si el usuario ha iniciado sesión y tiene el rol adecuado
if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'usuario') {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_pqrs = $_POST['tipo_pqrs'];
    $descripcion = $_POST['descripcion'];
    $archivos_subidos = [];

    // Verificar y procesar archivos
    if (isset($_FILES['archivo']['tmp_name'])) {
        if (is_array($_FILES['archivo']['tmp_name'])) {
            foreach ($_FILES['archivo']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['archivo']['error'][$key] == UPLOAD_ERR_OK) {
                    $archivo_nombre = basename($_FILES['archivo']['name'][$key]);
                    $archivo_ruta = $uploads_dir . '/' . $archivo_nombre;
                    if (move_uploaded_file($tmp_name, $archivo_ruta)) {
                        $archivos_subidos[] = $archivo_nombre; // Guarda solo el nombre del archivo
                    } else {
                        echo "Error al mover el archivo: $archivo_nombre.";
                        exit;
                    }
                }
            }
        } else {
            if ($_FILES['archivo']['error'] == UPLOAD_ERR_OK) {
                $archivo_nombre = basename($_FILES['archivo']['name']);
                $archivo_ruta = $uploads_dir . '/' . $archivo_nombre;
                if (move_uploaded_file($_FILES['archivo']['tmp_name'], $archivo_ruta)) {
                    $archivos_subidos[] = $archivo_nombre; // Guarda solo el nombre del archivo
                } else {
                    echo "Error al mover el archivo: $archivo_nombre.";
                    exit;
                }
            }
        }
    }

    // Convertir el array de nombres de archivos en una cadena
    $archivo_final = implode(',', $archivos_subidos);
    
    $sql = "INSERT INTO pqrs_solicitudes (id_usuario, tipo_pqrs, descripcion, archivo, estado) 
            VALUES (?, ?, ?, ?, 'pendiente')";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("isss", $_SESSION['id'], $tipo_pqrs, $descripcion, $archivo_final);

    if ($stmt->execute()) {
        header("Location: /prueba22/Cuenta_user/Pqrs_user.php");
        exit();
    } else {
        echo "Error al crear la solicitud: " . $stmt->error;
    }
    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud PQRS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="Pqrs.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="/assets/CSS/estilo_formu_pqrs.css">
    
</head>
<body>
    <main class="container solicitud">
        <h1 class="text-center">Solicitud de PQRS</h1>

        <h3>Detalles de la solicitud</h3>
        <p>
            En "Tramicat," estamos comprometidos con brindarte el mejor servicio. Elige entre las opciones disponibles (Petición, Queja, Reclamo, Sugerencia) según la naturaleza de tu solicitud, describe en detalle su solicitud. Una vez enviada tu solicitud, nuestro equipo se encargará de revisarla y te notificaremos el estado de la misma en el menor tiempo posible.
        </p>

        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="mb-3">
            <label for="tipo_pqrs">Tipos de solicitud: </label>
            <select name="tipo_pqrs" id="tipo_pqrs" required>
                <option value="">Seleccione un tipo</option>
                <option value="Petición">Petición</option>
                <option value="Queja">Queja</option>
                <option value="Reclamo">Reclamo</option>
                <option value="Sugerencia">Sugerencia</option>
            </select>
            <div id="tipoError" class="error-message"></div>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea name="descripcion" id="descripcion" placeholder="Descripción" required></textarea>
            <div id="descripcionError" class="error-message"></div>
        </div>

        <div class="mb-3">
            <input class="form-control" type="file" name="archivo" id="archivo" accept=".pdf">
            <div id="archivoError" class="error-message"></div>
        </div>

        <div class="btn-pqrs">
            <a href="pqrs_usuario.php" class="btn1" id="btn_cancelar">Cancelar</a>
            <button type="submit" class="btn1">Enviar</button>
        </div>
    </form>

    <script>
        function validateTipoPQRS() {
            const tipoPQRS = document.getElementById('tipo_pqrs');
            const errorElement = document.getElementById('tipoError');
            errorElement.textContent = '';
            if (tipoPQRS.value === "") {
                errorElement.textContent = 'Debe seleccionar un tipo de solicitud.';
            }
        }

        function validateDescripcion() {
            const descripcion = document.getElementById('descripcion');
            const errorElement = document.getElementById('descripcionError');
            errorElement.textContent = '';
            const regex = /^[a-zA-Z0-9\s.,;:?!&@()]+$/; // Permite letras, números y ciertos símbolos
            if (!regex.test(descripcion.value)) {
                errorElement.textContent = 'La descripción solo puede contener letras, números y símbolos permitidos.';
            }
        }

        function validateArchivo() {
            const archivo = document.getElementById('archivo');
            const errorElement = document.getElementById('archivoError');
            errorElement.textContent = '';

            if (archivo.files.length > 0) {
                const file = archivo.files[0];
                if (file.type !== 'application/pdf') {
                    errorElement.textContent = 'El archivo debe ser un documento PDF.';
                }
            }
        }

        function validateForm() {
            // Validaciones de cada campo
            validateTipoPQRS();
            validateDescripcion();
            validateArchivo();

            // Comprobar si hay errores
            return !document.querySelector('.error-message:empty') ? false : true;
        }

        // Añadir eventos para validación en tiempo real
        document.getElementById('tipo_pqrs').addEventListener('change', validateTipoPQRS);
        document.getElementById('descripcion').addEventListener('input', validateDescripcion);
        document.getElementById('archivo').addEventListener('change', validateArchivo);
    </script>

    </main>

    <style>

.error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 0.5em;
        }
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
}
.error {
            color: red;
            margin-top: 5px;
        }

.solicitud {
    margin: 50px auto; /* Centra el formulario horizontalmente */
    max-width: 800px; /* Limita el ancho máximo del formulario */
    padding: 55px;
    background-color: #ffffff; /* Fondo blanco para el formulario */
    border-radius: 8px; /* Bordes redondeados */
    box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombra suave */
}

h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
    margin: 40px;
}

h3 {
    margin: 1rem 0;
    font-size: 1.5rem;
}

p {
    margin-bottom: 1rem;
    font-size: 1rem;
    line-height: 1.5;
}

.selecion-pqrs{
    width: 100%;
    height: 6vh;   
}

label{
    font-weight: bold;
}

select{
    border-radius: 8px;
    width: 200px;
    height: 35px;
    align-items: center;
    border: 1px solid #ccc; /* Define el borde del textarea */
}

textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 1rem;
}
.btn-pqrs {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 2vh; /* Ajusta la posición vertical */
    text-decoration: none;
  }
  
  .btn1 {
    margin: 15px; /* Ajusta la separación entre botones */
    padding: 10px 20px;
    border: 1px solid #333;
    border-radius: 5px;
    background-color: #ffffff;
    color: #545151;
    cursor: pointer;
    text-decoration: none;
  }
  
  .btn1:hover {
    background-color: #75dd78;
  }
  
  #btn_cancelar {
    background-color: #ffffff;
  }
  
  #btn_cancelar:hover {
    background-color: #f36251;
  }

/* Media queries for responsiveness */
@media (max-width: 768px) {
    .solicitud {
        margin: 20px;
    }

    .btn-pqrs {
        flex-direction: column;
        gap: 10px;
    }

    .btn-pqrs .btn {
        width: 100%;
    }
}

    </style>
</body>
</html>
