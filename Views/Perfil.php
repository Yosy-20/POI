<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['idusuario'])) {
    // Redirigir al inicio de sesión si no está autenticado
    header("Location: login.php");
    exit();
}

// Incluir el archivo de funciones que contiene la función obtenerPerfilUsuario
require_once '../Controllers/llamarInfoPerfil.php';
require('../Controllers/conexion.php');
include("../Controllers/recompensaController.php");


// Obtener el ID del usuario desde la sesión
$id_usuario = $_SESSION['idusuario'];

// Llamar a la función para obtener los datos del usuario
$usuario = obtenerPerfilUsuario($conexion, $id_usuario);
$reconController = new RecompensaController($conexion);
$recompensas = $reconController->obtenerRecompensas($id_usuario);

function nombreNivel($nivel) {
    switch ($nivel) {
        case 1: return "buen comienzo";
        case 2: return "sigue asi";
        case 3: return "poquito mas";
        case 4: return "rey de tareas";
        case 5: return "GRANDE";
        case 6: return "master de tareas";
        default: return "DESCONOCIDO";
    }
}


if ($usuario) {

    // Mostrar los datos del usuario en el perfil
?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil de Usuario</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
        <link rel="stylesheet" href="css/styleInicio.css">
    </head>
    <body>
        <!-- Barra de navegación principal -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Plataforma</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user fa-fw"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="configuracion.php">Configuración</a></li>
                            <li><a class="dropdown-item" href="actividad.php">Actividad</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Barra lateral de navegación -->
        <div class="sidebar">
            <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-users"></i> Equipos</a></li>
            <li class="nav-item"><a class="nav-link" href="chat.php"><i class="fas fa-comments"></i> Chats</a></li>
            <li class="nav-item"><a class="nav-link" href="task.html"><i class="fas fa-tasks"></i> Tareas</a></li>
            <li class="nav-item"><a class="nav-link" href="calendar.html"><i class="fas fa-calendar"></i> Calendario</a></li>
            <li class="nav-item"><a class="nav-link" href="Reward.php"><i class="fas fa-gift"></i> Recompensas</a></li>
        </ul>
            <div class="sidebar-footer">
                <strong><i class="fas fa-user fa-fw"></i> <?php echo htmlspecialchars($usuario['usuario']); ?></strong>
                <a href="perfil.php"><i class="fas fa-gear"></i></a>
            </div>
        </div>
         <div class="cont">
            <section class="seccion-perfil-usuario">
           
            <div class="perfil-usuario-header">
                <?php 
                            $idmarco = $usuario['idmarco'];
                            $marco = null;

                            if ($idmarco) {
                            $stmt = $conexion->prepare("SELECT archivo FROM recompensa WHERE id = ?");
                                $stmt->bind_param("i", $idmarco);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                $marco = $res->fetch_assoc();
                            }
                            ?>
                <div class="perfil-usuario-portada">
                    <div class="perfil-usuario-avatar">
                        <!-- Imagen de perfil -->
                         
                        <div class="marco-recompensa">
                        <?php if ($marco): ?>
                        <img src="data:image/png;base64,<?= base64_encode($marco['archivo']) ?>" class="marco-img" id="imgr">
                        <?php endif; ?>
                        <img src="http://localhost/POI/<?= htmlspecialchars($usuario['Foto']) ?>" alt="Avatar de Usuario" class="avatar-principal">
                        </div>
                        <button type="button" class="boton-avatar">
                            <i class="far fa-image"></i>
                        </button>
                    </div>
                    <button type="button" class="boton-portada">
                        <i class="far fa-image"></i> Cambiar fondo
                    </button>
                </div>
            </div>
            <div class="perfil-usuario-body">
                <div class="perfil-usuario-bio">
                    <!-- Información personal del usuario -->
                    <h3 class="titulo"><?php echo htmlspecialchars($usuario['nombre']); ?></h3>
                    <p class="texto"><?php echo nl2br(htmlspecialchars($usuario['descripcion'])); ?></p>
                </div>
                <div class="perfil-usuario-footer">
                    <ul class="lista-datos">
                        <li><i class="icono fas fa-map-signs"></i> Nombre: <?php echo htmlspecialchars($usuario['nombre']); ?></li>
                        <li><i class="icono fas fa-phone-alt"></i> Apellido Paterno: <?php echo htmlspecialchars($usuario['apellidoP']); ?></li>
                        <li><i class="icono fas fa-briefcase"></i> Apellido Materno: <?php echo htmlspecialchars($usuario['apellidoM']); ?></li>
                        <li><i class="icono fas fa-building"></i> Correo electrónico: <?php echo htmlspecialchars($usuario['correo']); ?></li>
                        <li><i class="icono fas fa-user-tag"></i> Nivel de usuario: Usuario</li>
                    </ul>
                </div>
                <div class="Recompensas">
                            <?php foreach ($recompensas as $r): ?>
                                <div class="reward">
                                <img src="data:image/png;base64,<?= base64_encode($r['archivo']) ?>" alt="Recompensa">
                                <p><?= nombreNivel($r['nivel']) ?></p>
                                </div>
                            <?php endforeach; ?>
                    
                </div>
            </div>
            
        </section></div>
        <!-- Sección del perfil del usuario -->
        

        <!-- Scripts necesarios para el funcionamiento de Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
} else {
    echo '<p>No se encontraron datos para este usuario.</p>';
}
?>
