<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    
</body>
</html>

<?php
require_once "conexion.php";
$sql = "SELECT * FROM recetas";
$resultado = $conn->query($sql);
// Verificar si hay resultados
if ($resultado->num_rows > 0) {
?><div class="grilla"> 
        
        <?php
        // Recorrer resultados
        while ($fila = $resultado->fetch_assoc()) {
        ?><div onclick="abrirmodal('<?php echo $fila["id"]; ?>')" >
                <img class="receta" src="images/<?php echo $fila["fotografia"]; ?>"> 
                <div><?php echo $fila["titulo"]; ?></div>
        </div>       
        <?php
        } ?>
    </div>
<?php
} else {
    echo "No hay registros";
}
$conn->close();
?>
<a href="form_insertar.html">Crear</a>