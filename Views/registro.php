<?php
include("conexion.php");

$nombre = $_POST['name'];
$correo = $_POST['email'];
$password = $_POST['password'];



$imagen = "";
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $nombreImagen = basename($_FILES["file"]["name"]);
    $rutaDestino = "http://localhost/POI/Views/img/" . $nombreImagen; 
    $imagen=$rutaDestino; 
}



//var_dump($nombre, $correo, $password,$imagen);
$query = "INSERT INTO usuario (nombre, correo, contraseña, Foto)
        VALUES ('$nombre', '$correo', '$password', '$imagen')";

$stmt = $conexion->prepare($query);
$stmt->execute();

header('Location: ../Views/resgister.html');