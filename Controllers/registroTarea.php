<?php
include("tareaController.php");
session_start();

$supabase_url = 'https://gdvrvcdnqvjitelbpcyb.supabase.co';
$supabase_api_key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImdkdnJ2Y2RucXZqaXRlbGJwY3liIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDgyOTA1MDIsImV4cCI6MjA2Mzg2NjUwMn0.r5ybnwC4gyEpYwhAHat1tZ63MK-S2QeegCLIwxbJSGI';
$bucket = 'entregas';

$titulo = $_POST['titutlotarea'];
$descripcion = $_POST['descriptarea'];
$fecha = $_POST['fechtarea'];
$hora = $_POST['horatarea'];
$grupo_id = $_GET['id'];
$usuario_id = $_SESSION['idusuario']; // Asegúrate que esté bien seteado

$tareaModel = new TareaController($conexion);
$id_tarea = $tareaModel->guardarTarea($titulo, $descripcion, $fecha, $hora, $grupo_id, $usuario_id);

// Guardar archivos

foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
    if ($_FILES['files']['error'][$index] === UPLOAD_ERR_OK) {
        $fileTmp = $tmpName;
        $originalName = basename($_FILES['files']['name'][$index]);
        $uniqueName = time() . "_" . $originalName;

        $fileData = file_get_contents($fileTmp);
        $uploadUrl = "$supabase_url/storage/v1/object/$bucket/$uniqueName";

        $ch = curl_init($uploadUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $supabase_api_key,
            'Content-Type: application/octet-stream',
            'x-upsert: true'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 201) {
            // URL pública del archivo subido
            $publicUrl = "$supabase_url/storage/v1/object/public/$bucket/$uniqueName";

            // Insertar en la tabla `archivotar`
            $tareaModel->guardarArchivo($id_tarea, $publicUrl);

        } else {
            error_log("Error al subir archivo $originalName: $response");
        }
    }
}


//var_dump($destino);
header("Location: ../Views/teamT.php?id=$grupo_id");
