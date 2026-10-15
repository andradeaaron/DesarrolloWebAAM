<?php // Integrante: Aaron Andrade Mallco
require_once "conexion.php";
require_once "funciones.php";

$id = (int)($_GET["id"] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM citas WHERE paciente_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $total = (int)$stmt->get_result()->fetch_row()[0];

    if ($total > 0) {
        avisar("No se puede eliminar: el paciente tiene $total cita(s) registrada(s).", "error");
    } else {
        // 2. Eliminar
        $del = $conn->prepare("DELETE FROM pacientes WHERE id = ?");
        $del->bind_param("i", $id);
        if ($del->execute()) {
            avisar("Paciente eliminado correctamente.");
        } else {
            avisar("Error al eliminar: " . $del->error, "error");
        }
    }
}

header("Location: listar.php");
exit();
