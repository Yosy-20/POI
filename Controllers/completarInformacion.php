<?php
session_start();
require_once '../Controllers/UsuarioController.php';
include("conexion.php");

$controller = new UsuarioController($conexion);

$Usuario = $_SESSION['username'];  // O usa $_SESSION['username'] si prefieres buscar por usuario

$nombre = trim($_POST['nombre']);
$apellidoP = trim($_POST['apellidoP']);
$apellidoM = trim($_POST['apellidoM']);
$descripcion = trim($_POST['descripcion']);

$resultado = $controller->CompletarInformacion($Usuario, $nombre, $apellidoP, $apellidoM, $descripcion);

if (strpos($resultado, "correctamente") !== false) {
    header("Location: ../Views/index.php");  // Redirige a la página principal
} else {
    echo $resultado;  // Muestra el error
}

?>