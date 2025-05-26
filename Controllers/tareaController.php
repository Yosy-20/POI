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

    public  function obtenerPorId($id) {
        
        $stmt = $this->conn->prepare("SELECT * FROM tarea WHERE id = ?");
        $stmt->bind_param("i", $id);  // <-- AQUI ES CLAVE
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    
    public function obtenerPorTarea($idtarea) {
        
        $stmt = $this->conn->prepare("SELECT * FROM archivotar WHERE idtarea = ?");
        $stmt->bind_param("i", $idtarea);  // <-- AQUI ES CLAVE
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function guardar($ruta, $idusuario, $idtarea) {
        
        $stmt = $this->conn->prepare("INSERT INTO tareascompletadas (ruta, idusuario, idtarea) VALUES (?, ?, ?)");
        $stmt->bind_param("sii",$ruta, $idusuario, $idtarea);
        $stmt->execute();
        $stmt->close();
    }

    public function entregaExistente($idusuario, $idtarea) {
    $stmt = $this->conn->prepare("SELECT id FROM tareascompletadas WHERE idusuario = ? AND idtarea = ?");
    $stmt->bind_param("ii", $idusuario, $idtarea);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
    }

    public  function tareasPorUsuarioYGrupo($idusuario, $idequipo) {
    $stmt = $this->conn->prepare("SELECT t.id, t.titulo, t.fecha, t.hora, tc.ruta FROM tareascompletadas tc
        JOIN tarea t ON t.id = tc.idtarea
        WHERE tc.idusuario = ? AND t.idequipo = ?
        ORDER BY t.fecha DESC, t.hora DESC");
    $stmt->bind_param("ii", $idusuario, $idequipo);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

    public function tareasPendientes($idusuario, $idequipo) {
    $stmt = $this->conn->prepare("
        SELECT t.id, t.titulo, t.fecha, t.hora
        FROM tarea t
        WHERE t.idequipo = ?
          AND CONCAT(t.fecha, ' ', t.hora) > NOW()
          AND t.id NOT IN (
              SELECT idtarea FROM tareascompletadas WHERE idusuario = ?
          )
        ORDER BY t.fecha ASC, t.hora ASC
    ");
    $stmt->bind_param("ii", $idequipo, $idusuario);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

public function tareasVencidas($idusuario, $idequipo) {
    $stmt = $this->conn->prepare("
        SELECT t.id, t.titulo, t.fecha, t.hora
        FROM tarea t
        WHERE t.idequipo = ?
          AND CONCAT(t.fecha, ' ', t.hora) <= NOW()
          AND t.id NOT IN (
              SELECT idtarea FROM tareascompletadas WHERE idusuario = ?
          )
        ORDER BY t.fecha DESC, t.hora DESC
    ");
    $stmt->bind_param("ii", $idequipo, $idusuario);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

}

