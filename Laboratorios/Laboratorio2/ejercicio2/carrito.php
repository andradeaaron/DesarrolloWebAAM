<?php
// Name: Andrade Mallco Aaron 
session_start();
require_once "inc/Producto.php";
require_once "inc/Carrito.php";
require_once "inc/catalogo.php";

if (!isset($_COOKIE['cliente'])) {
    header("Location: tienda.php");
    exit();
}

$catalogo = obtenerCatalogo();

$carrito = new Carrito();
if (isset($_SESSION['carrito'])) {
    $carrito = unserialize($_SESSION['carrito']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'quitar' && isset($_POST['codigo'])) {
            $carrito->quitar($_POST['codigo']);
        } elseif ($_POST['accion'] === 'vaciar') {
            $carrito->vaciar();
        }
        $_SESSION['carrito'] = serialize($carrito);
        header("Location: carrito.php");
        exit();
    }
}

$temaActual = $_COOKIE['tema'] ?? 'claro';
$itemsCarrito = $carrito->getItems();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TecnoStore USFX - Carrito</title>
    <link rel="stylesheet" href="../estilos.css">
</head>
<body class="<?php echo htmlspecialchars($temaActual); ?>">

<header>
    <h1>Tecnostore USFX    Carrito</h1>
    <nav>
        <a href="tienda.php">Seguir comprando</a>
        <a href="salir.php">Salir</a>
    </nav>
</header>

<div class="container">
    <h2>Compra de <?php echo htmlspecialchars($_COOKIE['cliente']); ?></h2>
    
    <h3>Detalle del carrito</h3>
    <?php if ($carrito->estaVacio()): ?>
        <p>Su carrito está vacío actualmente.</p>
        <p><a href="tienda.php" style="color: var(--primary-color);">Volver a la tienda para agregar productos</a></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>CODIGO</th>
                    <th>PRODUCTO</th>
                    <th>PRECIO</th>
                    <th>CANTIDAD</th>
                    <th>SUBTOTAL</th>
                    <th>ACCION</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itemsCarrito as $codigo => $cantidad): 
                    $prod = buscarProducto($catalogo, $codigo);
                    if (!$prod) continue;
                    $subtotal = $prod->getPrecio() * $cantidad;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($codigo); ?></td>
                        <td><?php echo htmlspecialchars($prod->getNombre()); ?></td>
                        <td>Bs <?php echo number_format($prod->getPrecio(), 2); ?></td>
                        <td><?php echo $cantidad; ?></td>
                        <td>Bs <?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <form method="post" action="carrito.php" style="margin:0;">
                                <input type="hidden" name="accion" value="quitar">
                                <input type="hidden" name="codigo" value="<?php echo htmlspecialchars($codigo); ?>">
                                <button type="submit" style="background-color: #bb2121; 
                                color: white; 
                                border: none; 
                                padding: 5px 10px; 
                                border-radius: 3px; 
                                cursor: pointer;">Quitar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold; background-color: var(--card-bg);">
                    <td colspan="4" style="text-align: right;">Total a pagar (<?php echo $carrito->cantidadTotal(); ?> articulos)</td>
                    <td colspan="2">Bs <?php echo number_format($carrito->total($catalogo), 2); ?></td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <form method="post" action="carrito.php">
                <input type="hidden" name="accion" value="vaciar">
                <button type="submit" style="background-color: red; 
                color: white; 
                border: none; 
                padding: 8px 15px; 
                border-radius: 4px; 
                cursor: pointer;">Vaciar carrito</button>
            </form>
            <a href="tienda.php" style="padding: 8px 15px; background-color: var(--primary-color); color: white; text-decoration: none; border-radius: 4px;">Seguir comprando</a>
        </div>
    <?php endif; ?>

    <h3 style="margin-top: 40px;">Comprobacion de sesiones y cookies</h3>
    
    <h4>Identificador de la sesion</h4>
    <pre><?php echo session_id(); ?></pre>

    <h4>Contenido de $_SESSION</h4>
    <pre><?php print_r($_SESSION); ?></pre>

    <h4>Contenido de $_COOKIE</h4>
    <pre><?php print_r($_COOKIE); ?></pre>
</div>


</body>
</html>