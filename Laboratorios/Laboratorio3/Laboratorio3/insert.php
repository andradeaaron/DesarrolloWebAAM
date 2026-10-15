<?php // Integrante: Aaron Andrade Mallco 
require_once "proteger.php";
require_once "permiso.php";
require_once "conexion.php";
require_once "funciones.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: form_insertar.php");
    exit();
}

$nombres   = trim($_POST["nombres"] ?? "");
$apellidos = trim($_POST["apellidos"] ?? "");
$ci        = trim($_POST["ci"] ?? "");
$telefono  = trim($_POST["telefono"] ?? "");
$fecha     = trim($_POST["fecha_nacimiento"] ?? "");

if ($nombres === "" || $apellidos === "" || $ci === "" || $fecha === "") {
    avisar("Complete nombres, apellidos, CI y fecha de nacimiento.", "error");
    header("Location: form_insertar.php");
    exit();
}

$foto = subirFotografia($_FILES["fotografia"] ?? null);
if ($foto["error"] !== "") {
    avisar($foto["error"], "error");
    header("Location: form_insertar.php");
    exit();
}

$sql  = "INSERT INTO pacientes (fotografia, nombres, apellidos, ci, telefono, fecha_nacimiento)
         VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $foto["nombre"], $nombres, $apellidos, $ci, $telefono, $fecha);

if ($stmt->execute()) {
    avisar("El paciente $nombres $apellidos se registro correctamente.");
} else {
    avisar("Error al registrar el paciente: " . $stmt->error, "error");
}

header("Location: listar.php");
exit();
