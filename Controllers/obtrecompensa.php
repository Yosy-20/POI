<?php

include("../Controllers/recompensaController.php");

    $idusuario = $_POST['idusuario'];
    $idrecompensa = $_POST['idrecompensa'];

    $reconController = new RecompensaController($conexion);
    $recompensas = $reconController->obtenerRecompensa($idusuario,$idrecompensa);