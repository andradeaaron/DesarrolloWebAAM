<?php
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responder(405, ["exito" => false, "mensaje" => "Método no permitido. Use POST."]);
}

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

$stmt = $conn->prepare("SELECT imagen FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$fila) {
    responder(404, ["exito" => false, "mensaje" => "El producto no existe."]);
}

$stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    responder(500, ["exito" => false, "mensaje" => "No se pudo eliminar el producto."]);
}

$stmt->close();
$conn->close();

if (strpos($fila["imagen"], "prod_") === 0) {
    @unlink(__DIR__ . "/../images/" . basename($fila["imagen"]));
}

responder(200, ["exito" => true, "mensaje" => "Producto eliminado correctamente."]);
?>