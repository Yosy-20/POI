<?php
include("UsuarioController.php");

// Verificar si llegó el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre = $_POST['name'];
    $correo = $_POST['email'];
    $contrasena = $_POST['password'];
    $confirmar = $_POST['confirmpass'];

    // Validar que las contraseñas coinciden
    if ($contrasena !== $confirmar) {
        die("Las contraseñas no coinciden.");
    }

    // Procesar la foto
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $fotoTmp = $_FILES['file']['tmp_name'];
        $fotoNombre = basename($_FILES['file']['name']);
        $fotoDestino = "../uploads/" . $fotoNombre;

        // Asegúrate de que la carpeta uploads exista y tenga permisos de escritura
        if (!move_uploaded_file($fotoTmp, $fotoDestino)) {
            die("Error al subir la foto.");
        }
    } else {
        $fotoDestino = "../uploads/default.jpg";  // Foto por defecto si no se sube ninguna
    }

    // Crear controlador y registrar usuario
    $usuarioController = new UsuarioController($conexion);

    $resultado = $usuarioController->Registro(
        $nombre,    // Aquí usé $nombre como usuario por simplicidad
        $correo,
        $contrasena,
        $fotoDestino
    );

    echo $resultado;
} else {
    echo "Acceso no permitido.";
}
?>
