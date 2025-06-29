<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="css/styleTeamVL.css">
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
            <li class="nav-item"><a class="nav-link" href="index.html"><i class="fas fa-users"></i> Equipos</a></li>
            <li class="nav-item"><a class="nav-link" href="chat.php"><i class="fas fa-comments"></i> Chats</a></li>
            <li class="nav-item"><a class="nav-link" href="calendar.html"><i class="fas fa-calendar"></i> Calendario</a></li>
            <li class="nav-item"><a class="nav-link" href="Reward.php"><i class="fas fa-gift"></i> Recompensas</a></li>
        </ul>
        <div class="sidebar-footer">Sesión Iniciada: <br> <strong><i class="fas fa-user fa-fw"></i> Usuario</strong>  <a href="Perfil.php"> <i class="fas fa-gear" ></i></a></div>
    </div>
    <div class="content">
        <div class="Chats">
            <!-- Sub-sidebar: Lista de Chats -->
            <div class="sub-sidebar">
                <div class="profile">
                    <div class="profile-icon">E1</div>
                    <span class="team-name">Equipo1</span>
                    <i class="fas fa-ellipsis-v more-options"></i>
                </div>
                
                <ul class="menu">
                    <li><i class="fas fa-tasks"></i> Tareas</li>
                </ul>
                <hr>
                <div class="channels">
                    <span><i class="fa-solid fa-thumbtack"></i>  Canales de texto</span>
                    <ul>
                        <li><i class="fas fa-comments"></i> General</li>
                    </ul>
                    
                    <span><i class="fa-solid fa-thumbtack"></i>  Canales de voz</span>
                    <ul>
                        <li> <i class="fa-solid fa-bullhorn"></i> General</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="Llamada">
            <div class="header">📞 Nueva Reunión <span>00:41</span></div>

            <div class="call-container">
                <div class="remote-video">👤 Video del otro usuario</div>
                <div class="local-video">JV</div>
            </div>
        
            <div class="controls">
                <i class="fas fa-video"></i>
                <i class="fas fa-microphone-slash"></i>
                <i class="fas fa-comment-alt"></i>
                <i class="fas fa-ellipsis-h"></i>
                <i class="fas fa-phone end-call"></i>
            </div>
        
            <div class="top-icons">
                <i class="fas fa-expand"></i>
                <i class="fas fa-cog"></i>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>