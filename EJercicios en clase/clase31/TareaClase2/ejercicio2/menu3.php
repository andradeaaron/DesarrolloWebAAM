<?php
require_once 'pizarra.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['palabra'])) {
    $palabra = $_POST['palabra'];
    $color = $_POST['color'];
    $color_fondo = $_POST['color_fondo'];

    $pizarra = new Pizarra($palabra, $color, $color_fondo);

    $_SESSION['pizarra'] = $pizarra;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menu Pizarra</title>
</head>
<body>
    <h1>Menu Principal</h1>
    
    <ul>
        <li><a href="menu3.php?accion=triangulo">Mostrar Triangulo</a></li>
        <li><a href="formulario3.html">Configurar Nueva Pizarra</a></li>
    </ul>
    <hr>

    <?php
    if (isset($_GET['accion']) && $_GET['accion'] == 'triangulo') {
        if (isset($_SESSION['pizarra'])) {
            echo "<h2>Resultado: Triangulo</h2>";
            $_SESSION['pizarra']->triangulo();
        } else {
            echo "<p style='color: red;'>Primero debes configurar la pizarra en el formulario.</p>";
        }
    }
    ?>
</body>
</html>