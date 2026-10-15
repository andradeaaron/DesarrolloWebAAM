<?php // Integrante: Aaron Andrade Mallco
require_once "proteger.php";
require_once "permiso.php";
require_once "funciones.php";

$subtitulo = "Registrar paciente";
$activa = "listar";
include "menu.php";
?>
<?php mostrarMensaje(); ?>
<h2>Registrar un paciente</h2>

<form action="insert.php" method="POST" enctype="multipart/form-data" class="panel">
    <div class="fila">
        <div class="campo">
            <label for="nombres">Nombres</label>
            <input type="text" id="nombres" name="nombres" required>
        </div>
        <div class="campo">
            <label for="apellidos">Apellidos</label>
            <input type="text" id="apellidos" name="apellidos" required>
        </div>
    </div>
    <div class="fila">
        <div class="campo">
            <label for="ci">Carnet de identidad</label>
            <input type="text" id="ci" name="ci" required>
        </div>
        <div class="campo">
            <label for="telefono">Telefono</label>
            <input type="text" id="telefono" name="telefono" required>
        </div>
        <div class="campo">
            <label for="fecha_nacimiento">Fecha de nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
        </div>
    </div>
    <div class="campo">
        <label for="fotografia">Fotografia (jpg, jpeg, png o webp - maximo 2 MB, opcional)</label>
        <input type="file" id="fotografia" name="fotografia" accept=".jpg,.jpeg,.png,.webp">
    </div>
    <button type="submit" class="boton boton-ok">Guardar paciente</button>
    <a href="listar.php" class="boton boton-sec">Cancelar</a>
</form>
<?php pie(); ?>
