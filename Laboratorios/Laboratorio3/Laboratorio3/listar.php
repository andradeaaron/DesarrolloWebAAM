<?php // Integrante: Aaron Andrade Mallco
require_once "proteger.php";
require_once "conexion.php";
require_once "funciones.php";

$buscar = trim($_GET["buscar"] ?? "");
$orden  = $_GET["orden"] ?? "nombres";

$permitidas = ["nombres", "apellidos", "ci", "fecha_nacimiento"];
if (!in_array($orden, $permitidas, true)) {
    $orden = "nombres";
}

$sql = "SELECT id, fotografia, nombres, apellidos, ci, telefono, fecha_nacimiento
        FROM pacientes
        WHERE nombres LIKE ? OR apellidos LIKE ? OR ci LIKE ?
        ORDER BY $orden ASC";
$stmt  = $conn->prepare($sql);
$param = "%" . $buscar . "%";
$stmt->bind_param("sss", $param, $param, $param);
$stmt->execute();
$resultado = $stmt->get_result();

$esAdmin = ($_SESSION["rol"] ?? "") === "admin";
$q = urlencode($buscar);

$subtitulo = "Pacientes";
$activa = "listar";
include "menu.php";
?>
<?php mostrarMensaje(); ?>

<h2>Pacientes (<?= $resultado->num_rows ?> encontrados)</h2>

<form method="GET" action="listar.php" class="panel">
    <input type="hidden" name="orden" value="<?= e($orden) ?>">
    <div class="barra">
        <div style="flex:1 1 240px">
            <label for="buscar">Buscar por nombre, apellido o CI</label>
            <input type="text" id="buscar" name="buscar" value="<?= e($buscar) ?>">
        </div>
        <button type="submit" class="boton">Buscar</button>
        <a href="listar.php" class="boton boton-sec">Limpiar</a>
        <?php if ($esAdmin): ?>
            <a href="form_insertar.php" class="boton boton-ok">Nuevo paciente</a>
        <?php endif; ?>
    </div>
</form>

<?php if ($resultado->num_rows > 0): ?>
<table>
    <caption>Haga clic en el titulo de una columna para ordenar</caption>
    <thead>
        <tr>
            <th>Foto</th>
            <th><a href="listar.php?orden=nombres&buscar=<?= $q ?>">Nombres</a></th>
            <th><a href="listar.php?orden=apellidos&buscar=<?= $q ?>">Apellidos</a></th>
            <th><a href="listar.php?orden=ci&buscar=<?= $q ?>">CI</a></th>
            <th>Telefono</th>
            <th><a href="listar.php?orden=fecha_nacimiento&buscar=<?= $q ?>">Nacimiento</a></th>
            <?php if ($esAdmin): ?><th>Operaciones</th><?php endif; ?>
        </tr>
    </thead>
    <tbody>
    <?php while ($f = $resultado->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if ($f["fotografia"] !== "" && file_exists(__DIR__ . "/images/" . $f["fotografia"])): ?>
                    <img class="foto" src="images/<?= e($f["fotografia"]) ?>" alt="Foto de <?= e($f["nombres"]) ?>">
                <?php else: ?>
                    <div class="sinfoto">S/F</div>
                <?php endif; ?>
            </td>
            <td><?= e($f["nombres"]) ?></td>
            <td><?= e($f["apellidos"]) ?></td>
            <td><?= e($f["ci"]) ?></td>
            <td><?= e($f["telefono"]) ?></td>
            <td><?= e(date("d/m/Y", strtotime($f["fecha_nacimiento"]))) ?></td>
            <?php if ($esAdmin): ?>
            <td>
                <a class="boton mini" href="form_editar.php?id=<?= (int)$f["id"] ?>">Editar</a>
                <a class="boton boton-no mini eliminar" href="delete.php?id=<?= (int)$f["id"] ?>"
                   data-confirmar="Seguro que desea eliminar a este paciente?">Eliminar</a>
            </td>
            <?php endif; ?>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <div class="aviso aviso-error">No se encontraron pacientes que coincidan con la busqueda.</div>
<?php endif; ?>

<script>
document.addEventListener("click", (e) => {
    if (e.target.matches("a.eliminar") && !confirm(e.target.dataset.confirmar)) {
        e.preventDefault();
    }
});
</script>
<?php pie(); ?>
