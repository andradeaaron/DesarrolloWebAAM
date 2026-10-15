<?php // Integrante:  Quispe Serrano Carla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (($_SESSION["rol"] ?? "") !== "admin") {
    $_SESSION["mensaje"] = "Su rol (" . ($_SESSION["rol"] ?? "?") . ") no tiene permiso para realizar esa accion.";
    $_SESSION["tipo_mensaje"] = "error";
    header("Location: listar.php");
    exit();
}
