<?php
include 'conexion.php';
$mensaje = "";
$mensaje_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Verificar si el correo existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        // Generar un código de verificación aleatorio
        $codigo = rand(100000, 999999); // Código de 6 dígitos

        // Guardar el código en la base de datos (se puede agregar un campo 'codigo_recuperacion' en la tabla 'usuarios')
        $sql_update = "UPDATE usuarios SET codigo_recuperacion = '$codigo' WHERE email = '$email'";
        $conexion->query($sql_update);

        // Enviar el código al correo electrónico
        $to = $email;
        $subject = "Recuperación de contraseña - Tramicat";
        $message = "Tu código de recuperación es: $codigo";
        $headers = "From: tramicat024@gmail.com";

        if (mail($to, $subject, $message, $headers)) {
            $mensaje = "Te hemos enviado un código de recuperación a tu correo.";
            header("Location: verificar_codigo.php?email=$email");
            exit;
        } else {
            $mensaje_error = "Hubo un error al enviar el correo.";
        }
    } else {
        $mensaje_error = "El correo no está registrado.";
    }
}
?>

<!-- Formulario de recuperar contraseña -->
<form action="" method="POST">
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <button type="submit">Enviar código</button>
</form>

<?php
if ($mensaje) {
    echo "<div class='mensaje'>$mensaje</div>";
}
if ($mensaje_error) {
    echo "<div class='mensaje_error'>$mensaje_error</div>";
}
?>
