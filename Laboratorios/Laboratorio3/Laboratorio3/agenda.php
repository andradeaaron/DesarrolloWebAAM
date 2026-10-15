<?php // Integrante: Aaron Andrade Mallco 
require_once "conexion.php";
require_once "funciones.php";

$estado = trim($_GET["estado"] ?? "");
$estados = ["pendiente", "atendida", "cancelada"];
if (!in_array($estado, $estados, true)) {
    $estado = "";
}

$sql = "SELECT c.fecha, c.hora, c.motivo, c.estado,
               CONCAT(p.nombres, ' ', p.apellidos) AS paciente,
               CONCAT(m.nombres, ' ', m.apellidos) AS medico,
               e.nombre AS especialidad
        FROM citas c
        INNER JOIN pacientes p ON c.paciente_id = p.id
        INNER JOIN medicos m ON c.medico_id = m.id
        INNER JOIN especialidades e ON m.especialidad_id = e.id
        WHERE (? = '' OR c.estado = ?)
        ORDER BY c.fecha ASC, c.hora ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $estado, $estado);
$stmt->execute();
$resultado = $stmt->get_result();

$subtitulo = "Agenda de citas";
$activa = "agenda";
include "menu.php";
?>
<?php mostrarMensaje(); ?>
<h2>Agenda de citas (<?= $resultado->num_rows ?>)</h2>

<form method="GET" action="agenda.php" class="panel">
    <div class="barra">
        <div>
            <label for="estado">Filtrar por estado</label>
            <select id="estado" name="estado">
                <option value="">-- Todos --</option>
                <?php foreach ($estados as $op): ?>
                    <option value="<?= e($op) ?>" <?= $estado === $op ? "selected" : "" ?>><?= e(ucfirst($op)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="boton">Filtrar</button>
    </div>
</form>

<table>
    <caption>Datos tomados de cuatro tablas relacionadas con INNER JOIN</caption>
    <thead>
        <tr>
            <th>Fecha</th><th>Hora</th><th>Paciente</th><th>Medico</th>
            <th>Especialidad</th><th>Motivo</th><th>Estado</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($c = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= e(date("d/m/Y", strtotime($c["fecha"]))) ?></td>
            <td><?= e(substr($c["hora"], 0, 5)) ?></td>
            <td><?= e($c["paciente"]) ?></td>
            <td><?= e($c["medico"]) ?></td>
            <td><?= e($c["especialidad"]) ?></td>
            <td><?= e($c["motivo"]) ?></td>
            <td><span class="etiqueta et-<?= e($c["estado"]) ?>"><?= e($c["estado"]) ?></span></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php pie(); ?>
