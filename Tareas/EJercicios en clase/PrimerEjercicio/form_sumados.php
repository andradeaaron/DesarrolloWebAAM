<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forma_m</title>
</head>
<body>
    <?php
    $n = $_GET['n'];
    ?>
    <form action="resultados.php" method="get">
        <?php
            for ($i = 1; $i <$n; $i++) {
        ?>
        <input type="number" name="num<?php echo $i; ?>"></br>
        <?php

        }
        ?>
        <input type="hidden" name="n" value="<?php echo $n; ?>">
        <input type="submit" value="Sumar">
    </form>
</body>
</html>