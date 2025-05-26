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

        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            $nombre = basename($_FILES['archivo']['name']);
            $rutaFinal = '/uploads/' . uniqid() . "_" . $nombre;


            
            $tarean = $tareacontrol->guardar($rutaFinal, $idusuario, $idtarea);
           header("Location: ../Views/tareaT.php?id=$ideq&&idt=$idtarea");
        } else {
            echo "Error al subir el archivo.";
        }
    
