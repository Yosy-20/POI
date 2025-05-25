<?php
include("tareaController.php");
session_start();

$titulo = $_POST['titutlotarea'];
$descripcion = $_POST['descriptarea'];
$fecha = $_POST['fechtarea'];
$hora = $_POST['horatarea'];
$grupo_id = $_GET['id'];
$usuario_id = $_SESSION['idusuario']; // Asegúrate que esté bien seteado

$tareaModel = new TareaController($conexion);
$tarea_id = $tareaModel->guardarTarea($titulo, $descripcion, $fecha, $hora, $grupo_id, $usuario_id);

// Guardar archivos
if (!empty($_FILES['files']['name'][0])) {
    $total = count($_FILES['files']['name']);
    for ($i = 0; $i < $total; $i++) {
        $nombre = $_FILES['files']['name'][$i];
        $tmp = $_FILES['files']['tmp_name'][$i];
        $destino = "../uploads/" . uniqid() . "_" . basename($nombre);
        
        if (move_uploaded_file($tmp, $destino)) {
            $tareaModel->guardarArchivo($tarea_id, $destino);
        }
    }
}
var_dump($destino);

//header("Location: ../Views/tareas.php");
