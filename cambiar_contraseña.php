<?php
include 'conexion.php';
$mensaje = "";
$mensaje_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_GET['email']; // Correo enviado desde verificar_codigo.php
    $nueva_contraseña = $_POST['nueva_contraseña'];
    $confirmar_contraseña = $_POST['confirmar_contraseña'];

    // Verificar que las contraseñas coinciden
    if ($nueva_contraseña != $confirmar_contraseña) {
        $mensaje_error = "Las contraseñas no coinciden.";
    } else {
        // Encriptar la nueva contraseña
        $passwordHash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $sql = "UPDATE usuarios SET password = '$passwordHash', codigo_recuperacion = NULL WHERE email = '$email'";
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "Contraseña cambiada exitosamente.";
            header("Location: login.php");
            exit;
        } else {
            $mensaje_error = "Hubo un error al cambiar la contraseña.";
        }
    }
}
?>

<!-- Formulario para cambiar la contraseña -->
<form action="" method="POST">
    <input type="password" name="nueva_contraseña" placeholder="Nueva contraseña" required>
    <input type="password" name="confirmar_contraseña" placeholder="Confirmar contraseña" required>
    <button type="submit">Cambiar contraseña</button>
</form>

<?php
if ($mensaje) {
    echo "<div class='mensaje'>$mensaje</div>";
}
if ($mensaje_error) {
    echo "<div class='mensaje_error'>$mensaje_error</div>";
}
?>
