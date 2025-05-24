<?php
session_start();
include("UsuarioController.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['username']);
    $contrasena = trim($_POST['password']);

    // Validar campos vacíos
    if (empty($usuario) || empty($contrasena)) {
        header("Location: ../Views/login.php?error=" . urlencode("Debes completar todos los campos"));
        exit();
    }

  
    // Instanciar tu clase de usuario
    $usuarioObj = new UsuarioController($conexion); 

    $resultado = $usuarioObj->Login($usuario, $contrasena);

    if ($resultado === "Contraseña incorrecta" || $resultado === "Usuario no encontrado") {
        // Redirigir con mensaje de error
        header("Location: ../Views/login.php?error=" . urlencode($resultado));
        exit();
    }
    // Si es un objeto Usuario válido, el método Login ya debe estar redirigiendo (según tu código).
    // Aquí no hacemos nada más.
} else {
    header("Location: ../Views/login.php");
    exit();
}
?>

