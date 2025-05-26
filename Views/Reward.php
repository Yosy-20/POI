<?php
require('../Controllers/conexion.php');
include("../Controllers/recompensaController.php");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
  session_regenerate_id(true); // Protege contra fijación de sesión
}

$idUsuario = $_SESSION['idusuario'];
$reconController = new RecompensaController($conexion);
$recompensas = $reconController->recompensasPorUsuario($idUsuario);

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="css/stylereward.css">
</head>
<body>
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
                        <li><a class="dropdown-item" href="#">Configuración</a></li>
                        <li><a class="dropdown-item" href="#">Actividad</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-users"></i> Equipos</a></li>
            <li class="nav-item"><a class="nav-link" href="task.html"><i class="fas fa-tasks"></i> Tareas</a></li>
            <li class="nav-item"><a class="nav-link" href="calendar.html"><i class="fas fa-calendar"></i> Calendario</a></li>
            <li class="nav-item"><a class="nav-link" href="Reward.php"><i class="fas fa-gift"></i> Recompensas</a></li>
        </ul>
        <div class="sidebar-footer">Sesión Iniciada: <br> <strong><i class="fas fa-user fa-fw"></i> Usuario</strong>  <a href="Perfil.php"> <i class="fas fa-gear" ></i></a></div>
    </div>
    <div class="content">
        <nav class="sub-navbar">
            <h1><i class="fas fa-gift"></i> Recompensas</h1>
        </nav>
        <h5 id="dis">Disponibles</h5>

        <div class="disponible">
           
            <?php foreach ($recompensas['disponibles'] as $r): ?>
            <div class="reward">
            <img src="data:image/png;base64,<?= base64_encode($r['archivo']) ?>" alt="Recompensa">
            <p><?= nombreNivel($r['nivel']) ?></p>
        
            <?php if ($r['desbloqueada']): ?>
                <form action="../Controllers/obtrecompensa.php" method="post">
                <input type="hidden" name="idusuario" value="<?= $idUsuario ?>">
                <input type="hidden" name="idrecompensa" value="<?= $r['id'] ?>">
                <button type="submit" class="btn">Obtener</button>
                </form>
            <?php else: ?>
                <small style="color: gray;">No desbloqueada</small>
            <?php endif; ?>
            </div>
            <?php endforeach; ?>
          
        </div>
        <div class="division"></div>
        <h5>Reclamados</h5>
        <div class="reclamar">
            
            <?php foreach ($recompensas['obtenidas'] as $r): ?>
            <div class="reward">
            <img src="data:image/png;base64,<?= base64_encode($r['archivo']) ?>" alt="Recompensa">
            <p><?= nombreNivel($r['nivel']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>