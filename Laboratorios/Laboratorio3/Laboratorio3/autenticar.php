<?php // Integrante:  Quispe Serrano Carla
session_start();
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: form_login.php");
    exit();
}

$correo = trim($_POST["correo"] ?? "");
$hash   = sha1($_POST["clave"] ?? "");   // se compara resumen contra resumen

$sql  = "SELECT id, correo, nombre, rol FROM usuarios WHERE correo = ? AND clave = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $correo, $hash);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 1) {
    $u = $res->fetch_assoc();
    session_regenerate_id(true);
    $_SESSION["id"]     = $u["id"];
    $_SESSION["correo"] = $u["correo"];
    $_SESSION["nombre"] = $u["nombre"];
    $_SESSION["rol"]    = $u["rol"];
    header("Location: panel.php");
    exit();
}

// No se indica si fallo el correo o la clave
header("Location: form_login.php?error=1");
exit();
