<?php
// Autores: Andrade Mallco Aaron
require_once "Producto.php";

function obtenerCatalogo() {
    return [
        new Producto("P01", "Teclado", "Perifericos", 320.00, 12),
        new Producto("P02", "Mouse", "Perifericos", 148.50, 6),
        new Producto("P03", "Monitor", "Pantallas", 1250.00, 5),
        new Producto("P04", "Monitor", "Pantallas", 2100.00, 0),
        new Producto("P05", "Disco SD", "Almacenamiento", 380.00, 20),
        new Producto("P06", "Memoria", "Almacenamiento", 75.00, 35),
        new Producto("P07", "Audifonos", "Perifericos", 218.00, 0),
        new Producto("P08", "Disco externo", "Almacenamiento", 640.00, 6)
    ];
}

function buscarProducto(array $catalogo, $codigo) {
    foreach ($catalogo as $producto) {
        if ($producto->getCodigo() === $codigo) {
            return $producto;
        }
    }
    return null;
}