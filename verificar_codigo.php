<?php
include 'conexion.php';
$mensaje = "";
$mensaje_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $email = $_GET['email']; // Correo enviado desde recuperar_contraseña.php

    // Verificar si el código coincide con el almacenado en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND codigo_recuperacion = '$codigo'";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        // Mostrar el formulario para cambiar la contraseña
        header("Location: cambiar_contraseña.php?email=$email");
        exit;
    } else {
        $mensaje_error = "Código incorrecto.";
    }
}
?>

<!-- Formulario de verificación del código -->
<form action="" method="POST">
    <input type="text" name="codigo" placeholder="Código de verificación" required>
    <button type="submit">Verificar código</button>
</form>

<?php
if ($mensaje_error) {
    echo "<div class='mensaje_error'>$mensaje_error</div>";
}
?>
