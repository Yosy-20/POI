<?php
require('conexion.php');
session_start();

$idusuario = $_POST['idusuario'];
$idrecompensa = $_POST['idrecompensa'];

$stmt = $conexion->prepare("UPDATE usuario SET idmarco = ? WHERE id = ?");
$stmt->bind_param("ii", $idrecompensa, $idusuario);
$stmt->execute();

//var_dump($idusuario,$idrecompensa);

header("Location: ../Views/perfil.php");
exit;
