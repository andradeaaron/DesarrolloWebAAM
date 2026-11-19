<?php
require "conexion.php";

$resultado = $conn->query("SELECT id, nombre FROM categorias ORDER BY id");

if (!$resultado) {
    responder(500, ["exito" => false, "mensaje" => "No se pudieron consultar las categorías."]);
}

$categorias = [];
while ($fila = $resultado->fetch_assoc()) {
    $categorias[] = [
        "id" => (int)$fila["id"],
        "nombre" => $fila["nombre"]
    ];
}

$conn->close();
responder(200, $categorias);
?>