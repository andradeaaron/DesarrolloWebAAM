<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "conexion.php";

// Solo aceptamos el metodo DELETE
if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
    http_response_code(405);
    echo json_encode(["exito" => false, "error" => "Metodo no permitido"]);
    exit;
}

$id = $_GET["id"] ?? 0;

// Prepared statement: evita SQL Injection
$stmt = $conn->prepare("DELETE FROM recetas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["exito" => true]);
} else {
    echo json_encode(["exito" => false, "error" => "No existe la receta"]);
}
$conn->close();
?>
