<?php
include("conexion.php");
include("../Models/recompensa.php");

class RecompensaController{
   private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function obtenerRecompensas($idusuario) {
     $stmt = $this->conn->prepare("
        SELECT r.id, r.archivo, r.nivel 
        FROM recompensas_usuario ru
        JOIN recompensa r ON ru.idrecompensa = r.id
        WHERE ru.idusuario = ?
        ORDER BY r.nivel ASC
    ");
    $stmt->bind_param("i", $idusuario);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function recompensasPorUsuario($idusuario) {
    // Obtener cantidad de tareas completadas por usuario
    $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM tareascompletadas WHERE idusuario = ?");
    $stmt->bind_param("i", $idusuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $tareasCompletadas = $result->fetch_assoc()['total'];

    // Obtener todas las recompensas
    $recompensas = $this->conn->query("SELECT * FROM recompensa")->fetch_all(MYSQLI_ASSOC);

    // Obtener recompensas ya obtenidas por usuario
    $stmt = $this->conn->prepare("SELECT idrecompensa FROM recompensas_usuario WHERE idusuario = ?");
    $stmt->bind_param("i", $idusuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $obtenidas = array_column($resultado->fetch_all(MYSQLI_ASSOC), 'idrecompensa');

    $disponibles = [];
    $reclamadas = [];

    foreach ($recompensas as $r) {
        if (in_array($r['id'], $obtenidas)) {
            $reclamadas[] = $r;
        } else {
            $r['desbloqueada'] = $this->nivelDesbloqueado($r['nivel'], $tareasCompletadas);
            $disponibles[] = $r;
        }
    }

    return ['disponibles' => $disponibles, 'obtenidas' => $reclamadas];
}

private function nivelDesbloqueado($nivel, $tareas) {
    switch ($nivel) {
        case 1: return $tareas >= 1;
        case 2: return $tareas >= 3;
        case 3: return $tareas >= 5;
        case 4: return $tareas >= 7;
        case 5: return $tareas >= 10;
        case 6: return $tareas >= 15;
        default: return false;
    }
}

public function obtenerRecompensa($idusuario,$idrecompensa) {


    // Validar que no la tenga
    $stmt = $this->conn->prepare("SELECT * FROM recompensas_usuario WHERE idusuario = ? AND idrecompensa = ?");
    $stmt->bind_param("ii", $idusuario, $idrecompensa);
    $stmt->execute();
    $existe = $stmt->get_result()->num_rows;

    if ($existe == 0) {
        $insert = $this->conn->prepare("INSERT INTO recompensas_usuario (idusuario, idrecompensa) VALUES (?, ?)");
        $insert->bind_param("ii", $idusuario, $idrecompensa);
        $insert->execute();
    }

    header("Location: ../Views/Reward.php");
}


}