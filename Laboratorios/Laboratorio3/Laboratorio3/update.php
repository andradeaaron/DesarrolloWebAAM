<?php // Integrante: Aaron Andrade Mallco
require_once "conexion.php";
require_once "funciones.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: listar.php");
    exit();
}

$id        = (int)($_POST["id"] ?? 0);
$nombres   = trim($_POST["nombres"] ?? "");
$apellidos = trim($_POST["apellidos"] ?? "");
$ci        = trim($_POST["ci"] ?? "");
$telefono  = trim($_POST["telefono"] ?? "");
$fecha     = trim($_POST["fecha_nacimiento"] ?? "");
$fotoFinal = trim($_POST["foto_actual"] ?? "");  
if ($id <= 0 || $nombres === "" || $apellidos === "" || $ci === "" || $fecha === "") {
    avisar("Complete nombres, apellidos, CI y fecha de nacimiento.", "error");
    header("Location: form_editar.php?id=" . $id);
    exit();
}

$foto = subirFotografia($_FILES["fotografia"] ?? null);
if ($foto["error"] !== "") {
    avisar($foto["error"], "error");
    header("Location: form_editar.php?id=" . $id);
    exit();
}
if ($foto["nombre"] !== "") {
    $fotoFinal = $foto["nombre"];
}

$sql  = "UPDATE pacientes
         SET fotografia = ?, nombres = ?, apellidos = ?, ci = ?, telefono = ?, fecha_nacimiento = ?
         WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $fotoFinal, $nombres, $apellidos, $ci, $telefono, $fecha, $id);

if ($stmt->execute()) {
    avisar("El paciente se actualizo correctamente.");
} else {
    avisar("Error al actualizar: " . $stmt->error, "error");
}

header("Location: listar.php");
exit();
