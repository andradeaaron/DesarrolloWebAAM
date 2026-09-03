<?php
// Name: Andrade Mallco Aaron 
class Producto {
    private $codigo;
    private $nombre;
    private $categoria;
    private $precio;
    private $stock;

    public function __construct($codigo, $nombre, $categoria, $precio, $stock) {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->categoria = $categoria;
        $this->precio = $precio;
        $this->stock = $stock;
    }

    public function getCodigo() { return $this->codigo; }
    public function getNombre() { return $this->nombre; }
    public function getCategoria() { return $this->categoria; }
    public function getPrecio() { return $this->precio; }
    public function getStock() { return $this->stock; }

    public function getPrecioConDescuento($porcentaje = 10) {
        return round($this->precio * (1 - $porcentaje / 100), 2);
    }

    public function hayStock() {
        return $this->stock > 0;
    }

    public function mostrarTarjeta() {
        $stockHtml = $this->hayStock() 
            ? "Stock disponible: {$this->stock} unidades" 
            : '<span style="color:red; font-weight:bold;">AGOTADO</span>';

        return "
        <div class='card'>
            <div class='card-header'>{$this->codigo}</div>
            <div class='card-body'>
                <span class='categoria-tag'>{$this->categoria}</span>
                <div style='font-weight:bold; margin-bottom:8px;'>{$this->nombre}</div>
                <div style='margin-bottom:8px;'>Bs " . number_format($this->precio, 2) . "</div>
                <div style='font-size:0.85rem; color:#666; margin-bottom:8px;'>Con 10% de descuento: Bs " . number_format($this->getPrecioConDescuento(), 2) . "</div>
                <div style='font-size:0.85rem;'>{$stockHtml}</div>
            </div>
        </div>";
    }
}