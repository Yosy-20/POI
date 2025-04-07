<?php
include("conexion.php");

$nombre = $_POST['name'];
$correo = $_POST['email'];
$password = $_POST['password'];


if ($_POST['password'] !== $_POST['confirmpass']) {
    echo "Las contraseñas no coinciden.";
    exit;
}

$imagen = "";
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $nombreImagen = basename($_FILES["file"]["name"]);
    $rutaDestino = "img/" . $nombreImagen; 
    $imagen=$rutaDestino; 
}

//var_dump($nombre, $correo, $password,$imagen);
$query = "INSERT INTO usuario (nombre, correo, contraseña, Foto)
        VALUES ('$nombre', '$correo', '$password', '$imagen')";

$stmt = $conexion->prepare($query);
$stmt->execute();

header('Location: ../Views/register.html');