<?php
include("../Controllers/tareaController.php");
session_start();

        $tareacontrol = new TareaController($conexion);

        $idusuario = $_SESSION['idusuario'];
        $idtarea = $_GET['idt'] ?? null;
        $ideq = $_GET['id'];

        $tareas = $tareacontrol->obtenerPorId($idtarea);            
        $fechaLimite = new DateTime($tareas['fecha'] . ' ' . $tareas['hora']);
        $ahora = new DateTime();

        if ($ahora > $fechaLimite) {
            die("La tarea ha expirado.");
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $rutaFinal = $data['url'];


            
            $tarean = $tareacontrol->guardar($rutaFinal, $idusuario, $idtarea);
           header("Location: ../Views/tareaT.php?id=$ideq&&idt=$idtarea");
       
    
