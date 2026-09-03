<?php
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
        $this->precio = (float)$precio;
        $this->stock = (int)$stock;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getStock() {
        return $this->stock;
    }

    public function getPrecioConDescuento($porcentaje = 10) {
        $descuento = $this->precio * ($porcentaje / 100);
        return round($this->precio - $descuento, 2);
    }

    public function hayStock() {
        return $this->stock > 0;
    }

    public function mostrarTarjeta(){
        $precioOriginal = number_format($this->precio, 2);
        $precioDesc = number_format($this->getPrecioConDescuento(10), 2);
        $categoriaMayus = strtoupper($this->categoria);

        $stockHtml = $this->hayStock()
            ? "<p class='stock-text'>Stock disponible: {$this->stock} unidades</p>"
            : "<span class='badge-agotado'>AGOTADO</span>";

        return "
        <article class='card'>
            <div class='card-header-blue'>
                <h3>{$this->codigo}</h3>
            </div>
            <div class='card-stripe'>
                <span>{$categoriaMayus}</span>
            </div>
            <div class='card-body'>
                <h4 class='prod-title'>{$this->nombre}</h4>
                <div class='prod-price'>Bs {$precioOriginal}</div>
                <div class='prod-discount'>Con 10% de descuento: Bs {$precioDesc}</div>
                <div class='prod-footer'>
                    {$stockHtml}
                </div>
            </div>
        </article>";
    }
}
?>