<?php 
function e($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, "UTF-8");
}

function claseAviso($tipo) {
    return $tipo === "error" ? "aviso aviso-error" : "aviso";
}

function avisar($texto, $tipo = "ok") {
    $_SESSION["mensaje"] = $texto;
    $_SESSION["tipo_mensaje"] = $tipo;
}

function mostrarMensaje() {
    if (!empty($_SESSION["mensaje"])) {
        $tipo = $_SESSION["tipo_mensaje"] ?? "ok";
        echo '<div class="' . claseAviso($tipo) . '">' . e($_SESSION["mensaje"]) . '</div>';
    }
    unset($_SESSION["mensaje"], $_SESSION["tipo_mensaje"]);
}

function pie() {
    echo '</main>
<footer class="pie">SIS 256 - Tecnologia y Desarrollo Web - Ing. Carlos David Montellano Barriga</footer>
</body>
</html>';
}

function subirFotografia($archivo) {
    if (!isset($archivo) || $archivo["error"] === UPLOAD_ERR_NO_FILE) {
        return ["nombre" => "", "error" => ""];       
    }
    if ($archivo["error"] !== UPLOAD_ERR_OK) {
        return ["nombre" => "", "error" => "No se pudo subir el archivo (codigo " . $archivo["error"] . ")."];
    }
    if ($archivo["size"] > 2 * 1024 * 1024) {
        return ["nombre" => "", "error" => "La fotografia supera el maximo de 2 MB."];
    }
    $extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
    $permitidas = ["jpg", "jpeg", "png", "webp"];
    if (!in_array($extension, $permitidas, true)) {
        return ["nombre" => "", "error" => "Formato no permitido. Use jpg, jpeg, png o webp."];
    }
    if (getimagesize($archivo["tmp_name"]) === false) {
        return ["nombre" => "", "error" => "El archivo no es una imagen valida."];
    }
    $nuevo = uniqid() . "." . $extension;
    if (!move_uploaded_file($archivo["tmp_name"], __DIR__ . "/images/" . $nuevo)) {
        return ["nombre" => "", "error" => "No se pudo guardar la fotografia en el servidor."];
    }
    return ["nombre" => $nuevo, "error" => ""];
}
