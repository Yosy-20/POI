<?php  
include("../Controllers/tareaController.php");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
  session_regenerate_id(true); // Protege contra fijación de sesión
}

$tareacontrol = new TareaController($conexion);

$idUsuario = $_SESSION['idusuario'];
$idtarea = $_GET['idt'] ?? null;

$tareas = $tareacontrol->obtenerPorId($idtarea);
$archivoBase = $tareacontrol->obtenerPorTarea($idtarea);

$entregaHecha = $tareacontrol->entregaExistente($idUsuario, $idtarea);
//var_dump($idtarea);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="css/styletarea.css">
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
            <li class="nav-item"><a class="nav-link" href="chat.php"><i class="fas fa-comments"></i> Chats</a></li>
            <li class="nav-item"><a class="nav-link" href="calendar.html"><i class="fas fa-calendar"></i> Calendario</a></li>
            <li class="nav-item"><a class="nav-link" href="Reward.html"><i class="fas fa-gift"></i> Recompensas</a></li>
        </ul>
        <div class="sidebar-footer">Sesión Iniciada: <br> <strong> <i class="fas fa-user fa-fw"></i> <?=$_SESSION['username']  ?></strong>  <a href="Perfil.php"> <i class="fas fa-gear" ></i></a></div>
    </div>
    <div class="contenido">
        <nav class="sub-navbar">
            <a href="task.html"><h5>Volver</h5></a>
        </nav>
        <div class="info">
        <div class="conten">
            <h1><?php echo $tareas['titulo'] ?></h1>
            <p class="fe">Vence el <?php echo $tareas['fecha']?>, <?php echo $tareas['hora'] ?> </p>
            <p class="peque" id="i">Intrucciones </p>
            <p><?php echo $tareas['descripcion'] ?></p>

            <?php if ($archivoBase): ?>
            <p><a href="uploads/<?php echo $archivoBase['ruta'] ?>" target="_blank">Archivo base</a></p>
            <?php endif; ?>

            <p class="peque" id="t">Mi trabajo</p>

            <?php
            $expirada = (new DateTime($tareas['fecha'] . ' ' . $tareas['hora'])) < new DateTime();
            if (!$expirada  && !$entregaHecha): ?>
                <form action="../Controllers/comtarea.php?idt=<?php echo $tareas['id'] ?>" method="post" enctype="multipart/form-data">
                    <label for="archivo" class="adj"><i class="fa fa-paperclip"></i></i>  Adjuntar</label>
                    <input type="file" name="archivo" id="archivo" required>
                    <p id="nombrea" class="arch"></p>

                    <button type="submit">Entregar</button>
                </form>
            <?php elseif ($entregaHecha): ?>
                <p style="color: green;">Ya has enviado tu archivo. ¡Gracias!</p>
            <?php else: ?>
                <p style="color: red;">La tarea ha expirado.</p>
            S<?php endif; ?>

        </div>
        
        </div>

    </div>

    <script src="js/scriptsta.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>