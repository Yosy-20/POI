
// Consulta SQL para obtener los datos 

<?php

include("conexion.php");

function obtenerPerfilUsuario($conexion, $id_usuario) {
    // Consulta para obtener los datos del usuario por ID
    $sql = "SELECT nombre, usuario, apellidoP, apellidoM, correo, descripcion, foto FROM usuario WHERE Id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se encontró el usuario
    if ($resultado->num_rows > 0) {
        // Retornar los datos del usuario como un array asociativo
        return $resultado->fetch_assoc();
    } else {
        return null; // No se encontró el usuario
    }
}
?>