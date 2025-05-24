<?php
include("conexion.php");
include("../Models/equipo.php");

class equipoController{
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function guardar($nombre,$descripcion,$codigo,$color,$usuarioCreador,$usuarios){
        $status=1;
        $stmt = $this->conn->prepare("INSERT INTO equipo (nombre, descripcion, codigo, color, estatus, id_usuario) VALUES (?, ?, ?, ?,?, ?)");
        $stmt->bind_param("ssssii", $nombre, $descripcion, $codigo, $color,$status, $usuarioCreador);
        $stmt->execute();
        $groupId = $stmt->insert_id;
        $stmt->close(); 
        var_dump($usuarios);

       foreach ($usuarios as $nombreUsuario) {
            $result = $this->conn->prepare("SELECT Id FROM usuario WHERE usuario = ?");
            if (!$result) {
                die("Error al preparar SELECT usuario: " . $this->conn->error);
            }

            $result->bind_param("s", $nombreUsuario);
            $result->execute();

            $userId = null;

            $result->bind_result($userId);
            var_dump($userId,$result);
            if ($result->fetch()) {
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

        header("Location: ../Views/index.php");

    }

}