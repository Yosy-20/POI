<?php
include("conexion.php");
include("../Models/usuario.php");

class UsuarioController {

    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    // Función para registrar usuario
   public function Registro($usuario, $correo, $contrasena, $foto) {
    // Primero: validar que el correo no exista
    $stmt = $this->conn->prepare("SELECT id FROM usuario WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        return "Error: El correo ya está registrado.";
    }

    // Segundo: validar que el usuario no exista
    $stmt = $this->conn->prepare("SELECT id FROM usuario WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        return "Error: El nombre de usuario ya está registrado.";
    }


    $stmt = $this->conn->prepare("INSERT INTO usuario (usuario, correo, contraseña, Foto) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $usuario, $correo, $contrasena, $foto);

    if ($stmt->execute()) {
        header("Location:../Views/login.php");
        return "Registro exitoso";
        
    } else {
        return "Error en registro: " . $stmt->error;
    }
}

    // Función para inicio de sesión
    public function Login($usuario, $contrasena) {
    $stmt = $this->conn->prepare("SELECT id, nombre, usuario, apellidoP, apellidoM, correo, contraseña, descripcion, Foto FROM usuario WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if ($contrasena === $row['contraseña']) {
            // Crear objeto usuario
            $usuarioObj = new Usuario(
                $row['usuario'],
                $row['correo'],
                $row['contraseña']
            );
            $usuarioObj->UsuarioInfo(
                $row['id'],
                $row['nombre'],
                $row['apellidoP'],
                $row['apellidoM'],
                $row['foto']
            );

            // Guardar sesión
            session_start();
            $_SESSION['username'] = $row['usuario'];
            $_SESSION['correo'] = $row['correo'];
            $_SESSION['idusuario'] = $row['id'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['apellidoP'] = $row['apellidoP'];
            $_SESSION['apellidoM'] = $row['apellidoM'];
            $_SESSION['foto'] = $row['foto'];

            // Validar si faltan datos personales
            if (empty($row['nombre']) || empty($row['apellidoP']) || empty($row['apellidoM'])) {
                // Redirigir a completar perfil
                header("Location: ../Views/completarPerfil.php");
                exit();
            } else {
                // Redirigir a la vista principal
                header("Location: ../Views/index.php");
                exit();
            }
        } else {
            return "Contraseña incorrecta";
        }
    } else {
        return "Usuario no encontrado";
    }
}

public function CompletarInformacion($username, $nombre, $apellidoP, $apellidoM, $descripcion) {
    $stmt = $this->conn->prepare("UPDATE usuario SET nombre = ?, apellidoP = ?, apellidoM = ?, descripcion = ? WHERE usuario = ?");
    $stmt->bind_param("sssss", $nombre, $apellidoP, $apellidoM, $descripcion, $username);

    if ($stmt->execute()) {
        return "Información actualizada correctamente.";
    } else {
        return "Error al actualizar información: " . $stmt->error;
    }
}

public function logout() {
            session_unset();
            session_destroy();
             header("Location: ../Views/index.php");
        }
       
}
?>
