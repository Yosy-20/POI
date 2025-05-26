<?php
require('../Controllers/conexion.php');
include("../Controllers/equipoController.php");
include("../Controllers/tareaController.php");
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

function obtenerIniciales($nombre)
{
    $palabras = explode(' ', $nombre);
    $iniciales = '';
    foreach ($palabras as $palabra) {
        $iniciales .= strtoupper($palabra[0]);
    }
    return $iniciales;
}

$tareacontrol = new TareaController($conexion);

$idUsuario = $_SESSION['idusuario'];
$idtarea = $_GET['idt'] ?? null;

$tareas = $tareacontrol->obtenerPorId($idtarea);
$archivoBase = $tareacontrol->obtenerPorTarea($idtarea);

$entrega = $tareacontrol->entregaExistente($idUsuario, $idtarea);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="css/styletareaT.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Workly</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">Configuración</a></li>
                        <li><a class="dropdown-item" href="#">Actividad</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="../Controllers/cerrarSesion.php">Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-users"></i> Equipos</a></li>
            <li class="nav-item"><a class="nav-link" href="task.html"><i class="fas fa-tasks"></i> Tareas</a></li>
            <li class="nav-item"><a class="nav-link" href="calendar.html"><i class="fas fa-calendar"></i> Calendario</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="Reward.php"><i class="fas fa-gift"></i> Recompensas</a></li>
        </ul>
        <div class="sidebar-footer">Sesión Iniciada: <br> <strong><i class="fas fa-user fa-fw"></i>
                <?= $_SESSION['username'] ?></strong> <a href="Perfil.html"> <i class="fas fa-gear"></i></a></div>
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
                    <a href="teamT.php?id=<?= $equipo['id'] ?>">
                        <li><i class="fas fa-tasks"></i> Tareas</li>
                    </a>
                </ul>
                <hr>
                <div class="channels">
                    <span><i class="fa-solid fa-thumbtack"></i> Canales de texto</span>
                    <ul>
                        <a href="team.php?id=<?= $equipo['id'] ?>">
                            <li><i class="fas fa-comments"></i> General</li>
                        </a>
                    </ul>

                    <span><i class="fa-solid fa-thumbtack"></i> Canales de voz</span>
                    <ul>
                        <a href="teamV.php?id=<?= $equipo['id'] ?>">
                            <li> <i class="fa-solid fa-bullhorn"></i> General</li>
                        </a>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tarea">
            <nav class="sub-navbar">
                <a href="../Views/teamT.php?id=<?= $equipo['id'] ?>">
                    <h5>Volver</h5>
                </a>
            </nav>
            <div class="info">
                <div class="conten">
                    <h1><?php echo $tareas['titulo'] ?></h1>
                    <p class="fe">Vence el <?php echo $tareas['fecha'] ?>, <?php echo $tareas['hora'] ?> </p>
                    <p class="peque" id="i">Intrucciones </p>
                    <p><?php echo $tareas['descripcion'] ?></p>

                    <?php if ($archivoBase): ?>

                        <strong>Archivos adjuntos:</strong>
                        <ul>
                            <?php foreach ($archivoBase as $archivo) { ?>
                                <li><a href="<?= htmlspecialchars($archivo['ruta']) ?>" target="_blank"><?= basename($archivo['ruta']) ?></a></li>
                                <?php } ?>
                        </ul>
                    <?php endif; ?>

                     

                    <p class="peque" id="t">Mi trabajo</p>

                    <?php
                    $expirada = (new DateTime($tareas['fecha'] . ' ' . $tareas['hora'])) < new DateTime();
                    if (!$expirada && !$entrega): ?>
                        <form id="uploadForm">
                            <label for="archivo" class="adj">
                                <i class="fa fa-paperclip"></i> Adjuntar
                            </label>
                            <input type="file" name="archivo" id="archivo" required>
                            <p id="nombrea" class="arch"></p>
                            <img id="preview" src="" style="max-width: 200px; display:none;">
                            <button type="submit">Entregar</button>
                        </form>
                    <?php elseif ($entrega): ?>
                        <p style="color: green;">Ya has enviado tu archivo.</p>
                        <p><strong>Archivo entregado:</strong>
                            <a href="<?= htmlspecialchars($entrega['ruta']) ?>" target="_blank">
                                <?= basename($entrega['ruta']) ?>
                            </a>
                        </p>
                    <?php else: ?>
                        <p style="color: red;">La tarea ha expirado.</p>
                    <?php endif; ?>


                </div>

            </div>
        </div>

    </div>


    <script type="module">
        import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2/+esm';
        const supabase = createClient(
            'https://gdvrvcdnqvjitelbpcyb.supabase.co', // 🔁 reemplaza con tu URL
            'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImdkdnJ2Y2RucXZqaXRlbGJwY3liIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDgyOTA1MDIsImV4cCI6MjA2Mzg2NjUwMn0.r5ybnwC4gyEpYwhAHat1tZ63MK-S2QeegCLIwxbJSGI'                    // 🔁 reemplaza con tu key pública
        );


        document.getElementById('archivo').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('nombrea').textContent = file.name;

                // Mostrar previsualización si es imagen
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.getElementById('preview');
                        img.src = e.target.result;
                        img.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        document.getElementById('uploadForm').addEventListener('submit', async function (event) {
            event.preventDefault();

            const file = document.getElementById('archivo').files[0];
            if (!file) return;

            const filePath = `${Date.now()}_${file.name}`; // ruta única

            const { data, error } = await supabase.storage
                .from('entregas')
                .upload(filePath, file);

            if (error) {
                alert("Error al subir archivo: " + error.message);
            } else {
                alert("Archivo subido correctamente");

                // (Opcional) Guardar la URL en tu base de datos vía fetch a PHP
                const publicUrl = supabase.storage.from('entregas').getPublicUrl(filePath).data.publicUrl;

                fetch('../Controllers/comtarea.php?id=<?= $equipo['id'] ?>&idt=<?= $tareas['id'] ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ url: publicUrl, nombre: file.name })
                }).then(r => r.text()).then(console.log);
            }
        });
    </script>
</body>

</html>