<?php
// Name: Andrade Mallco Aaron 
class Carrito {
    private $items = []; 

    public function __construct() {
        $this->items = [];
    }

    public function agregar($codigo, $cantidad = 1) {
        $cantidad = max(1, (int)$cantidad);
        if (isset($this->items[$codigo])) {
            $this->items[$codigo] += $cantidad;
        } else {
            $this->items[$codigo] = $cantidad;
        }
    }

    public function quitar($codigo) {
        if (isset($this->items[$codigo])) {
            unset($this->items[$codigo]);
        }
    }

    public function vaciar() {
        $this->items = [];
    }

    public function getItems() {
        return $this->items;
    }

    public function estaVacio() {
        return count($this->items) === 0;
    }

    public function cantidadTotal() {
        if ($this->estaVacio()) {
            return 0;
        }
        return array_sum($this->items);
    }

    public function total(array $catalogo) {
        $importeTotal = 0;
        foreach ($this->items as $codigo => $cantidad) {
            $producto = buscarProducto($catalogo, $codigo);
            if ($producto) {
                $importeTotal += $producto->getPrecio() * $cantidad;
            }
        }
        return round($importeTotal, 2);
    }
}