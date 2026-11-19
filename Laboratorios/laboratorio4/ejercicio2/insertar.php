<?php
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responder(405, ["exito" => false, "mensaje" => "Método no permitido. Use POST."]);
}

$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : "";
$categoria_id = isset($_POST["categoria_id"]) ? (int)$_POST["categoria_id"] : 0;
$precioTexto = isset($_POST["precio"]) ? trim($_POST["precio"]) : "";
$descripcion = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : "";
$disponible = (isset($_POST["disponible"]) && $_POST["disponible"] === "0") ? 0 : 1;

$errores = [];

if (mb_strlen($nombre) < 3 || mb_strlen($nombre) > 60) {
    $errores[] = "El nombre debe tener entre 3 y 60 caracteres.";
}

if ($categoria_id <= 0) {
    $errores[] = "Seleccione una categoría.";
} else {
    $stmt = $conn->prepare("SELECT id FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $categoria_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        $errores[] = "La categoría seleccionada no existe.";
    }
    $stmt->close();
}

if (!is_numeric($precioTexto) || (float)$precioTexto <= 0 || (float)$precioTexto > 9999.99) {
    $errores[] = "El precio debe ser un número mayor a cero.";
}

if (mb_strlen($descripcion) > 255) {
    $errores[] = "La descripción no puede superar los 255 caracteres.";
}

$nombreImagen = "";
$archivoTemporal = "";
$hayArchivo = isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE;

if ($hayArchivo) {
    $archivo = $_FILES["imagen"];
    $extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));

    if ($archivo["error"] !== UPLOAD_ERR_OK) {
        $errores[] = "No se pudo recibir la fotografía.";
    } elseif (!in_array($extension, ["jpg", "jpeg", "png", "webp"], true)) {
        $errores[] = "La fotografía debe ser jpg, jpeg, png o webp.";
    } elseif ($archivo["size"] > 2 * 1024 * 1024) {
        $errores[] = "La fotografía no puede superar los 2 MB.";
    } elseif (@getimagesize($archivo["tmp_name"]) === false) {
        $errores[] = "El archivo enviado no es una imagen válida.";
    } else {
        $nombreImagen = uniqid("prod_") . "." . $extension;
        $archivoTemporal = $archivo["tmp_name"];
    }
}

if (count($errores) > 0) {
    responder(422, ["exito" => false, "errores" => $errores]);
}

if ($nombreImagen !== "") {
    $destino = __DIR__ . "/../images/" . $nombreImagen;
    if (!move_uploaded_file($archivoTemporal, $destino)) {
        responder(500, ["exito" => false, "mensaje" => "No se pudo guardar la fotografía en el servidor."]);
    }
}

$precio = (float)$precioTexto;

$stmt = $conn->prepare("INSERT INTO productos (nombre, categoria_id, precio, descripcion, imagen, disponible) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    responder(500, ["exito" => false, "mensaje" => "No se pudo preparar la inserción"]);
}

$stmt->bind_param("sidssi", $nombre, $categoria_id, $precio, $descripcion, $nombreImagen, $disponible);

if (!$stmt->execute()) {
    if ($nombreImagen !== "") {
        @unlink(__DIR__ . "/../images/" . $nombreImagen);
    }
    responder(500, ["exito" => false, "mensaje" => "No se pdo registrar el producto."]);
}

$id = $conn->insert_id;
$stmt->close();
$conn->close();

responder(201, ["exito" => true, "id" => $id, "mensaje" => "Producto registrado correctamente."]);
?>