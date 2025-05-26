<?php
include("conexion.php");
include("../Models/equipo.php");
include("../Models/chat.php");

class equipoController{
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }
public function guardar($nombre, $descripcion, $codigo, $color, $usuarioCreador, $usuarios) {
    $status = 1;
    $stmt = $this->conn->prepare("INSERT INTO equipo (nombre, descripcion, codigo, color, estatus, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $nombre, $descripcion, $codigo, $color, $status, $usuarioCreador);
    $stmt->execute();
    $groupId = $stmt->insert_id;
    $stmt->close();

    $integranteIds = [];

    foreach ($usuarios as $nombreUsuario) {
        $result = $this->conn->prepare("SELECT Id FROM usuario WHERE usuario = ?");
        if (!$result) {
            die("Error al preparar SELECT usuario: " . $this->conn->error);
        }

        $result->bind_param("s", $nombreUsuario);
        $result->execute();

        $userId = null;
        $result->bind_result($userId);

        if ($result->fetch()) {
            $integranteIds[] = $userId;  // Guardamos el ID para después
            $result->close();

            $estatusUsuario = 1;
            $insert = $this->conn->prepare("INSERT INTO usuarioequipo (idequipo, idusuario, estatus) VALUES (?, ?, ?)");
            if (!$insert) {
                die("Error al preparar INSERT equipo_usuario: " . $this->conn->error);
            }

            $insert->bind_param("iii", $groupId, $userId, $estatusUsuario);
            $insert->execute();
            $insert->close();
        } else {
            $result->close();
        }
    }

    // Crear el chat relacionado al equipo
    $fechaActual = date('Y-m-d H:i:s');
    $stmtChat = $this->conn->prepare("INSERT INTO chat (idequipo, fecha) VALUES (?, ?)");
    if (!$stmtChat) {
        die("Error al preparar INSERT chat: " . $this->conn->error);
    }
    $stmtChat->bind_param("is", $groupId, $fechaActual);
    $stmtChat->execute();
    $chatId = $stmtChat->insert_id;
    $stmtChat->close();

    // Insertar a cada integrante en chatusuario
    foreach ($integranteIds as $userId) {
        $insertChatUser = $this->conn->prepare("INSERT INTO chatusuario (idChat, idUsuario) VALUES (?, ?)");
        if (!$insertChatUser) {
            die("Error al preparar INSERT chatusuario: " . $this->conn->error);
        }

        $insertChatUser->bind_param("ii", $chatId, $userId);
        $insertChatUser->execute();
        $insertChatUser->close();
    }

    header("Location: ../Views/index.php");
}


     public function mostrarEquipos() {
        $equipos = [];

        $sql = "SELECT id, nombre, descripcion, codigo, color FROM equipo WHERE estatus = 1";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $equipos[] = $row;
            }
        }

        return $equipos; // Devuelve un array de equipos
    }

    public function mostrarEquiposPorUsuario($idUsuario) {
    $equipos = [];

    $sql = "
        SELECT DISTINCT e.id, e.nombre, e.descripcion, e.codigo, e.color 
        FROM equipo e
        LEFT JOIN usuarioequipo ue ON e.id = ue.idequipo
        WHERE e.estatus = 1
          AND (e.id_usuario = ? OR ue.idusuario = ?)
    ";

    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación del query: " . $this->conn->error);
    }

    $stmt->bind_param("ii", $idUsuario, $idUsuario);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $equipos[] = $row;
        }
    }

    $stmt->close();

    return $equipos;
}
public function obtenerEquipoPorId($idEquipo) {
    $sql = "SELECT id, nombre, descripcion, codigo, color FROM equipo WHERE id = ? AND estatus = 1";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        die("Error al preparar query: " . $this->conn->error);
    }

    $stmt->bind_param("i", $idEquipo);
    $stmt->execute();

    $result = $stmt->get_result();
    $equipo = $result->fetch_assoc();

    $stmt->close();

    return $equipo;
}

public function obtenerIdChatPorEquipo($idEquipo) {
    $idChat = null;  

    $stmt = $this->conn->prepare("SELECT id FROM chat WHERE idequipo = ?");
    $stmt->bind_param("i", $idEquipo);
    $stmt->execute();
    $stmt->bind_result($idChat);
    if ($stmt->fetch()) {
        $stmt->close();
        return $idChat;
    } else {
        $stmt->close();
        return null;
    }
}

}