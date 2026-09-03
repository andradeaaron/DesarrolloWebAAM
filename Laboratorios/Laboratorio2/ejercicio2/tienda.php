<?php
// Name: Andrade Mallco Aaron
session_start();
require_once "inc/Producto.php";
require_once "inc/Carrito.php";
require_once "inc/catalogo.php";
require_once "inc/funciones.php";

gestionarCookiesYVisitas();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_cliente'])) {
    $nombreCliente = trim($_POST['nombre_cliente']);
    if (!empty($nombreCliente)) {
        setcookie("cliente", $nombreCliente, time() + (86400 * 7), "/");
    }
    header("Location: tienda.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $codigo = $_POST['codigo'] ?? '';
    $cantidad = (int)($_POST['cantidad'] ?? 1);

    $catalogo = obtenerCatalogo();
    $producto = buscarProducto($catalogo, $codigo);

    if ($producto && $producto->hayStock()) {
        if (!isset($_SESSION['carrito'])) {
            $carrito = new Carrito();
        } else {
            $carrito = unserialize($_SESSION['carrito']);
        }

        $carrito->agregar($codigo, $cantidad);
        $_SESSION['carrito'] = serialize($carrito);
        $_SESSION['mensaje'] = "Se agregó \"{$producto->getNombre()}\" al carrito.";
    }
    header("Location: tienda.php");
    exit();
}

$clienteIdentificado = $_COOKIE['cliente'] ?? null;
$temaActual = $_COOKIE['tema'] ?? 'claro';
$visitasTotales = $_COOKIE['visitas'] ?? 1;
$catalogo = obtenerCatalogo();

$cantidadArticulosEnCarrito = 0;
if (isset($_SESSION['carrito'])) {
    $carritoObj = unserialize($_SESSION['carrito']);
    $cantidadArticulosEnCarrito = $carritoObj->cantidadTotal();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tecnostore USFX - Tienda</title>
    <link rel="stylesheet" href="../estilos.css">
</head>
<body class="<?php echo htmlspecialchars($temaActual); ?>">

<header>
    <h1>TecnoStore USFX - Tienda</h1>
    <?php if ($clienteIdentificado): ?>
        <nav>
            <a href="carrito.php">Carrito (<?php echo $cantidadArticulosEnCarrito; ?>)</a>
            <a href="tienda.php?tema=<?php echo $temaActual === 'claro' ? 'oscuro' : 'claro'; ?>">
                <?php echo $temaActual === 'claro' ? 'Tema oscuro' : 'Tema claro'; ?>
            </a>
            <a href="salir.php">Salir</a>
        </nav>
    <?php endif; ?>
</header>

<div class="container">
    <?php if (!$clienteIdentificado): ?>
        <h2>Identificacion cliente</h2>
        <p>Escriba su nombre. Se guardará en una cookie durante 7 dias de modo que el navegador le recuerde aunque cierre la pagina</p>
        <form method="post" action="tienda.php" style="margin-top: 20px;">
            <label for="nombre_cliente" style="display:block; margin-bottom:8px; font-weight:bold;">Nombre del cliente:</label>
            <input type="text" name="nombre_cliente" id="nombre_cliente"  required style="padding: 8px; width: 300px; margin-right: 10px;">
            <button type="submit" style="padding: 9px 18px; background-color: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer;">Ingresar a la tienda</button>
        </form>
    <?php else: ?>
        
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="mensaje-exito">
                <?php 
                    echo htmlspecialchars($_SESSION['mensaje']); 
                    unset($_SESSION['mensaje']);
                ?>
            </div>
        <?php endif; ?>

        <h2>Hola <?php echo htmlspecialchars($clienteIdentificado); ?></h2>
        
        <div style="background-color: var(--card-bg); border: 1px solid var(--card-border); padding: 15px; border-radius: 3px; margin-bottom: 25px;">
            <p>Esta es su visita numero <strong><?php echo htmlspecialchars($visitasTotales); ?></strong> (dato guardado en la cookie visitas).</p>
            <p>Su carrito tiene <strong><?php echo $cantidadArticulosEnCarrito; ?></strong> artículo y vive en la sesión (<?php echo session_id(); ?> ).</p>
        </div>

        <h3>Productos disponibles</h3>
        <div class="card-grid">
            <?php foreach ($catalogo as $p): ?>
                <div class="card">
                    <div class="card-header"><?php echo htmlspecialchars($p->getCodigo()); ?></div>
                    <div class="card-body">
                        <span class="categoria-tag"><?php echo htmlspecialchars($p->getCategoria()); ?></span>
                        <div style="font-weight:bold; margin-bottom:1px;"><?php echo htmlspecialchars($p->getNombre()); ?></div>
                        <div style="margin-bottom:12px;">Bs <?php echo number_format($p->getPrecio(), 2); ?></div>
                        
                        <?php if ($p->hayStock()): ?>
                            <form method="post" action="tienda.php" style="display: flex; gap: 8px; align-items: center;">
                                <input type="hidden" name="accion" value="agregar">
                                <input type="hidden" name="codigo" value="<?php echo htmlspecialchars($p->getCodigo()); ?>">
                                <input type="number" name="cantidad" value="1" min="1" max="<?php echo $p->getStock(); ?>" style="width: 50px; padding: 5px;">
                                <button type="submit" style="background-color: #04835b; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">Agregar</button>
                            </form>
                        <?php else: ?>
                            <span style="color:red; font-weight:bold;">Agotado</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>


</body>
</html>