<?php
// conexion.php
$servidor = "localhost";
$usuario  = "root";
$clave    = "";           // en XAMPP, root no tiene contrasena por defecto
$bd       = "bd_cafeteria26";
$puerto   = 3306

// Crear conexion
$conexion = new mysqli($servidor, $usuario, $clave, $bd, $puerto);

// Verificar conexion
if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}

// Establecer charset UTF-8
$conexion->set_charset("utf8");
?>