<?php
$operacion = $_POST['operacion'] ?? 'multiplicacion';
$n = intval($_POST['n'] ?? 1);

switch ($operacion) {
    case 'suma':
        $titulo = "Tabla de Suma";
        $simbolo = "+";
        break;
    case 'resta':
        $titulo = "Tabla de Resta";
        $simbolo = "-";
        break;
    case 'multiplicacion':
        $titulo = "Tabla de Multiplicacion";
        $simbolo = "*";
        break;
    case 'division':
        $titulo = "Tabla de Division";
        $simbolo = "/";
        break;
    default:
        $titulo = "Tabla";
        $simbolo = "";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $titulo; ?></title>
    <style>
        table { 
            border-collapse: collapse; 
            text-align: center; 
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid black; 
            padding: 10px; 
            width: 40px; 
            height: 40px; 
        }
        .gris { 
            background-color: #D9D9D9; 
            font-weight: bold; 
        }
    </style>
</head>
<body>
    <h1><?php echo $titulo; ?></h1>

    <table>
        <?php
        for ($i = 0; $i <= $n; $i++) {
            echo "<tr>";
            for ($j = 0; $j <= $n; $j++) {
                if ($i == 0 && $j == 0) {
                    echo "<td class='gris'>$simbolo</td>";
                }
                elseif ($i == 0) {
                    echo "<td class='gris'>$j</td>";
                }
                elseif ($j == 0) {
                    echo "<td class='gris'>$i</td>";
                }
                else {
                    switch ($operacion) {
                        case 'suma':
                            $res = $i + $j;
                            break;
                        case 'resta':
                            $res = $i - $j;
                            break;
                        case 'multiplicacion':
                            $res = $i * $j;
                            break;
                        case 'division':
                            $res = number_format($i / $j, 2);
                            break;
                    }
                    echo "<td>$res</td>";
                }
            }
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>