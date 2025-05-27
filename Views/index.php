<?php
require('../Controllers/conexion.php');

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
?>
 <?php
// Función para obtener iniciales del nombre
function obtenerIniciales($nombre) {
    $palabras = explode(' ', $nombre);
    $iniciales = '';
    foreach ($palabras as $p) {
        $iniciales .= mb_strtoupper(mb_substr($p, 0, 1));
    }
    return $iniciales;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Workly</a>
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
                        <li><a class="dropdown-item" href="../Controllers/cerrarSesion.php">Cerrar Sesión</a></li>
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
            <li class="nav-item"><a class="nav-link" href="Reward.php"><i class="fas fa-gift"></i> Recompensas</a></li>
        </ul>
        <div class="sidebar-footer">Sesión Iniciada: <br> <strong> <i class="fas fa-user fa-fw"></i> <?=$_SESSION['username']  ?></strong>  <a href="Perfil.php"> <i class="fas fa-gear" ></i></a></div>
    </div>
    <div class="content">
        <nav class="sub-navbar">
            <h3>Equipos</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearEquipoModal">
                <i class="fas fa-plus"></i> Crear Grupo
              </button>
              
        </nav>
        <?php
include("../Controllers/equipoController.php");

$equipoController = new equipoController($conexion);
$idUsuario = $_SESSION['idusuario'];

$equipos = $equipoController->mostrarEquiposPorUsuario($idUsuario);
?>

<div class="row g-3">
    <?php if (!empty($equipos)): ?>
        <?php foreach ($equipos as $equipo): ?>
            <div class="col-md-4">
                <div class="custom-card" style="border-left: 5px solid <?= htmlspecialchars($equipo['color']) ?>; " >


<div class="card-icon" style="background-color: <?= htmlspecialchars($equipo['color']) ?>; ">
    <?= obtenerIniciales($equipo['nombre']) ?>
</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($equipo['nombre']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($equipo['descripcion']) ?></p>
                    </div>
                    <div class="card-actions">
                        <i class="fa-solid fa-comments"></i>
                        <i class="fa-solid fa-tasks"></i>
                        <a class="nav-link" href="team.php?id=<?= $equipo['id'] ?>"> <i class="fa-solid fa-pen"></i></a> 
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color: white;">No hay equipos registrados.</p>
    <?php endif; ?>
</div>
    </div>
    
<!-- Modal para crear equipo -->
<div class="modal fade" id="crearEquipoModal" tabindex="-1" aria-labelledby="crearEquipoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="crearEquipoModalLabel"><i class="fas fa-users"></i> Crear Nuevo Equipo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="crearEquipoForm" action="../Controllers/registroEquipo.php?" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="nombreEquipo" class="form-label">Nombre del Equipo</label>
              <input type="text" class="form-control" id="nombreEquipo" name="nombreEquipo" placeholder="Ej. Desarrollo Web" required>
            </div>
            <div class="mb-3">
              <label for="descripcionEquipo" class="form-label">Descripción</label>
              <textarea class="form-control" id="descripcionEquipo" name="descripcionEquipo" rows="3" placeholder="Breve descripción del equipo"></textarea>
            </div>
            <div class="mb-3">
              <label for="codigoEquipo" class="form-label">Código de Identificación</label>
              <input type="text" class="form-control" id="codigoEquipo" name="codigoEquipo" placeholder="Ej. 053 A2024" required>
            </div>
            <div class="mb-3">
              <label for="colorEquipo" class="form-label">Color del Equipo</label>
              <input type="color" class="form-control form-control-color" id="colorEquipo" name="colorEquipo" value="#17a2b8">
            </div>
            <div class="mb-3">
              <label for="usuariosEquipo" class="form-label">Agregar Usuarios</label>
              <input type="text" class="form-control" id="usuarioInput" name="usuarioInput" placeholder="Escribe un nombre y presiona Enter">
              <div id="usuariosLista" class="mt-2"></div>
            </div>
            <input type="hidden" id="usuariosEquipoInput" name="usuariosEquipo">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Equipo</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  
    <script src="js/script.js"></script>
    <script src="js/scriptEq.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
