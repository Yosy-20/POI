<?php
session_start();
include("equipoController.php");
require_once '../Controllers/UsuarioController.php';
$_SESSION['idusuario'];
$_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombreEquipo'];
    $descripcion = $_POST['descripcionEquipo'];
    $codigo = $_POST['codigoEquipo'];
    $color = $_POST['colorEquipo'];
    $creado = $_SESSION['idusuario'];
    

    $usuarios = json_decode($_POST['usuariosEquipo'], true);
    $equipo= new equipoController($conexion);
    $id =  $equipo->guardar($nombre,$descripcion,$codigo,$color,$creado,$usuarios);

}