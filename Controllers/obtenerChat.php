<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('../Controllers/conexion.php');
session_start();

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['username']) || !isset($_SESSION['idusuario'])) {
        echo json_encode(['error' => 'Usuario no autenticado']);
        exit;
    }

    $idUsuario = $_SESSION['idusuario'];

    $stmt = $conexion->prepare("
        SELECT 
            cp.id_chat,
            u.id AS otro_usuario_id,
            u.usuario,
            u.Foto
        FROM chatprivado cp
        JOIN usuario u ON (u.id = CASE 
            WHEN cp.id_usuario1 = ? THEN cp.id_usuario2
            ELSE cp.id_usuario1
        END)
        WHERE cp.id_usuario1 = ? OR cp.id_usuario2 = ?
    ");
    if (!$stmt) {
        throw new Exception("Error al preparar statement: " . $conexion->error);
    }

    $stmt->bind_param("iii", $idUsuario, $idUsuario, $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    $chatsPrivados = [];
    while ($row = $result->fetch_assoc()) {
        $chatsPrivados[] = [
            'chat_id' => $row['id_chat'],
            'id' => $row['otro_usuario_id'],
            'nombre' => $row['usuario'],
            'foto' => $row['Foto']
        ];
    }

    echo json_encode($chatsPrivados);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}