<?php
require_once "conexion.php";
$id=$_GET["id"];
$sql = "SELECT recetas.id, recetas.fotografia, recetas.titulo, tiporeceta, preparacion FROM recetas
        join tiporeceta on recetas.idtiporeceta = tiporeceta.id
        where recetas.id=?";
$stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $receta = $stmt->get_result()->fetch_assoc();
    ?>
<div>
    
    <img width="300px" height="300px" src="images/<?php echo $receta['fotografia'] ?>" id="imagen-modal">
    <div id="titulo-modal"><?php echo $receta['titulo'] ?></div>
    <div id="tipo-modal"><?php echo $receta['tiporeceta'] ?></div>
    <div id="receta-modal"><?php echo $receta['preparacion'] ?></div>
</div>
