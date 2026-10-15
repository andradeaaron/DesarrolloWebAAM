<?php // Integrante:  Quispe Serrano Carla
require_once "proteger.php";
require_once "conexion.php";
require_once "funciones.php";

$pacientes  = $conn->query("SELECT COUNT(*) FROM pacientes")->fetch_row()[0];
$medicos    = $conn->query("SELECT COUNT(*) FROM medicos")->fetch_row()[0];
$pendientes = $conn->query("SELECT COUNT(*) FROM citas WHERE estado = 'pendiente'")->fetch_row()[0];

$subtitulo = "Panel principal";
$activa = "panel";
include "menu.php";
?>
<?php mostrarMensaje(); ?>
<h2>Bienvenido/a, <?= e($_SESSION["nombre"]) ?></h2>
<div class="panel">
    <p>Correo: <strong><?= e($_SESSION["correo"]) ?></strong> |
       Rol: <span class="etiqueta et-<?= e($_SESSION["rol"]) ?>"><?= e($_SESSION["rol"]) ?></span></p>
    <small>Identificador de la sesion: <?= e(session_id()) ?></small>
</div>

<h2>Resumen del consultorio</h2>
<div class="tarjetas">
    <div class="tarjeta"><div class="numero"><?= (int)$pacientes ?></div><div class="texto">Pacientes registrados</div></div>
    <div class="tarjeta"><div class="numero"><?= (int)$medicos ?></div><div class="texto">Medicos</div></div>
    <div class="tarjeta"><div class="numero"><?= (int)$pendientes ?></div><div class="texto">Citas pendientes</div></div>
</div>
<?php pie(); ?>
