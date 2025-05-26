<?php
require('../Controllers/conexion.php');
include("../Controllers/equipoController.php");
// Verificar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
  session_start();
  session_regenerate_id(true); // Protege contra fijación de sesión
}

// Validar si el usuario está autenticado
if (empty($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

if (!isset($_GET['id'])) {
    die("No se especificó el equipo.");
}

$idEquipo = intval($_GET['id']);
$equipoController = new equipoController($conexion);
$equipo = $equipoController->obtenerEquipoPorId($idEquipo);

if (!$equipo) {
    die("Equipo no encontrado.");
}

function obtenerIniciales($nombre) {
    $palabras = explode(' ', $nombre);
    $iniciales = '';
    foreach ($palabras as $palabra) {
        $iniciales .= strtoupper($palabra[0]);
    }
    return $iniciales;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Workly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
    <link rel="stylesheet" href="css/styleTeamV.css" />
    <style>
        /* Ajustes para que el iframe ocupe todo el contenedor */
        #jitsi-container {
            height: 500px;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
        }
        .controls i {
            cursor: pointer;
            font-size: 1.4rem;
            margin-right: 15px;
        }
        .controls i.end-call {
            color: red;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Workly</a>
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="userDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="#">Configuración</a></li>
                    <li><a class="dropdown-item" href="#">Actividad</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="../Controllers/cerrarSesion.php">Cerrar Sesión</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="index.php"><i class="fas fa-users"></i> Equipos</a>
        </li>
         <li class="nav-item"><a class="nav-link" href="chat.php"><i class="fas fa-comments"></i> Chats</a></li>
        <li class="nav-item">
            <a class="nav-link" href="task.html"><i class="fas fa-tasks"></i> Tareas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="calendar.html"><i class="fas fa-calendar"></i> Calendario</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="Reward.html"><i class="fas fa-gift"></i> Recompensas</a>
        </li>
    </ul>
    <div class="sidebar-footer">
        Sesión Iniciada: <br />
        <strong><i class="fas fa-user fa-fw"></i><?= $_SESSION['username'] ?></strong>
        <a href="Perfil.html"> <i class="fas fa-gear"></i></a>
    </div>
</div>
<div class="content">
    <div class="Chats">
        <!-- Sub-sidebar: Lista de Chats -->
        <div class="sub-sidebar">
            <div class="profile">
                <div class="profile-icon" style="background-color: <?= htmlspecialchars($equipo['color']) ?>">
                    <?= obtenerIniciales($equipo['nombre']) ?>
                </div>
                <span class="team-name"><?= htmlspecialchars($equipo['nombre']) ?></span>
                <i class="fas fa-ellipsis-v more-options"></i>
            </div>
            <ul class="menu">
                <a href="teamT.php?id=<?= $equipo['id'] ?>"><li><i class="fas fa-tasks"></i> Tareas</li></a>
            </ul>
            <hr />
            <div class="channels">
                <span><i class="fa-solid fa-thumbtack"></i> Canales de texto</span>
                <ul>
                    <a href="team.php?id=<?= $equipo['id'] ?>"><li><i class="fas fa-comments"></i> General</li></a>
                </ul>

                <span><i class="fa-solid fa-thumbtack"></i> Canales de voz</span>
                <ul>
                    <a href="teamV.php?id=<?= $equipo['id'] ?>"><li> <i class="fa-solid fa-bullhorn"></i> General</li></a>
                </ul>
            </div>
        </div>
    </div>
    <div class="Llamada">

        <div class="call-container">
            <div id="jitsi-container"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://meet.jit.si/external_api.js"></script>
<script>
    const domain = "meet.jit.si";
   const options = {
    roomName: "WorklyTeam_<?= $equipo['id'] ?>",
    width: "100%",
    height: 500,
    parentNode: document.querySelector("#jitsi-container"),
    userInfo: {
        displayName: "<?= $_SESSION['username'] ?>"
    },
    interfaceConfigOverwrite: {
        TOOLBAR_BUTTONS: [
            "microphone",
            "camera",
            "chat",
            "desktop",
            "fullscreen",
            "hangup",
            "settings",
            "raisehand",
            "videoquality",
            "tileview",
        ],
    },
};

    const api = new JitsiMeetExternalAPI(domain, options);

    // Botón para colgar llamada (usa el icono con clase .end-call)
    document.querySelector(".end-call").addEventListener("click", () => {
        api.executeCommand("hangup");
        window.location.href = 'team.php?id=<?= $equipo['id'] ?>';
    });

    // Evento cuando se está por cerrar la reunión
    api.addEventListener("readyToClose", () => {
        alert("La reunión ha finalizado");
        window.location.href = 'team.php?id=<?= $equipo['id'] ?>';
    });
</script>
</body>
</html>
