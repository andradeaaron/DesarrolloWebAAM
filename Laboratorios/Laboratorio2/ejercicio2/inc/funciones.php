<?php
// Name: Andrade Mallco Aaron

function gestionarCookiesYVisitas() {
    if (isset($_GET['tema'])) {
        $nuevoTema = $_GET['tema'] === 'oscuro' ? 
        'oscuro' : 'claro';
        setcookie("tema", $nuevoTema, time() + (86400 * 30), "/");
        header("Location: tienda.php");
        exit();
    }

    if (!isset($_SESSION['visisa_contada'])) {
        $visitasActuales = isset($_COOKIE['visitas']) ? (int)$_COOKIE['visitas'] : 0;
        $visitasActuales++;
        setcookie("visitas", $visitasActuales, time() + (86400 * 30), "/");
        $_SESSION['visisa_contada'] = true;
    }
}