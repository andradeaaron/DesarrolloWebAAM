<?php
class Pizarra {
    public $palabra;
    public $color;
    public $color_fondo;

    public function __construct($palabra, $color, $color_fondo) {
        $this->palabra = $palabra;
        $this->color = $color;
        $this->color_fondo = $color_fondo;
    }

    public function triangulo() {
        $n = strlen($this->palabra);
        
        echo "<table style='
        border-collapse: 
        collapse; 
        text-align: 
        center;'>";
        
        for ($i = 0; $i < $n; $i++) {
            echo "<tr>";
            for ($j = 0; $j < $n; $j++) {
                if ($j <= $i) {
                    $letra = $this->palabra[$j];
                    echo "<td style='border: 1px 
                    solid black; 
                    width: 40px; 
                    height: 40px; 
                    color: {$this->color}; 
                    background-color: {$this->color_fondo}; 
                    font-weight: bold;'>$letra
                    </td>";
                } else {
                    echo "<td style='border: 1px 
                    solid black; 
                    width: 40px; 
                    height: 40px;'>
                    </td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
    }
}
?>