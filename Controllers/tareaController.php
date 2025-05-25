<?php
include("conexion.php");
include("../Models/tarea.php");

class TareaController{
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function guardarTarea($titulo, $descripcion, $fecha, $hora, $grupo_id, $usuario_id) {
        $stmt = $this->conn->prepare("INSERT INTO tarea (titulo, descripcion, fecha, hora, idequipo, idusuario) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $titulo, $descripcion, $fecha, $hora, $grupo_id, $usuario_id);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function guardarArchivo($tarea_id, $ruta) {
        $stmt = $this->conn->prepare("INSERT INTO archivotar (idtarea, ruta) VALUES (?, ?)");
        $stmt->bind_param("is", $tarea_id, $ruta);
        $stmt->execute();
        $stmt->close();
    }

}

