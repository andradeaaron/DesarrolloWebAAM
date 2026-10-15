<?php // Integrantes: Aaron Andrade Mallco y Jhonnathan Peñaranda Mamani
session_start();
$_SESSION = [];
session_destroy();
header("Location: form_login.php");
exit();
