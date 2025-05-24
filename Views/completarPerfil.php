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

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Workly</title>
  <link rel="stylesheet" href="css/styleregister.css">
</head>

<body>
  <div class="container">
    <div class="logo">Bienvenido <?= $_SESSION['username'] ?></div>
    <div class="title">Completa tu infomacion</div>
    <form action="../Controllers/completarInformacion.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <input type="text" placeholder="Nombre" name="nombre" id="nombre" required>
        <input type="text" placeholder="Apellido Paterno" name="apellidoP" id="apellidoP" required>
        <input type="text" placeholder="Apellido Materno" name="apellidoM" id="apellidoM" required>
        
      </div>
      <div class="form-group">
        <input type="text" placeholder="Descripcion" name="descripcion" id="descripcion" required>
      </div>
      <div class="buttons">
        <button type="submit" class="create">Confirmar</button>
      </div>
    </form>
  </div>
</body>
</html>