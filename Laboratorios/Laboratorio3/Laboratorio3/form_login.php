<?php // Integrante: Quispe Serrano Carla
$error = $_GET["error"] ?? "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingreso - Consultorio USFX</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="login">
    <h1>Consultorio USFX</h1>
    <p class="sub">Sistema de pacientes y citas - SIS 256</p>

    <?php if ($error === "1"): ?>
        <div class="aviso aviso-error">Correo o contrasena incorrectos.</div>
    <?php elseif ($error === "2"): ?>
        <div class="aviso aviso-error">Debe iniciar sesion para acceder al sistema.</div>
    <?php endif; ?>

    <form method="POST" action="autenticar.php">
        <div class="campo">
            <label for="correo">Correo electronico</label>
            <input type="email" id="correo" name="correo" placeholder="usuario@sis256.edu" required>
        </div>
        <div class="campo">
            <label for="clave">Contrasena</label>
            <input type="password" id="clave" name="clave" required>
        </div>
        <button type="submit" class="boton">Ingresar</button>
    </form>

    <p class="ayuda">Usuarios de prueba:<br>
        admin@sis256.edu / 123 (rol admin)<br>
        recepcion@sis256.edu / 123 (rol user)</p>
</div>
</body>
</html>
