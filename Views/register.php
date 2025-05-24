<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="css/styleregistro.css">
</head>
<body>
    <div class="registro">
        <form action="../Controllers/registro.php" method="POST" enctype="multipart/form-data" id="registro">
            <h1 id="titulo">Registro</h1>
        <div class="form">
          <div class="foto">
            <h4>Imagen de perfil</h4>
            <div class="preview">
                <img id="image-preview" src="#" alt="imagen">
            </div>
            <input type="hidden" id="imagen" name="imagen">
            <input type="file" id="file" name="file" accept="image/*">
            <label for="file" class="upload-btn">Agregar Foto</label>
            <div class="error" id="error"></div>
          </div>
          <div class="datos">
            <h4>Información</h4>
            <input type="text" class="name" name="name" placeholder="usuario" id="name" required>
            <input type="text" class="email" placeholder="Correo" name="email" id="email" required>
            <input type="password" placeholder="Contraseña" name="password" id="password" required>
            <input type="password" placeholder="Confirmar Contraseña" name="confirmpass" id="confirmpass" required>
            <br>
            <input type="submit" value="Continuar">
            <br>
            <a href="login.html">Iniciar Sesion</a>
          </div>
        </div>
        </form>
    </div>
    <script src="js/scriptRe.js"></script>
</body>
</html>