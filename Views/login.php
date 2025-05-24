<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workly</title>
    <link rel="stylesheet" href="css/styleLogin.css">
</head>
<body>

    <form action="../Controllers/InicioSesion.php" method="POST">
        <h2>Login</h2>

        <label for="username">Usuario</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>

        <a href="register.html">Registrarse</a>
        <br>

        <input type="submit" class="btn-1" value="Iniciar Sesión">
    </form>
    
</body>
</html>