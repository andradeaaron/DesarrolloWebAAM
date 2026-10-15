<?php
require_once "funciones.php";
$subtitulo = $subtitulo ?? "";
$activa    = $activa ?? "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($subtitulo) ?> - Consultorio USFX</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<header class="cabecera">
    <div>
        <h1>Consultorio USFX</h1>
        <p><?= e($subtitulo) ?></p>
    </div>
    <nav>
        <a href="panel.php" class="<?= $activa === "panel" ? "activo" : "" ?>">Panel</a>
        <a href="listar.php" class="<?= $activa === "listar" ? "activo" : "" ?>">Pacientes</a>
        <a href="agenda.php" class="<?= $activa === "agenda" ? "activo" : "" ?>">Agenda</a>
        <a href="cerrar_sesion.php">Salir (<?= e($_SESSION["nombre"] ?? "") ?>)</a>
    </nav>
</header>
<main class="contenedor">
