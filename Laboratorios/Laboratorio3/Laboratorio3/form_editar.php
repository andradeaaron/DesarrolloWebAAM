<?php // Integrante: Aaron Andrade Mallco
require_once "proteger.php";
require_once "permiso.php";
require_once "conexion.php";
require_once "funciones.php";

$id = (int)($_GET["id"] ?? 0);

$stmt = $conn->prepare("SELECT id, fotografia, nombres, apellidos, ci, telefono, fecha_nacimiento FROM pacientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();

if (!$p) {
    avisar("El paciente solicitado no existe.", "error");
    header("Location: listar.php");
    exit();
}

$subtitulo = "Editar paciente";
$activa = "listar";
include "menu.php";
?>
<?php mostrarMensaje(); ?>
<h2>Editar paciente</h2>

<form action="update.php" method="POST" enctype="multipart/form-data" class="panel">
    <input type="hidden" name="id" value="<?= (int)$p["id"] ?>">
    <input type="hidden" name="foto_actual" value="<?= e($p["fotografia"]) ?>">
    <div class="fila">
        <div class="campo">
            <label for="nombres">Nombres</label>
            <input type="text" id="nombres" name="nombres" value="<?= e($p["nombres"]) ?>" required>
        </div>
        <div class="campo">
            <label for="apellidos">Apellidos</label>
            <input type="text" id="apellidos" name="apellidos" value="<?= e($p["apellidos"]) ?>" required>
        </div>
    </div>
    <div class="fila">
        <div class="campo">
            <label for="ci">Carnet de identidad</label>
            <input type="text" id="ci" name="ci" value="<?= e($p["ci"]) ?>" required>
        </div>
        <div class="campo">
            <label for="telefono">Telefono</label>
            <input type="text" id="telefono" name="telefono" value="<?= e($p["telefono"]) ?>" required>
        </div>
        <div class="campo">
            <label for="fecha_nacimiento">Fecha de nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= e($p["fecha_nacimiento"]) ?>" required>
        </div>
    </div>
    <div class="campo">
        <label>Fotografia actual</label>
        <?php if ($p["fotografia"] !== "" && file_exists(__DIR__ . "/images/" . $p["fotografia"])): ?>
            <img class="foto" src="images/<?= e($p["fotografia"]) ?>" alt="Foto actual">
        <?php else: ?>
            <div class="sinfoto">S/F</div>
        <?php endif; ?>
    </div>
    <div class="campo">
        <label for="fotografia">Cambiar fotografia (opcional)</label>
        <input type="file" id="fotografia" name="fotografia" accept=".jpg,.jpeg,.png,.webp">
    </div>
    <button type="submit" class="boton boton-ok">Actualizar paciente</button>
    <a href="listar.php" class="boton boton-sec">Cancelar</a>
</form>
<?php pie(); ?>
