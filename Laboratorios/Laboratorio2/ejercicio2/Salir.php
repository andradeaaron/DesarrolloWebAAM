<?php
// Name: Andrade Mallco Aaron 
session_start();

$_SESSION = [];

session_destroy();

if (isset($_COOKIE['cliente'])) {
    setcookie("cliente", "", time() - 3600, "/");
}
if (isset($_COOKIE['tema'])) {
    setcookie("tema", "", time() - 3600, "/");
}

header("Location: tienda.php");
exit();