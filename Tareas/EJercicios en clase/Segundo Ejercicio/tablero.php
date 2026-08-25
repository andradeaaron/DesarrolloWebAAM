<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado Tablero</title>
    <style>
        /* Opcional: Esto ayuda a que el tablero se vea como una cuadrícula real */
        td {
            width: 40px;
            height: 40px;
            text-align: center;
        }
    </style>
</head>
<body>
    <table border="1">
    <?php
    // Recibimos los datos del formulario de forma segura
    $filas = isset($_GET['filas']) ? intval($_GET['filas']) : 0;
    $columnas = isset($_GET['columnas']) ? intval($_GET['columnas']) : 0;

    // Bucle anidado para generar filas y columnas
    for ($i = 0; $i < $filas; $i++) {
        echo "<tr>";
        for ($j = 0; $j < $columnas; $j++) {
            echo "<td>&nbsp;</td>";
        }
        echo "</tr>";
    }
    ?>
    </table>
    <br>
</body>
</html>