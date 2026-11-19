<?php
require "conexion.php";

$buscar = isset($_GET["buscar"]) ? trim($_GET["buscar"]) : "";
$categoria = isset($_GET["categoria"]) ? (int)$_GET["categoria"] : 0;
$like = "%" . $buscar . "%";

$sql = "SELECT p.id, p.nombre, p.precio, p.imagen, p.disponible, c.nombre AS categoria
        FROM productos p
        INNER JOIN categorias c ON c.id = p.categoria_id
        WHERE p.nombre LIKE ? AND (? = 0 OR p.categoria_id = ?)
        ORDER BY c.nombre, p.nombre";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    responder(500, ["exito" => false, "mensaje" => "No se puddo preparar la consulta"]);
}

$stmt->bind_param("sii", $like, $categoria, $categoria);
$stmt->execute();
$resultado = $stmt->get_result();

$productos = [];
while ($fila = $resultado->fetch_assoc()) {
    $productos[] = [
        "id" => (int)$fila["id"],
        "nombre" => $fila["nombre"],
        "precio" => (float)$fila["precio"],
        "imagen" => $fila["imagen"],
        "disponible" => (bool)$fila["disponible"],
        "categoria" => $fila["categoria"]
    ];
}

$stmt->close();
$conn->close();
responder(200, $productos);
